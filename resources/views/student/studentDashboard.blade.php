@extends('student.studentLayout')

@section('content')
    <main class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto">
        <section class="bg-white rounded-xl shadow-lg p-6 w-full h-full flex flex-col">
            <header class="bg-white p-4 text-center">
                <h1 class="text-4xl font-bold">Bienvenido a Evaluación Docencia</h1>
            </header>

            <!-- Instrucciones -->
            <section class="flex flex-wrap gap-8 mt-6 justify-center w-full h-auto text-center max-w-4xl mx-auto">
                <article>
                    <h2 class="font-bold text-lg mb-2">INSTRUCCIONES</h2>
                    <p class="p-2 leading-relaxed text-gray-700">
                        Estimado estudiante, queremos conocer aspectos pedagógicos y metodológicos del desarrollo de la
                        clase,
                        para ello se le presentarán parejas de enunciados en donde usted deberá seleccionar uno,
                        considerando el que es más afín a lo que sucede en clase. Recuerde solo debe seleccionar una
                        alternativa y no deje de responder ninguna pregunta, debe seleccionar la opción deseada.
                        Trate de ser lo más objetivo posible.
                    </p>
                    <img src="{{ asset('img/usapstudent.webp') }}" alt="Estudiante USAP"
                        class="mx-auto mt-4 shadow-md rounded-md" loading="eager" decoding="async" width="400"
                        height="auto">
                </article>
            </section>
        </section>
    </main>
@endsection
