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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('issue_type_id');
            $table->unsignedBigInteger('department_id');
            $table->string('title', 1000);
            $table->text('description');
            $table->string('delivery_timeline')->default('3 Days');
            $table->boolean('specific_tag')->default(false);
            $table->string('employee_name')->nullable();
            $table->string('employee_email')->nullable();
            $table->string('employee_mobile')->nullable();
            $table->boolean('send_mail')->default(false);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
