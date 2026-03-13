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
        Schema::create('complaint_reply_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_reply_id');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('complaint_reply_id')->references('id')->on('complaint_replies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_reply_attachments');
    }
};
