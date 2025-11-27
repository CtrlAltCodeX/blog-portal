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
        Schema::create('work_types', function (Blueprint $table) {
            $table->id();
            $table->string('cause');      // Work / Cause Name
            $table->decimal('amount', 10, 2); // Expected amount
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_types');
    }
};
