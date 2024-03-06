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
        Schema::table('google_credentails', function (Blueprint $table) {
            $table->string('scope');
            $table->string('merchant_id')->nullable();
            $table->string('blog_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('google_credentails', function (Blueprint $table) {
            //
        });
    }
};
