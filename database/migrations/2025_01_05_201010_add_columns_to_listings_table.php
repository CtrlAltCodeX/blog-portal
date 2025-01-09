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
        Schema::table('listings', function (Blueprint $table) {
            $table->string('isbn_10')->nullable();
            $table->string('isbn_13')->nullable();
            $table->string('publish_year')->nullable();
            $table->string('weight')->nullable();
            $table->string('reading_age')->nullable();
            $table->string('country_origin')->nullable();
            $table->string('genre')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('importer')->nullable();
            $table->string('packer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            //
        });
    }
};
