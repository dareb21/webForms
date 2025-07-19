@extends('admin.adminLayout')
@section('content')
    @php
        $minimo = 10;
        $i = 1;
    @endphp
    <!-- Sweet Alert -->
    @if (session('alert'))
        <script>
            Swal.fire({
                title: "Advertencia",
                text: {!! json_encode(session('alert')) !!},
                icon: "warning"
            });
        </script>
    @endif

    <!-- Main Content -->
    <div class="flex-1 ml-0 md:ml-64 p-4 md:p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 w-full max-w-screen-lg mx-auto">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>CREACIÓN DE EVALUACIONES</h1>
            </div>

            <!-- FORMULARIO -->
            <form id="form-evaluacion" action="{{ route('createNewEvaluation') }}" method="POST">
                @csrf
                <div class="flex flex-col md:flex-row justify-center gap-6 py-4 flex-wrap">
                    <!-- Columna izquierda -->
                    <div class="flex flex-col gap-4 w-full md:w-auto">
                        <div class="flex flex-col md:flex-row items-center gap-2">
                            <label for="evaluationName">Evaluación -</label>
                            <input type="text" name="evaluationName" value="{{ old('evaluationName') }}"
                                class="shadow-md border border-gray-200 w-full md:w-auto">
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2">
                            <label for="term" class="md:pr-5">Período -</label>
                            <select name="term" id="term"
                                class="shadow-md border border-gray-200 w-full md:w-auto text-center">
                                <option value="1">Período 1</option>
                                <option value="2">Período 2</option>
                                <option value="3">Período 3</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="flex flex-col gap-4 w-full md:w-auto">
                        <div class="flex flex-col md:flex-row items-center gap-2">
                            <label for="dateStart">Fecha Inicio -</label>
                            <input type="date" name="dateStart" value="{{ old('dateStart') }}"
                                class="shadow-md border border-gray-200 w-full md:w-auto">
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2">
                            <label for="dateEnd">Fecha Cierre -</label>
                            <input type="date" name="dateEnd" value="{{ old('dateEnd') }}"
                                class="shadow-md border border-gray-200 w-full md:w-auto">
                        </div>
                    </div>
                </div>

                <!-- Indicadores -->
                <div id="grupos-container">
                    @for ($i = 1; $i <= $minimo; $i++)
                        <div class="flex flex-col gap-4 py-4">
                            <p class="font-bold text-lg">Indicador {{ $i }}</p>

                            <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                                <label for="g{{ $i }}p1">Pregunta 1 -</label>
                                <input type="text" name="questions[{{ $i }}][p1]" id="g{{ $i }}p1"
                                    value="{{ old("questions.$i.p1") }}"
                                    class="shadow-md border border-gray-200 w-full md:flex-1">

                                <label for="g{{ $i }}c1">Calificación</label>
                                <input type="number" name="cal[{{ $i }}][c1]" id="g{{ $i }}c1"
                                    value="{{ old("cal.$i.c1") }}" class="shadow-md border border-gray-200 w-16">
                            </div>

                            <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                                <label for="g{{ $i }}p2">Pregunta 2 -</label>
                                <input type="text" name="questions[{{ $i }}][p2]"
                                    id="g{{ $i }}p2" value="{{ old("questions.$i.p2") }}"
                                    class="shadow-md border border-gray-200 w-full md:flex-1">

                                <label for="g{{ $i }}c2">Calificación</label>
                                <input type="number" name="cal[{{ $i }}][c2]" id="g{{ $i }}c2"
                                    value="{{ old("cal.$i.c2") }}" class="shadow-md border border-gray-200 w-16">
                            </div>
                        </div>
                    @endfor
                </div>
            </form>

            <!-- Botones AGREGAR y BORRAR -->
            <div class="w-full flex flex-col md:flex-row justify-center gap-4 py-4 bg-white mt-8">
                <a id="agregar-grupo"
                    class="bg-blue-600 hover:cursor-pointer hover:bg-orange-500 text-white font-bold py-2 px-6 rounded text-center">AGREGAR</a>
                <a id="borrar-grupo"
                    class="bg-blue-600 hover:cursor-pointer hover:bg-orange-500 text-white font-bold py-2 px-6 rounded text-center">BORRAR</a>
            </div>

            <!-- Botones GUARDAR, LIMPIAR y REGRESAR -->
            <div class="w-full flex flex-col md:flex-row justify-center gap-4 py-4 bg-white mt-4">
                <button onclick="document.getElementById('form-evaluacion').submit()"
                    class="bg-blue-600 hover:cursor-pointer hover:bg-orange-500 text-white font-bold py-2 px-6 rounded">GUARDAR</button>
                <a href="javascript:void(0);" onclick="document.getElementById('form-evaluacion').reset();"
                    class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded text-center">
                    LIMPIAR
                </a>
                <a href="{{ route('adminEvaluation') }}"
                    class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-2 px-6 rounded text-center">REGRESAR</a>
            </div>
        </div>
    </div>

    <script>
        const oldQuestions = @json(old('questions') ?? []);
        const oldCal = @json(old('cal') ?? []);
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("grupos-container");
            const agregarBtn = document.getElementById("agregar-grupo");
            const borrarBtn = document.getElementById("borrar-grupo");

            const oldQuestions = @json(old('questions') ?? []);
            const oldCal = @json(old('cal') ?? []);

            let grupoActual = 10;

            const indicesOld = Object.keys(oldQuestions)
                .map(i => parseInt(i))
                .filter(i => !isNaN(i) && i > 10);

            if (indicesOld.length > 0) {
                const maxIndex = Math.max(...indicesOld);
                grupoActual = maxIndex;

                indicesOld.sort((a, b) => a - b).forEach(i => {
                    addGrupo(i, oldQuestions[i], oldCal?.[i] ?? {});
                });
            }

            agregarBtn.addEventListener("click", function() {
                grupoActual++;
                addGrupo(grupoActual);
            });

            borrarBtn.addEventListener("click", function() {
                const gruposDinamicos = container.querySelectorAll(".grupo-dinamico");
                if (gruposDinamicos.length > 0) {
                    gruposDinamicos[gruposDinamicos.length - 1].remove();
                    grupoActual--;
                }
            });

            function addGrupo(index, preguntas = {}, cal = {}) {
                const grupoDiv = document.createElement("div");
                grupoDiv.className = "flex flex-col gap-4 py-4 grupo-dinamico";
                grupoDiv.innerHTML = `
            <p class="font-bold text-lg">Indicador ${index}</p>
            <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                <label for="g${index}p1" class="whitespace-nowrap">Pregunta 1 -</label>
                <input type="text" name="questions[${index}][p1]" id="g${index}p1" 
                       value="${preguntas.p1 ?? ''}" 
                       class="shadow-md border border-gray-200 w-full md:flex-1">
                <label for="g${index}c1">Calificación</label>
                <input type="number" name="cal[${index}][c1]" id="g${index}c1" 
                       value="${cal.c1 ?? ''}" 
                       class="shadow-md border border-gray-200 w-16">
            </div>
            <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                <label for="g${index}p2" class="whitespace-nowrap">Pregunta 2 -</label>
                <input type="text" name="questions[${index}][p2]" id="g${index}p2" 
                       value="${preguntas.p2 ?? ''}" 
                       class="shadow-md border border-gray-200 w-full md:flex-1">
                <label for="g${index}c2">Calificación</label>
                <input type="number" name="cal[${index}][c2]" id="g${index}c2" 
                       value="${cal.c2 ?? ''}" 
                       class="shadow-md border border-gray-200 w-16">
            </div>
        `;
                container.appendChild(grupoDiv);
            }
        });
    </script>
@endsection
