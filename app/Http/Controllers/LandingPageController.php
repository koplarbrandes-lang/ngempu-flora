<?php

namespace App\Http\Controllers;

use App\Models\Species;
use App\Models\Tree;
use App\Services\CarbonCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        protected CarbonCalculatorService $carbonCalculator
    ) {}

    /**
     * Show the main informative Landing Page
     */
    public function index(): View
    {
        $trees = Tree::with(['species', 'measurements'])->get();
        $totalTrees = $trees->count();

        // Calculate total cumulative CO2 across all trees
        $totalCO2 = $trees->sum(function (Tree $t) {
            $latest = $t->measurements->sortByDesc('week_number')->first();
            $measurement = $latest ? $latest->measurement_cm : $t->species->expectedHeight($t->current_week);
            return $this->carbonCalculator->calculateTotalCO2($t, $measurement);
        });

        $species = Species::all();
        $sampleTrees = $trees->take(4);

        return view('landing', [
            'totalTrees' => $totalTrees,
            'totalCO2Formatted' => $this->carbonCalculator->formatCO2($totalCO2),
            'species' => $species,
            'sampleTrees' => $sampleTrees,
        ]);
    }
}
