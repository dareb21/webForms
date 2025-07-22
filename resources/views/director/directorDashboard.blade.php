@extends('director.directorLayout')
@section('content')
    @php
        $pAnual = $dashboard['anual'];

        $p1 = isset($dashboard['resultsPerTerm'][0]['termScore']) ? $dashboard['resultsPerTerm'][0]['termScore']: 0;
        $p2 = isset($dashboard['resultsPerTerm'][1]['termScore']) ? $dashboard['resultsPerTerm'][1]['termScore']: 0;
        $p3 = isset($dashboard['resultsPerTerm'][2]['termScore']) ? $dashboard['resultsPerTerm'][2]['termScore']: 0;
    @endphp
    <!-- Main Content -->
    <!-- Chart de períodos -->
    <div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto"
        x-data="modalChartComponent()">

        <div class="flex flex-col md:h-full md:flex-row gap-6">
            <!-- Gráfico principal -->
            <div class="bg-white rounded-lg shadow-md p-6 flex-1">
                <div class="flex justify-between items-center mt-4 mb-4">
                    <h2 class="text-2xl text-gray-800 font-bold">Bienvenido Administrador</h2>
                </div>
                <div
                    class="flex flex-col md:flex-row justify-between md:items-center space-y-2 md:space-y-0 md:space-x-1 w-full mt-10 mb-2">
                    <div class="text-gray-700 font-bold">
                        <h3>Satisfacción por períodos</h3>
                    </div>
                    <div>
                        <label for="schoolSegmentation" class="text-gray-700 font-bold">Segmentación: </label>
                        <form action="{{ route('deanDashboard') }}" method="GET" class="inline-block">
                            <select name="schoolSegmentation" id="schoolSegmentation" onchange="this.form.submit()"
                                class="rounded-sm p-1 md:w-80 shadow-md">
                                {{-- @foreach ($schoolInfo['schoolsInfo'] as $school)
                                    <option value="{{ $school['id'] }}"
                                        {{ request('schoolSegmentation') == $school['id'] ? 'selected' : '' }}>
                                        {{ $school['name'] }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </form>
                    </div>
                </div>
                <div class="flex justify-center items-center">
                    <div class="w-full h-full md:max-w-[700px] md:h-[350px] relative">
                        <canvas id="revenueChart" class="w-full h-full"></canvas>
                    </div>
                </div>

            </div>

            <!-- Stats por período -->
            <div class="grid grid-cols-1 bg-orange-100 text-center sm:grid-cols-2 md:grid-cols-1 gap-4 md:w-64">
                <!-- Clases evaluados -->
                <div class="bg-white p-1 rounded-lg shadow-md">
                    <p class="font-bold text-gray-800 text-xl">Clases evaluadas</p>
                    <canvas id="progressChart" class="mt-2 w-36 h-30 mx-auto"></canvas>
                </div>

                <!-- Período 1 -->
                <div class="bg-white p-2 rounded-lg shadow-md cursor-pointer hover:bg-blue-50 transition"
                    @click="showModalChart(
                    'Período 1',
                    ['Maestro A', 'Maestro B', 'Maestro C', 'Maestro D', 'Maestro E'],
                    [12, 17, 15, 14, 18]
                )">
                    <p class="font-bold text-gray-800 text-xl">Período 1</p>
                    <h2 class="text-3xl font-bold mt-2 text-gray-600">{{ $p1 }}</h2>
                </div>

                <!-- Período 2 -->
                <div class="bg-white p-2 rounded-lg shadow-md cursor-pointer hover:bg-blue-50 transition"
                    @click="showModalChart(
                    'Período 2',
                    ['Maestro F', 'Maestro G', 'Maestro H', 'Maestro I', 'Maestro J'],
                    [12, 15, 18, 14, 17]
                )">
                    <p class="font-bold text-gray-800 text-xl">Período 2</p>
                    <h2 class="text-3xl font-bold mt-2 text-gray-600">{{ $p2 }}</h2>
                </div>

                <!-- Período 3 -->
                <div class="bg-white p-2 rounded-lg shadow-md cursor-pointer hover:bg-blue-50 transition"
                    @click="showModalChart(
                    'Período 3',
                    ['Maestro K', 'Maestro L', 'Maestro M', 'Maestro N', 'Maestro O'],
                    [12, 15, 18, 14, 17]
                )">
                    <p class="font-bold text-gray-800 text-xl">Período 3</p>
                    <h2 class="text-3xl font-bold mt-2 text-gray-600">{{ $p3 }}</h2>
                </div>
            </div>
        </div>
        <!-- Puntuación docentes --->
        <!-- Mayores a 15 -->
        <div class="flex flex-col-2 mt-6 md:h-full md:flex-row gap-6">
            <div class="bg-white text-center p-2 rounded-lg shadow-m w-1/2">
                <p class="font-bold text-gray-800 text-xl">Docentes Mayor a 15</p>
                <div class="mt-2">
                    <table class="mx-auto w-4/5">
                        <tbody>
                            @foreach ($higherOrLower['higher15'] as $higher)
                                <tr class="border-b">
                                    <td class="py-2 text-gray-700 text-left">{{ $higher->name }}</td>
                                    <td class="py-2 text-gray-900 text-right font-bold">{{ round($higher->Calification) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Menores a 10 -->
            <div class="bg-white text-center p-2 rounded-lg shadow-m w-1/2">
                <p class="font-bold text-gray-800 text-xl">Docentes Menor a 10</p>
                <div class="mt-2">
                    <table class="mx-auto w-4/5">
                        <tbody>
                            @foreach ($higherOrLower['lower10'] as $lower)
                                <tr class="border-b">
                                    <td class="py-2 text-gray-700 text-left">Maestro {{ $lower->name }}</td>
                                    <td class="py-2 text-gray-900 text-right font-bold">{{ round($lower->Calification) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div x-show="openModal" x-cloak
            class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg p-8 relative w-full max-w-2xl">
                <button @click="openModal = false" class="absolute top-4 right-4 text-gray-600 hover:text-red-600 text-2xl">
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
            type: 'bar',
            data: {
                labels: ["Período 1", "Período 2", "Período 3"],
                datasets: [{
                    label: 'Puntaje',
                    data: [{{ $p1 }},{{ $p2 }},{{ $p3 }}],
                    backgroundColor: ['#f7dc6f', '#0000FF', '#58d68d'],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    maxBarThickness: 100,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 21,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Períodos Académicos'
                        }
                    }

                }
            }
        });


        // Gráfico de clases evaluados
        const value = {{ $schoolInfo['withSubmits'] }};
        const remaining = {{ $schoolInfo['sections'] }} - value;

        new Chart(document.getElementById('progressChart'), {
            type: 'doughnut',
            data: {
                labels: ['Clases evaluadas', 'Clases Restantes'],
                datasets: [{
                    data: [value, remaining],
                    backgroundColor: ['#0000FF', '#e5e7eb'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                }
            }
        });
    </script>
@endsection
