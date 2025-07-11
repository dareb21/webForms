@extends('admin.adminLayout')
@section('content')

<!-- Main Content -->
<!-- Chart de períodos -->
<div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto"
     x-data="{
        openModal: false,
        modalChartLabels: [],
        modalChartData: [],
        modalTitle: '',
        showModalChart(title, labels, data) {
            this.modalTitle = title;
            this.modalChartLabels = labels;
            this.modalChartData = data;
            this.openModal = true;

            this.$nextTick(() => {
                const ctx = document.getElementById('modalChart').getContext('2d');

                // Destruir gráfico anterior si existe
                if (window.modalChartInstance) {
                    window.modalChartInstance.destroy();
                }

                // Crear gráfico de línea conectado
                window.modalChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.modalChartLabels, // nombres de los maestros
                        datasets: [{
                            label: 'Evaluaciones',
                            data: this.modalChartData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: 25,
                                ticks: {
                                    stepSize: 5
                                }
                            },
                            x: {
                                display: true
                            }
                        }
                    }
                });
            });
        }
     }">



    <div class="flex flex-col md:h-full md:flex-row gap-6">
        <!-- Gráfico principal -->
        <div class="bg-white rounded-lg shadow-md p-6 flex-1">
            <div class="flex justify-between items-center mt-4 mb-4">
                <h2 class="text-2xl text-gray-800 font-bold">Bienvenido Administrador</h2>
            </div>
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-gray-700 font-bold md:mt-10 mb-2 text-left">Satisfacción por períodos</h3>
                <span class="text-md font-medium text-right">Catedráticos evaluados: </span>
            </div>
            <div class="flex justify-center items-center">
                <div class="md:w-85 md:h-85 relative">
                    <canvas id="revenueChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Stats por período -->
        <div class="grid grid-cols-1 bg-orange-100 text-center sm:grid-cols-2 md:grid-cols-1 gap-4 md:w-64">
            <!-- Catedráticos evaluados -->
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl">Catedráticos evaluados</p>
                <canvas id="progressChart" class="w-36 h-36 mx-auto"></canvas>
            </div>

            <!-- Período 1 -->
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 1
                    <a @click="showModalChart('Período 1',
                          ['Maestro A', 'Maestro B', 'Maestro C', 'Maestro D', 'Maestro E'],
                          [12, 17, 15, 14, 18])"
                        class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white cursor-pointer">
                        Ver más
                    </a>
                </p>
            </div>

            <!-- Período 2 -->
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 2
                    <a @click="showModalChart('Período 1', [12, 15, 18, 14, 17])"
                       class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white cursor-pointer">
                        Ver más
                    </a>
                </p>
            </div>

            <!-- Período 3 -->
            <div class="bg-white p-2 rounded-lg shadow-md">
                <p class="font-bold text-gray-800 text-xl flex justify-between items-center">
                    Período 3
                    <a @click="showModalChart('Período 1', [12, 15, 18, 14, 17])"
                       class="p-1 font-normal bg-white text-gray-700 border border-gray-500 rounded-md hover:bg-blue-600 hover:text-white cursor-pointer">
                        Ver más
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="openModal" x-cloak
        class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 relative w-full max-w-2xl">
            <button @click="openModal = false"
                    class="absolute top-4 right-4 text-gray-600 hover:text-red-600 text-2xl">
                &times;
            </button>
            <h2 class="text-2xl font-bold mb-6 text-center" x-text="modalTitle"></h2>
            <div class="w-full h-[400px]">
                <canvas id="modalChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    </div>

</div>

<!-- Gráfico principal (torta de períodos) -->
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'pie',
        data: {
            labels: ["Período 1", "Período 2", "Período 3"],
            datasets: [{
                label: 'Promedios por Período',
                data: [17, 13, 20],
                backgroundColor: ['#FF5C00', '#0000FF', '#00FFFF'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });

    // Gráfico de catedráticos evaluados
    const value = 75;
    const remaining = 100 - value;

    new Chart(document.getElementById('progressChart'), {
        type: 'doughnut',
        data: {
            labels: ['Progreso', 'Restante'],
            datasets: [{
                data: [value, remaining],
                backgroundColor: ['#0000FF', '#e5e7eb'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
</script>
@endsection
