<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Resultados por Evaluación</h2>
    <table>
        <thead>
            <tr>
                <th>Catedrático</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataResults as $profesor)
                <td>{{ $profesor['professorName'] }}</td>
                <td>{{ $profesor['professorScoreAvg'] }}</td>
            @empty
                <tr>
                    <td colspan="3">No hay resultados disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
