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
        Schema::create('on_demand_listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by');
            $table->string('image');
            $table->string('category')->comment('Create, Update');
            $table->string('status')->default('Requested')->comment('Requested, Completed');
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('completed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('on_demand_listings');
    }
};
