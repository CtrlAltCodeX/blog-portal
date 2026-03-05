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
        Schema::create('marketplace_commissions', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_range', 10, 2)->nullable();
            $table->decimal('max_range', 10, 2)->nullable();
            $table->decimal('min_commission', 10, 2)->nullable();
            $table->decimal('max_commission', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_commissions');
    }
};
