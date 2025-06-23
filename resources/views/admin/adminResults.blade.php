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

            <!-- Búsqueda -->
            <div class="flex justify-center gap-x-4 flex-wrap py-4">
                <h1 class="font-bold">Búsqueda</h1>
                <input type="text" name="deanSearch" id="deanSearch" class="shadow-md border border-gray-200">
                <select name="deanSearchSelect" id="deanSearchSelect" class="shadow-md border border-gray-200">
                    <option value="0" disabled selected hidden></option>
                    <option value="catedratico">Catedrático</option>
                    <option value="escuela">Escuela</option>
                    <option value="clase">Clase</option>
                </select>
            </div>
            
            <!-- Segmentación por años y períodos -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                    <label for="anualYear">Año</label>
                    <form action="{{ route('adminResultSearch')}}">
                    <select name="anualYear" id="anualYear" class="shadow-md border border-gray-200">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                    <label for="anualPeriod">Período</label>
                    <select name="anualPeriod" id="anualPeriod" class="shadow-md border border-gray-200">
                        <option value="0">Total</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">P 3</option>
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
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiantes</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">2/20</td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection