<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_number',
        'room_number',
        'capacity',
        'status',
        'guest_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
