<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso no autorizado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('img/usapico.png') }}" type="image/x-icon">
</head>

<body class="bg-gray-200 w-screen h-screen">
    <div class="bg-white rounded-xl shadow-lg p-8 text-center w-full h-full flex flex-col justify-center items-center">
        <img src="{{ asset('img/unauthorized.png') }}" alt="No autorizado" class="size-50 mb-6">
        <h1 class="text-4xl font-bold mb-4">Acceso no autorizado.</h1>
        <h2 class="text-2xl font-bold">Consulte al administrador del sistema.</h2>
    </div>
</body>

</html>
