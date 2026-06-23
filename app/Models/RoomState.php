<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomState extends Model
{
    use HasFactory;

    protected $fillable = [
        'ronda_id',
        'room_id',
        'estado',
        'observacion',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'estado' => 'string',
    ];

    public function ronda(): BelongsTo
    {
        return $this->belongsTo(Ronda::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
