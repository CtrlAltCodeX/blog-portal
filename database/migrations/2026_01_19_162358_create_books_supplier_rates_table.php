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

            $table->string('book_title');
            $table->string('publisher_name');

            $table->decimal('supplier_1_rate', 10, 2)->nullable();
            $table->decimal('supplier_2_rate', 10, 2)->nullable();
            $table->decimal('supplier_3_rate', 10, 2)->nullable();
            $table->decimal('supplier_4_rate', 10, 2)->nullable();
            $table->decimal('supplier_5_rate', 10, 2)->nullable();
            $table->decimal('supplier_6_rate', 10, 2)->nullable();
            $table->decimal('supplier_7_rate', 10, 2)->nullable();
            $table->decimal('supplier_8_rate', 10, 2)->nullable();
            $table->decimal('supplier_9_rate', 10, 2)->nullable();
            $table->decimal('supplier_10_rate', 10, 2)->nullable();

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
