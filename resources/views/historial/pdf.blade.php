<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de cambios - Sistema de Llamados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.5;
        }

        .header {
            background-color: #1e3a8a;
            color: white;
            padding: 28px 40px;
            margin-bottom: 30px;
        }

        .header-content {
            width: 100%;
        }

        .header-row {
            width: 100%;
            display: table;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 160px;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 200px;
        }

        .header-center {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 50%;
        }

        .header-center h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .header-center p {
            font-size: 11px;
        }

        .meta-info {
            font-size: 11px;
        }

        .meta-info p {
            margin-bottom: 3px;
        }

        .content {
            padding: 0 40px 40px;
        }

        .summary-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .summary-title {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px;
        }

        .summary-item {
            text-align: center;
            padding: 20px 10px;
            border-radius: 10px;
            background-color: white;
            border: 1px solid #dbeafe;
            width: 25%;
            vertical-align: top;
        }

        .summary-item.funciona {
            border-top: 5px solid #16a34a;
            background-color: #f0fdf4;
        }

        .summary-item.no-funciona {
            border-top: 5px solid #dc2626;
            background-color: #fef2f2;
        }

        .summary-item.aislado {
            border-top: 5px solid #d97706;
            background-color: #fffbeb;
        }

        .summary-item.no-hay {
            border-top: 5px solid #6b7280;
            background-color: #f8fafc;
        }

        .summary-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .summary-number {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 6px;
            color: #1e293b;
        }

        .summary-label {
            font-size: 12px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 10px;
            overflow: hidden;
        }

        .main-table th {
            background-color: #1e3a8a;
            color: white;
            text-align: left;
            padding: 14px 12px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .main-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .main-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .estado-chip {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .estado-chip.funciona {
            background: #dcfce7;
            color: #15803d;
        }

        .estado-chip.no-funciona {
            background: #fee2e2;
            color: #b91c1c;
        }

        .estado-chip.aislado {
            background: #fef3c7;
            color: #92400e;
        }

        .estado-chip.no-hay {
            background: #f3f4f6;
            color: #374151;
        }

        .cambio-estado {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .flecha {
            color: #64748b;
            font-size: 12px;
        }

        .seccion-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #eef2ff;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 10px;
            color: #16468e;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-row">
                <div class="header-left">
                    @php $logoPath = public_path('img/logo.png'); @endphp
                    <img src="{{ file_exists($logoPath) ? $logoPath : '' }}" alt="Logo" style="width: 145px; height: auto; display: inline-block; vertical-align: middle;">
                </div>
                <div class="header-center">
                    <h1>Historial de cambios</h1>
                    <p>Sistema de Control de Llamados</p>
                </div>
                <div class="header-right">
                    <div class="meta-info">
                        <p><strong>Generado:</strong> {{ $fecha }}</p>
                        <p><strong>Registros:</strong> {{ $historial->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if($historial->count() > 0)
            @php
                $estados = [
                    'funciona' => 0,
                    'no-funciona' => 0,
                    'aislado' => 0,
                    'no-hay' => 0
                ];
                
                foreach($historial as $h) {
                    if(isset($estados[$h->nuevo])) {
                        $estados[$h->nuevo]++;
                    }
                }
            @endphp

            <div class="summary-section">
                <div class="summary-title">Resumen de cambios en el histórico</div>
                <table class="summary-table">
                    <tr>
                        <td class="summary-item funciona">
                            <div class="summary-number">{{ $estados['funciona'] }}</div>
                            <div class="summary-label">Funciona</div>
                        </td>
                        <td class="summary-item no-funciona">
                            <div class="summary-number">{{ $estados['no-funciona'] }}</div>
                            <div class="summary-label">No Funciona</div>
                        </td>
                        <td class="summary-item aislado">
                            <div class="summary-number">{{ $estados['aislado'] }}</div>
                            <div class="summary-label">Aislado</div>
                        </td>
                        <td class="summary-item no-hay">
                            <div class="summary-number">{{ $estados['no-hay'] }}</div>
                            <div class="summary-label">No Hay</div>
                        </td>
                    </tr>
                </table>
            </div>

            <table class="main-table">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Fecha</th>
                        <th>Sección</th>
                        <th>Habitación</th>
                        <th>Estado Anterior</th>
                        <th>Estado Nuevo</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historial as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->ts)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                            <td><span class="seccion-badge">{{ $item->section }}</span></td>
                            <td>
                                <strong>{{ $item->room_name }}</strong><br>
                                @if($item->room)
                                    <span style="font-family: monospace; font-size: 10px; color: #64748b;">{{ $item->room->room_id }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->anterior)
                                                    <span class="estado-chip {{ $item->anterior }}">
                                        {{ ['funciona' => 'Funciona', 'no-funciona' => 'No Funciona', 'aislado' => 'Aislado', 'no-hay' => 'No Hay'][$item->anterior] ?? $item->anterior }}
                                    </span>
                                @else
                                    <span style="color: #64748b; font-style: italic;">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="estado-chip {{ $item->nuevo }}">
                                    {{ ['funciona' => 'Funciona', 'no-funciona' => 'No Funciona', 'aislado' => 'Aislado', 'no-hay' => 'No Hay'][$item->nuevo] ?? $item->nuevo }}
                                </span>
                            </td>
                            <td>{{ $item->observacion ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <p>No hay registros de histórico para mostrar.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        Este documento fue generado automáticamente por el Sistema de Control de Llamados de CAC Santa Bárbara.
    </div>
</body>
</html>
