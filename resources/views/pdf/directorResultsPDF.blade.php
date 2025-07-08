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
                <th>Clase</th>
                <th>Sección</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataResults as $profesor)
                @foreach ($profesor['coursesPerProfessor'] as $curso)
                    <tr>
                        <td>{{ $profesor['Professor'] }}</td>
                        <td>{{ $curso['courses'] }}</td>
                        <td>Hola</td>
                        <td>{{ $curso['scorePerCourse'] }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="4">No hay resultados disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
