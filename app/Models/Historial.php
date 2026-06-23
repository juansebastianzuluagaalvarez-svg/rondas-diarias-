<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Historial extends Model
{
    use HasFactory;

    protected $table = 'historial';

    protected $fillable = [
        'date',
        'room_id',
        'room_name',
        'section',
        'anterior',
        'nuevo',
        'observacion',
        'ts',
        'user_id',
    ];

    protected $casts = [
        'ts' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('section')) {
            $query->where('section', 'like', $request->section . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('room_name', 'like', "%{$search}%")
                    ->orWhereHas('room', function (Builder $q2) use ($search) {
                        $q2->where('room_id', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('only_bathrooms') && $request->only_bathrooms) {
            $query->where('section', 'like', '%Baños%');
        }

        return $query;
    }
}
