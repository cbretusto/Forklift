<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SystemOneDepartment;
use App\Models\SystemOneSection;

class SystemOneHRIS extends Model
{
    // use HasFactory;
    protected $table = 'tbl_EmployeeInfo';
    protected $connection = 'mysql_systemone';

    public function department_info(){
        return $this->hasOne(SystemOneDepartment::class, 'pkid', 'fkDepartment');
    } 

    public function section_info(){
        return $this->hasOne(SystemOneSection::class, 'pkid', 'fkSection');
    } 
}
