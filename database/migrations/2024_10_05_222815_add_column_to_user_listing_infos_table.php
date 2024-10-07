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
        Schema::table('user_listing_infos', function (Blueprint $table) {
            $table->enum('status_listing', ['Created', 'Edited']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_listing_infos', function (Blueprint $table) {
            //
        });
    }
};
