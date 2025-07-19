@extends('admin.adminLayout')

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

    <!-- Main Content -->
    <div class="flex-1 ml-0 md:ml-64 p-4 h-full bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full min-h-[calc(100vh-3rem)]">
            <div class="flex flex-col items-center">
                <div class="bg-white p-4 text-center text-2xl font-bold">
                    <h1>RESULTADOS DE EVALUACIONES</h1>
                </div>

                <!-- Búsqueda segmentada por nombre, años y períodos -->
                <div class="w-full h-full mt-6 overflow-x-auto">
                    <div class="w-full flex flex-col items-start pt-3">
                        <div class="flex gap-x-4 flex-wrap py-4">
                            <form action="{{ route('adminResultSearch') }}" method="GET"
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

                                <a href="{{ route('adminResults') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                    Refrescar
                                </a>
                                <a href="{{ route('admin.adminPDF') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                    PDF
                                </a>
                                <a href="{{ route('reporte.adminResultsExcel') }}"
                                    class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                    EXCEL
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sección de evaluaciones -->
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2 text-center">Catedrático</th>
                                <th class="px-4 py-2 text-center">Promedio</th>
                                <th class="px-4 py-2 text-center">Acción</th>
                            </tr>
                        </thead>

                        @if (isset($noInfo) && $noInfo)
                            <tbody x-data="{ open: false }" class="border-b">
                                @for ($i = 1; $i <= 4; $i++)
                                    <td class="px-4 py-2 text-center"></td>
                                @endfor
                            @else
                                @foreach ($adminResults['dataResults'] as $resultado)
                            <tbody x-data="{ open: false }" class="border-b">
                                <tr>
                                    <td class="px-4 py-2 text-center">{{ $resultado['professorName'] }}</td>
                                    <td class="px-4 py-2 text-center">{{ $resultado['professorScoreAvg'] }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button @click="open = !open"
                                            class="text-blue-600 hover:underline focus:outline-none">
                                            <span x-show="!open" class="hover:cursor-pointer">Ver detalles</span>
                                            <span x-show="open" class="hover:cursor-pointer">Ocultar</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr x-show="open" x-cloak>
                                    <td colspan="4" class="py-2 bg-gray-50 text-sm">
                                        <div class="flex flex-col items-center gap-y-3">
                                            @foreach ($resultado['coursesData'] as $courses)
                                                <div
                                                    class="w-full max-w-4xl grid grid-cols-4 gap-x-6 items-center border border-gray-200 rounded px-4 py-2 bg-white shadow-sm">

                                                    <!-- Columna 1: Clase -->
                                                    <div class="text-left truncate">
                                                        <strong>Clase:</strong> {{ $courses['course'] }}
                                                    </div>

                                                    <!-- Columna 2: Sección -->
                                                    <div class="text-left truncate">
                                                        <strong>Sección:</strong> {{ $courses['sectionCode'] }}
                                                    </div>

                                                    <!-- Columna 3: Calificación -->
                                                    <div class="text-left truncate">
                                                        <strong>Calificación:</strong> {{ $courses['totPerCourse'] }}
                                                    </div>

                                                    <!-- Columna 4: Botón Ver más -->
                                                    <div class="text-right">
                                                        <a href="{{ route('adminStudentView', [
                                                            'courseId' => $courses['sectionId'],
                                                            'course' => $courses['course'],
                                                            'profesor' => $resultado['professorName'],
                                                            'annualYear' => request('annualYear'),
                                                            'annualPeriod' => request('annualPeriod'),
                                                            'sectionCode' => $courses['sectionCode'],
                                                        ]) }}"
                                                            class="px-2 py-0.5 bg-blue-600 text-white rounded-sm hover:bg-blue-700 text-xs transition whitespace-nowrap">
                                                            Ver más
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- Paginación Condicional noInfo-->
                    {{-- <div class="w-full flex justify-center py-4">
                    {{ $courses->links() }} 
                </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
