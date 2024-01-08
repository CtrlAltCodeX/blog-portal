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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('mobile');
            $table->tinyInteger('account_type');
            $table->integer('aadhaar_no');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('state');
            $table->integer('pincode');
            $table->text('full_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
