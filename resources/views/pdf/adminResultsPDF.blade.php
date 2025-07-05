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
    <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
        <thead>
            <tr>
                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Calificación</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($noInfo) && $noInfo)
                @for ($i=1; $i<=6; $i++)
                    <td class="border border-gray-400 px-4 py-2 text-center"></td>
                @endfor
            @else
                @foreach ($resultados as $resultado)
                    <tr>
                        <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['profesor'] }}</td>
                        <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['course'] }}</td>
                        <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                        <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['score'] }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>
