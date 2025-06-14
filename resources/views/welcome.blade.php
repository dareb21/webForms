<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEBFORMS</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="{{ asset('img/usapico.png') }}" type="image/x-icon">
</head>
<body>
    <div class="flex h-screen">
        <!-- Imagen izq -->
        <div class="relative h-screen w-full md:w-xl p-1 bg-[url('/public/img/backgroundusap.png')] bg-left bg-cover bg-no-repeat">
            <!-- Logo centrado -->
            <div class="absolute top-30 left-1/2 transform -translate-x-1/2 z-50">
                <img src="img/usapwhite.png" alt="Logo USAP" class="w-48">
            </div>
            <!-- Boton -->
            <div class="absolute bottom-50 left-1/2 transform -translate-x-1/2 z-50">
                <p class="flex text-center justify-center gap-2 py-2 text-white font-bold text-2xl">
                    Ingrese con su correo institucional:
                </p>
                <button class="flex items-center justify-center gap-2 border-2 px-30 py-2 rounded-2xl shadow-xl border-amber-600 bg-amber-600 text-white font-bold hover:cursor-pointer">
                    <img src="/img/googlefavicon.ico" alt="Google" class="w-5 h-5">
                    Google
                </button>
                
            </div>
            <!-- Texto inferior centrado -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-50 w-full text-center">
                <p class="text-white font-bold text-xl sm:text-2xl">
                    Evaluación docente
                </p>
            </div>

        </div>
        
        <!-- Imagen der -->
        <div class="h-screen md:w-full bg-[url('/public/img/students.png')] bg-right bg-cover bg-no-repeat">

        </div>
    </div>
    
</body>
</html>