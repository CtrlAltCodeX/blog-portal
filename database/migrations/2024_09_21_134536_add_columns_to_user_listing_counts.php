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
        Schema::table('user_listing_counts', function (Blueprint $table) {
            $table->enum('status', ['Created', 'Edited']);
            $table->integer('delete_count')->default(0);
            $table->integer('create_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_listing_counts', function (Blueprint $table) {
            //
        });
    }
};
