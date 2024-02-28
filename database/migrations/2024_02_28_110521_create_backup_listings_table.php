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
        Schema::create('backup_listings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->text('title');
            $table->longText('description');
            $table->double('mrp');
            $table->double('selling_price');
            $table->string('publisher');
            $table->string('author_name');
            $table->string('edition');
            $table->json('categories');
            $table->string('sku');
            $table->string('language');
            $table->string('no_of_pages');
            $table->string('condition');
            $table->string('binding_type');
            $table->string('insta_mojo_url');
            $table->text('base_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_listings');
    }
};
