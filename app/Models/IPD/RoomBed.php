<?php

namespace App\Models\IPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomBed extends Model
{
    use HasFactory;
    protected $table ='room_beds';
    protected $fillable = [
        'room_id',
        'bed_number',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
