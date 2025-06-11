<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [ 'name','type'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Add coa_id to fillable if accounts config exists
        if (file_exists(base_path('config/accounts.php'))) {
            $this->fillable[] = 'coa_id';
        }
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
