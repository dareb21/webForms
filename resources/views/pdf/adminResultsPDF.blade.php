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
        h2{
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Resultados Evaluaciones</h2>
    <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-2 text-center">Catedrático</th>
                <th class="px-4 py-2 text-center">Promedio</th>
            </tr>
        </thead>
        
            @if (isset($noInfo) && $noInfo)
                <tbody x-data="{ open: false }" class="border-b">
                @for ($i=1; $i<=4; $i++)
                    <td class="px-4 py-2 text-center"></td>
                @endfor
            @else
                @foreach ($dataResults as $resultado)
                <tbody x-data="{ open: false }" class="border-b">
                    <tr>
                        <td class="px-4 py-2 text-center">{{ $resultado['professorName'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $resultado['professorScoreAvg'] }}</td>
                    </tr>
                </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>
