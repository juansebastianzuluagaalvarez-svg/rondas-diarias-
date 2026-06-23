<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Ronda;
use App\Models\RoomState;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $historial = Historial::with('room', 'user')
            ->orderBy('ts', 'desc')
            ->applyFilters($request)
            ->paginate(50);

        return response()->json([
            'historial' => $historial,
        ]);
    }

    public function update(Request $request, Historial $historial)
    {
        $request->validate([
            'nuevo' => 'required|in:funciona,no-funciona,aislado,no-hay',
            'observacion' => 'nullable|string',
        ]);

        $historial->update([
            'nuevo' => $request->nuevo,
            'observacion' => $request->observacion,
        ]);

        $ronda = Ronda::where('date', $historial->date)->first();
        if ($ronda && !$ronda->completada) {
            RoomState::where('ronda_id', $ronda->id)
                ->where('room_id', $historial->room_id)
                ->update([
                    'estado' => $request->nuevo,
                    'observacion' => $request->observacion,
                ]);
        }

        return response()->json(['success' => true, 'historial' => $historial->fresh()]);
    }

    public function destroy(Historial $historial)
    {
        $historial->delete();
        return response()->json(['success' => true]);
    }
}
