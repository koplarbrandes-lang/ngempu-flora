<?php

namespace App\Services;

use App\Models\Species;
use App\Models\Tree;
use App\Models\TreeMeasurement;

class CarbonCalculatorService
{
    public const CARBON_FRACTION = 0.47;
    public const CO2_PER_C = 3.67; // 44 / 12 ratio
    public const TOTAL_MONTHS = 12;
    public const TOTAL_WEEKS = 12; // backward compatibility alias

    /**
     * Calculate dry biomass in kilograms for a given species and measurement (cm).
     * Formula: A * (measurement_cm ^ B) * (1 + root_share)
     */
    public function calculateDryBiomass(Species $species, float $measurementCm): float
    {
        if ($measurementCm <= 0) {
            return 0.0;
        }

        $base = $species->param_a * pow($measurementCm, $species->param_b);
        $total = $base * (1 + $species->root_share);

        return round($total, 4);
    }

    /**
     * Calculate standing CO2 in kilograms from biomass.
     * Formula: dry_biomass * 0.47 * 3.67
     */
    public function calculateStandingCO2(Species $species, float $measurementCm): float
    {
        $biomass = $this->calculateDryBiomass($species, $measurementCm);
        $co2 = $biomass * self::CARBON_FRACTION * self::CO2_PER_C;

        return round($co2, 4);
    }

    /**
     * Calculate CO2 from pruned material in kg.
     * Pruned weight in grams, assumed 25% dry matter.
     * Formula: (pruned_g * 0.25 * 0.47 * 3.67) / 1000
     */
    public function calculatePrunedCO2(float $prunedGrams): float
    {
        if ($prunedGrams <= 0) {
            return 0.0;
        }

        return round(($prunedGrams * 0.25 * self::CARBON_FRACTION * self::CO2_PER_C) / 1000, 4);
    }

    /**
     * Calculate total CO2 absorbed for a tree at a specific measurement,
     * including cumulative pruned material.
     */
    public function calculateTotalCO2(Tree $tree, float $currentMeasurementCm, float $currentPrunedG = 0): float
    {
        $standingCO2 = $this->calculateStandingCO2($tree->species, $currentMeasurementCm);
        $currentPrunedCO2 = $tree->species->has_pruning ? $this->calculatePrunedCO2($currentPrunedG) : 0;

        // Sum previous pruned CO2 from database measurements
        $pastPrunedG = $tree->measurements()->sum('pruned_weight_g');
        $pastPrunedCO2 = $tree->species->has_pruning ? $this->calculatePrunedCO2($pastPrunedG) : 0;

        return round($standingCO2 + $pastPrunedCO2 + $currentPrunedCO2, 4);
    }

    /**
     * Format CO2 for human reading: e.g. "450 g" or "1.82 kg"
     */
    public function formatCO2(float $kg): string
    {
        if ($kg <= 0) {
            return '0 g';
        }

        if ($kg < 1) {
            return round($kg * 1000) . ' g';
        }

        return number_format($kg, 2) . ' kg';
    }

    /**
     * Check for measurement anomalies (too high jump or drop).
     *
     * @return array{status: 'ok'|'warning'|'danger', message: string}
     */
    public function checkGrowthAnomaly(Tree $tree, int $week, float $newMeasurementCm, float $prunedG = 0): array
    {
        $species = $tree->species;
        $prevMeasurement = $tree->measurements()
            ->where('week_number', '<', $week)
            ->latest('week_number')
            ->value('measurement_cm') ?? $species->start;

        $diff = $newMeasurementCm - $prevMeasurement;
        $expectedDiff = $species->expectedHeight($week) - $species->expectedHeight(max(1, $week - 1));

        if ($diff < 0 && $prunedG <= 0) {
            return [
                'status' => 'danger',
                'message' => 'Ukuran lebih rendah dari minggu sebelumnya. Periksa kembali angka atau catat berat pangkasan.',
            ];
        }

        if ($expectedDiff > 0 && $diff > ($expectedDiff * 3)) {
            $formattedDiff = round($diff, 1);
            $formattedExp = round($expectedDiff, 1);
            return [
                'status' => 'warning',
                'message' => "Lonjakan pertumbuhan tidak biasa (+{$formattedDiff} cm; estimasi normal ~{$formattedExp} cm). Periksa sebelum menyimpan.",
            ];
        }

        return [
            'status' => 'ok',
            'message' => 'Pertumbuhan terlihat normal.',
        ];
    }
}
