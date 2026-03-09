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
        Schema::create('market_place_calculation_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('min', 12, 2)->default(0);
            $table->decimal('max', 12, 2)->default(0);
            $table->decimal('weight', 10, 2)->default(0);
            $table->decimal('courier_rate', 10, 2)->default(0);
            $table->decimal('packing_charge', 10, 2)->default(0);
            $table->decimal('our_min_profit', 10, 2)->default(0);
            $table->decimal('max_profit', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_place_calculation_settings');
    }
};
