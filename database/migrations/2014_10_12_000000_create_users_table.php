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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->tinyInteger('account_type');
            $table->integer('aadhaar_no');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('state');
            $table->integer('pincode');
            $table->text('full_address');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('plain_password');
            $table->boolean('status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
