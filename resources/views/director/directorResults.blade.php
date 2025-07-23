@extends('director.directorLayout')
@section('content')
    <!-- Sweet Alert -->
    @if (session('alert'))
        <script>
            Swal.fire({
                title: "Advertencia",
                text: {!! json_encode(session('alert')) !!},
                icon: "warning"
            });
        </script>
    @endif
    <div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full min-h-[calc(100vh-3rem)]">
            <div class="flex flex-col items-center">
                <div class="bg-white p-4 text-center text-2xl font-bold">
                    <h1>
                        EVALUACIONES POR CATEDRÁTICOS
                    </h1>
                </div>
                <br>
                <div>
                    <form action="">
                        <div>
                            <label for="schoolSegmentation" class="text-gray-700 font-bold">Segmentación: </label>
                            <form action="{{ route('deanDashboard') }}" method="GET" class="inline-block">
                                <select name="schoolSegmentation" id="schoolSegmentation" onchange="this.form.submit()"
                                    class="rounded-sm p-1 md:w-80 shadow-md">
                                    {{-- @foreach ($schoolInfo['schoolsInfo'] as $school)
                                        <option value="{{ $school['id'] }}"
                                            {{ request('schoolSegmentation') == $school['id'] ? 'selected' : '' }}>
                                            {{ $school['name'] }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </form>
                        </div>
                    </form>
                </div>
                <!-- Búsqueda segmentada por nombre, años y períodos -->
                <div class="w-full h-full mt-6 overflow-x-auto">
                    <div class="w-full flex flex-col items-start pt-3">
                        <div class="flex gap-x-4 flex-wrap py-4">
                            <form action="{{ route('directorFilter') }}" method="GET"
                                class="flex flex-wrap items-center gap-x-4">
                                <label for="catedraticoBusqueda">Búsqueda por nombre</label>
                                <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda"
                                    value="{{ request('catedraticoBusqueda') }}"
                                    class="shadow-sm ml-2 border border-gray-200">
                                <div>
                                    <label for="annualYear">Año</label>
                                    <select name="annualYear" id="annualYear" class="shadow-md border border-gray-200">
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

                                    <label for="annualPeriod">Período</label>
                                    <select name="annualPeriod" id="annualPeriod" class="shadow-md border border-gray-200">
                                        <option value="4" {{ request('annualPeriod') == 4 ? 'selected' : '' }}>Anual
                                        </option>
                                        <option value="1" {{ request('annualPeriod') == 1 ? 'selected' : '' }}>Período
                                            1</option>
                                        <option value="2" {{ request('annualPeriod') == 2 ? 'selected' : '' }}>Período
                                            2</option>
                                        <option value="3" {{ request('annualPeriod') == 3 ? 'selected' : '' }}>Período
                                            3</option>
                                    </select>
                                </div>

                                <button type="submit"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold py-1 px-3 rounded">
                                    Buscar
                                </button>

                                <a href="{{ route('directorResults') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                    Refrescar
                                </a>
                                <a href="{{ route('director.directorPDF') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                    PDF
                                </a>
                                <a href="{{ route('reporte.directorResultsExcel') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                    EXCEL
                                </a>
                            </form>
                        </div>
                    </div>
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
                                    <td class="px-4 py-2 text-center">{{ $results['professorName'] }}</td>
                                    <td class="px-4 py-2 text-center">{{ $results['professorScoreAvg'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button @click="open = !open"
                                            class="text-blue-600 hover:underline focus:outline-none">
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
                                                        <a href="{{ route('directorStudentView', ['sectionId' => $course['sectionId'] ?? '0', 'Professor' => $results['professorName'], 'courses' => $course['course']]) }}"
                                                            class="ml-2 p-1 bg-white text-orange-600 rounded-sm border border-blue-600 hover:bg-blue-100 transition">
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
    </div>
@endsection
