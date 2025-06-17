@extends('admin.adminLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full relative pb-24">
        <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    EDICIÓN DE EVALUACIONES
                </h1>
        </div>
            <div class="flex justify-center gap-x-4 flex-wrap py-4">
                <label for="">EVALUACION # -</label>
                <input type="text" class="shadow-md border border-gray-200">
                <label for="">Fecha Inicio -</label>
                <input type="date" name="" id="" class="shadow-md border border-gray-200">
                <label for="">Fecha Cierre -</label>
                <input type="date" name="" id="" class="shadow-md border border-gray-200">
            </div>
            <!-- for each para las preguntas -->
            <div id="grupos-container">
                <div class="flex flex-col gap-y-4 py-4">
                    <p class="font-bold text-lg">Grupo 1</p>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 1 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 2 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                </div>
                <div class="flex flex-col gap-y-4 py-4">
                    <p class="font-bold text-lg">Grupo 2</p>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 1 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 2 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                </div>
                <div class="flex flex-col gap-y-4 py-4">
                    <p class="font-bold text-lg">Grupo 3</p>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 1 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 2 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                </div>
                <div class="flex flex-col gap-y-4 py-4">
                    <p class="font-bold text-lg">Grupo 4</p>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 1 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                    <div class="flex items-center gap-x-2 w-full">
                        <label for="" class="whitespace-nowrap">Pregunta 2 -</label>
                        <input type="text" class="shadow-md border border-gray-200 flex-1 w-full">
                    </div>
                </div>
            </div>

            <!-- Botones  -->
            <div class="w-full flex justify-center gap-4 flex-col md:flex-row py-4 bg-white rounded-b-xl mt-8">
                <a id="agregar-grupo" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded hover:cursor-pointer">AGREGAR</a>
                <a id="borrar-grupo" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded hover:cursor-pointer">BORRAR</a>
            </div>
            <div class="w-full flex justify-center gap-4 flex-col md:flex-row py-4 bg-white rounded-b-xl mt-8">
                <a href="#" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">GUARDAR</a>
                <a href="#" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">ACTIVAR</a>
                <a href="#" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-6 rounded">DESACTIVAR</a>
                <a href="#" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-6 rounded">ELIMINAR</a>
                <a href="{{ route('adminEvaluation') }}" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">REGRESAR</a>
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
                const match = titulo.textContent.match(/Grupo\s+(\d+)/);
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
                <p class="font-bold text-lg">Grupo ${nuevoNumeroGrupo}</p>
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
