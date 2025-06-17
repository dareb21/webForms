<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluacion Docente</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="shortcut icon" href="{{ asset('img/usapico.png') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <div class="fixed top-0 left-0 right-0 z-20 h-14 bg-white shadow-md border-b-2 border-gray-100 flex items-center justify-between px-4">
        <button id="sidebarToggle" class="md:hidden mr-3 focus:outline-none">
            <img src="img/sidebar.png" alt="Menu" class="h-6 w-6 hover:cursor-pointer">
        </button>

        <a href="{{ route('deanDashboard') }}">
            <img src="img/usapblue.png" alt="logo" class="h-10 w-auto hover:cursor-pointer">
        </a>

        <!-- PFP Dropdown -->
        <div class="relative">
            <img id="profileBtn" src="img/pfp.jpg" alt="profileImg" class="h-10 w-10 rounded-full hover:cursor-pointer border-2 border-gray-300">

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-30">
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-orange-500 hover:text-white">Nombre</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-orange-500 hover:text-white">Correo</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-orange-500 hover:text-white">Cerrar sesión</a>
            </div>
        </div>
    </div>

    <!-- Sidebar + Contenido -->
    <div class="pt-14 flex">
        <!-- Sidebar -->
        <div id="sidebar" class="
            fixed top-14 left-0 h-[calc(100vh-4rem)] w-80 py-8 bg-white shadow- p-4 z-30
            transform -translate-x-full transition-transform duration-300 ease-in-out
            md:translate-x-0
            ">

            <ul class="font-bold space-y-4">
                <li>
                    <a href="{{ route('deanDashboard') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
                        <img src="img/home.png" alt="" class="w-5 h-5 group-hover:invert group-hover:brightness-0 group-hover:contrast-200">Inicio
                    </a>
                </li>
                <li>
                    <a href="{{ route('deanResults') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
                        <img src="img/results.png" alt="" class="w-5 h-5 group-hover:invert group-hover:brightness-0 group-hover:contrast-200">Resultados
                    </a>
                </li>
            </ul>
        </div>
        <script>
                document.addEventListener("DOMContentLoaded", function () {
                const profileBtn = document.getElementById("profileBtn");
                const dropdownMenu = document.getElementById("dropdownMenu");

                profileBtn.addEventListener("click", function () {
                    dropdownMenu.classList.toggle("hidden");
                });

                // Cerrar dropdown si se hace clic fuera
                document.addEventListener("click", function (event) {
                    if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.add("hidden");
                    }
                });
            });
        </script>
        @yield('content')
</body>
</html>
