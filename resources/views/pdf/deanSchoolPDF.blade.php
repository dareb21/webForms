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

        th,
        td {
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
    <div class="overflow-x-auto w-full mt-4">
        <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-2 text-center">Escuela</th>
                    <th class="px-4 py-2 text-center">Promedio</th>
                </tr>
            </thead>
            <tbody class="border-b">
                @foreach ($school as $results)
                    <tr>
                        <td class="px-4 py-2 text-center">{{ $results['Name'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $results['score'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
