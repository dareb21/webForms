@extends('director.directorLayout')
@section('content')
<div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full min-h-full">
        <div class="bg-white p-4 text-center text-2xl font-bold">
            <h1>
                EVALUACIONES POR CATEDRÁTICOS
            </h1>
        </div>
         <!-- Segmentación por años y períodos -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                    <label for="catedraticoBusqueda">Búsqueda por nombre </label>
                    <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda" class="shadow-sm ml-2 border-1 border-gray-200">
                    <label for="anualYear">Año</label>
                    <select name="anualYear" id="anualYear" class="shadow-md border border-gray-200">
                        <option value="anualY1">2025</option>
                        <option value="anualY2">2024</option>
                        <option value="anualY2">2023</option>
                    </select>
                    <label for="anualPeriod">Período</label>
                    <select name="anualPeriod" id="anualPeriod" class="shadow-md border border-gray-200">
                        <option value="anualP1">Total</option>
                        <option value="anualP2">Período 1</option>
                        <option value="anualP3">Período 2</option>
                        <option value="anualP4">Período 3</option>
                    </select>
                    <button class="inlie-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold px-3 rounded">
                        Buscar
                    </button>
                    <a href="{{ route('directorResults') }}" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                        Refrescar
                    </a>
                </div>

        <div class="overflow-x-auto w-full mt-4">
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
                            <td class="px-4 py-2 text-center">Catedrático {{ $results['Professor'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $results['avgScoreProfessor'] }}</td>
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
                                    @foreach ($results['coursesPerProfessor'] as $course)
                                        <div class="grid grid-cols-3 gap-4 items-center">
                                            <span>
                                                <strong>Clase:</strong> {{ $course['courses'] }}
                                            </span>
                                            <span>
                                                <strong>Calificación:</strong> {{ $course['scorePerCourse'] }}
                                            </span>
                                            <span>
                                                <strong>Evaluaciones estudiantes &rarr;</strong>
                                               <a href="{{ route('directorStudentView', ['courseId' => $course['courseId'] ?? '0', 'Professor' => $results['Professor'], 'courses' => $course['courses']]) }}" class="ml-2 p-1 bg-white text-orange-600 rounded-sm border border-blue-600 hover:bg-blue-100 transition">
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
        </div>
    </div>
</div>
@endsection
