@extends('director.directorLayout')
@section('content')
@php
    $pAnual = $anual;

    $p1 = isset($resultados[0]['termScore']) ? $resultados[0]['termScore'] : 0;
    $p2 = isset($resultados[1]['termScore']) ? $resultados[1]['termScore'] : 0;
    $p3 = isset($resultados[2]['termScore']) ? $resultados[2]['termScore'] : 0;
@endphp
<!-- Main Content -->
<div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="flex flex-col md:h-full md:flex-row gap-6">
        <!-- Gráfico dentro del div blanco -->
        <div class="bg-white rounded-lg shadow-md p-6 flex-1">
            <div class="flex justify-between items-center mt-4 mb-4">
                <h2 class="text-2xl text-gray-800 font-bold">Bienvenido a la escuela de Ciencias Informáticas</h2>
            </div>
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-gray-700 font-bold md:mt-10 mb-2 text-left">Satisfacción por períodos</h3>
                <span class="text-md font-medium text-right">Catedráticos evaluados: {{ $professorsEvaluated }}  / {{ $allProfessor }}</span>
            </div>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <!-- Period Stats fuera del div blanco -->
        <div class="grid grid-cols-1 bg-orange-100 text-center sm:grid-cols-2 md:grid-cols-1 gap-4 md:w-64">
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Promedio Anual
                    <a href="{{ route('directorResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">{{ $pAnual }}</h2>
            </div>
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 1
                    <a href="{{ route('directorResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">{{ $p1 }}</h2>
            </div>
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 2
                    <a href="{{ route('directorResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">{{ $p2 }}</h2>
            </div>
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 3
                    <a href="{{ route('directorResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">{{ $p3 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Script para inicializar los gráficos -->
<script>
    const chartOptions = {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { display: false }, y: { display: false } }
    };

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: ["Perído 1", "Período 2", "Período 3"],
            datasets: [
                {
                    label: 'Promedio Anual',
                    data: [{{ $pAnual }},{{ $pAnual }},{{ $pAnual }}],
                    borderColor: '#FF5C00',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'Períodos',
                    data: [{{ $p1 }},{{ $p2 }},{{ $p3 }}],
                    borderColor: '#000080',
                    backgroundColor: 'transparent',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true,
                    position: 'top',
                 }
            },
            scales: {
                y: {
                    min: 0,
                    max: 25,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: value => `${value.toLocaleString()}`
                    }
                }
            }
        }
    });
</script>
@endsection
