<?php

namespace App\Models\OPD;

use App\Models\OPD\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'charges',
        'description',
        'tax_id',
        'category_id',
        'is_nursing',
        'is_advance',
    ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Add coa_id to fillable if accounts config exists
        if (file_exists(base_path('config/accounts.php'))) {
            $this->fillable[] = 'coa_id';
        }
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
