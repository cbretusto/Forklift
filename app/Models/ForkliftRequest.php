<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ForkliftRequestApprover;

class ForkliftRequest extends Model
{
    // use HasFactory;
    protected $table = "forklift_requests";
    protected $connection = "mysql";

    public function forklift_request_approver_info(){
        return $this->hasOne(ForkliftRequestApprover::class, 'forklift_request_id', 'id');
    } 

    public function employee_info(){
        return $this->hasOne(User::class, 'employee_no', 'employee_no');
    } 
}
