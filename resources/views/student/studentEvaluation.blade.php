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
                    <span class="uppercase">{{ $courses[$noClaseId] ?? 'N/D' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold">DOCENTE:</span>
                    <span class="uppercase">{{ $profe[$noClaseId] ?? 'N/D' }}</span>
                </div>
            </div>
        </header>

        <!-- Formulario de preguntas -->
        <form action="{{ route('studentSubmit') }}" method="POST" class="max-w-5xl mx-auto">
            @csrf
            <div class="overflow-x-auto">
                <table class="table-auto border border-gray-400 w-full text-left">
                    <tbody>
                        @foreach ($questionGroups as $group)
                            @php
                                $groupOptions = $collectionOptions->where('question_group_id', $group->id)->values();
                            @endphp

                            @foreach ($groupOptions as $option)
                                <tr>
                                    @if ($loop->first)
                                        <th
                                            class="border border-gray-400 text-center bg-blue-600 text-white px-4 py-2 align-middle"
                                            rowspan="{{ $groupOptions->count() }}"
                                            scope="rowgroup"
                                        >
                                            Grupo {{ $group->id }}
                                        </th>
                                    @endif
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        {{ $option->option ?? 'Pregunta sin texto' }}
                                    </td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        <input
                                            type="checkbox"
                                            name="option_{{ $option->id }}"
                                            id="option_{{ $option->id }}"
                                            class="group-checkbox"
                                            data-group="{{ $group->id }}"
                                            aria-labelledby="label_option_{{ $option->id }}"
                                        >
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
        const checkboxes = document.querySelectorAll('.group-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const group = this.dataset.group;
                const groupCheckboxes = document.querySelectorAll(`.group-checkbox[data-group='${group}']`);

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
