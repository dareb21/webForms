@extends('adminDCA.adminDCALayout')
@section('content')
    @php
        $k = 0;
        $j = 0;
    @endphp
    <!-- Main Content -->
    <!-- Chart de períodos -->
    <div
        class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto"
        x-data="modalSchoolComponent()"
    >

        <div class="flex flex-col md:h-full md:flex-row gap-6">
            <!-- Gráfico principal -->
            <div class="bg-white rounded-lg shadow-md p-6 flex-1">
                <div class="flex justify-between items-center mt-4 mb-4">
                    <h2 class="text-2xl text-gray-800 font-bold">Bienvenido Administrador DCA</h2>
                </div>
                <div
                    class="flex flex-col md:flex-row justify-between md:items-center space-y-2 md:space-y-0 md:space-x-1 w-full mt-10 mb-2"
                >
                    <div class="text-gray-700 font-bold">
                        <h3>Información de clases evaluadas</h3>
                    </div>
                    <div>
                        <label for="schoolSegmentation" class="text-gray-700 font-bold">Segmentación: </label>
                        <select
                            name="schoolSegmentation"
                            id="schoolSegmentation"
                            class="rounded-sm p-1 shadow-md"
                        >
                            <option value="">Datos Generales</option>
                            <option value="">Escuela de Ciencias Informáticas</option>
                            <option value="">Escuela de Ciencias Exactas</option>
                            <option value="">Escuela de Derecho</option>
                            <option value="">Escuela de Comunicación</option>
                            <option value="">Escuela de Agronómicas</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-center items-center">
                    <div
                        class="flex justify-center items-center w-full md:mt-5 h-full md:max-w-[700px] md:h-[350px] relative"
                    >
                        <canvas id="progressChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stats por período -->
            <div
                class="grid grid-rows-1 bg-orange-100 text-center sm:grid-rows-2 gap-4 md:w-64"
            >
                <!-- Escuelas con más clases -->
                <div
                    class="flex justify-center items-center bg-white p-2 rounded-lg shadow-md cursor-pointer hover:bg-green-50 transition"
                    @click="showSchoolModal(
                        'Escuelas con más clases Evaluadas',
                        ['Escuela A', 'Escuela B', 'Escuela C']
                    )"
                >
                    <p class="text-center font-bold text-gray-800 text-xl">
                        Escuelas con más clases Evaluadas
                    </p>
                </div>

                <!-- Escuelas con menos clases -->
                <div
                    class="flex justify-center items-center bg-white p-2 rounded-lg shadow-md cursor-pointer hover:bg-red-50 transition"
                    @click="showSchoolModal(
                        'Escuelas con menos clases Evaluadas',
                        ['Escuela F', 'Escuela G', 'Escuela H']
                    )"
                >
                    <p class="text-center font-bold text-gray-800 text-xl">
                        Escuelas con menos clases Evaluadas
                    </p>
                </div>
            </div>

            <!-- Modal -->
            <div
                x-show="openModal"
                x-cloak
                x-transition
                class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm z-50 flex items-center justify-center"
                style="display: none;"
            >
                <div
                    class="bg-white rounded-lg shadow-lg p-8 relative w-full max-w-md"
                    @click.away="openModal = false"
                >
                    <!-- Botón de cerrar -->
                    <button
                        @click="openModal = false"
                        class="absolute top-4 right-4 text-gray-600 hover:text-red-600 text-2xl"
                    >
                        &times;
                    </button>

                    <!-- Título del modal -->
                    <h2 class="text-2xl font-bold mb-6 text-center" x-text="modalTitle"></h2>

                    <!-- Lista de escuelas -->
                    <ul class="space-y-4">
                        <template x-for="(escuela, index) in modalEscuelas" :key="index">
                            <li
                                class="bg-gray-100 rounded-lg p-4 flex justify-between items-center shadow-sm"
                            >
                                <span
                                    class="font-semibold text-gray-700"
                                    x-text="escuela.nombre"
                                ></span>
                                <span
                                    class="text-blue-600 font-bold"
                                    x-text="`${escuela.clases} clases`"
                                ></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        const value = 553;
        const maxValue = 1000;
        const remaining = maxValue - value;

        // Plugin para texto centrado
        const centerTextPlugin = {
            id: "centerText",
            beforeDraw(chart) {
                const { width, height, ctx } = chart;
                ctx.save();

                const fontSize = Math.min(width, height) / 16;
                ctx.font = `${fontSize}px sans-serif`;
                ctx.fillStyle = "#000";
                ctx.textBaseline = "middle";
                ctx.textAlign = "center";

                const text = `${value} / ${maxValue} evaluado`;
                const centerX = width / 2;
                const centerY = height / 2;

                ctx.fillText(text, centerX, centerY);
                ctx.restore();
            },
        };

        // Renderizado del gráfico principal
        new Chart(document.getElementById("progressChart"), {
            type: "doughnut",
            data: {
                labels: ["Clases evaluadas", "Clases Restantes"],
                datasets: [
                    {
                        data: [value, remaining],
                        backgroundColor: ["#0000FF", "#e5e7eb"],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                cutout: "70%",
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                    },
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                },
            },
            plugins: [centerTextPlugin],
        });

        function modalSchoolComponent() {
            return {
                openModal: false,
                modalEscuelas: [],
                modalTitle: "",

                showSchoolModal(title, labels) {
                    this.modalTitle = title;
                    this.modalEscuelas = labels.map((label) => ({
                        nombre: label,
                        clases: Math.floor(Math.random() * 30) + 1,
                    }));
                    this.openModal = true;
                },
            };
        }
    </script>
@endsection
