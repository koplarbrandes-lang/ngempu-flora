<?php

namespace Tests\Feature;

use App\Models\Species;
use App\Models\Tree;
use App\Models\TreeMeasurement;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreeAdoptionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk untuk mengakses dashboard Anda');
        $response->assertSee('admin@adopsi.test');
    }

    public function test_admin_can_login_and_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@adopsi.test',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_adopter_can_login_and_redirects_to_user_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'ayuwati@example.com',
            'password' => 'user123',
        ]);

        $response->assertRedirect('/user/dashboard');
        $this->assertAuthenticated();
    }

    public function test_adopter_cannot_access_admin_panel(): void
    {
        $user = User::where('email', 'ayuwati@example.com')->first();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_adopter_user_dashboard_shows_their_trees(): void
    {
        $user = User::where('email', 'ayuwati@example.com')->first();

        $response = $this->actingAs($user)->get('/user/dashboard');
        $response->assertStatus(200);
        $response->assertSee('AYU WATI');
        $response->assertSee('TR-0142');
        $response->assertSee('Perkembangan Bulanan Pohon');
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ngempu Flora');
        $response->assertSee('Moringa');
        $response->assertSee('Kacang Inka');
        $response->assertSee('Yayasan Kedai Masyarakat');
    }

    public function test_tracker_explorer_page_requires_auth_and_loads_for_adopter(): void
    {
        $guestResponse = $this->get('/trees/track');
        $guestResponse->assertRedirect('/login');

        $user = User::where('email', 'ayuwati@example.com')->first();
        $authResponse = $this->actingAs($user)->get('/trees/track');
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Ngempu Flora');
        $authResponse->assertSee('TR-0142');
    }

    public function test_adopter_can_only_access_their_own_tree(): void
    {
        // Guest redirected to login
        $guestResponse = $this->get('/tree/TR-0142-k7f2q9');
        $guestResponse->assertRedirect('/login');

        // Owner can access
        $owner = User::where('email', 'ayuwati@example.com')->first();
        $response = $this->actingAs($owner)->get('/tree/TR-0142-k7f2q9');
        $response->assertStatus(200);
        $response->assertSee('AYU WATI');
        $response->assertSee('Moringa oleifera');

        // Non-owner is forbidden (403)
        $otherUser = User::factory()->create([
            'role' => 'user',
            'name' => 'Bambang Sudrajat',
        ]);
        $forbiddenResponse = $this->actingAs($otherUser)->get('/tree/TR-0142-k7f2q9');
        $forbiddenResponse->assertStatus(403);
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Perkembangan Bulan Ini');
        $response->assertSee('Pohon Aktif');
    }

    public function test_authenticated_admin_can_access_trees_list(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->get('/admin/trees');
        $response->assertStatus(200);
        $response->assertSee('Daftar Pohon');
        $response->assertSee('TR-0142');
        $response->assertSee('Tambah Pohon Baru');
    }

    public function test_admin_create_tree_page_loads(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->get('/admin/trees/create');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Pohon Baru');
        $response->assertSee('Kode Pohon');
        $response->assertSee('Token Keamanan QR');
    }

    public function test_admin_can_store_new_tree_with_initial_measurement(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::where('email', 'admin@adopsi.test')->first();
        $species = Species::where('key', 'moringa')->first();
        $fakePhoto = \Illuminate\Http\UploadedFile::fake()->image('pohon_awal.jpg', 600, 600);

        $response = $this->actingAs($admin)->post('/admin/trees', [
            'tree_code' => 'TR-0200',
            'token' => 'z9x8y7',
            'species_id' => $species->id,
            'adopter_name' => 'BUDI SANTOSO, Surabaya',
            'planted_date' => '2026-10-20',
            'plot' => 'Semaya, Lajur 5 - 10 Pohon',
            'initial_measurement_cm' => 25.5,
            'initial_leaf_count' => 12,
            'initial_note' => 'Pohon baru ditanam dan disiram dengan pupuk organik.',
            'initial_photo' => $fakePhoto,
        ]);

        $response->assertRedirect('/admin/trees');
        $this->assertDatabaseHas('trees', [
            'tree_code' => 'TR-0200',
            'adopter_name' => 'BUDI SANTOSO, Surabaya',
            'current_week' => 1,
        ]);

        $newTree = Tree::where('tree_code', 'TR-0200')->first();
        $measurement = $newTree->measurements()->where('week_number', 1)->first();
        $this->assertNotNull($measurement);
        $this->assertNotNull($measurement->photo_path);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($measurement->photo_path);
    }

    public function test_admin_can_update_tree(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $tree = Tree::where('tree_code', 'TR-0142')->first();

        $response = $this->actingAs($admin)->put("/admin/trees/{$tree->id}", [
            'tree_code' => 'TR-0142',
            'token' => 'k7f2q9',
            'species_id' => $tree->species_id,
            'adopter_name' => 'AYU WATI KUSUMA, Jakarta Barat',
            'planted_date' => '2026-06-22',
            'plot' => 'Semaya, Lajur 3 - Blok A',
        ]);

        $response->assertRedirect('/admin/trees');
        $this->assertDatabaseHas('trees', [
            'id' => $tree->id,
            'adopter_name' => 'AYU WATI KUSUMA, Jakarta Barat',
        ]);
    }

    public function test_admin_can_save_monthly_measurement(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $tree = Tree::where('tree_code', 'TR-0142')->first();

        $response = $this->actingAs($admin)->post('/admin/update', [
            'tree_id' => $tree->id,
            'week_number' => 12,
            'measurement_cm' => 158.0,
            'leaf_count' => 70,
            'pruned_weight_g' => 0,
            'health' => 'good',
            'note' => 'Pertumbuhan prima bulan ke-12.',
            'action' => 'publish',
        ]);

        $response->assertRedirect('/admin/update?tree_id=' . $tree->id . '&month=12');
        $this->assertDatabaseHas('tree_measurements', [
            'tree_id' => $tree->id,
            'week_number' => 12,
            'leaf_count' => 70,
        ]);
    }

    public function test_admin_can_view_adopters_list(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->get('/admin/adopters');
        $response->assertStatus(200);
        $response->assertSee('Daftar Adopter (Penanam)');
        $response->assertSee('AYU WATI');
    }

    public function test_admin_can_view_create_adopter_page(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->get('/admin/adopters/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Adopter Baru');
    }

    public function test_admin_can_store_new_adopter(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->post('/admin/adopters', [
            'name' => 'DEWA ANOM',
            'email' => 'dewaanom@example.com',
            'phone' => '081987654321',
            'city' => 'Ubud',
            'password' => 'user123',
        ]);

        $response->assertRedirect('/admin/adopters');
        $this->assertDatabaseHas('users', [
            'email' => 'dewaanom@example.com',
            'city' => 'Ubud',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_store_adopter_and_redirect_to_trees_create(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();

        $response = $this->actingAs($admin)->post('/admin/adopters', [
            'name' => 'SITI AMINAH',
            'email' => 'sitiaminah@example.com',
            'phone' => '081233445566',
            'city' => 'Bandung',
            'password' => 'user123',
            'redirect_to' => 'trees_create',
        ]);

        $user = User::where('email', 'sitiaminah@example.com')->first();
        $response->assertRedirect("/admin/trees/create?user_id={$user->id}");
    }

    public function test_admin_can_update_adopter(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $adopter = User::where('email', 'ayuwati@example.com')->first();

        $response = $this->actingAs($admin)->put("/admin/adopters/{$adopter->id}", [
            'name' => 'AYU WATI KUSUMA',
            'email' => 'ayuwati@example.com',
            'phone' => '081299998888',
            'city' => 'Jakarta Selatan',
        ]);

        $response->assertRedirect('/admin/adopters');
        $this->assertDatabaseHas('users', [
            'id' => $adopter->id,
            'name' => 'AYU WATI KUSUMA',
            'city' => 'Jakarta Selatan',
        ]);
    }

    public function test_admin_can_delete_adopter_safely(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $adopter = User::where('email', 'madedalam@example.com')->first();

        $response = $this->actingAs($admin)->delete("/admin/adopters/{$adopter->id}");
        $response->assertRedirect('/admin/adopters');

        $this->assertDatabaseMissing('users', [
            'id' => $adopter->id,
        ]);
        // Trees previously linked must remain intact with user_id set to null
        $this->assertDatabaseHas('trees', [
            'tree_code' => 'TR-0150',
            'user_id' => null,
        ]);
    }

    public function test_admin_can_store_tree_with_selected_adopter_user(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $adopter = User::where('email', 'ayuwati@example.com')->first();
        $species = Species::where('key', 'moringa')->first();

        $response = $this->actingAs($admin)->post('/admin/trees', [
            'tree_code' => 'TR-0300',
            'token' => 't9y8u7',
            'species_id' => $species->id,
            'user_id' => $adopter->id,
            'adopter_name' => $adopter->formatted_adopter_name,
            'planted_date' => '2026-10-20',
            'plot' => 'Semaya, Lajur 6',
        ]);

        $response->assertRedirect('/admin/trees');
        $this->assertDatabaseHas('trees', [
            'tree_code' => 'TR-0300',
            'user_id' => $adopter->id,
            'adopter_name' => 'AYU WATI, Jakarta',
        ]);
    }

    public function test_admin_can_bulk_delete_trees(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $tree1 = Tree::where('tree_code', 'TR-0142')->first();
        $tree2 = Tree::where('tree_code', 'TR-0143')->first();

        $response = $this->actingAs($admin)->post('/admin/trees/bulk-destroy', [
            'ids' => "{$tree1->id},{$tree2->id}",
        ]);

        $response->assertRedirect('/admin/trees');
        $this->assertDatabaseMissing('trees', ['id' => $tree1->id]);
        $this->assertDatabaseMissing('trees', ['id' => $tree2->id]);
    }

    public function test_admin_can_bulk_delete_adopters_safely(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $user1 = User::where('email', 'ayuwati@example.com')->first();
        $user2 = User::where('email', 'danielwong@example.com')->first();

        $response = $this->actingAs($admin)->post('/admin/adopters/bulk-destroy', [
            'ids' => "{$user1->id},{$user2->id}",
        ]);

        $response->assertRedirect('/admin/adopters');
        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
        // Admin must never be deleted
        $this->assertDatabaseHas('users', ['email' => 'admin@adopsi.test']);
    }

    public function test_admin_update_form_loads_gracefully_when_trees_table_is_empty(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        // Kosongkan semua data pohon
        Tree::query()->delete();

        $response = $this->actingAs($admin)->get('/admin/update');
        $response->assertStatus(200);
        $response->assertSee('Belum Ada Pohon Terdaftar');
        $response->assertSee('Daftarkan Pohon Baru');
    }

    public function test_admin_can_edit_previous_month_measurement_data_and_photo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::where('email', 'admin@adopsi.test')->first();
        $tree = Tree::where('tree_code', 'TR-0142')->first();

        // Pohon saat ini sudah di bulan 14 (atau bulan ke-6 ke atas)
        $this->assertGreaterThanOrEqual(6, $tree->current_week);

        // Update / Koreksi data bulan ke-1 yang sudah lampau
        $newPhoto = \Illuminate\Http\UploadedFile::fake()->image('revisi_bulan1.jpg', 600, 600);
        $response = $this->actingAs($admin)->post('/admin/update', [
            'tree_id' => $tree->id,
            'week_number' => 1,
            'measurement_cm' => 38.5,
            'leaf_count' => 14,
            'pruned_weight_g' => 0,
            'health' => 'good',
            'note' => 'Koreksi data pengukuran dan foto bibit bulan pertama.',
            'photo' => $newPhoto,
            'action' => 'publish',
        ]);

        $response->assertRedirect('/admin/update?tree_id=' . $tree->id . '&month=1');

        // Pastikan record bulan 1 telah terupdate
        $this->assertDatabaseHas('tree_measurements', [
            'tree_id' => $tree->id,
            'week_number' => 1,
            'measurement_cm' => 38.5,
            'leaf_count' => 14,
            'note' => 'Koreksi data pengukuran dan foto bibit bulan pertama.',
        ]);

        // Pastikan progress current_week pohon tidak turun (tetap memegang bulan tertinggi)
        $tree->refresh();
        $this->assertGreaterThanOrEqual(14, $tree->current_week);
    }

    public function test_admin_update_form_loads_successfully_with_specific_tree_id(): void
    {
        $admin = User::where('email', 'admin@adopsi.test')->first();
        $tree = Tree::where('tree_code', 'TR-0142')->first();

        $response = $this->actingAs($admin)->get('/admin/update?tree_id=' . $tree->id);
        $response->assertStatus(200);
        $response->assertSee('TR-0142');
        $response->assertSee('Input & Edit Perkembangan Bulanan');
    }
}
