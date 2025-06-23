@extends('admin.adminLayout')
@section('content')
@php
    $minimo = 10;
    $i = 1;
@endphp

<!-- Main Content -->
<div class="flex-1 ml-0 md:ml-64 p-4 md:p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
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
                        <input type="text" name="evaluationName" class="shadow-md border border-gray-200 w-full md:w-auto">
                    </div>
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <label for="term" class="md:pr-5">Período -</label>
                        <select name="term" id="term" class="shadow-md border border-gray-200 w-full md:w-auto text-center">
                            <option value="p1">Período 1</option>
                            <option value="p2">Período 2</option>
                            <option value="p3">Período 3</option>
                        </select>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="flex flex-col gap-4 w-full md:w-auto">
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <label for="dateStart">Fecha Inicio -</label>
                        <input type="date" name="dateStart" class="shadow-md border border-gray-200 w-full md:w-auto">
                    </div>
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <label for="dateEnd">Fecha Cierre -</label>
                        <input type="date" name="dateEnd" class="shadow-md border border-gray-200 w-full md:w-auto">
                    </div>
                </div>
            </div>

            <!-- Indicadores -->
            <div id="grupos-container">
                @for ($i; $i <= $minimo; $i++)
                    <div class="flex flex-col gap-4 py-4">
                        <p class="font-bold text-lg">Indicador {{ $i }}</p>
                        <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                            <label for="g{{ $i }}p1" class="whitespace-nowrap">Pregunta 1 -</label>
                            <input type="text" name="questions[{{ $i }}][p1]" id="g{{ $i }}p1" class="shadow-md border border-gray-200 w-full md:flex-1">
                            <label for="g{{ $i }}c1">Calificación</label>
                            <input type="number" name="cal[{{ $i }}][c1]" id="g{{ $i }}c1" class="shadow-md border border-gray-200 w-16">
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                            <label for="g{{ $i }}p2" class="whitespace-nowrap">Pregunta 2 -</label>
                            <input type="text" name="questions[{{ $i }}][p2]" id="g{{ $i }}p2" class="shadow-md border border-gray-200 w-full md:flex-1">
                            <label for="g{{ $i }}c2">Calificación</label>
                            <input type="number" name="cal[{{ $i }}][c2]" id="g{{ $i }}c2" class="shadow-md border border-gray-200 w-16">
                        </div>
                    </div>
                @endfor
            </div>
        </form>

        <!-- Botones AGREGAR y BORRAR -->
        <div class="w-full flex flex-col md:flex-row justify-center gap-4 py-4 bg-white mt-8">
            <a id="agregar-grupo" class="bg-blue-600 hover:cursor-pointer hover:bg-orange-500 text-white font-bold py-2 px-6 rounded text-center">AGREGAR</a>
            <a id="borrar-grupo" class="bg-blue-600 hover:cursor-pointer hover:bg-orange-500 text-white font-bold py-2 px-6 rounded text-center">BORRAR</a>
        </div>

        <!-- Botones GUARDAR, LIMPIAR y REGRESAR -->
        <div class="w-full flex flex-col md:flex-row justify-center gap-4 py-4 bg-white mt-4">
            <button onclick="document.getElementById('form-evaluacion').submit()" class="bg-blue-600 hover:cursor-pointer hover:bg-orange-500 text-white font-bold py-2 px-6 rounded">GUARDAR</button>
            <a href="#" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded text-center">LIMPIAR</a>
            <a href="{{ route('adminEvaluation') }}" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-2 px-6 rounded text-center">REGRESAR</a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("grupos-container");
    const agregarBtn = document.getElementById("agregar-grupo");
    const borrarBtn = document.getElementById("borrar-grupo");
    let grupoActual = 10;

    agregarBtn.addEventListener("click", function () {
        grupoActual++;

        const grupoDiv = document.createElement("div");
        grupoDiv.className = "flex flex-col gap-4 py-4 grupo-dinamico";
        grupoDiv.innerHTML = `
            <p class="font-bold text-lg">Indicador ${grupoActual}</p>
            <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                <label for="g${grupoActual}p1" class="whitespace-nowrap">Pregunta 1 -</label>
                <input type="text" name="questions[${grupoActual}][p1]" id="g${grupoActual}p1" class="shadow-md border border-gray-200 w-full md:flex-1">
                <label for="g${grupoActual}c1">Calificación</label>
                <input type="number" name="cal[${grupoActual}][c1]" id="g${grupoActual}c1" class="shadow-md border border-gray-200 w-16">
            </div>
            <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                <label for="g${grupoActual}p2" class="whitespace-nowrap">Pregunta 2 -</label>
                <input type="text" name="questions[${grupoActual}][p2]" id="g${grupoActual}p2" class="shadow-md border border-gray-200 w-full md:flex-1">
                <label for="g${grupoActual}c2">Calificación</label>
                <input type="number" name="cal[${grupoActual}][c2]" id="g${grupoActual}c2" class="shadow-md border border-gray-200 w-16">
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
