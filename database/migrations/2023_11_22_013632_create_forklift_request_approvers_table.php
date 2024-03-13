<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForkliftRequestApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forklift_request_approvers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('forklift_request_id')->unsigned();

            $table->string('requestor')->nullable();
            $table->string('requestor_date_time_approved_disapproved')->nullable();

            $table->string('department_approver')->nullable();
            $table->string('department_date_time_approved_disapproved')->nullable();

            $table->string('traffic_sr_supervisor_approver')->nullable();
            $table->string('traffic_sr_supervisor_date_time_approved_disapproved')->nullable();

            $table->string('forklift_operator_approver')->nullable();
            $table->string('forklift_operator_date_time_approved_disapproved')->nullable();

            $table->string('approval_remarks')->nullable();

            $table->unsignedTinyInteger('logdel')->default(0)->comment = '0-show,1-hide';
            $table->timestamps();

            // Foreign Key
            $table->foreign('forklift_request_id')->references('id')->on('forklift_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forklift_request_approvers');
    }
}
