@extends('student.studentLayout')
@section('content')
<!-- Variables Sesiones -->
    @php
        $userInfo = session('userInfo');
        $courses = $userInfo['courses'];
        $profe = $userInfo['teacher']
    @endphp
<!-- Main Content -->
        <div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <!-- Cuadros centrados -->
                <div class="flex flex-col items-center">
                    <div class="bg-white p-4 text-center text-2xl font-bold">
                        <h1>
                            EVALUACION DE SATISFACCIÓN
                        </h1>
                    </div>
                    <div class="flex flex-wrap gap-8 mt-4 justify-center w-full h-auto">
                        <!-- Primer par: Asignatura -->
                        <div class="flex items-center gap-2">
                            <h1 class="font-bold">ASIGNATURA:</h1>
                            <h1 class="uppercase">{{ $courses[$noClaseId] }}</h1>
                        </div>

                        <!-- Segundo par: Docente -->
                        <div class="flex items-center gap-2">
                            <h1 class="font-bold">DOCENTE:</h1>
                            <h1 class="uppercase">{{ $profe[$noClaseId] }}</h1>
                        </div>
                    </div>

                    <!-- Seccion de preguntas -->
                    <form action="{{ route('studentSubmit') }}" method="POST">
                    @csrf
                        <div class="w-full mt-6 overflow-x-auto">
                            <table class="table-auto border border-gray-400 w-full text-left">
                                <tbody>
                                    @foreach ($questionGroups as $group)
                                    @php
                                        $groupOptions = $collectionOptions->where('question_group_id', $group->id)->values();
                                    @endphp

                                    @foreach ($groupOptions as $option)
                                        <tr>
                                            @if ($loop->first)
                                                <th class="border border-gray-400 text-center bg-blue-600 text-white px-4 py-2 align-middle" rowspan="{{ $groupOptions->count() }}">
                                                    Grupo {{ $group->id }}
                                                </th>
                                            @endif
                                            <td class="border border-gray-400 px-4 py-2 text-center">
                                                {{ $option->option ?? 'Pregunta sin texto' }}
                                            </td>
                                            <td class="border border-gray-400 px-4 py-2 text-center">
                                                <input type="checkbox" name="option_{{ $option->id }}" id="option_{{ $option->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                    
                                </tbody>
                            </table>
                            </div>
                            <!-- Observaciones -->
                            <div class="font-bold text-xl text-center p-2">
                                <h1>Observaciones</h1>
                            </div>
                            <div class="shadow-lg bg-gray-50 border-2 border-gray-200 w-full  ">
                                <textarea name="observaciones" class="w-full h-42 sm:w-full sm:h-42 resize-none rounded p-2 justify-center"></textarea>
                            </div>
                            <!-- boton enviar -->
                            <div class="p-6 flex justify-center">
                                <button type="submit" class="p-2 rounded-lg shadow-sm border-2 border-white text-white font-bold bg-orange-500 hover:cursor-pointer hover:bg-blue-500 hover:text-white hover:border-orange-500">
                                    ENVIAR
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
@endsection