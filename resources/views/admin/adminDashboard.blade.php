@extends('admin.adminLayout')
@section('content')
<!-- Main Content -->
<div class="flex-1 ml-0 md:h-full md:ml-64 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">

    <div class="flex flex-col md:h-full md:flex-row gap-6">
        <!-- Gráfico dentro del div blanco -->
        <div class="bg-white rounded-lg shadow-md p-6 flex-1">
            <div class="flex justify-between items-center mt-4 mb-4">
                <h2 class="text-2xl text-gray-800 font-bold">Bienvenido Administrador</h2>
            </div>
            <h3 class="text-gray-700 font-bold md:mt-10 mb-2">Satisfacción por períodos</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        <!-- Top Stats fuera del div blanco -->
        <div class="grid grid-cols-1 bg-orange-100 text-center sm:grid-cols-2 md:grid-cols-1 gap-4 md:w-64">
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Promedio Anual
                    <a href="{{ route('adminResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">66%</h2>
            </div>
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 1
                    <a href="{{ route('adminResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">75%</h2>
            </div>
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 2
                    <a href="{{ route('adminResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">69%</h2>
            </div>
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 3
                    <a href="{{ route('adminResults') }}" class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white">Ver más</a>
                </p>
                <h2 class="text-2xl font-bold mt-2 text-gray-600">72%</h2>
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
                    data: [50,50,50],
                    borderColor: '#FF5C00',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'Períodos',
                    data: [45, 60, 20],
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
                    max: 100,
                    beginAtZero: true,
                    ticks: {
                        callback: value => `${value.toLocaleString()}`
                    }
                }
            }
        }
    });
</script>
@endsection
