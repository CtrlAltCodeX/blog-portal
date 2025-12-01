<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename category → category_id
        if (Schema::hasColumn('content', 'category')) {
            DB::statement("ALTER TABLE `content` CHANGE `category` `category_id` BIGINT(20) NULL;");
        }

        // Rename sub_category → sub_category_id
        if (Schema::hasColumn('content', 'sub_category')) {
            DB::statement("ALTER TABLE `content` CHANGE `sub_category` `sub_category_id` BIGINT(20) NULL;");
        }

        // Rename sub_sub_category → sub_sub_category_id
        if (Schema::hasColumn('content', 'sub_sub_category')) {
            DB::statement("ALTER TABLE `content` CHANGE `sub_sub_category` `sub_sub_category_id` BIGINT(20) NULL;");
        }
    }

    public function down()
    {
        if (Schema::hasColumn('content', 'category_id')) {
            DB::statement("ALTER TABLE `content` CHANGE `category_id` `category` BIGINT(20) NULL;");
        }

        if (Schema::hasColumn('content', 'sub_category_id')) {
            DB::statement("ALTER TABLE `content` CHANGE `sub_category_id` `sub_category` BIGINT(20) NULL;");
        }

        if (Schema::hasColumn('content', 'sub_sub_category_id')) {
            DB::statement("ALTER TABLE `content` CHANGE `sub_sub_category_id` `sub_sub_category` BIGINT(20) NULL;");
        }
    }
};
