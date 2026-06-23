<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HistorialPdfController extends Controller
{
    public function download(Request $request)
    {
        $historial = Historial::with('room', 'user')
            ->orderBy('ts', 'desc')
            ->applyFilters($request)
            ->get();

        // Generar el PDF con la plantilla
        $pdf = Pdf::loadView('historial.pdf', [
            'historial' => $historial,
            'fecha' => now()->format('d/m/Y H:i:s'),
        ]);

        // Descargar el PDF con un nombre descriptivo
        return $pdf->download('historial_llamados_' . now()->format('YmdHis') . '.pdf');
    }
}