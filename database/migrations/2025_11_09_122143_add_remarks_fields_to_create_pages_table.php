<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('create_pages', function (Blueprint $table) {
            $table->text('official_remark')->nullable();
            $table->unsignedBigInteger('remarks_user_id')->nullable();
            $table->dateTime('remarks_date')->nullable();

            // Optional: if remarks_user_id relates to users table
            $table->foreign('remarks_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('create_pages', function (Blueprint $table) {
            $table->dropForeign(['remarks_user_id']);
            $table->dropColumn(['official_remark', 'remarks_user_id', 'remarks_date']);
        });
    }
};
