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
    Schema::create('promotional_images', function (Blueprint $table) {
        $table->id();
           $table->unsignedBigInteger('user_id');
$table->string('batch_id', 10);
        $table->string('category');
        $table->string('sub_category');
        $table->string('sub_sub_category')->nullable();
        $table->string('title');
        $table->text('brief_description')->nullable();
        $table->date('preferred_date')->nullable();
        $table->string('attach_image')->nullable(); 
        $table->string('attach_url')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_images');
    }
};
