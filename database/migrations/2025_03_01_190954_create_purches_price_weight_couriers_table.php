<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('purches_price_weight_couriers', function (Blueprint $table) {
            $table->id();
            $table->decimal('min', 8, 2);
            $table->decimal('max', 8, 2);
            $table->decimal('weight', 8, 2);
            $table->decimal('courier_rate', 8, 2);
            $table->decimal('packing_charge', 8, 2);
            $table->decimal('our_min_profit', 8, 2);
            $table->decimal('max_profit', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purches_price_weight_couriers');
    }
};

