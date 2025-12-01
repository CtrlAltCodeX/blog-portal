<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up()
    {
        // Rename category → category_id
        if (Schema::hasColumn('promotional', 'category')) {
            DB::statement("ALTER TABLE `promotional` CHANGE `category` `category_id` BIGINT(20) NULL;");
        }

        // Rename sub_category → sub_category_id
        if (Schema::hasColumn('promotional', 'sub_category')) {
            DB::statement("ALTER TABLE `promotional` CHANGE `sub_category` `sub_category_id` BIGINT(20) NULL;");
        }

        // Rename sub_sub_category → sub_sub_category_id
        if (Schema::hasColumn('promotional', 'sub_sub_category')) {
            DB::statement("ALTER TABLE `promotional` CHANGE `sub_sub_category` `sub_sub_category_id` BIGINT(20) NULL;");
        }
    }

    public function down()
    {
        if (Schema::hasColumn('promotional', 'category_id')) {
            DB::statement("ALTER TABLE `promotional` CHANGE `category_id` `category` BIGINT(20) NULL;");
        }

        if (Schema::hasColumn('promotional', 'sub_category_id')) {
            DB::statement("ALTER TABLE `promotional` CHANGE `sub_category_id` `sub_category` BIGINT(20) NULL;");
        }

        if (Schema::hasColumn('promotional', 'sub_sub_category_id')) {
            DB::statement("ALTER TABLE `promotional` CHANGE `sub_sub_category_id` `sub_sub_category` BIGINT(20) NULL;");
        }
    }
};
