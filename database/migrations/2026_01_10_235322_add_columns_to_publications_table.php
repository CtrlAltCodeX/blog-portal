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
        Schema::table('weight_vs_couriers', function (Blueprint $table) {
            $table->string('company_activity')->nullable();
            $table->string('sourcing_pattern')->nullable();
            $table->string('sourcing_city')->nullable();
            $table->string('official_url')->nullable();
            $table->string('sku_pattern')->nullable();
            $table->string('marginal_gaps')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            //
        });
    }
};
