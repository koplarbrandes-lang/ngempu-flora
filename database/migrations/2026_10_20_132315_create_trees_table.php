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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->string('tree_code')->unique();
            $table->string('token')->unique();
            $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
            $table->string('adopter_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('planted_date');
            $table->string('plot')->nullable();
            $table->integer('current_week')->default(0);
            $table->date('last_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
