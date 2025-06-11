<?php

namespace App\Models\HRMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCashSummary extends Model
{
    use HasFactory;
    protected $table = 'user_cash_summaries';
    protected $fillable = ['user_id','receipt_count','total_amount','collected_amount','remaining_amount','opening_balance','status','activity_time','approved_by','created_by','updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

}

