@extends('dean.deanLayout')
@section('content')
    <!-- Main Content -->
    <div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
            <div class="flex flex-col items-center">
                <div class="bg-white p-4 text-center text-2xl font-bold">
                    <h1 class="uppercase">
                        EVALUACIONES <span class="italic">{{ request('courses') }}</span>
                    </h1>
                    <h1 class="text-xl mt-4 uppercase">
                        CATEDRÁTICO <span class="italic">{{ request('Professor') }}</span>
                    </h1>
                </div>
                <!-- Seccion de evaluaciones -->
                <div class="w-full h-full mt-6 overflow-x-auto">
                    <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                        <thead>
                            <tr>
                                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estudiante
                                </th>
                                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Puntuación
                                </th>
                                <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resultados as $results)
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $results['nameStudent'] }}
                                    </td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $results['score'] }}</td>
                                    <!-- Agrega este bloque dentro de tu <td> en la tabla -->
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        <div x-data="deanAnswers()" class="relative">
                                            <!-- Botón que activa el modal y carga las respuestas -->
                                            <a @click="fetchAnswers({{ $results['submitId'] }})"
                                                class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded cursor-pointer">
                                                VER RESPUESTAS
                                            </a>

                                            <!-- Modal -->
                                            <div x-show="open" x-cloak x-transition
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-2xl relative">
                                                    <!-- Cerrar -->
                                                    <button @click="open = false"
                                                        class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">
                                                        &times;
                                                    </button>
                                                    <h2 class="text-xl font-bold mb-4">Detalles de la Evaluación</h2>

                                                    <!-- Spinner -->
                                                    <template x-if="loading">
                                                        <div class="flex justify-center py-8">
                                                            <svg class="animate-spin h-8 w-8 text-gray-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12"
                                                                    r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8v8z"></path>
                                                            </svg>
                                                        </div>
                                                    </template>

                                                    <!-- Contenedor con scroll de respuestas -->
                                                    <div x-show="!loading"
                                                        class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                                                        <template x-if="answers.length === 0">
                                                            <p class="text-center text-gray-500">No hay respuestas.</p>
                                                        </template>

                                                        <template x-for="resp in answers" :key="resp.indicator">
                                                            <div class="border-b pb-2">
                                                                <p><strong>Indicador <span x-text="resp.indicator">
                                                                        </span></strong></p>
                                                                <p><strong>Respuesta:</strong> <span
                                                                        x-text="resp.answer"></span></p>
                                                            </div>
                                                        </template>

                                                        <template x-if="observation">
                                                            <div class="mt-4">
                                                                <p><strong>Observaciones:</strong></p>
                                                                <p x-text="observation"></p>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 flex justify-center">
                    <a href="javascript:void(0);" onclick="history.back();"
                        class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                        REGRESAR
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function deanAnswers() {
            return {
                open: false,
                loading: false,
                answers: [],
                observation: '',

                fetchAnswers(submitId) {
                    this.open = true;
                    this.loading = true;
                    fetch(`/deanViewAnswer/${submitId}`)
                        .then(res => res.json())
                        .then(data => {
                            this.observation = '';
                            this.answers = data.filter(item => item.indicator);
                            const obsItem = data.find(item => item.observation);
                            if (obsItem) {
                                this.observation = obsItem.observation;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.answers = [];
                            this.observation = '';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }
        }
    </script>
@endsection
