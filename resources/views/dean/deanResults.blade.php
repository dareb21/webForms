@extends('dean.deanLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 h-full ml-0 md:ml-64 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    RESULTADOS DE EVALUACIONES
                </h1>
            </div>
            <!-- Segmentación por años y períodos -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                    <label for="catedraticoBusqueda">Búsqueda por nombre </label>
                    <input type="text" name="catedraticoBusqueda" id="catedraticoBusqueda" class="shadow-sm ml-2 border-1 border-gray-200">
                    <label for="anualYear">Año</label>
                    <select name="anualYear" id="anualYear" class="shadow-md border border-gray-200">
                        <option value="anualY1">2025</option>
                        <option value="anualY2">2024</option>
                        <option value="anualY2">2023</option>
                    </select>
                    <label for="anualPeriod">Período</label>
                    <select name="anualPeriod" id="anualPeriod" class="shadow-md border border-gray-200">
                        <option value="anualP1">Anual</option>
                        <option value="anualP2">Período 1</option>
                        <option value="anualP3">Período 2</option>
                        <option value="anualP4">Período 3</option>
                    </select>
                    <button class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold px-3 rounded">
                        Buscar
                    </button>
                    <a href="{{ route('deanResults') }}" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                        Refrescar
                    </a>
                </div>
            <!-- Seccion de evaluaciones -->
            <div class="w-full h-full overflow-x-auto">
                <table id="mi-tabla" class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Escuela</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Calificación</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiantes</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Exportar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Aqui ira la info de busqueda -->
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">2/20</td>
                            <td class="border border-gray-400 px-4 py-2 text-center">
                                <a href="{{ route('deanStudentView') }}"  class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                    VER
                                </a>
                            </td>
                            <td class="border border-gray-400 px-4 py-2 text-center">
                                <a href="#" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                    EXPORTAR
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-6 flex justify-center">
                    <a href="{{ route('deanSchools') }}" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                        REGRESAR
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let ascending = true; // Alternancia ascendente/descendente

document.getElementById("sort-button").addEventListener("click", function () {
    const table = document.getElementById("mi-tabla").querySelector("tbody");
    const rows = Array.from(table.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const tdA = a.children[4].innerText.trim(); // Columna 5: Calificación
        const tdB = b.children[4].innerText.trim();

        const [numA, denA] = tdA.split('/').map(Number);
        const [numB, denB] = tdB.split('/').map(Number);

        const valA = numA / denA;
        const valB = numB / denB;

        return ascending ? valA - valB : valB - valA;
    });

    // Reordenar las filas en el <tbody>
    rows.forEach(row => table.appendChild(row));

    ascending = !ascending;
});
</script>

@endsection