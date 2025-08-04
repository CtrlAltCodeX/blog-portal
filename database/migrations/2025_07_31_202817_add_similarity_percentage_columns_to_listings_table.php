<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('listings', function (Blueprint $table) {
            $table->float('similarity_percentage')->nullable();
            $table->float('change_percentage')->nullable();
        });
    }

    public function down(): void {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('similarity_percentage');
            $table->dropColumn('change_percentage');
        });
    }
};
