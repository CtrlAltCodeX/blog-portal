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
        Schema::table('content', function (Blueprint $table) {

            $table->unsignedBigInteger('verified_by')->nullable()->after('user_id');
            $table->date('verified_date')->nullable();
            $table->time('verified_time')->nullable();

            $table->enum('status', ['pending', 'approved', 'denied'])
                ->default('pending');

            $table->text('rejection_cause')->nullable();

            $table->unsignedBigInteger('worktype_id')->nullable();
            $table->decimal('expected_amount', 10, 2)->nullable();

            $table->text('content_report_note')->nullable();
            $table->text('host_record_note')->nullable();

            $table->foreign('verified_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('worktype_id')->references('id')->on('work_types')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('content', function (Blueprint $table) {
            $table->dropColumn([
                'verified_by',
                'verified_date',
                'verified_time',
                'status',
                'rejection_cause',
                'worktype_id',
                'expected_amount',
                'content_report_note',
                'host_record_note'
            ]);
        });
    }
};
