@extends('director.directorLayout')
@section('content')

<div class="flex-1 ml-0 md:ml-64 h-full p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
    <table class="min-w-full border border-gray-300 divide-y divide-gray-200 mt-4">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-2 text-center">Clase</th>
                <th class="px-4 py-2 text-center">Sección</th>
                <th class="px-4 py-2 text-center">Calificación</th>
                <th class="px-4 py-2 text-center">Acción</th>
            </tr>
        </thead>

        @for ($i = 1; $i <= 5; $i++)
            <tbody x-data="{ open: false }" class="border-b">
                <tr>
                    <td class="px-4 py-2 text-center">Clase {{ $i }}</td>
                    <td class="px-4 py-2 text-center">Sección {{ $i }}</td>
                    <td class="px-4 py-2 text-center">{{ rand(0,20) }}</td>
                    <td class="px-4 py-2 text-center">
                        <button @click="open = !open" class="text-blue-600 hover:underline focus:outline-none">
                            <span x-show="!open" class="hover:cursor-pointer">Ver detalles</span>
                            <span x-show="open" class="hover:cursor-pointer">Ocultar</span>
                        </button>
                    </td>
                </tr>
                <tr x-show="open" x-cloak>
                    <td colspan="4" class="px-4 py-2 bg-gray-50 text-sm">
                        <p class="text-left"><strong>Estudiante:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p class="text-left"><strong>Calificación:</strong> {{ rand(10, 100) }}</p>
                        <p><strong>Evaluación detallada abajo &#8595</strong></p>
                        <p class="mt-2"><a href="{{ route('directorStudentView') }}" class="p-1 bg-white text-right text-orange-600 rounded-sm border-1 border-blue-600"><strong>Ver más</strong></a><p>
                    </td>
                </tr>
            </tbody>
        @endfor
    </table>
    </div>
</div>
@endsection
