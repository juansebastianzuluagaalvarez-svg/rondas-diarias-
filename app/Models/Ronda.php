<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ronda extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'completada',
        'completada_at',
        'user_id',
        'resumen',
    ];

    protected $casts = [
        'date' => 'date',
        'completada' => 'boolean',
        'completada_at' => 'datetime',
        'resumen' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roomStates(): HasMany
    {
        return $this->hasMany(RoomState::class);
    }
}
