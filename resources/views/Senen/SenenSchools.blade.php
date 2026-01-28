@extends('Senen.SenenLayout')
@section('content')
    <div class="flex-1 ml-0 md:h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full min-h-full">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    EVALUACIONES POR ESCUELAS
                </h1>
            </div>

            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">
                        <label for="schoolSearch">Búsqueda por escuela </label>

                        <form action="{{ route('senen.schools.filter') }}">
                            <select name="schoolSearch" id="schoolSearch" class="shadow-sm ml-2 border-1 border-gray-200">
                                @foreach ($school as $results)
                                    <option value="{{ $results['id'] }}">{{ $results['Name'] }}</option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold ml-2 py-1 px-3 rounded">
                                Buscar
                            </button>
                        </form>

                        <a href="{{ route('senen.schools') }}"
                            class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                            Refrescar
                        </a>

                        <a href="{{ route('senen.school.pdf') }}"
                            class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                            PDF
                        </a>

                        <a href="{{ route('senen.school.excel') }}"
                            class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                            EXCEL
                        </a>
                    </div>

                    <div class="overflow-x-auto w-full mt-4">
                        <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-2 text-center">Escuela</th>
                                    <th class="px-4 py-2 text-center">Promedio</th>
                                    <th class="px-4 py-2 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="border-b border-gray-300">
                                @foreach ($school as $results)
                                    <tr>
                                        <td class="border-b border-gray-300 px-4 py-2 text-center">
                                            {{ $results['Name'] }}
                                        </td>
                                        <td class="border-b border-gray-300 px-4 py-2 text-center">
                                            {{ $results['score'] }}
                                        </td>
                                        <td class="border-b border-gray-300 px-4 py-2 text-center">
                                            <a href="{{ route('senen.results', ['schoolId' => $results['id']]) }}"
                                                class="bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold py-1 px-3 rounded">
                                                VER ESCUELA
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection