<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sukha — Reporte de Seguimiento Emocional - {{ $user->name }}</title>
    <!-- Favicon Permanente -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=4">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #2C3E35;
            margin: 30px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #7D9B76;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #2C3E35;
        }
        .meta {
            color: #64748b;
            font-size: 11px;
        }
        .disclaimer {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 10px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
        .print-btn {
            background: #6366f1;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 15px;
        }
        @media print {
            .print-btn { display: none; }
            body { margin: 10px; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ Imprimir / Guardar en PDF</button>

    <div class="header">
        <div style="display: flex; align-items: center; gap: 14px;">
            <img src="{{ asset('favicon.png') }}?v=4" alt="Sukha" style="width: 46px; height: 46px; object-fit: contain; border-radius: 10px;">
            <div>
                <div class="title">Sukha — Reporte de Bienestar y Seguimiento Emocional</div>
                <div class="meta">Usuario: <strong>{{ $user->name }}</strong> ({{ $user->email }}) • Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="disclaimer">
        <strong>Aviso Legal y Clínico:</strong> Este documento es un resumen de autorregistro preventivo generado a través de la plataforma web Sukha. <strong>No constituye un diagnóstico médico o psicológico.</strong> Puede ser presentado como insumo de orientación en una consulta profesional de salud mental.
    </div>

    @if($latestAssessment)
        <div style="margin-bottom: 20px; padding: 12px; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 6px;">
            <strong style="color: #6b21a8;">Último Tamizaje Realizado ({{ $latestAssessment->completed_at->format('d/m/Y') }}):</strong><br>
            Nivel de Tensión: <strong>{{ ucfirst($latestAssessment->risk_level) }}</strong> | 
            Puntaje Ansiedad: <strong>{{ $latestAssessment->score_anxiety }}/15</strong> | 
            Puntaje Estrés: <strong>{{ $latestAssessment->score_stress }}/15</strong> | 
            Total: <strong>{{ $latestAssessment->total_score }}/30</strong><br>
            <span style="font-size: 11px; color: #581c87;">"{{ $latestAssessment->non_diagnostic_feedback }}"</span>
        </div>
    @endif

    <h3 style="margin-bottom: 5px;">Historial de Registros Diarios (Últimos 60 días)</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Ansiedad (1-10)</th>
                <th>Estrés (1-10)</th>
                <th>Ánimo</th>
                <th>Sueño (h)</th>
                <th>Notas del Usuario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td><strong>{{ $log->log_date->format('d/m/Y') }}</strong></td>
                    <td>{{ $log->anxiety_level }} / 10</td>
                    <td>{{ $log->stress_level }} / 10</td>
                    <td>{{ $log->mood_label }}</td>
                    <td>{{ $log->sleep_hours }} h</td>
                    <td>{{ $log->notes ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8;">No hay registros disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
