<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Species;
use App\Models\SpeciesDefaultNote;
use App\Models\Tree;
use App\Models\TreeMeasurement;
use App\Models\User;
use App\Services\BarcodeService;
use App\Services\CarbonCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected CarbonCalculatorService $carbonCalculator,
        protected BarcodeService $barcodeService
    ) {}

    /**
     * Admin Overview Dashboard
     */
    public function dashboard(): View
    {
        $trees = Tree::with(['species', 'measurements'])->get();

        $totalTrees = $trees->count();
        $dueTrees = $trees->filter(fn (Tree $t) => !$t->isUpdatedThisMonth());
        $lateTrees = $trees->filter(fn (Tree $t) => $t->isLate() && !$t->isUpdatedThisMonth());
        $updatedCount = $totalTrees - $dueTrees->count();

        // Calculate total CO2 absorbed across all trees
        $totalCO2 = $trees->sum(function (Tree $t) {
            $latest = $t->measurements->sortByDesc('week_number')->first();
            $measurement = $latest ? $latest->measurement_cm : $t->species->expectedHeight($t->current_week);
            return $this->carbonCalculator->calculateTotalCO2($t, $measurement);
        });

        return view('admin.dashboard', [
            'totalTrees' => $totalTrees,
            'updatedCount' => $updatedCount,
            'dueTrees' => $dueTrees,
            'lateTrees' => $lateTrees,
            'totalCO2Formatted' => $this->carbonCalculator->formatCO2($totalCO2),
            'trees' => $trees,
        ]);
    }

    /**
     * Trees Table List
     */
    public function trees(): View
    {
        $trees = Tree::with(['species', 'measurements'])->orderBy('tree_code')->get();

        return view('admin.trees', [
            'trees' => $trees,
        ]);
    }

    /**
     * Show Form to Create New Tree
     */
    public function createTree(Request $request): View
    {
        $species = Species::all();
        $users = User::where('role', 'user')->orderBy('name')->get();

        // Auto-suggest next tree code
        $lastTree = Tree::orderBy('id', 'desc')->first();
        $nextNumber = 156;
        if ($lastTree && preg_match('/TR-(\d+)/', $lastTree->tree_code, $matches)) {
            $nextNumber = ((int)$matches[1]) + 1;
        }
        $suggestedCode = sprintf('TR-%04d', $nextNumber);
        $suggestedToken = strtolower(Str::random(6));

        $tree = new Tree();
        $preselectedUserId = $request->query('user_id');
        if ($preselectedUserId) {
            $tree->user_id = (int)$preselectedUserId;
            $preUser = $users->firstWhere('id', (int)$preselectedUserId);
            if ($preUser) {
                $tree->adopter_name = $preUser->formatted_adopter_name;
            }
        }

        return view('admin.trees-form', [
            'species' => $species,
            'users' => $users,
            'suggestedCode' => $suggestedCode,
            'suggestedToken' => $suggestedToken,
            'tree' => $tree,
            'isEdit' => false,
        ]);
    }

    /**
     * Store New Tree
     */
    public function storeTree(Request $request): RedirectResponse
    {
        if ($request->filled('user_id') && !$request->filled('adopter_name')) {
            $selectedUser = User::find($request->input('user_id'));
            if ($selectedUser) {
                $request->merge(['adopter_name' => $selectedUser->formatted_adopter_name]);
            }
        }

        $validated = $request->validate([
            'tree_code' => 'required|string|max:50|unique:trees,tree_code',
            'token' => 'required|string|max:20|unique:trees,token',
            'species_id' => 'required|exists:species,id',
            'adopter_name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'planted_date' => 'required|date',
            'plot' => 'nullable|string|max:255',
            'total_weeks' => 'nullable|integer|min:1|max:120',
            'initial_measurement_cm' => 'nullable|numeric|min:0',
            'initial_leaf_count' => 'nullable|integer|min:0',
            'initial_note' => 'nullable|string',
            'initial_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $species = Species::findOrFail($validated['species_id']);
        $hasInitial = !empty($validated['initial_measurement_cm']) || $request->hasFile('initial_photo');

        $tree = Tree::create([
            'tree_code' => strtoupper(trim($validated['tree_code'])),
            'token' => strtolower(trim($validated['token'])),
            'species_id' => $validated['species_id'],
            'adopter_name' => trim($validated['adopter_name']),
            'user_id' => $validated['user_id'] ?? null,
            'planted_date' => $validated['planted_date'],
            'plot' => $validated['plot'] ? trim($validated['plot']) : null,
            'total_weeks' => (int) ($validated['total_weeks'] ?? 12),
            'current_week' => $hasInitial ? 1 : 0,
            'last_updated_at' => $hasInitial ? $validated['planted_date'] : null,
        ]);

        // Upload initial photo if provided
        $photoPath = null;
        if ($request->hasFile('initial_photo')) {
            $photoPath = $request->file('initial_photo')->store('tree-logs/' . $tree->tree_code, 'public');
        }

        // If initial measurement or photo was provided, record Month 1
        if ($hasInitial) {
            $initialCm = !empty($validated['initial_measurement_cm']) 
                ? (float)$validated['initial_measurement_cm'] 
                : (float)$species->start;
            $co2 = $this->carbonCalculator->calculateStandingCO2($species, $initialCm);

            $tree->measurements()->create([
                'week_number' => 1,
                'measurement_cm' => $initialCm,
                'leaf_count' => $validated['initial_leaf_count'] ?? null,
                'pruned_weight_g' => 0,
                'health' => 'good',
                'note' => $validated['initial_note'] ?? 'Pohon baru ditanam dan didokumentasikan.',
                'photo_path' => $photoPath,
                'carbon_absorbed_kg' => $co2,
                'is_published' => true,
                'logged_at' => $validated['planted_date'],
            ]);
        }

        return redirect()->route('admin.trees')
            ->with('success', "Pohon baru {$tree->tree_code} ({$species->name}) berhasil didaftarkan untuk {$tree->adopter_name}!");
    }

    /**
     * Show Form to Edit Tree
     */
    public function editTree(Tree $tree): View
    {
        $species = Species::all();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.trees-form', [
            'species' => $species,
            'users' => $users,
            'tree' => $tree,
            'isEdit' => true,
        ]);
    }

    /**
     * Update Existing Tree
     */
    public function updateTree(Request $request, Tree $tree): RedirectResponse
    {
        $validated = $request->validate([
            'tree_code' => 'required|string|max:50|unique:trees,tree_code,' . $tree->id,
            'token' => 'required|string|max:20|unique:trees,token,' . $tree->id,
            'species_id' => 'required|exists:species,id',
            'adopter_name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'planted_date' => 'required|date',
            'plot' => 'nullable|string|max:255',
            'total_weeks' => 'nullable|integer|min:1|max:120',
        ]);

        $tree->update([
            'tree_code' => strtoupper(trim($validated['tree_code'])),
            'token' => strtolower(trim($validated['token'])),
            'species_id' => $validated['species_id'],
            'adopter_name' => trim($validated['adopter_name']),
            'user_id' => $validated['user_id'] ?? null,
            'planted_date' => $validated['planted_date'],
            'plot' => $validated['plot'] ? trim($validated['plot']) : null,
            'total_weeks' => (int) ($validated['total_weeks'] ?? $tree->total_weeks ?? 12),
        ]);

        return redirect()->route('admin.trees')
            ->with('success', "Data pohon {$tree->tree_code} berhasil diperbarui!");
    }

    /**
     * Increment or decrement total monitoring months for a tree
     */
    public function adjustWeeks(Request $request, Tree $tree): RedirectResponse
    {
        $delta = (int) $request->input('delta', 1);
        $newWeeks = max($tree->current_week, max(1, $tree->total_weeks + $delta));

        $tree->update(['total_weeks' => $newWeeks]);

        $actionText = $delta > 0 ? "ditambahkan menjadi {$newWeeks} bulan" : "dikurangi menjadi {$newWeeks} bulan";
        return redirect()->back()
            ->with('success', "Durasi program untuk {$tree->tree_code} berhasil {$actionText}!");
    }

    /**
     * Delete Tree
     */
    public function destroyTree(Tree $tree): RedirectResponse
    {
        $code = $tree->tree_code;
        $tree->delete();

        return redirect()->route('admin.trees')
            ->with('success', "Pohon {$code} berhasil dihapus dari sistem.");
    }

    /**
     * Bulk Delete Trees
     */
    public function bulkDestroyTrees(Request $request): RedirectResponse
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('admin.trees')
                ->with('error', 'Tidak ada pohon yang dipilih untuk dihapus.');
        }

        $trees = Tree::whereIn('id', $ids)->get();
        $count = $trees->count();

        foreach ($trees as $tree) {
            $tree->delete();
        }

        return redirect()->route('admin.trees')
            ->with('success', "Berhasil menghapus {$count} pohon terpilih beserta riwayat pengukurannya.");
    }

    /**
     * Monthly Update Form & History Editor
     */
    public function updateForm(Request $request): View
    {
        $trees = Tree::with(['species', 'measurements'])->orderBy('tree_code')->get();

        if ($trees->isEmpty()) {
            return view('admin.update-form', [
                'trees' => collect(),
                'selectedTree' => null,
                'targetMonth' => 1,
                'currentMeasurement' => null,
                'defaultNote' => null,
            ]);
        }

        $selectedTreeId = $request->query('tree_id') ?? $trees->firstWhere(fn ($t) => !$t->isUpdatedThisMonth())?->id ?? $trees->first()?->id;
        $selectedTree = Tree::with(['species', 'measurements' => fn ($q) => $q->orderBy('week_number')])->find($selectedTreeId) ?? $trees->first();

        // Target month can be requested via query parameter ?month=X (for editing historical months)
        if ($request->has('month')) {
            $targetMonth = (int) $request->query('month');
        } else {
            // Default next month to update
            $targetMonth = $selectedTree->isUpdatedThisMonth() ? $selectedTree->current_week + 1 : max(1, $selectedTree->current_week);
        }

        // Find if measurement already exists for this tree & month
        $currentMeasurement = $selectedTree->measurements->firstWhere('week_number', $targetMonth);

        // Fetch default note for species and month if not yet set
        $defaultNote = SpeciesDefaultNote::where('species_id', $selectedTree->species_id)
            ->where('week_number', $targetMonth)
            ->value('note');

        return view('admin.update-form', [
            'trees' => $trees,
            'selectedTree' => $selectedTree,
            'targetMonth' => $targetMonth,
            'currentMeasurement' => $currentMeasurement,
            'defaultNote' => $defaultNote,
        ]);
    }

    /**
     * Store Monthly Update
     */
    public function storeUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tree_id' => 'required|exists:trees,id',
            'week_number' => 'required|integer|min:1|max:120',
            'measurement_cm' => 'required|numeric|min:0',
            'leaf_count' => 'nullable|integer|min:0',
            'pruned_weight_g' => 'nullable|numeric|min:0',
            'health' => 'required|in:good,watch,poor',
            'note' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'action' => 'required|in:publish,draft',
        ]);

        $tree = Tree::with('species')->findOrFail($validated['tree_id']);
        $prunedG = (float) ($validated['pruned_weight_g'] ?? 0);

        // Upload photo if present
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('tree-logs/' . $tree->tree_code, 'public');
        }

        // Calculate CO2 for this measurement
        $co2Absorbed = $this->carbonCalculator->calculateTotalCO2(
            $tree,
            (float) $validated['measurement_cm'],
            $prunedG
        );

        // Save or update measurement
        $measurement = TreeMeasurement::updateOrCreate(
            [
                'tree_id' => $tree->id,
                'week_number' => $validated['week_number'],
            ],
            [
                'measurement_cm' => $validated['measurement_cm'],
                'leaf_count' => $validated['leaf_count'] ?? null,
                'pruned_weight_g' => $prunedG,
                'health' => $validated['health'],
                'note' => $validated['note'] ?? null,
                'photo_path' => $photoPath ?? TreeMeasurement::where('tree_id', $tree->id)->where('week_number', $validated['week_number'])->value('photo_path'),
                'carbon_absorbed_kg' => $co2Absorbed,
                'is_published' => $validated['action'] === 'publish',
                'logged_at' => now(),
            ]
        );

        if ($validated['action'] === 'publish') {
            // Keep current_week accurate: the highest published month number
            $highestMonth = TreeMeasurement::where('tree_id', $tree->id)
                ->where('is_published', true)
                ->max('week_number') ?? $validated['week_number'];

            $tree->update([
                'current_week' => max($tree->current_week, $highestMonth),
                'last_updated_at' => now(),
            ]);

            return redirect()->route('admin.update', ['tree_id' => $tree->id, 'month' => $validated['week_number']])
                ->with('success', "Data Bulan {$validated['week_number']} untuk {$tree->tree_code} ({$tree->species->name}) berhasil disimpan dan dipublikasikan!");
        }

        return redirect()->route('admin.update', ['tree_id' => $tree->id, 'month' => $validated['week_number']])
            ->with('success', "Draft Bulan {$validated['week_number']} untuk {$tree->tree_code} berhasil disimpan.");
    }

    /**
     * Real-time calculation and anomaly check endpoint (AJAX)
     */
    public function previewCarbon(Request $request): JsonResponse
    {
        $treeId = $request->input('tree_id');
        $week = (int) $request->input('week');
        $measurement = (float) $request->input('measurement_cm');
        $prunedG = (float) $request->input('pruned_g', 0);

        $tree = Tree::with(['species', 'measurements'])->findOrFail($treeId);
        $species = $tree->species;

        // Current CO2 & Previous CO2
        $prevMeasurement = $tree->measurements()
            ->where('week_number', '<', $week)
            ->latest('week_number')
            ->value('measurement_cm') ?? $species->expectedHeight(max(1, $week - 1));

        $standingCO2 = $this->carbonCalculator->calculateStandingCO2($species, $measurement);
        $prunedCO2 = $species->has_pruning ? $this->carbonCalculator->calculatePrunedCO2($prunedG) : 0;
        $totalCO2 = $standingCO2 + $prunedCO2;

        $prevTotal = $this->carbonCalculator->calculateStandingCO2($species, $prevMeasurement);
        $monthGainCO2 = max(0, $totalCO2 - $prevTotal);

        $anomaly = $this->carbonCalculator->checkGrowthAnomaly($tree, $week, $measurement, $prunedG);

        $dryBiomass = $this->carbonCalculator->calculateDryBiomass($species, $measurement);

        $explanation = "{$species->label} {$measurement} cm → estimasi biomassa kering {$dryBiomass} kg (termasuk akar) × 47% karbon × 3.67 = {$this->carbonCalculator->formatCO2($totalCO2)}.";
        if ($prunedCO2 > 0) {
            $explanation .= " Bahan pangkasan menambah {$this->carbonCalculator->formatCO2($prunedCO2)}.";
        }

        return response()->json([
            'week_co2' => $this->carbonCalculator->formatCO2($monthGainCO2),
            'month_co2' => $this->carbonCalculator->formatCO2($monthGainCO2),
            'total_co2' => $this->carbonCalculator->formatCO2($totalCO2),
            'dry_biomass_kg' => $dryBiomass,
            'anomaly' => $anomaly,
            'explanation' => $explanation,
        ]);
    }

    /**
     * Batch QR Codes View
     */
    public function qrCodes(Request $request): View
    {
        $selectedIds = $request->input('ids');
        if (!empty($selectedIds)) {
            $ids = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
            $trees = Tree::with('species')->whereIn('id', $ids)->get();
        } else {
            $trees = Tree::with('species')->take(8)->get();
        }

        return view('admin.qr-codes', [
            'trees' => $trees,
        ]);
    }

    /**
     * Certificates Directory View for Admin
     */
    public function certificates(Request $request): View
    {
        $search = $request->query('q');
        $query = Tree::with(['species', 'measurements', 'user'])->orderBy('tree_code');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tree_code', 'LIKE', "%{$search}%")
                  ->orWhere('adopter_name', 'LIKE', "%{$search}%")
                  ->orWhere('token', 'LIKE', "%{$search}%");
            });
        }

        $trees = $query->get()->map(function (Tree $tree) {
            $latest = $tree->measurements->sortByDesc('week_number')->first();
            $measurement = $latest ? $latest->measurement_cm : $tree->species->expectedHeight($tree->current_week);
            $totalCO2 = $this->carbonCalculator->calculateTotalCO2($tree, $measurement);
            $tree->carbon_absorbed_formatted = $this->carbonCalculator->formatCO2($totalCO2);
            $tree->barcode_svg = $this->barcodeService->generateSvg($tree->certificate_number, 36, 1.4, false);
            return $tree;
        });

        return view('admin.certificates', [
            'trees' => $trees,
            'search' => $search,
        ]);
    }
}
