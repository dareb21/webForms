@extends('student.studentLayout')
@section('content')
    <div class="flex-1 ml-0 md:ml-80 p-6 bg-gray-200 min-h-[calc(100vh-4rem)] overflow-auto flex justify-center items-center">
        <div class="bg-white rounded-xl shadow-lg p-6 h-full w-full text-center flex flex-col justify-center items-center">
            <img src="{{ asset('img/bluecheck.png') }}" alt="" class="size-40">
            <h1 class="font-bold text-4xl mb-4">
                Evaluación finalizada
            </h1>
            <h1 class="font-bold text-4xl">
                Muchas gracias por su tiempo.
            </h1>
        </div>
    </div>
@endsection
