<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $table = 'invoice_items';
    protected $fillable = ['product_id', 'currency_id', 'item_rate', 'item_quantity', 'item_amount','item_notes'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
