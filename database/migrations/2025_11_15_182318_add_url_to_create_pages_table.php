<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('create_pages', function (Blueprint $table) {
        $table->string('url')->nullable()->after('upload');
    });
}

public function down()
{
    Schema::table('create_pages', function (Blueprint $table) {
        $table->dropColumn('url');
    });
}

};
