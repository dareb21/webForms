@extends('estudiante.estudianteLayout')
@section('content')
    <div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full h-full">
            <div class="flex flex-col">
                <div class="bg-white p-4 text-center text-4xl font-bold">
                    <h1>
                        Bienvenido a Evaluacion Docencia
                    </h1>
                </div>
                <!-- Instrucciones -->
                <div class="flex flex-wrap gap-8 mt-4 justify-center w-full h-auto">
                    <div class="text-center">
                        <h1 class="font-bold text-lg">INSTRUCCIONES</h1>
                        <h1 class="p-2 text-center">
                            Estimado estudiante, queremos conocer aspectos pedagógicos y medotológicos del desarrollo de la clase,
                            para ello se le presentarán parejas de enunciados en donde usted deberá seleccionar uno,
                            considerando el que es más afin a lo que sucede en clase. Recuerde solo debe seleccionar una
                            alternativa y no deje de responder ninguna pregunta, debe seleccionar la opcion deseada.
                            Trate de ser lo más objetivo posible.
                        </h1>
                        <img src="img/usapstudent.webp" alt="usapEstudiante" class="mx-auto mt-4 shadow-md">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection