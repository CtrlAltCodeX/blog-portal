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
        Schema::create('on_demand_listing_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by');
            $table->integer('count');
            $table->string('category');
            $table->timestamps();

            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('on_demand_listing_logs');
    }
};
