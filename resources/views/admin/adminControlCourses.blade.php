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
<div class="flex-1 ml-0 md:ml-64 p-4 h-full bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full min-h-[calc(100vh-3rem)]">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 text-center text-2xl font-bold">
                <h1>CONTROL DE CURSOS</h1>
            </div>

            <!-- Búsqueda segmentada por nombre, años y períodos -->
            <div class="w-full h-full mt-6 overflow-x-auto">
                <div class="w-full flex flex-col items-start pt-3">
                    <div class="flex gap-x-4 flex-wrap py-4">  
                        <form action="{{ route('searchCourse') }}" method="GET" class="flex flex-wrap items-center gap-x-4">
                            <label for="courseSearch">Búsqueda por nombre</label>
                            <input type="text" name="courseSearch" id="courseSearch"
                                value="{{ request('courseSearch') }}"
                                class="shadow-sm ml-2 border border-gray-200">
                            <button type="submit" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white text-center font-bold py-1 px-4 rounded">
                                Buscar
                            </button>

                            <a href="{{ route('adminControlCourses') }}" class="inline-block bg-orange-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-4 rounded">
                                Refrescar
                            </a>
                        </form>
                    </div>
                </div>

            <!-- Sección de evaluaciones -->
                <table class="table-auto border border-gray-400 w-full min-w-[600px] text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Catedrático</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Clase</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Sección</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Estado</th>
                            <th class="border border-gray-400 px-4 py-2 text-center bg-blue-600 text-white">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($noInfo) && $noInfo)
                            @for ($i=1; $i<=6; $i++)
                                <td class="border border-gray-400 px-4 py-2 text-center"></td>
                            @endfor
                        @else
                            @foreach ($courses as $course)
                                <tr>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $course['courseProfessor'] }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">{{ $course['courseName'] }}</td>
                                    <td class="border border-gray-400 px-4 py-2 text-center">Hola</td>
                                    @if ($course['courseStatus'] ===1)
                                        <td class="border border-gray-400 px-4 py-2 text-center">Activo</td>
                                    @else
                                        <td class="border border-gray-400 px-4 py-2 text-center">Inactivo</td>
                                    @endif
                                    
                                    <td class="md:w-40 border border-gray-400 px-4 py-2 text-center">
                                        <div class="flex flex-col sm:flex-row sm:justify-center sm:items-center gap-1">
                                            <form action="{{ route('blockCourse', ['courseId'=> $course['courseId']]) }}" method="POST">
                                                @csrf
                                                <button class="bg-red-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-1 rounded w-full sm:w-auto">
                                                    Bloquear
                                                </button>
                                            </form>
                                            <form action="{{ route('unblockCourse', ['courseId'=> $course['courseId']]) }}" method="POST">
                                                @csrf
                                                <button class="bg-green-500 hover:bg-blue-700 hover:cursor-pointer text-white font-bold py-1 px-1 rounded w-full sm:w-auto">
                                                    Desbloquear
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{-- <!-- Paginación -->
                <div class="w-full flex justify-center py-4">
                    {{ $data->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
