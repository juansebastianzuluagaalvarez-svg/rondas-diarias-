<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Room;
use App\Models\RoomState;
use App\Models\Ronda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RondaController extends Controller
{
    public function getOrCreateToday()
    {
        $today = Carbon::now()->hour < 7 ? Carbon::yesterday() : Carbon::today();
        $ronda = Ronda::firstOrCreate(
            ['date' => $today->toDateString()],
            ['completada' => false]
        );

        $ronda->load('roomStates.room');

        $defaultStates = config('rondas.default_states');
        $defaultObservations = config('rondas.default_observations');

        $roomStatesKeyed = $ronda->roomStates->keyBy('room_id');

        $rooms = Room::all()->map(function ($room) use ($ronda, $roomStatesKeyed, $defaultStates, $defaultObservations) {
            $roomState = $roomStatesKeyed->get($room->id);
            $estado = $roomState ? $roomState->estado : ($defaultStates[$room->room_id] ?? 'funciona');
            $observacion = $roomState ? $roomState->observacion : ($defaultObservations[$room->room_id] ?? null);

            return [
                ...$room->toArray(),
                'estado' => $estado,
                'observacion' => $observacion,
                'timestamp' => $roomState?->timestamp,
            ];
        });

        return response()->json([
            'ronda' => $ronda,
            'rooms' => $rooms,
        ]);
    }

    public function updateRoomState(Request $request, Ronda $ronda, Room $room)
    {
        $request->validate([
            'estado' => 'required|in:funciona,no-funciona,aislado,no-hay',
            'observacion' => 'nullable|string',
        ]);

        $user = $request->user();

        DB::transaction(function () use ($ronda, $room, $request, $user) {
            $previousState = RoomState::where('ronda_id', $ronda->id)
                ->where('room_id', $room->id)
                ->lockForUpdate()
                ->first();

            $anterior = $previousState?->estado;

            RoomState::updateOrCreate(
                ['ronda_id' => $ronda->id, 'room_id' => $room->id],
                [
                    'estado' => $request->estado,
                    'observacion' => $request->observacion,
                    'timestamp' => now(),
                ]
            );

            Historial::create([
                'date' => $ronda->date,
                'room_id' => $room->id,
                'room_name' => $room->name,
                'section' => $room->section,
                'anterior' => $anterior,
                'nuevo' => $request->estado,
                'observacion' => $request->observacion,
                'ts' => now(),
                'user_id' => $user->id,
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function finalize(Request $request, Ronda $ronda)
    {
        $user = $request->user();

        $funciona = 0;
        $noFunciona = 0;
        $aislado = 0;
        $noHay = 0;

        foreach ($ronda->roomStates as $state) {
            switch ($state->estado) {
                case 'funciona':
                    $funciona++;
                    break;
                case 'no-funciona':
                    $noFunciona++;
                    break;
                case 'aislado':
                    $aislado++;
                    break;
                case 'no-hay':
                    $noHay++;
                    break;
            }
        }

        $ronda->update([
            'completada' => true,
            'completada_at' => now(),
            'user_id' => $user->id,
            'resumen' => [
                'funciona' => $funciona,
                'no-funciona' => $noFunciona,
                'aislado' => $aislado,
                'no-hay' => $noHay,
            ],
        ]);

        return response()->json(['success' => true, 'ronda' => $ronda->fresh()]);
    }

    public function reopen(Request $request, Ronda $ronda)
    {
        $ronda->update([
            'completada' => false,
            'completada_at' => null,
        ]);

        return response()->json(['success' => true, 'ronda' => $ronda->fresh()]);
    }
}
