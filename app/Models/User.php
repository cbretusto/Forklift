<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\RapidXUser;

class User extends Model
{
    // use HasFactory;
    protected $table = "users";
    protected $connection = "mysql";

    public function rapidx_user_info(){
        return $this->hasOne(RapidXUser::class, 'employee_number', 'employee_no');
    } 
}
