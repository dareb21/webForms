@extends('admin.adminLayout')
@section('content')
<!-- Sweet Alert -->
    @if (session('alert'))
        <script>
            Swal.fire({
                title: "Advertencia",
                text: {!! json_encode(session('alert')) !!},
                icon: "warning"
            });
        </script>
    @endif
    
<!-- Main Content -->
<div class="flex-1 h-full md:ml-64 p-4 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full lg:h-full">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>
                    EVALUACIONES
                </h1>
            </div>

            

            <!-- Seccion de evaluaciones -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <!-- Búsqueda -->
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                    <label for="adminSearch">Búsqueda </label>
                    <form action="{{ route('adminEvaluationSearch')}}"  method="GET">
                    <input type="text" name="adminSearch" id="adminSearch" value="{{ request('adminSearch') }}" class="shadow-md border border-gray-200">
                    <select name="adminSearchSelect" id="adminSearchSelect" class="shadow-md border border-gray-200">
                        <option value="revision" {{ request('adminSearchSelect') == 'revision' ? 'selected' : '' }}>Revisión</option>
                        <option value="autor" {{ request('adminSearchSelect') == 'autor' ? 'selected' : '' }}>Autor</option>
                        <option value="fechaInicio" {{ request('adminSearchSelect') == 'fechaInicio' ? 'selected' : '' }}>Fecha Inicio</option>
                    </select>
                    <button type="submit" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                        Buscar
                    </button>
                    <a href="{{ route('adminEvaluation') }}" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                        Refrescar
                    </a>
                    </form>
                </div>
                </div>

                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white md:w-60">Revisión</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Fecha de Inicio</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Fecha de Cierre</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Autor</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estado</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surveys as $survey)
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->revision }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->dateStart }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->dateEnd }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->Author }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $survey->status == 1 ? "Activa" : "Inactiva"; }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">
                                        <form action="{{ route('adminEvaluationEdit', ['id' => $survey->id]) }}">
                                            <button type="submit" class="bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-3 rounded">
                                                Ver/Editar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6">
                <a href="{{ route('adminNewEvaluation') }}" class="bg-orange-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                    Crear Nueva
                </a>
            </div>
            
        </div>
    </div>
</div>
@endsection