<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemOneSection extends Model
{
    // use HasFactory;

    protected $table = 'tbl_Section';
    protected $connection = 'mysql_systemone';
}
