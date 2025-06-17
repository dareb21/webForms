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

            <!-- Botones  -->
            <div class="w-full flex justify-center gap-4 flex-col md:flex-row py-4 bg-white rounded-b-xl mt-8">
                <a href="#" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">GUARDAR</a>
                <a href="#" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">ACTIVAR</a>
                <a href="#" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-6 rounded">DESACTIVAR</a>
                <a href="#" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-6 rounded">ELIMINAR</a>
                <a href="{{ route('adminEvaluation') }}" class="bg-blue-600 hover:bg-orange-500 text-white font-bold py-1 px-6 rounded">REGRESAR</a>
            </div>
    </div>
</div>
@endsection
