<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RosterType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
