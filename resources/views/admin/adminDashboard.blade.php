@extends('admin.adminLayout')
@section('content')
<!-- Main Content -->
        <div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">

        <!-- Top Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <p class="text-gray-500">Evaluacion</p>
                <h2 class="text-2xl font-bold">300</h2>
                <canvas id="chart1" height="40"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <p class="text-gray-500">Estudiantes</p>
                <h2 class="text-2xl font-bold">300</h2>
                <canvas id="chart2" height="40"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <p class="text-gray-500">Directores</p>
                <h2 class="text-2xl font-bold">300</h2>
                <canvas id="chart3" height="40"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <p class="text-gray-500">Ejemplo</p>
                <h2 class="text-2xl font-bold">300</h2>
                <canvas id="chart4" height="40"></canvas>
            </div>
        </div>

        <!-- Overview Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Overview</h2>
                <button class="text-blue-500 border border-blue-500 px-4 py-1 rounded">Export</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Summary Cards -->
                <div class="md:col-span-1 space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Expenses</p>
                        <h3 class="text-xl font-bold">$72,000</h3>
                        <p class="text-green-500 text-sm">↑ 22%</p>
                        <canvas id="expensesChart" height="40"></canvas>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Orders</p>
                        <h3 class="text-xl font-bold">600</h3>
                        <p class="text-red-500 text-sm">↓ 5%</p>
                        <canvas id="ordersChart" height="40"></canvas>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">New Clients</p>
                        <h3 class="text-xl font-bold">100</h3>
                        <p class="text-green-500 text-sm">↑ 3%</p>
                        <canvas id="clientsChart" height="40"></canvas>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="md:col-span-4">
                    <h3 class="text-gray-700 font-bold mb-2">Revenue</h3>
                    <canvas id="revenueChart" height="120"></canvas>
                    <div class="flex gap-4 mt-2 text-sm text-gray-600">
                        <div><span class="inline-block w-3 h-3 bg-blue-400 mr-1 rounded-full"></span> Individuals</div>
                        <div><span class="inline-block w-3 h-3 bg-indigo-600 mr-1 rounded-full"></span> Businesses</div>
                    </div>
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

            const smallData = {
                labels: ["", "", "", "", ""],
                datasets: [{
                    borderColor: "#3B82F6",
                    borderWidth: 2,
                    fill: false,
                    data: [1, 3, 1.5, 3.2, 2.8]
                }]
            };

            ["chart1", "chart2", "chart3", "chart4"].forEach(id => {
                new Chart(document.getElementById(id), {
                    type: 'line',
                    data: smallData,
                    options: chartOptions
                });
            });

            new Chart(document.getElementById('expensesChart'), {
                type: 'bar',
                data: {
                    labels: ["", "", "", "", ""],
                    datasets: [{
                        backgroundColor: "#3B82F6",
                        data: [6, 8, 4, 7, 6]
                    }]
                },
                options: chartOptions
            });

            new Chart(document.getElementById('ordersChart'), {
                type: 'bar',
                data: {
                    labels: ["", "", "", "", ""],
                    datasets: [{
                        backgroundColor: "#3B82F6",
                        data: [8, 5, 6, 4, 5]
                    }]
                },
                options: chartOptions
            });

            new Chart(document.getElementById('clientsChart'), {
                type: 'bar',
                data: {
                    labels: ["", "", "", "", ""],
                    datasets: [{
                        backgroundColor: "#3B82F6",
                        data: [2, 3, 4, 3, 5]
                    }]
                },
                options: chartOptions
            });

            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                    datasets: [
                        {
                            label: 'Individuals',
                            data: [150000, 120000, 100000, 110000, 140000, 180000],
                            borderColor: '#3B82F6',
                            backgroundColor: 'transparent',
                            tension: 0.4
                        },
                        {
                            label: 'Businesses',
                            data: [90000, 80000, 70000, 100000, 130000, 150000],
                            borderColor: '#6366F1',
                            backgroundColor: 'transparent',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => `$${value.toLocaleString()}`
                            }
                        }
                    }
                }
            });
            </script>
@endsection