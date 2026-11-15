<?php

namespace Database\Seeders;

use App\Models\Species;
use App\Models\SpeciesDefaultNote;
use App\Models\Tree;
use App\Models\TreeMeasurement;
use App\Models\User;
use App\Services\CarbonCalculatorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $calculator = new CarbonCalculatorService();

        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@adopsi.test'],
            [
                'name' => 'Admin Kebun',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $adopterUser = User::updateOrCreate(
            ['email' => 'ayuwati@example.com'],
            [
                'name' => 'AYU WATI',
                'phone' => '081234567890',
                'city' => 'Jakarta',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        $danielUser = User::updateOrCreate(
            ['email' => 'danielwong@example.com'],
            [
                'name' => 'DANIEL WONG',
                'phone' => '+65 9123 4567',
                'city' => 'Singapore',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        $madeUser = User::updateOrCreate(
            ['email' => 'madedalam@example.com'],
            [
                'name' => 'MADE DALAM',
                'phone' => '081398765432',
                'city' => 'Singaraja',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        $linaUser = User::updateOrCreate(
            ['email' => 'linahartono@example.com'],
            [
                'name' => 'LINA HARTONO',
                'phone' => '081287654321',
                'city' => 'Bogor',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // 2. Species
        $moringa = Species::updateOrCreate(
            ['key' => 'moringa'],
            [
                'name' => 'Moringa',
                'display_name' => 'Moringa oleifera',
                'label' => 'Height',
                'start' => 20,
                'gain' => 190,
                'tau' => 12,
                'param_a' => 0.00004,
                'param_b' => 1.9,
                'root_share' => 0.25,
                'has_pruning' => true,
            ]
        );

        $inca = Species::updateOrCreate(
            ['key' => 'inca'],
            [
                'name' => 'Inca Nut',
                'display_name' => 'Inca nut (Plukenetia volubilis)',
                'label' => 'Vine length',
                'start' => 10,
                'gain' => 260,
                'tau' => 14,
                'param_a' => 0.00002,
                'param_b' => 1.7,
                'root_share' => 0.20,
                'has_pruning' => false,
            ]
        );

        // 3. Default Notes per Species (Bulanan: Bulan 1 - 12)
        $moringaNotes = [
            1 => 'Bulan 1: Pohon baru ditanam dan disiram intensif. Bibit moringa mulai beradaptasi di tanah hangat.',
            2 => 'Bulan 2: Daun majemuk pertama terbuka lebat. Mulsa jerami ditambahkan di sekeliling pangkal batang.',
            3 => 'Bulan 3: Pertumbuhan vertikal sangat pesat. Batang utama mengeras dan tegak kuat.',
            4 => 'Bulan 4: Pemangkasan ujung batang untuk merangsang pertumbuhan cabang lateral yang rimbun.',
            5 => 'Bulan 5: Cabang-cabang samping baru tumbuh subur dengan daun hijau pekat.',
            6 => 'Bulan 6: Setengah tahun masa adopsi. Pohon kokoh dengan sistem perakaran tunjang yang dalam.',
            7 => 'Bulan 7: Kanopi semakin melebar. Pemangkasan ringan dilakukan untuk menjaga kesehatan cabang.',
            8 => 'Bulan 8: Bebas hama, pohon sangat produktif memproduksi biomassa hijau.',
            9 => 'Bulan 9: Bunga-bunga putih kelor mulai bermekaran dengan harum semerbak.',
            10 => 'Bulan 10: Polong kelor muda mulai terbentuk bergelantungan dengan sehat.',
            11 => 'Bulan 11: Kanopi rimbun penuh, serapan karbon kumulatif meningkat signifikan.',
            12 => 'Bulan 12: Satu tahun penuh pemantauan. Pohon dewasa prima dengan serapan karbon puncak.',
        ];

        foreach ($moringaNotes as $w => $note) {
            SpeciesDefaultNote::updateOrCreate(
                ['species_id' => $moringa->id, 'week_number' => $w],
                ['note' => $note]
            );
        }

        $incaNotes = [
            1 => 'Bulan 1: Bibit ditanam di dekat tiang teralis kawat dan disiram teratur.',
            2 => 'Bulan 2: Daun sejati berbentuk hati telah membuka. Ujung sulur mulai memanjat ke tiang penyangga.',
            3 => 'Bulan 3: Sulur merambat melilit kawat dengan terarah dan dipandu staf kebun.',
            4 => 'Bulan 4: Pertumbuhan stabil dan agresif. Penyiangan gulma di sekitar pangkal tanaman dilakukan.',
            5 => 'Bulan 5: Mencapai bentangan kawat kedua pada teralis. Tunas-tunas samping mulai menyebar.',
            6 => 'Bulan 6: Daun lebat menaungi area teralis. Terpantau bebas dari gangguan hama daun.',
            7 => 'Bulan 7: Sulur utama mencapai puncak penyangga dan membentuk kanopi peneduh.',
            8 => 'Bulan 8: Kuncup bunga bintang khas sacha inchi mulai bermunculan di sepanjang cabang.',
            9 => 'Bulan 9: Bunga berkelamin ganda mekar sempurna, penyerbukan alami berlangsung optimal.',
            10 => 'Bulan 10: Buah bintang hijau berbilah mulai membesar dengan tekstur keras berisi biji berkadar omega-3.',
            11 => 'Bulan 11: Buah mulai berubah warna menuju kematangan panen pertama.',
            12 => 'Bulan 12: Satu tahun siklus pemantauan tuntas. Sulur abadi produktif dengan tutupan kanopi maksimal.',
        ];

        foreach ($incaNotes as $w => $note) {
            SpeciesDefaultNote::updateOrCreate(
                ['species_id' => $inca->id, 'week_number' => $w],
                ['note' => $note]
            );
        }

        // 4. Trees
        $treesData = [
            [
                'code' => 'TR-0142',
                'token' => 'k7f2q9',
                'species' => $moringa,
                'adopter' => 'AYU WATI, Jakarta',
                'user_id' => $adopterUser->id,
                'planted' => '2026-06-22',
                'week' => 14,
                'plot' => 'Semaya, Lajur 3 - 5 Pohon',
                'lastUpdated' => '2026-09-25',
            ],
            [
                'code' => 'TR-0143',
                'token' => 'm3x8dp',
                'species' => $inca,
                'adopter' => 'DANIEL WONG, Singapore',
                'user_id' => $danielUser->id,
                'planted' => '2026-07-27',
                'week' => 9,
                'plot' => 'Teba, Lajur Trellis 1 - 10 Pohon',
                'lastUpdated' => '2026-09-25',
            ],
            [
                'code' => 'TR-0150',
                'token' => 'q9v4ze',
                'species' => $moringa,
                'adopter' => 'MADE DALAM, Singaraja',
                'user_id' => $madeUser->id,
                'planted' => '2026-05-11',
                'week' => 20,
                'plot' => 'Semaya, Lajur 2 - 5 Pohon',
                'lastUpdated' => '2026-09-14',
            ],
            [
                'code' => 'TR-0151',
                'token' => 'b2n6ry',
                'species' => $moringa,
                'adopter' => 'KENJI SATO, Japan',
                'user_id' => null,
                'planted' => '2026-05-11',
                'week' => 20,
                'plot' => 'Teba, Lajur 1 - 10 Pohon',
                'lastUpdated' => '2026-09-21',
            ],
            [
                'code' => 'TR-0152',
                'token' => 't5c1wa',
                'species' => $inca,
                'adopter' => 'LINA HARTONO, Bogor',
                'user_id' => $linaUser->id,
                'planted' => '2026-07-27',
                'week' => 20,
                'plot' => 'Semaya, Trellis - 10 Pohon',
                'lastUpdated' => '2026-09-21',
            ],
            [
                'code' => 'TR-0153',
                'token' => 'h8j3ku',
                'species' => $moringa,
                'adopter' => 'AHMAD HADAS, Malaysia',
                'user_id' => null,
                'planted' => '2026-06-22',
                'week' => 24,
                'plot' => 'Semaya, Lajur 1 - 5 Pohon',
                'lastUpdated' => '2026-09-25',
            ],
            [
                'code' => 'TR-0154',
                'token' => 'y4d7sg',
                'species' => $inca,
                'adopter' => 'JOAN VANY, Australia',
                'user_id' => null,
                'planted' => '2026-07-27',
                'week' => 24,
                'plot' => 'Teba, Lajur Trellis 2 - 10 Pohon',
                'lastUpdated' => '2026-09-14',
            ],
            [
                'code' => 'TR-0155',
                'token' => 'p6e2fx',
                'species' => $moringa,
                'adopter' => 'TOM BECKER, Argentina',
                'user_id' => null,
                'planted' => '2026-06-22',
                'week' => 24,
                'plot' => 'Semaya, Lajur 4 - 5 Pohon',
                'lastUpdated' => '2026-09-21',
            ],
        ];

        foreach ($treesData as $t) {
            $tree = Tree::updateOrCreate(
                ['tree_code' => $t['code']],
                [
                    'token' => $t['token'],
                    'species_id' => $t['species']->id,
                    'adopter_name' => $t['adopter'],
                    'user_id' => $t['user_id'],
                    'planted_date' => $t['planted'],
                    'plot' => $t['plot'],
                    'current_week' => $t['week'],
                    'total_weeks' => 12,
                    'last_updated_at' => $t['lastUpdated'],
                ]
            );

            // Populate sample measurements for TR-0142
            if ($t['code'] === 'TR-0142') {
                $m13 = TreeMeasurement::updateOrCreate(
                    ['tree_id' => $tree->id, 'week_number' => 13],
                    [
                        'measurement_cm' => 147,
                        'leaf_count' => 61,
                        'pruned_weight_g' => 0,
                        'health' => 'good',
                        'note' => 'Good growth after rain. New side branches on the left.',
                        'carbon_absorbed_kg' => $calculator->calculateStandingCO2($moringa, 147),
                        'is_published' => true,
                        'logged_at' => '2026-09-18',
                    ]
                );

                $m14 = TreeMeasurement::updateOrCreate(
                    ['tree_id' => $tree->id, 'week_number' => 14],
                    [
                        'measurement_cm' => 152,
                        'leaf_count' => 66,
                        'pruned_weight_g' => 0,
                        'health' => 'good',
                        'note' => 'Steady week. Watered and checked; no pests found.',
                        'photo_path' => 'tree-logs/TR-0142/zEr1taDAwtR8LMgrJK9c0Bn6nf910KsapJotjz6x.jpg',
                        'carbon_absorbed_kg' => $calculator->calculateStandingCO2($moringa, 152),
                        'is_published' => true,
                        'logged_at' => '2026-09-25',
                    ]
                );
            }
        }
    }
}
