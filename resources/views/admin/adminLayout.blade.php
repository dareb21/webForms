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

  <!-- Preload imágenes necesarias -->
  <link rel="preload" as="image" href="{{ asset('img/usapblue.png') }}">
  <link rel="preload" as="image" href="{{ asset('img/pfp.jpg') }}">
</head>
<body class="bg-gray-100">

  <!-- Navbar -->
  <div class="fixed top-0 left-0 right-0 z-20 h-14 bg-white shadow-md border-b-2 border-gray-100 flex items-center justify-between px-4">
    <button id="sidebarToggle" class="md:hidden mr-3 focus:outline-none">
      <!-- SVG ícono menú -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Logo USAP -->
    <a href="{{ route('adminDashboard') }}">
      <img src="{{ asset('img/usapblue.png') }}" alt="logo" class="h-10 w-auto hover:cursor-pointer" loading="eager">
    </a>

    <!-- PFP -->
    <div class="relative">
      <img id="profileBtn" src="{{ asset('img/pfp.jpg') }}" alt="profileImg" class="h-10 w-10 rounded-full hover:cursor-pointer border-2 border-gray-300" loading="eager">

      <!-- Dropdown -->
      <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-30">
        <a href="#" class="block px-4 py-2 text-gray-700">Nombre</a>
        <a href="#" class="block px-4 py-2 text-gray-700">Correo</a>
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
      md:translate-x-0">

      <ul class="font-bold space-y-4">
        <li>
          <a href="{{ route('adminDashboard') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
            <!-- SVG Casa -->
            <svg class="w-5 h-5 text-gray-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2h-4a2 2 0 01-2-2V13H9v7a2 2 0 01-2 2H3a2 2 0 01-2-2V9z" />
            </svg>
            Inicio
          </a>
        </li>
        <li>
          <a href="{{ route('adminEvaluation') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
            <!-- SVG Encuesta -->
            <svg class="w-5 h-5 text-gray-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 11H7v2h2v-2zm0-4H7v2h2V7zm0 8H7v2h2v-2zm4-8h6v2h-6V7zm0 4h6v2h-6v-2zm0 4h6v2h-6v-2z" />
            </svg>
            Evaluaciones
          </a>
        </li>
        <li>
          <a href="{{ route('adminResults') }}" class="group flex items-center p-4 px-6 gap-2 hover:rounded-lg hover:bg-blue-500 hover:text-white">
            <!-- SVG Gráfico -->
            <svg class="w-5 h-5 text-gray-500 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4 22h16V2zM6 6h2v10H6zm5 4h2v6h-2zm5 2h2v4h-2z" />
            </svg>
            Resultados
          </a>
        </li>
      </ul>
    </div>

    @yield('content')
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="//unpkg.com/alpinejs" defer></script>

  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
    });

    // Cerrar sidebar al hacer clic fuera (en móvil)
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

      document.addEventListener("click", function (event) {
        if (!profileBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
          dropdownMenu.classList.add("hidden");
        }
      });
    });
  </script>
</body>
</html>
