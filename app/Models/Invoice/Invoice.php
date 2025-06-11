<?php

namespace App\Models\Finance;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'invoice_date',
        'due_date',
        'invoice_remarks',
        'invoice_subtotal',
        'tax_id',
        'invoice_total',
        'bank_id',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice_item()
    {
        return $this->hasMany(InvoiceItem::class);
    }

}
