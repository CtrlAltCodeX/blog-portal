<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('weight_vs_couriers', function (Blueprint $table) {
            $table->id();
            $table->string('pub_name');
            $table->string('book_type_1')->nullable();
            $table->string('book_discount_1')->nullable();
            $table->string('book_type_2')->nullable();
            $table->string('book_discount_2')->nullable();
            $table->string('book_type_3')->nullable();
            $table->string('book_discount_3')->nullable();
            $table->string('book_type_4')->nullable();
            $table->string('book_discount_4')->nullable();
            $table->string('book_type_5')->nullable();
            $table->string('book_discount_5')->nullable();
            $table->string('book_type_6')->nullable();
            $table->string('book_discount_6')->nullable();
            $table->string('location_dis')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weight_vs_couriers');
    }
};
