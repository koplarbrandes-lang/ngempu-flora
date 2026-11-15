<?php

namespace App\Http\Controllers\Adopter;

use App\Http\Controllers\Controller;
use App\Models\SpeciesDefaultNote;
use App\Models\Tree;
use App\Services\BarcodeService;
use App\Services\CarbonCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdopterDashboardController extends Controller
{
    public function __construct(
        protected CarbonCalculatorService $carbonCalculator,
        protected BarcodeService $barcodeService
    ) {}

    /**
     * Display Adopter's personal dashboard with live weekly tree monitoring
     */
    public function dashboard(Request $request): View
    {
        $user = Auth::user();

        // 1. Get all trees strictly linked to this user
        $trees = Tree::with(['species', 'measurements'])
            ->where('user_id', $user->id)
            ->orderBy('tree_code')
            ->get();

        // Fallback: If not linked via user_id, check adopter_name match
        if ($trees->isEmpty()) {
            $trees = Tree::with(['species', 'measurements'])
                ->where('adopter_name', 'LIKE', '%' . $user->name . '%')
                ->orderBy('tree_code')
                ->get();
        }

        // If user is Admin, they can view all trees if they don't have their own adopted tree
        if ($trees->isEmpty() && $user->isAdmin()) {
            $trees = Tree::with(['species', 'measurements'])->orderBy('tree_code')->get();
        }

        // If the user truly has no trees assigned
        if ($trees->isEmpty()) {
            return view('adopter.no-trees', [
                'user' => $user,
            ]);
        }

        // 2. Select active tree (from query parameter or default to first tree)
        $selectedTreeId = $request->query('tree_id');
        $selectedTree = null;
        if ($selectedTreeId) {
            $selectedTree = $trees->firstWhere('id', (int) $selectedTreeId);
        }
        if (!$selectedTree) {
            $selectedTree = $trees->first();
        }

        // 3. Calculate combined totals across all user's trees
        $totalCO2 = $trees->sum(function (Tree $t) {
            $latest = $t->measurements->sortByDesc('week_number')->first();
            $measurement = $latest ? $latest->measurement_cm : $t->species->expectedHeight($t->current_week);
            return $this->carbonCalculator->calculateTotalCO2($t, $measurement);
        });

        $totalLeaves = $trees->sum(function (Tree $t) {
            $latest = $t->measurements->sortByDesc('week_number')->first();
            return $latest ? ($latest->leaf_count ?? 0) : 0;
        });

        // 4. Prepare monthly data for the selected tree
        $species = $selectedTree->species;
        $measurements = $selectedTree->measurements()->where('is_published', true)->get()->keyBy('week_number');
        $defaultNotes = SpeciesDefaultNote::where('species_id', $species->id)->get()->keyBy('week_number');

        $totalMonths = (int) ($selectedTree->total_weeks ?: CarbonCalculatorService::TOTAL_MONTHS);
        $monthlyData = [];

        for ($m = 1; $m <= $totalMonths; $m++) {
            $isUnlocked = ($m <= $selectedTree->current_week);
            $hasReal = $measurements->has($m);
            $real = $measurements->get($m);

            $measurementCm = $hasReal ? $real->measurement_cm : $species->expectedHeight($m);
            $leafCount = $hasReal ? $real->leaf_count : null;
            $note = $hasReal && !empty($real->note)
                ? $real->note
                : ($defaultNotes->get($m)?->note ?? 'Perkembangan pohon berjalan stabil dan optimal pada bulan ini.');
            $photoUrl = $hasReal ? $real->photo_url : null;
            $health = $hasReal ? $real->health : 'good';

            $dryBiomass = $this->carbonCalculator->calculateDryBiomass($species, $measurementCm);
            $totalCO2Tree = $this->carbonCalculator->calculateStandingCO2($species, $measurementCm);

            $monthlyData[$m] = [
                'month' => $m,
                'week' => $m, // alias for backwards compatibility
                'is_unlocked' => $isUnlocked,
                'has_real' => $hasReal,
                'measurement_cm' => $measurementCm,
                'leaf_count' => $leafCount,
                'note' => $note,
                'photo_url' => $photoUrl,
                'health' => $health,
                'dry_biomass_kg' => $dryBiomass,
                'total_co2_kg' => $totalCO2Tree,
                'total_co2_formatted' => $this->carbonCalculator->formatCO2($totalCO2Tree),
                'logged_at' => $hasReal && $real->logged_at ? $real->logged_at->translatedFormat('d M Y') : null,
            ];
        }

        $activeMonth = max(1, min($selectedTree->current_week, $totalMonths));
        $activeData = $monthlyData[$activeMonth];

        return view('adopter.dashboard', [
            'user' => $user,
            'trees' => $trees,
            'selectedTree' => $selectedTree,
            'species' => $species,
            'totalTrees' => $trees->count(),
            'totalCO2Formatted' => $this->carbonCalculator->formatCO2($totalCO2),
            'totalLeaves' => $totalLeaves,
            'totalWeeks' => $totalMonths, // alias
            'totalMonths' => $totalMonths,
            'weeklyData' => $monthlyData, // alias
            'monthlyData' => $monthlyData,
            'activeWeek' => $activeMonth, // alias
            'activeMonth' => $activeMonth,
            'activeData' => $activeData,
        ]);
    }

    /**
     * Display official Certificate of Adoption with Barcode & Verification QR
     */
    public function certificate(Tree $tree): View
    {
        $user = Auth::user();

        // Ensure user is authorized: admin OR tree owner
        if ($user->role !== 'admin' && $tree->user_id !== $user->id && !str_contains(strtolower($tree->adopter_name), strtolower($user->name))) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat pohon ini.');
        }

        $species = $tree->species;
        $latest = $tree->measurements->sortByDesc('week_number')->first();
        $measurement = $latest ? $latest->measurement_cm : $species->expectedHeight($tree->current_week);
        $totalCO2 = $this->carbonCalculator->calculateTotalCO2($tree, $measurement);

        $barcodeSvg = $this->barcodeService->generateSvg($tree->certificate_number, 52, 2.0, true);

        return view('certificates.show', [
            'tree' => $tree,
            'species' => $species,
            'totalCO2Formatted' => $this->carbonCalculator->formatCO2($totalCO2),
            'barcodeSvg' => $barcodeSvg,
            'verificationUrl' => route('certificate.verify', ['codeAndToken' => $tree->full_code_token]),
        ]);
    }
}
