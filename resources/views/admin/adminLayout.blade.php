<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Evaluación Docente</title>

  @vite('resources/css/app.css')
  @vite('resources/js/app.js')

  <link rel="shortcut icon" href="{{ asset('img/usapico.png') }}" type="image/x-icon" />

  <link rel="preload" as="image" href="{{ asset('img/usapblue.png') }}">
  <link rel="preload" as="image" href="{{ asset('img/pfp.jpg') }}">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100">

<!-- Botón para mostrar sidebar en móviles -->
<div class="md:hidden fixed top-0 left-0 z-50">
  <button id="sidebarToggle" class="p-2 bg-white rounded shadow">
    <!-- Ícono menú -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>
</div>

<!-- Sidebar + Contenido -->
<div class="flex h-screen relative">
  <!-- Sidebar -->
  <div id="sidebar" class="fixed top-0 left-0 h-full w-60 p-3 space-y-2 bg-white z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="flex items-center p-2 space-x-4">
      <img src="{{ asset('img/pfp.jpg') }}" alt="" class="w-12 h-12 rounded-full bg-gray-300" />
      <div>
        <h2 class="text-lg font-semibold">Admin USAP</h2>
        <span class="text-sm font-light">admin@usap.edu</span>
      </div>
    </div>

    <div class="divide-y divide-gray-300">
      <ul class="pt-2 pb-4 space-y-1 text-sm">
        
        <li class="hover:bg-blue-700 hover:text-white">
          <a href="{{ route('adminDashboard') }}" class="group flex items-center p-2 space-x-3 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              class="w-5 h-5 fill-current text-gray-700 transition duration-300 group-hover:invert">
              <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="hover:bg-blue-700 hover:text-white">
          <a href="{{ route('adminEvaluation') }}" class="group flex items-center p-2 space-x-3 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              class="w-5 h-5 fill-current text-gray-700 transition duration-300 group-hover:invert">
              <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H3V4zm0 4h18v12a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm5 2v2h2v-2H8zm0 4v2h2v-2H8zm4-4v2h6v-2h-6zm0 4v2h6v-2h-6z"/>
            </svg>
            <span>Evaluaciones</span>
          </a>
        </li>

        <li class="hover:bg-blue-700 hover:text-white">
          <a href="{{ route('adminResults') }}" class="group flex items-center p-2 space-x-3 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              class="w-5 h-5 fill-current text-gray-700 transition duration-300 group-hover:invert">
              <path d="M5 9h2v10H5V9zm6 4h2v6h-2v-6zm6-8h2v14h-2V5z"/>
            </svg>
            <span>Resultados</span>
          </a>
        </li>

      </ul>

      <ul class="pt-4 pb-2 space-y-1 text-sm">
        <li class="hover:bg-orange-600 hover:text-white">
          <a href="{{route('logOut')}}" class="group flex items-center p-2 space-x-3 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
              class="w-5 h-5 fill-current text-gray-600 transition duration-300 group-hover:invert">
              <path d="M440,424V88H352V13.005L88,58.522V424H16v32h86.9L352,490.358V120h56V456h88V424ZM320,453.642,120,426.056V85.478L320,51Z"/>
              <rect width="32" height="64" x="256" y="232"/>
            </svg>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
      <img src="{{ asset('img/usapblue.png') }}" alt="Logo" class="h-14">
    </div>
  </div>

  <!-- Contenido -->
  <main class="flex-1 p-2 h-full bg-gray-200 overflow-auto min-h-screen">
    @yield('content')
  </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
