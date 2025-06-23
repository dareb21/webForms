@extends('admin.adminLayout')
@section('content')
    @php
    $minimo = 16;
    $i = 1;
     @endphp

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
<div class="flex-1 ml-0 md:ml-64 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full relative pb-24">
        <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    EDICIÓN DE EVALUACIONES
                </h1>
        </div>
        @php
            $evaluacion = $survey->revision;
            $dateStart = $survey->dateStart;
            $dateEnd = $survey->dateEnd;
            $surveyId = $survey->id;
        @endphp
        <form action="{{ route('adminUpdateOrReuse',['surveyId'=>$surveyId]) }}" method="POST">    
                @csrf
               
            <div class="flex justify-center gap-x-4 flex-wrap py-4">
                <label for="">EVALUACION # </label>
                <input type="text" name="revision" value="{{ $evaluacion }}" class="shadow-md border border-gray-200">
                <label for="">Fecha Inicio :  </label>
                <input type="date" name="dateStart" id="" value="{{ $dateStart }}" class="shadow-md border border-gray-200">
                <label for="">Fecha Cierre </label>
                <input type="date" name="dateEnd" id="" value="{{ $dateEnd }}" class="shadow-md border border-gray-200">
            </div>
            
            <!-- for each para las preguntas -->
            <div id="grupos-container">
                @foreach ($questionGroups as $group)
                    @php
                        $groupOptions = $collectionOptions->where('question_group_id', $group->id)->values();
                    @endphp

                    <div class="flex flex-col gap-y-4 py-4">
                        <p class="font-bold text-lg">{{ $group->groupName }}</p>
                        <div class="flex items-center gap-x-2 w-full">
                            <label class="whitespace-nowrap">Pregunta 1 -</label>
                            <input type="text" name="options[{{ $group->id }}][{{ $groupOptions[0]->id ?? 'new1' }}]" id="" value="{{ $groupOptions[0]->option ?? '' }}" class="shadow-md border border-gray-200 flex-1 w-full">
                        </div>
                        <div class="flex items-center gap-x-2 w-full">
                            <label class="whitespace-nowrap">Pregunta 2 -</label>
                            <input type="text" name="options[{{ $group->id }}][{{ $groupOptions[1]->id ?? 'new1' }}]" id="" value="{{ $groupOptions[1]->option ?? '' }}" class="shadow-md border border-gray-200 flex-1 w-full">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botones -->
            <div class="w-full flex flex-col items-center md:flex-row md:justify-center gap-4 py-4 bg-white rounded-b-xl mt-8">
                <a id="agregar-grupo" class="w-auto h-auto text-center bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded hover:cursor-pointer">
                    AGREGAR
                </a>
                <a id="borrar-grupo" class="w-auto h-auto bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded hover:cursor-pointer">
                    BORRAR
                </a>
            </div>

            <div class="w-full flex flex-col lg:flex-row justify-center gap-4 mt-8">
            <!-- Primer grupo de botones -->
            <div class="text-center w-full lg:w-auto flex flex-col items-center md:flex-row md:justify-center gap-1 py-4 bg-white rounded-b-xl">
                <a href="" class="w-30 bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded">
                    GUARDAR
                </a>
               <input type="hidden" name="survey_id" value="{{$surveyId}}">
            <button type="submit" name="btn"  value="reuse" class="w-30 bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded">REUTILIZAR</button>
        </form>
                <a href="{{ route('enableEvaluation', ['surveyId' => $surveyId]) }}" class="w-30 bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded">
                    ACTIVAR
                </a>
            </div>

            <!-- Segundo grupo de botones -->
            <div class="text-center w-full lg:w-auto flex flex-col items-center md:flex-row md:justify-center gap-1 py-4 bg-white rounded-b-xl">
                <a href="{{ route('unableEvaluation', ['surveyId' => $surveyId]) }}" class="w-30 bg-red-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                    DESACTIVAR
                </a>
                <a href="{{ route('adminDelete', ['surveyId' => $surveyId]) }}" class="w-30 bg-red-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded">
                    ELIMINAR
                </a>
                <a href="{{ route('adminEvaluation') }}" class="w-30 bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-4 rounded">
                    REGRESAR
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("grupos-container");
        const agregarBtn = document.getElementById("agregar-grupo");
        const borrarBtn = document.getElementById("borrar-grupo");

        function obtenerUltimoNumeroGrupo() {
            const titulos = container.querySelectorAll("p.font-bold.text-lg");
            let max = 0;
            titulos.forEach(titulo => {
                const match = titulo.textContent.match(/Indicador\s+(\d+)/);
                if (match) {
                    const num = parseInt(match[1]);
                    if (num > max) max = num;
                }
            });
            return max;
        }

        agregarBtn.addEventListener("click", function () {
            const nuevoNumeroGrupo = obtenerUltimoNumeroGrupo() + 1;

            const grupoDiv = document.createElement("div");
            grupoDiv.className = "flex flex-col gap-y-4 py-4 grupo-dinamico";
            grupoDiv.innerHTML = `
                <p class="font-bold text-lg">Indicador ${nuevoNumeroGrupo}</p>
                <div class="flex items-center gap-x-2 w-full">
                    <label class="whitespace-nowrap">Pregunta 1 -</label>
                    <input type="text" name="grupos[${nuevoNumeroGrupo}][pregunta1]" class="shadow-md border border-gray-200 flex-1 w-full">
                </div>
                <div class="flex items-center gap-x-2 w-full">
                    <label class="whitespace-nowrap">Pregunta 2 -</label>
                    <input type="text" name="grupos[${nuevoNumeroGrupo}][pregunta2]" class="shadow-md border border-gray-200 flex-1 w-full">
                </div>
            `;

            container.appendChild(grupoDiv);
        });

        borrarBtn.addEventListener("click", function () {
            const gruposDinamicos = container.querySelectorAll(".grupo-dinamico");
            if (gruposDinamicos.length > 0) {
                gruposDinamicos[gruposDinamicos.length - 1].remove();
            }
        });
    });
</script>
@endsection
