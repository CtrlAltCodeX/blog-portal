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
        Schema::create('books_supplier_rates', function (Blueprint $table) {
            $table->id();

            $table->string('book_title')->nullable();
            $table->string('publisher_name')->nullable();

            $table->string('supplier_1_rate')->nullable();
            $table->string('supplier_2_rate')->nullable();
            $table->string('supplier_3_rate')->nullable();
            $table->string('supplier_4_rate')->nullable();
            $table->string('supplier_5_rate')->nullable();
            $table->string('supplier_6_rate')->nullable();
            $table->string('supplier_7_rate')->nullable();
            $table->string('supplier_8_rate')->nullable();
            $table->string('supplier_9_rate')->nullable();
            $table->string('supplier_10_rate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books_supplier_rates');
    }
};
