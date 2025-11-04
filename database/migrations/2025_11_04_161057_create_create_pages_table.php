<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('create_pages', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('category_id')->nullable();
        $table->unsignedBigInteger('sub_category_id')->nullable();
        $table->string('any_preferred_date')->default('No');
        $table->date('date')->nullable();
        $table->string('upload')->nullable();
        $table->string('batch_id', 10);
        $table->string('status')->default('pending');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_pages');
    }
};
