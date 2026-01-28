@extends('Senen.SenenLayout')
@section('content')
    <div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
            <div class="flex flex-col items-center">
                <div class="bg-white p-4 text-center text-2xl font-bold">
                    <h1 class="uppercase">
                        RESULTADOS DE {{ $schoolName ?? 'Escuela' }}
                    </h1>
                </div>

                <div class="w-full h-full mt-6 overflow-x-auto">
                    <form action="{{ route('senen.results.filter') }}" method="GET">
                        <div class="w-full flex flex-col items-start pt-3">
                            <div class="flex gap-x-4 flex-wrap py-4">
                                <label for="catedraticoBusqueda">Búsqueda por nombre </label>
                                <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda"
                                    class="shadow-sm ml-2 border-1 border-gray-200">

                                <div>
                                    <label for="anualYear">Año</label>
                                    <select name="anualYear" id="anualYear" class="shadow-md border border-gray-200">
                                        @if (isset($noInfo) && $noInfo)
                                            <option value="noInfo">None</option>
                                        @else
                                            @foreach ($years as $year)
                                                <option value="{{ $year->{'Year(dateStart)'} }}"
                                                    {{ request('annualYear') == $year->{'Year(dateStart)'} ? 'selected' : '' }}>
                                                    {{ $year->{'Year(dateStart)'} }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <label for="anualPeriod">Período</label>
                                    <select name="anualPeriod" id="anualPeriod" class="shadow-md border border-gray-200">
                                        <option value="0">Anual</option>
                                        <option value="1">Período 1</option>
                                        <option value="2">Período 2</option>
                                        <option value="3">Período 3</option>
                                    </select>
                                </div>

                                <input type="hidden" name="schoolId" value="{{ $schoolId }}">

                                <button
                                    class="inline-block bg-orange-500 hover:bg-blue-700 text-white font-bold px-3 rounded">
                                    Buscar
                                </button>

                                <a href="{{ route('senen.results', ['schoolId' => $schoolId]) }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                    Refrescar
                                </a>

                                <a href="{{ route('senen.school.pdf') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                    PDF
                                </a>

                                <a href="{{ route('senen.school.excel') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                    EXCEL
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="w-full h-full overflow-x-auto">
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
                                            <td class="px-4 py-2 text-center">
                                                Catedrático {{ $results['professorName'] }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ $results['professorScoreAvg'] }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <button @click="open = !open" class="text-blue-600 hover:underline">
                                                    <span x-show="!open">Ver detalles</span>
                                                    <span x-show="open">Ocultar</span>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr x-show="open" x-cloak>
                                            <td colspan="3" class="px-4 py-2 bg-gray-50 text-sm">
                                                <div class="space-y-3">
                                                    @foreach ($results['coursesData'] as $course)
                                                        <div
                                                            class="grid grid-cols-4 gap-x-6 items-center border rounded px-4 py-2 bg-white shadow-sm">
                                                            <div>
                                                                <strong>Clase:</strong> {{ $course['course'] }}
                                                            </div>

                                                            <div>
                                                                <strong>Calificación:</strong> {{ $course['totPerCourse'] }}
                                                            </div>

                                                            <div class="text-right col-span-2">
                                                                <a href="{{ route('senen.studentView', [
                                                                    'sectionId' => $course['sectionId'],
                                                                    'course' => $course['course'],
                                                                    'profesor' => $results['professorName'],
                                                                    'annualYear' => request('annualYear'),
                                                                    'annualPeriod' => request('annualPeriod'),
                                                                ]) }}"
                                                                    class="px-2 py-0.5 bg-blue-600 text-white rounded-sm text-xs hover:bg-blue-700">
                                                                    Ver más
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>

                        <div class="p-6 flex justify-center">
                            <a href="{{ route('senen.schools') }}"
                                class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                REGRESAR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection