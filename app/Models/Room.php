<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'name',
        'section',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function roomStates(): HasMany
    {
        return $this->hasMany(RoomState::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(Historial::class);
    }
}
