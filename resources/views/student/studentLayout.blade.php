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
    <link rel="preload" as="image" href="{{ asset('img/usapico.png') }}">
    <link rel="preload" as="image" href="{{ asset('img/usapblue.png') }}">
    <link rel="preload" as="image" href="{{ asset('img/pfp.jpg') }}">
</head>
<body class="bg-gray-100">
    <!-- Variables Sesiones -->
    @php
        $userInfo = session('userInfo');
        $courses = $userInfo['courses'];
        $nombre = $userInfo['nameUser'];
        $correo = $userInfo['email'];
        $coursesId = $userInfo['coursesId'];
    @endphp
    <!-- Navbar -->
    <div class="fixed top-0 left-0 right-0 z-20 h-14 bg-white shadow-md border-b-2 border-gray-100 flex items-center justify-between px-4">
        <button id="sidebarToggle" class="md:hidden mr-3 focus:outline-none">
            <img src="{{ asset('img/sidebar.png') }}" alt="Menu" class="h-6 w-6 hover:cursor-pointer">
        </button>

        <a href="{{ route('studentDashboard') }}">
            <img src="{{ asset('img/usapblue.png') }}" alt="logo" class="h-10 w-auto hover:cursor-pointer">
        </a>

        <!-- PFP Dropdown -->
        <div class="relative">
            <img id="profileBtn" src="{{ asset('img/pfp.jpg') }}" alt="profileImg" class="h-10 w-10 rounded-full hover:cursor-pointer border-2 border-gray-300">

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-54 bg-white border border-gray-200 rounded-xl shadow-lg z-30">
                <a class="block px-4 py-2 text-gray-700" >{{ $nombre }}</a>
                <a class="block px-4 py-2 text-gray-700" >{{ $correo }}</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-orange-500 hover:text-white">Cerrar sesión</a>
            </div>
        </div>
    </div>

    <!-- Sidebar + Contenido -->
    <div class="pt-14 flex">
        <!-- Sidebar -->
        <div id="sidebar" class="
            fixed top-14 left-0 h-[calc(100vh-4rem)] w-80 py-10 bg-white shadow- p-4 z-30
            transform -translate-x-full transition-transform duration-300 ease-in-out
            md:translate-x-0
            ">
            <ul class="font-bold space-y-4 ">
                @foreach ($courses as $course)
                    <li>
                        <form action="{{ route('studentEvaluation') }}">
                            @csrf
                            <input type="hidden" name="noClaseId" value="{{ $loop->index }}">
                            <input type="hidden" name="courseId" value="{{ $coursesId }}">
                            <button type="submit"
                                class="group flex items-center w-full p-2 md:p-4 px-6 gap-2 hover:cursor-pointer hover:rounded-lg hover:bg-blue-500 hover:text-white border-t-1 border-b-1 md:border-t-0 md:border-b-0 border-orange-500 text-blue-500 md:text-black">
                                {{ $course }}
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        
       @yield('content')

        <!-- Script para esconder el sidebar -->
        <script>
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });

            // Opcional: cerrar sidebar si haces clic fuera, solo en móvil
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 768) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    if (!sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    }
                }
                }
            });

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
    </div>
</body>
</html>
