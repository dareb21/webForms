<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Docente</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('img/usapico.png') }}" type="image/x-icon">

    <!-- Preload imagenes -->
    <link rel="preload" as="image" href="{{ asset('img/usapblue.png') }}">
    <link rel="preload" as="image" href="{{ asset('img/pfp.jpg') }}">
    <link rel="preload" as="image" href="{{ asset('img/sidebar.png') }}">
</head>
<body class="bg-gray-100 antialiased">
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
            <img src="{{ asset('img/sidebar.png') }}" alt="Menú" class="h-6 w-6 cursor-pointer" loading="lazy" decoding="async">
        </button>

        <a href="{{ route('studentDashboard') }}">
            <img src="{{ asset('img/usapblue.png') }}" alt="Logo USAP" class="h-10 w-auto cursor-pointer" loading="lazy" decoding="async">
        </a>

        <!-- Perfil Dropdown -->
        <div class="relative">
            <img id="profileBtn" src="{{ asset('img/pfp.jpg') }}" alt="Perfil" class="h-10 w-10 rounded-full cursor-pointer border-2 border-gray-300" loading="lazy" decoding="async">
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-54 bg-white border border-gray-200 rounded-xl shadow-lg z-30">
                <span class="block px-4 py-2 text-gray-700">{{ $nombre }}</span>
                <span class="block px-4 py-2 text-gray-700">{{ $correo }}</span>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-orange-500 hover:text-white">Cerrar sesión</a>
            </div>
        </div>
    </div>

    <!-- Sidebar + Contenido -->
    <div class="pt-14 flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed top-14 left-0 h-[calc(100vh-4rem)] w-80 py-10 bg-white shadow p-4 z-30 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0">
            <ul class="font-bold space-y-4">
                @foreach ($courses as $course)
                    <li>
                        <form action="{{ route('studentEvaluation') }}" method="GET">
                            <input type="hidden" name="noClaseId" value="{{ $loop->index }}">
                            <input type="hidden" name="courseId" value="{{ $coursesId[$loop->index] }}">
                            <button type="submit" class="group flex items-center w-full p-2 md:p-4 px-6 gap-2 cursor-pointer rounded-lg hover:bg-blue-500 hover:text-white border-t border-b md:border-0 border-orange-500 text-blue-500 md:text-black transition">
                                {{ $course }}
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- Contenido dinámico -->
        @yield('content')
    </div>

    <!-- Script optimizado -->
    <script defer>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const profileBtn = document.getElementById("profileBtn");
            const dropdownMenu = document.getElementById("dropdownMenu");

            // Toggle sidebar
            toggleBtn?.addEventListener('click', () => {
                sidebar?.classList.toggle('-translate-x-full');
            });

            // Cerrar sidebar en móvil si se hace clic fuera
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 768) {
                    if (!sidebar?.contains(e.target) && !toggleBtn?.contains(e.target)) {
                        if (!sidebar?.classList.contains('-translate-x-full')) {
                            sidebar?.classList.add('-translate-x-full');
                        }
                    }
                }
            });

            // Dropdown del perfil
            profileBtn?.addEventListener("click", (event) => {
                event.stopPropagation();
                dropdownMenu?.classList.toggle("hidden");
            });

            // Cerrar dropdown si se hace clic fuera
            document.addEventListener("click", function (event) {
                if (!profileBtn?.contains(event.target) && !dropdownMenu?.contains(event.target)) {
                    dropdownMenu?.classList.add("hidden");
                }
            });
        });
    </script>
</body>
</html>
