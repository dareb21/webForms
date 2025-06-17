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
    <style>[x-cloak] { display: none !important; }</style>

</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <div class="fixed top-0 left-0 right-0 z-20 h-14 bg-white border-b-2 border-gray-100 shadow-md flex items-center justify-between px-4">
        <button id="sidebarToggle" class="md:hidden mr-3 focus:outline-none">
            <img src="img/sidebar.png" alt="Menu" class="h-6 w-6 hover:cursor-pointer">
        </button>
        <a href="#">
            <img src="img/usapblue.png" alt="logo" class="h-10 w-auto hover:cursor-pointer">
        </a>
        <img src="img/pfp.jpg" alt="profileImg" class="h-10 w-auto hover:cursor-pointer">
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
                    <a href="{{ route('adminDashboard') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
                        <img src="img/home.png" alt="" class="w-5 h-5 group-hover:invert group-hover:brightness-0 group-hover:contrast-200">Inicio
                    </a>
                </li>
                <li>
                    <a href="{{  route('adminEvaluation') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
                        <img src="img/survey.png" alt="" class="w-5 h-5 group-hover:invert group-hover:brightness-0 group-hover:contrast-200">Evaluaciones
                    </a>
                </li>
                <li>
                    <a href="{{ route('adminResults') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
                        <img src="img/results.png" alt="" class="w-5 h-5 group-hover:invert group-hover:brightness-0 group-hover:contrast-200">Resultados
                    </a>
                </li>
            </ul>
        </div>

        @yield('content')
        <!-- Chart.js CDN (único en toda la app, si ya lo tienes no lo repitas) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="//unpkg.com/alpinejs" defer></script>

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

        </script>
</body>
</html>