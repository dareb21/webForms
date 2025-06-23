@extends('dean.deanLayout')
@section('content')
<div class="flex-1 ml-0 md:ml-64 h-full p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full min-h-full">
        <div class="bg-white p-4 text-center text-2xl font-bold">
            <h1>
                EVALUACIONES POR ESCUELAS
            </h1>
        </div>
         <!-- Segmentación por años y períodos -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                    <label for="catedraticoBusqueda">Búsqueda por escuela </label>
                    <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda" class="shadow-sm ml-2 border-1 border-gray-200">
                </div>

        <div class="overflow-x-auto w-full mt-4">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-center">Escuela</th>
                        <th class="px-4 py-2 text-center">Promedio</th>
                        <th class="px-4 py-2 text-center">Acción</th>
                    </tr>
                </thead>
                @for ($i = 1; $i <= 5; $i++)
                    <tbody class="border-b">
                        <tr>
                            <td class="px-4 py-2 text-center">Escuela {{ $i }}</td>
                            <td class="px-4 py-2 text-center">{{ rand(10, 20) }}</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('deanResults') }}" class="bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold py-1 px-3 rounded">VER ESCUELA</a>
                            </td>
                        </tr>
                    </tbody>
                @endfor
            </table>
        </div>
    </div>
</div>
@endsection
