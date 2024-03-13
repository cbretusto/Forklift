<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RapidXUser;
use App\Models\ForkliftRequest;

class ForkliftRequestApprover extends Model
{
    // use HasFactory;

    protected $table = "forklift_request_approvers";
    protected $connection = "mysql";

    public function request_info(){
        return $this->hasOne(ForkliftRequest::class, 'id', 'forklift_request_id');
    } 

    public function requestor_approver_info(){
        return $this->hasOne(RapidXUser::class, 'employee_number', 'requestor');
    } 

    public function department_approver_info(){
        return $this->hasOne(RapidXUser::class, 'employee_number', 'department_approver');
    } 

    public function traffic_sr_supervisor_approver_info(){
        return $this->hasOne(RapidXUser::class, 'employee_number', 'traffic_sr_supervisor_approver');
    } 

    public function forklift_operator_approver_info(){
        return $this->hasOne(RapidXUser::class, 'employee_number', 'forklift_operator_approver');
    } 
}
