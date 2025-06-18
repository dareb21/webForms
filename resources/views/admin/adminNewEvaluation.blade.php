@extends('admin.adminLayout')
@section('content')
    @php
    $minimo = 16;
    $i = 1;
     @endphp

<!-- Main Content -->
<div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full relative pb-24">
        <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    CREACION DE EVALUACIONES
                </h1>
        </div>
               <form action="{{ route('createNewEvaluation') }}" Method="POST">    
                @csrf
            <div class="flex justify-start gap-x-4 flex-wrap py-4">
                <label for="">EVALUACION # -</label>
                <input type="text" name="evaluationName" class="shadow-md border border-gray-200">
                <label for="">Fecha Inicio -</label>
                <input type="date" name="dateStart" id="" class="shadow-md border border-gray-200">
                <label for="">Fecha Cierre -</label>
                <input type="date" name="dateEnd" id="" class="shadow-md border border-gray-200">
            </div>

         <!-- Preguntas -->
            <div id="grupos-container">
            
                @for ($i; $i <= $minimo; $i++)
                    <div class="flex flex-col gap-y-4 py-4">
                        <p class="font-bold text-lg">Grupo {{ $i }}</p>
                        <div class="flex items-center gap-x-2 w-full">
                            <label for="g{{ $i }}p1" class="whitespace-nowrap">Pregunta 1 -</label>
                            <input type="text" name="questions[{{ $i }}][p1]" id="g{{ $i }}p1" class="shadow-md border border-gray-200 flex-1 w-full">
                        </div>
                        <div class="flex items-center gap-x-2 w-full">
                            <label for="g{{ $i }}p2" class="whitespace-nowrap">Pregunta 2 -</label>
                            <input type="text" name="questions[{{ $i }}][p2]" id="g{{ $i }}p2" class="shadow-md border border-gray-200 flex-1 w-full">
                        </div>
                    </div>
                @endfor
            </div>
                <button type="submit" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">GUARDAR</button>
            </form>
            <div class="w-full flex justify-center gap-4 flex-col md:flex-row py-4 bg-white rounded-b-xl mt-8">
                <a id="agregar-grupo" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded hover:cursor-pointer">AGREGAR</a>
                <a id="borrar-grupo" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded hover:cursor-pointer">BORRAR</a>
            </div>

            <!-- Botones  -->
            <div class="w-full flex justify-center gap-4 flex-col md:flex-row py-4 bg-white rounded-b-xl mt-8">
                
          
                <a href="#" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-6 rounded">LIMPIAR</a>
                <a href="{{ route('adminEvaluation') }}" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">REGRESAR</a>
            </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("grupos-container");
        const agregarBtn = document.getElementById("agregar-grupo");
        const borrarBtn = document.getElementById("borrar-grupo");
        let grupoActual = 16;

        agregarBtn.addEventListener("click", function () {
            grupoActual++;

            const grupoDiv = document.createElement("div");
            grupoDiv.className = "flex flex-col gap-y-4 py-4 grupo-dinamico";
            grupoDiv.innerHTML = `
                <p class="font-bold text-lg">Grupo ${grupoActual}</p>
                <div class="flex items-center gap-x-2 w-full">
                    <label class="whitespace-nowrap">Pregunta 1 -</label>
                    <input type="text" name="grupos[${grupoActual}][pregunta1]" class="shadow-md border border-gray-200 flex-1 w-full">
                </div>
                <div class="flex items-center gap-x-2 w-full">
                    <label class="whitespace-nowrap">Pregunta 2 -</label>
                    <input type="text" name="grupos[${grupoActual}][pregunta2]" class="shadow-md border border-gray-200 flex-1 w-full">
                </div>
            `;

            container.appendChild(grupoDiv);
        });

        borrarBtn.addEventListener("click", function () {
            const gruposDinamicos = container.querySelectorAll(".grupo-dinamico");
            if (gruposDinamicos.length > 0) {
                gruposDinamicos[gruposDinamicos.length - 1].remove();
                grupoActual--;
            }
        });
    });
</script>
@endsection
