<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tree_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tree_id')->constrained('trees')->cascadeOnDelete();
            $table->integer('week_number');
            $table->double('measurement_cm');
            $table->integer('leaf_count')->nullable();
            $table->double('pruned_weight_g')->default(0);
            $table->string('health')->default('good'); // good, watch, poor
            $table->text('note')->nullable();
            $table->string('photo_path')->nullable();
            $table->double('carbon_absorbed_kg')->nullable();
            $table->boolean('is_published')->default(true);
            $table->date('logged_at')->nullable();
            $table->timestamps();

            $table->unique(['tree_id', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_measurements');
    }
};
