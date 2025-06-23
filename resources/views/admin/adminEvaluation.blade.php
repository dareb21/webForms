@extends('admin.adminLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 h-full md:ml-64 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    EVALUACIONES
                </h1>
            </div>

            <!-- Búsqueda -->
            <div class="flex justify-center gap-x-4 flex-wrap py-4">
                <h1 class="font-bold">Búsqueda</h1>
                <form action="{{ route('adminEvaluationSearch')}}"  method="GET">
                <input type="text" name="adminSearch" id="adminSearch" class="shadow-md border border-gray-200">
                <select name="adminSearchSelect" id="adminSearchSelect" class="shadow-md border border-gray-200">
                    <option value="0" disabled selected hidden></option>
                    <option value="revision">Revisión</option>
                    <option value="autor">Autor</option>
                    <option value="fechaInicio">Fecha Inicio</option>
                </select>
                <button type="submit">Buscar</button>
                </form>
            </div>

            <!-- Seccion de evaluaciones -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Revisión</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Fecha de Inicio</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Fecha de Cierre</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Autor</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estado</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surveys as $survey)
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->revision }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->dateStart }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->dateEnd }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->Author }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->status == 1 ? "Activa" : "Inactiva"; }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        <form action="{{ route('adminEvaluationEdit', ['id' => $survey->id]) }}">
                                            <button type="submit" class="bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                                VER/EDITAR
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6">
                <a href="{{ route('adminNewEvaluation') }}" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                    CREAR NUEVA
                </a>
            </div>
            
        </div>
    </div>
</div>
@endsection