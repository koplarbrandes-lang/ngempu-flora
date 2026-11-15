<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Code 128 Pattern Table (Values 0 to 106)
     * Each pattern is 6 integers representing alternating bar and space widths (summing to 11 modules).
     * Stop pattern (106) has 7 integers (summing to 13 modules).
     */
    protected static array $patterns = [
        0 => '212222', 1 => '222122', 2 => '222221', 3 => '121223', 4 => '121322',
        5 => '131222', 6 => '122213', 7 => '122312', 8 => '132212', 9 => '221213',
        10 => '221312', 11 => '231212', 12 => '112232', 13 => '122132', 14 => '122231',
        15 => '113222', 16 => '123122', 17 => '123221', 18 => '223211', 19 => '221132',
        20 => '221231', 21 => '213212', 22 => '223112', 23 => '312131', 24 => '311222',
        25 => '321122', 26 => '321221', 27 => '312212', 28 => '322112', 29 => '322211',
        30 => '212123', 31 => '212321', 32 => '232121', 33 => '111323', 34 => '131123',
        35 => '131321', 36 => '112313', 37 => '132113', 38 => '132311', 39 => '211313',
        40 => '231113', 41 => '231311', 42 => '112133', 43 => '112331', 44 => '132131',
        45 => '113123', 46 => '113321', 47 => '133121', 48 => '313121', 49 => '211331',
        50 => '231131', 51 => '213113', 52 => '213311', 53 => '213131', 54 => '311123',
        55 => '311321', 56 => '331121', 57 => '312113', 58 => '312311', 59 => '332111',
        60 => '314111', 61 => '221411', 62 => '431111', 63 => '111224', 64 => '111422',
        65 => '121124', 66 => '121421', 67 => '141122', 68 => '141221', 69 => '112214',
        70 => '112412', 71 => '122114', 72 => '122411', 73 => '142112', 74 => '142211',
        75 => '241211', 76 => '221114', 77 => '413111', 78 => '241112', 79 => '134111',
        80 => '111242', 81 => '121142', 82 => '121241', 83 => '114212', 84 => '124112',
        85 => '124211', 86 => '411212', 87 => '421112', 88 => '421211', 89 => '212141',
        90 => '214121', 91 => '412121', 92 => '111143', 93 => '111341', 94 => '131141',
        95 => '114113', 96 => '114311', 97 => '411113', 98 => '411311', 99 => '113141',
        100 => '114131', 101 => '311141', 102 => '411131', 103 => '211412', 104 => '211214', // Start B
        105 => '211232', // Start C
        106 => '2331112', // Stop
    ];

    /**
     * Generate Code 128 (Subset B) as SVG
     */
    public function generateSvg(string $text, int $barHeight = 50, int $moduleWidth = 2, bool $showText = true): string
    {
        $text = trim($text);
        if (empty($text)) {
            $text = 'CERT-0000';
        }

        // Code 128B start symbol is 104
        $symbols = [104];
        $checksum = 104;

        for ($i = 0; $i < strlen($text); $i++) {
            $ascii = ord($text[$i]);
            // Subset B: ASCII 32 to 127 maps to symbol value = ascii - 32
            $val = $ascii >= 32 && $ascii <= 127 ? $ascii - 32 : 0;
            $symbols[] = $val;
            $checksum += ($i + 1) * $val;
        }

        $checkSymbol = $checksum % 103;
        $symbols[] = $checkSymbol;
        $symbols[] = 106; // Stop symbol

        // Build bars
        $rectangles = [];
        $x = 10; // Quiet zone left

        foreach ($symbols as $sym) {
            $pattern = self::$patterns[$sym] ?? '212222';
            $isBar = true;
            for ($p = 0; $p < strlen($pattern); $p++) {
                $width = ((int) $pattern[$p]) * $moduleWidth;
                if ($isBar) {
                    $rectangles[] = sprintf(
                        '<rect x="%d" y="0" width="%d" height="%d" fill="#14231B" />',
                        $x,
                        $width,
                        $barHeight
                    );
                }
                $x += $width;
                $isBar = !$isBar;
            }
        }

        $totalWidth = $x + 10; // Quiet zone right
        $totalHeight = $showText ? $barHeight + 22 : $barHeight;

        $svg = [];
        $svg[] = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="100%%" height="%d" style="max-width:%dpx; display:block; margin:0 auto;">',
            $totalWidth,
            $totalHeight,
            $totalHeight,
            $totalWidth
        );

        $svg[] = implode("\n", $rectangles);

        if ($showText) {
            $svg[] = sprintf(
                '<text x="%d" y="%d" text-anchor="middle" font-family="Courier, monospace" font-size="13" font-weight="700" letter-spacing="2" fill="#14231B">%s</text>',
                $totalWidth / 2,
                $barHeight + 16,
                htmlspecialchars($text)
            );
        }

        $svg[] = '</svg>';

        return implode("\n", $svg);
    }
}
