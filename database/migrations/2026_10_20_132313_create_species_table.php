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
        Schema::create('species', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('display_name');
            $table->string('label')->default('Height');
            $table->double('start')->default(0);
            $table->double('gain')->default(0);
            $table->double('tau')->default(1);
            $table->double('param_a')->default(0);
            $table->double('param_b')->default(1);
            $table->double('root_share')->default(0.20);
            $table->boolean('has_pruning')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('species');
    }
};
