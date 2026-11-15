<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use App\Models\User;
use App\Services\CarbonCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAdopterController extends Controller
{
    public function __construct(
        protected CarbonCalculatorService $carbonCalculator
    ) {}

    /**
     * Display list of all registered adopters
     */
    public function index(): View
    {
        $adopters = User::where('role', 'user')
            ->with(['trees.species', 'trees.measurements'])
            ->withCount('trees')
            ->orderBy('name')
            ->get();

        // Calculate metrics for each adopter
        $adopters->transform(function ($adopter) {
            $adopter->total_co2_kg = $adopter->trees->sum(function (Tree $t) {
                $latest = $t->measurements->sortByDesc('week_number')->first();
                $measurement = $latest ? $latest->measurement_cm : $t->species->expectedHeight($t->current_week);
                return $this->carbonCalculator->calculateTotalCO2($t, $measurement);
            });
            $adopter->total_co2_formatted = $this->carbonCalculator->formatCO2($adopter->total_co2_kg);
            return $adopter;
        });

        return view('admin.adopters.index', [
            'adopters' => $adopters,
        ]);
    }

    /**
     * Show form to create new adopter
     */
    public function create(Request $request): View
    {
        return view('admin.adopters.form', [
            'adopter' => new User(),
            'isEdit' => false,
            'redirectTo' => $request->query('redirect_to'),
        ]);
    }

    /**
     * Store new adopter
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'city' => $validated['city'] ? trim($validated['city']) : null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        $redirectTo = $request->input('redirect_to');
        if ($redirectTo === 'trees_create') {
            return redirect()->route('admin.trees.create', ['user_id' => $user->id])
                ->with('success', "Adopter baru '{$user->name}' berhasil didaftarkan dan langsung dipilih untuk pohon baru!");
        }

        return redirect()->route('admin.adopters.index')
            ->with('success', "Akun adopter baru '{$user->name}' ({$user->email}) berhasil didaftarkan!");
    }

    /**
     * Show form to edit adopter
     */
    public function edit(User $adopter): View
    {
        abort_if($adopter->role !== 'user', 404);

        return view('admin.adopters.form', [
            'adopter' => $adopter,
            'isEdit' => true,
            'redirectTo' => null,
        ]);
    }

    /**
     * Update adopter info
     */
    public function update(Request $request, User $adopter): RedirectResponse
    {
        abort_if($adopter->role !== 'user', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $adopter->id,
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'city' => $validated['city'] ? trim($validated['city']) : null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $adopter->update($data);

        return redirect()->route('admin.adopters.index')
            ->with('success', "Data adopter '{$adopter->name}' berhasil diperbarui!");
    }

    /**
     * Delete adopter
     */
    public function destroy(User $adopter): RedirectResponse
    {
        abort_if($adopter->role !== 'user', 404);

        $name = $adopter->name;

        // Unlink trees so trees are preserved
        $adopter->trees()->update(['user_id' => null]);
        $adopter->delete();

        return redirect()->route('admin.adopters.index')
            ->with('success', "Akun adopter '{$name}' berhasil dihapus. Pohon yang sebelumnya diadopsi tetap tersimpan di sistem.");
    }

    /**
     * Bulk Delete Adopters
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('admin.adopters.index')
                ->with('error', 'Tidak ada adopter yang dipilih untuk dihapus.');
        }

        // Only delete users with role 'user' (never delete admin)
        $adopters = User::where('role', 'user')->whereIn('id', $ids)->get();
        $count = $adopters->count();

        foreach ($adopters as $adopter) {
            $adopter->trees()->update(['user_id' => null]);
            $adopter->delete();
        }

        return redirect()->route('admin.adopters.index')
            ->with('success', "Berhasil menghapus {$count} akun adopter terpilih. Pohon terkait tetap tersimpan dengan aman.");
    }
}
