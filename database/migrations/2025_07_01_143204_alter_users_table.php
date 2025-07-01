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
            $table->string('account_type')->nullable()->change();
            $table->string('aadhaar_no')->nullable()->change();
            $table->string('father_name')->nullable()->change();
            $table->string('mother_name')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('pincode')->nullable()->change();
            $table->text('full_address')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('plain_password')->nullable()->change();
            $table->integer('allow_sessions')->nullable()->change();
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
