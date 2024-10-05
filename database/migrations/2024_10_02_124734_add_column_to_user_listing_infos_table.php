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
            $table->unsignedBigInteger('listings_id');

            $table->foreign('listings_id')
                ->references('id')
                ->on('listings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_listing_info', function (Blueprint $table) {
            //
        });
    }
};
