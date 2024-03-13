<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForkliftRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forklift_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('department')->nullable();
            $table->string('date_needed')->nullable();
            $table->string('time')->nullable();
            $table->string('pick_up_from')->nullable();
            $table->string('delivery_to')->nullable();
            $table->string('package_commodity')->nullable();
            $table->unsignedTinyInteger('volume_of_trips')->nullable();
            $table->unsignedTinyInteger('assign_forklift_operator')->nullable();

            $table->unsignedTinyInteger('approval_status')
                ->default(0)
                ->comment =  '0-requestor,
                                1-sect.head/supervisor/clerk,
                                2-traffic sr.supervisor,
                                3-forklift operator,
                                4-cancelled,
                                5-requestor disapproved,
                                6-sect.head/supervisor disapproved,
                                7-traffic sr.supervisor disapproved,
                                8-forklift operator disapproved,
                                9-approved';

            $table->unsignedTinyInteger('request_status')->default(0)->comment = '0-open,1-served';
            $table->unsignedTinyInteger('logdel')->default(0)->comment = '0-show,1-hide';
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forklift_requests');
    }
}
