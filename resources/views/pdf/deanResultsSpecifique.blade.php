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
    <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-2 text-center">Catedrático</th>
                <th class="px-4 py-2 text-center">Promedio</th>
                <th class="px-4 py-2 text-center">Acción</th>
            </tr>
        </thead>
        @foreach ($dataResults as $results)
            <tbody x-data="{ open: false }" class="border-b">
                <tr>
                    <td class="px-4 py-2 text-center">Catedrático {{ $results['professorName'] }}</td>
                    <td class="px-4 py-2 text-center">{{ $results['professorScoreAvg'] }}</td>
                    <td class="px-4 py-2 text-center">
                        <button @click="open = !open" class="text-blue-600 hover:underline focus:outline-none">
                            <span x-show="!open" class="hover:cursor-pointer">Ver detalles</span>
                            <span x-show="open" class="hover:cursor-pointer">Ocultar</span>
                        </button>
                    </td>
                </tr>
                <tr x-show="open" x-cloak>
                    <td colspan="3" class="px-4 py-2 bg-gray-50 text-sm">
                        <div class="space-y-3">
                            @foreach ($results['coursesData'] as $course)
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <span>
                                        <strong>Clase:</strong> {{ $course['course'] }}
                                    </span>
                                    <span>
                                        <strong>Calificación:</strong> {{ $course['totPerCourse'] }}
                                    </span>
                                    <span>
                                        <strong>Evaluaciones estudiantes &rarr;</strong>
                                    <a href="{{ route('deanStudentView', ['courseId' => $course['courseId'] ?? '0', 'Professor' => $results['professorName'], 'courses' => $course['course']]) }}" class="ml-2 p-1 bg-white text-orange-600 rounded-sm border border-blue-600 hover:bg-blue-100 transition">
                                            <strong>Ver más</strong>
                                        </a>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            </tbody>
        @endforeach
    </table>
</body>
</html>