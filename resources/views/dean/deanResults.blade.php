@extends('dean.deanLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    RESULTADOS DE EVALUACIONES
                </h1>
            </div>
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
            <!-- Seccion de evaluaciones -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Escuela</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Calificación</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiantes</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Aqui ira la info de busqueda -->
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">2/20</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">
                                <a href="#" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
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