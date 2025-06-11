<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTestDetail extends Model
{
    use HasFactory;
    protected $table ='patient_test_details';
    protected $fillable =['patient_test_id','remarks'];
}
