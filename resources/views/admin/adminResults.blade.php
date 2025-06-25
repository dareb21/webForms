@extends('admin.adminLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 ml-0 md:ml-64 p-6 h-full bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    RESULTADOS DE EVALUACIONES
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
                        <option value="annualY1">2025</option>
                        <option value="annualY2">2024</option>
                        <option value="annualY2">2023</option>
                    </select>
                    <label for="annualPeriod">Período</label>
                    <select name="annualPeriod" id="annualPeriod" class="shadow-md border border-gray-200">
                        <option value="annualT">Anual</option>
                        <option value="annualP2">Período 1</option>
                        <option value="annualP3">Período 2</option>
                        <option value="annualP4">Período 3</option>
                    </select>
                    <button class="bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold px-3 rounded">
                        Buscar
                    </button>
                </form>
                </div>
            
            <!-- Seccion de evaluaciones -->
                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Calificación</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Fecha</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiantes</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $resultado)
                        <tr>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['profesor'] }}</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['course'] }}</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">{{ $resultado['score'] }}</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">
                            <a href="{{ route('adminStudentView') }}" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                VER
                            </a>
                            </td>
                            <td class="border border-gray-400 px-4 py-2 text-center">
                                <a href="#" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                    EXPORTAR
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection