<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLabours extends Model
{
    use HasFactory;
    protected $table = 'tbl_project_labours';
    protected $primaryKey = 'id';
}
