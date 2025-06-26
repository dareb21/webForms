@extends('admin.adminLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 ml-0 md:ml-64 h-full p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    EVALUACIONES HECHAS POR ESTUDIANTES
                </h1>
            </div>

            <!-- Búsqueda segmentada por nombre, años y períodos -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                    <label for="catedraticoBusqueda">Búsqueda por nombre </label>
                    <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda" class="shadow-sm ml-2 border-1 border-gray-200">
                    <label for="annualYear">Año</label>
                    <select name="annualYear" id="annualYear" class="shadow-md border border-gray-200">
                        @foreach ($years as $year)
                            <option value="{{ $year->{'Year(dateStart)'} }}">{{ $year->{'Year(dateStart)'} }}</option>
                        @endforeach
                    </select>
                    <label for="annualPeriod">Período</label>
                    <select name="annualPeriod" id="annualPeriod" class="shadow-md border border-gray-200">
                        <option value="4">Anual</option>
                        <option value="1">Período 1</option>
                        <option value="2">Período 2</option>
                        <option value="3">Período 3</option>
                    </select>
                    <button class="bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold px-3 rounded">
                        Buscar
                    </button>
                </div>

            <!-- Seccion de evaluaciones -->
                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiante</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Calificación</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $resultado)
                        <tr>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['nameStudent'] }}</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['profesor'] }}</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['course'] }}</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['score'] }}</td>
                            <!-- Agrega este bloque dentro de tu <td> en la tabla -->
                            <td class="border border-gray-400 px-4 py-2 text-center">
                                <div x-data="{ open: false }" class="relative">
                                    <!-- Botón que activa el modal -->
                                    <a @click="open = true" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded cursor-pointer">
                                        VER RESPUESTAS
                                    </a>

                                    <!-- Modal -->
                                    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-2xl relative">
                                            <!-- Botón de cerrar -->
                                            <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
                                            <h2 class="text-xl font-bold mb-4">Detalles de la Evaluación</h2>

                                            <!-- Contenedor con scroll -->
                                            <div class="space-y-2 max-h-[70vh] overflow-y-auto pr-2">
                                                <p><strong>Catedrático:</strong> Nombre Ejemplo</p>
                                                <p><strong>Clase:</strong> Nombre de la Clase</p>
                                                <p><strong>Sección:</strong> A1</p>
                                                <p><strong>Puntuación:</strong> 95/100</p>
                                                <p><strong>Comentarios:</strong> Excelente desempeño.</p>
                                                <p><strong>Catedrático:</strong> Nombre Ejemplo</p>
                                                <p><strong>Clase:</strong> Nombre de la Clase</p>
                                                <p><strong>Sección:</strong> A1</p>
                                                <p><strong>Puntuación:</strong> 95/100</p>
                                                <p><strong>Comentarios:</strong> Excelente desempeño.</p>
                                                <p><strong>Catedrático:</strong> Nombre Ejemplo</p>
                                                <p><strong>Clase:</strong> Nombre de la Clase</p>
                                                <p><strong>Sección:</strong> A1</p>
                                                <p><strong>Puntuación:</strong> 95/100</p>
                                                <p><strong>Comentarios:</strong> Excelente desempeño.</p>
                                                <p><strong>Catedrático:</strong> Nombre Ejemplo</p>
                                                <p><strong>Clase:</strong> Nombre de la Clase</p>
                                                <p><strong>Sección:</strong> A1</p>
                                                <p><strong>Puntuación:</strong> 95/100</p>
                                                <p><strong>Comentarios:</strong> Excelente desempeño.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 flex justify-center">
                <a href="{{ route('adminResults') }}" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                    REGRESAR
                </a>
            </div>
        </div>
    </div>
</div>
@endsection