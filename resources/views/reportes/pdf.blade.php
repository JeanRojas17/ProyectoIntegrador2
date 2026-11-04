<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Envíos</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #212529; font-size: 11px; margin: 0; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .subtitulo { color: #6c757d; font-size: 11px; margin-bottom: 12px; }
        .filtros { background: #f1f3f5; border: 1px solid #dee2e6; padding: 8px 10px; margin-bottom: 12px; }
        .filtros span { margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #212529; color: #fff; text-align: left; padding: 6px 8px; font-size: 10px; }
        tbody td { border-bottom: 1px solid #dee2e6; padding: 6px 8px; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        .text-end { text-align: right; }
        .totales td { font-weight: bold; background: #e9ecef; border-top: 2px solid #212529; }
        .footer { margin-top: 16px; color: #6c757d; font-size: 9px; text-align: right; }
        .vacio { text-align: center; color: #6c757d; padding: 16px; }
    </style>
</head>
<body>
    <h1>Transportes RBL</h1>
    <div class="subtitulo">Reporte de Envíos</div>

    <div class="filtros">
        <span><strong>Estado:</strong> {{ $filtros['estado'] ?? 'Todos' }}</span>
        <span><strong>Ruta:</strong>
            @if($rutaSeleccionada)
                {{ $rutaSeleccionada->origen }} → {{ $rutaSeleccionada->destino }}
            @else
                Todas
            @endif
        </span>
        <span><strong>Desde:</strong> {{ $filtros['fecha_desde'] ?? 'Sin límite' }}</span>
        <span><strong>Hasta:</strong> {{ $filtros['fecha_hasta'] ?? 'Sin límite' }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Ruta</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th class="text-end">Peso (kg)</th>
                <th class="text-end">Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($envios as $envio)
                <tr>
                    <td>{{ $envio->codigo }}</td>
                    <td>{{ $envio->cliente->nombre ?? 'Sin cliente' }}</td>
                    <td>
                        @if($envio->ruta)
                            {{ $envio->ruta->origen }} → {{ $envio->ruta->destino }}
                        @else
                            Sin ruta
                        @endif
                    </td>
                    <td>{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y') : '' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $envio->estado)) }}</td>
                    <td class="text-end">{{ number_format($envio->peso_kg, 2) }}</td>
                    <td class="text-end">{{ number_format($envio->valor_declarado ?? 0, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="vacio">No hay envíos con los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
        @if($envios->isNotEmpty())
            <tfoot>
                <tr class="totales">
                    <td colspan="5">Total: {{ $envios->count() }} envíos</td>
                    <td class="text-end">{{ number_format($envios->sum('peso_kg'), 2) }}</td>
                    <td class="text-end">{{ number_format($envios->sum('valor_declarado'), 2) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">Generado el {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
