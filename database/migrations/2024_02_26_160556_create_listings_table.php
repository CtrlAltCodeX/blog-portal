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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->double('mrp')->nullable();
            $table->double('selling_price')->nullable();
            $table->string('publisher')->nullable();
            $table->string('author_name')->nullable();
            $table->string('edition')->nullable();
            $table->json('categories')->nullable();
            $table->string('sku')->nullable();
            $table->string('language')->nullable();
            $table->string('no_of_pages')->nullable();
            $table->string('condition')->nullable();
            $table->string('binding_type')->nullable();
            $table->string('insta_mojo_url')->nullable();
            $table->json('images')->nullable();
            $table->json('multiple_images')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
