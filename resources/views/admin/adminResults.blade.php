@extends('admin.adminLayout')

@section('content')

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
                        <form action="{{ route('adminResultSearch') }}" method="GET" class="flex flex-wrap items-center gap-x-4">
                            <label for="catedraticoBusqueda">Búsqueda por nombre</label>
                            <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda"
                                value="{{ request('catedraticoBusqueda') }}"
                                class="shadow-sm ml-2 border border-gray-200">
                            <label for="annualYear">Año</label>
                            <select name="annualYear" id="annualYear" class="shadow-md border border-gray-200">
                                @foreach ($years as $year)
                                    <option value="{{ $year->{'Year(dateStart)'} }}"
                                        {{ request('annualYear') == $year->{'Year(dateStart)'} ? 'selected' : '' }}>
                                        {{ $year->{'Year(dateStart)'} }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="annualPeriod">Período</label>
                            <select name="annualPeriod" id="annualPeriod" class="shadow-md border border-gray-200">
                                <option value="4" {{ request('annualPeriod') == 4 ? 'selected' : '' }}>Anual</option>
                                <option value="1" {{ request('annualPeriod') == 1 ? 'selected' : '' }}>Período 1</option>
                                <option value="2" {{ request('annualPeriod') == 2 ? 'selected' : '' }}>Período 2</option>
                                <option value="3" {{ request('annualPeriod') == 3 ? 'selected' : '' }}>Período 3</option>
                            </select>

                            <button type="submit" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold py-1 px-4 rounded">
                                Buscar
                            </button>

                            <a href="{{ route('adminResults') }}" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                                Refrescar
                            </a>
                        </form>
                    </div>
                </div>

            <!-- Sección de evaluaciones -->
                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Calificación</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiantes</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (request('noInfo') === True)
                            @for ($i=1; $i<=6; $i++)
                                <td class="border border-gray-400 px-4 py-2 text-center">Aún no hay info.</td>
                            @endfor
                        @else
                            @foreach ($resultados as $resultado)
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['profesor'] }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['course'] }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['score'] }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        <a href="{{ route('adminStudentView', ['courseId' => $resultado['courseId'], 'annualYear' => request('annualYear'), 'annualPeriod' => request('annualPeriod')]) }}" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                            Ver
                                        </a>
                                    </td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        <a href="#" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                            Exportar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="w-full flex justify-center py-4">
                {{ $courses->links() }} 
            </div>
        </div>
    </div>
</div>

@endsection
