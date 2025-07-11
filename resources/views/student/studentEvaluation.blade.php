@extends('student.studentLayout')

@section('content')
@php
    $userInfo = session('userInfo');
    $courses = $userInfo['courses'];
    $profe = $userInfo['teacher'];
@endphp

<main class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <section class="bg-white rounded-xl shadow-lg p-6">
        <!-- Título -->
        <header class="flex flex-col items-center mb-6">
            <h1 class="text-2xl font-bold text-center">EVALUACIÓN DE SATISFACCIÓN</h1>

            <!-- Información Asignatura y Docente -->
            <div class="flex flex-wrap gap-8 mt-4 justify-center w-full max-w-4xl">
                <div class="flex items-center gap-2">
                    <span class="font-bold">ASIGNATURA:</span>
                    <span class="uppercase">{{ $courses[$courseArrayPosition] ?? 'N/D' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold">DOCENTE:</span>
                    <span class="uppercase">{{ $profe[$courseArrayPosition] ?? 'N/D' }}</span>
                </div>
            </div>
        </header>

        <!-- Formulario de preguntas -->
        <form action="{{ route('studentSubmit', ['courseId' => $coursesId]) }}" method="POST" class="max-w-5xl mx-auto">
            @csrf
            <div class="overflow-x-auto">
                <table class="table-auto border border-gray-400 w-full text-left">
                    <tbody>
                        @foreach ($data as $groupName => $group)
                            @foreach ($group as $index => $item)
                                <tr>
                                    @if ($index === 0)
                                        <th
                                            class="border border-gray-400 text-center bg-blue-600 text-white w-24 md:w-36 px-4 py-2 align-middle"
                                            rowspan="{{ count($group) }}"
                                            scope="rowgroup"
                                        >
                                            Indicador {{ $groupName }}
                                        </th>
                                    @endif
                                    <td class="border border-gray-400 px-4 py-2" colspan="4">
                                        <div class="flex flex-col divide-y divide-gray-300">
                                            <div class="flex justify-between items-center py-2">
                                                <span class="font-medium">{{ $item['option1'] ?? 'Pregunta sin texto' }}</span>
                                                <input
                                                    type="checkbox"
                                                    name="option_{{ $item['option1Id'] }}"
                                                    id="option_{{ $item['option1Id'] }}"
                                                    class="w-5 h-5 text-blue-600"
                                                    data-group="{{ $groupName }}"
                                                >
                                            </div>

                                            <div class="flex justify-between items-center py-2">
                                                <span class="font-medium">{{ $item['option2'] ?? 'Pregunta sin texto' }}</span>
                                                <input
                                                    type="checkbox"
                                                    name="option_{{ $item['option2Id'] }}"
                                                    id="option_{{ $item['option2Id'] }}"
                                                    class="w-5 h-5 text-blue-600"
                                                    data-group="{{ $groupName }}"
                                                >
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Observaciones -->
            <section class="mt-6">
                <h2 class="text-xl font-bold text-center mb-2">Observaciones</h2>
                <textarea
                    name="observaciones"
                    class="w-full h-40 resize-none rounded p-2 border border-gray-300 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Escribe tus observaciones aquí..."
                    aria-label="Observaciones"
                ></textarea>
            </section>

            <!-- Botón enviar -->
            <div class="p-6 flex justify-center">
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg shadow-sm border-2 border-transparent text-white font-bold bg-orange-500
                        hover:bg-blue-500 hover:border-orange-500 hover:text-white hover:cursor-pointer transition-colors duration-200">
                    ENVIAR
                </button>
            </div>
        </form>
    </section>
</main>

<!-- Script para manejo de selección única por grupo -->
<script defer>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-group]');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const group = this.dataset.group;
                const groupCheckboxes = document.querySelectorAll(`input[type="checkbox"][data-group='${group}']`);

                if (this.checked) {
                    groupCheckboxes.forEach(cb => {
                        if (cb !== this) cb.disabled = true;
                    });
                } else {
                    groupCheckboxes.forEach(cb => cb.disabled = false);
                }
            });
        });
    });
</script>
@endsection
