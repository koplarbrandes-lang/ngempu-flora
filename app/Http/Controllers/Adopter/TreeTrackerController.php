<?php

namespace App\Http\Controllers\Adopter;

use App\Http\Controllers\Controller;
use App\Models\SpeciesDefaultNote;
use App\Models\Tree;
use App\Services\CarbonCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreeTrackerController extends Controller
{
    public function __construct(
        protected CarbonCalculatorService $carbonCalculator
    ) {}

    /**
     * Show Tree Growth Tracker for Adopter (Requires Authentication)
     * Supports /tree/{codeAndToken} or /trees/track
     */
    public function show(Request $request, ?string $codeAndToken = null): View
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $tree = null;
        if ($codeAndToken) {
            // Split e.g. TR-0142-k7f2q9
            $parts = explode('-', $codeAndToken);
            if (count($parts) >= 3) {
                $code = $parts[0] . '-' . $parts[1];
                $token = $parts[2];
                $tree = Tree::where('tree_code', $code)->where('token', $token)->first();
            }
        }

        // If a specific tree is requested, verify permission immediately
        if ($tree) {
            $isOwner = ($tree->user_id === $user->id) || str_contains(strtolower($tree->adopter_name), strtolower($user->name));
            if (!$user->isAdmin() && !$isOwner) {
                abort(403, 'Anda tidak memiliki hak akses untuk memantau pohon ini.');
            }
        }

        // 1. Get user's own trees
        $userTrees = Tree::with('species')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('adopter_name', 'LIKE', '%' . $user->name . '%');
            })
            ->orderBy('tree_code')
            ->get();

        // Admins can inspect all trees
        if ($user->isAdmin() && $userTrees->isEmpty()) {
            $userTrees = Tree::with('species')->orderBy('tree_code')->get();
        }

        if ($userTrees->isEmpty()) {
            return view('adopter.no-trees', [
                'user' => $user,
            ]);
        }

        if (!$tree) {
            // Default to user's first tree
            $tree = $userTrees->first();
        }

        abort_if(!$tree, 404, 'Data pohon tidak ditemukan.');

        $species = $tree->species;
        $measurements = $tree->measurements()->where('is_published', true)->get()->keyBy('week_number');
        $defaultNotes = SpeciesDefaultNote::where('species_id', $species->id)->get()->keyBy('week_number');

        $totalMonths = (int) ($tree->total_weeks ?: CarbonCalculatorService::TOTAL_MONTHS);
        $monthlyData = [];

        for ($m = 1; $m <= $totalMonths; $m++) {
            $isUnlocked = ($m <= $tree->current_week);
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
            $totalCO2 = $this->carbonCalculator->calculateStandingCO2($species, $measurementCm);

            $monthlyData[$m] = [
                'month' => $m,
                'week' => $m, // alias
                'is_unlocked' => $isUnlocked,
                'has_real' => $hasReal,
                'measurement_cm' => $measurementCm,
                'leaf_count' => $leafCount,
                'note' => $note,
                'photo_url' => $photoUrl,
                'health' => $health,
                'dry_biomass_kg' => $dryBiomass,
                'total_co2_kg' => $totalCO2,
                'total_co2_formatted' => $this->carbonCalculator->formatCO2($totalCO2),
            ];
        }

        $activeMonth = max(1, min($tree->current_week, $totalMonths));
        $activeData = $monthlyData[$activeMonth];

        return view('adopter.tracker', [
            'tree' => $tree,
            'species' => $species,
            'allTrees' => $userTrees,
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
     * Public Certificate Verification Page (Scanned via Barcode or QR Code)
     */
    public function verifyCertificate(Request $request, ?string $codeAndToken = null): View
    {
        $code = $codeAndToken ?? $request->query('cert');
        $tree = null;

        if ($code) {
            // Check if CERT-TR0142-K7F2Q9 format
            if (preg_match('/^CERT-([A-Z0-9]+)-([A-Z0-9]+)$/i', $code, $m)) {
                $codePart = substr($m[1], 0, 2) . '-' . substr($m[1], 2);
                $tokenPart = strtolower($m[2]);
                $tree = Tree::where('tree_code', $codePart)->where('token', $tokenPart)->first();
            }
            // Check TR-0142-k7f2q9 format
            if (!$tree) {
                $parts = explode('-', $code);
                if (count($parts) >= 3) {
                    $treeCode = $parts[0] . '-' . $parts[1];
                    $token = $parts[2];
                    $tree = Tree::where('tree_code', $treeCode)->where('token', $token)->first();
                }
            }
            // Direct tree code or token match
            if (!$tree) {
                $tree = Tree::where('tree_code', $code)->orWhere('token', $code)->first();
            }
        }

        abort_if(!$tree, 404, 'Sertifikat tidak ditemukan atau kode verifikasi tidak valid.');

        $species = $tree->species;
        $latest = $tree->measurements->sortByDesc('week_number')->first();
        $measurement = $latest ? $latest->measurement_cm : $species->expectedHeight($tree->current_week);
        $totalCO2 = $this->carbonCalculator->calculateTotalCO2($tree, $measurement);

        return view('certificates.verify', [
            'tree' => $tree,
            'species' => $species,
            'latest' => $latest,
            'totalCO2Formatted' => $this->carbonCalculator->formatCO2($totalCO2),
        ]);
    }
}
