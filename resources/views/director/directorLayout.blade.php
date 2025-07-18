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
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
        <h2 class="text-lg font-semibold">Director USAP</h2>
        <span class="text-sm font-light">director@usap.edu</span>
      </div>
    </div>

    <div class="divide-y divide-gray-300">
      <ul class="pt-2 pb-4 space-y-1 text-sm">
        <li class="hover:bg-blue-700 hover:text-white">
          <a href="{{ route('directorDashboard') }}" class="group flex items-center p-2 space-x-3 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
              class="w-5 h-5 fill-current text-gray-600 transition duration-300 group-hover:invert">
              <path d="M68.983,382.642l171.35,98.928a32.082,32.082,0,0,0,32,0l171.352-98.929a32.093,32.093,0,0,0,16-27.713V157.071a32.092,32.092,0,0,0-16-27.713L272.334,30.429a32.086,32.086,0,0,0-32,0L68.983,129.358a32.09,32.09,0,0,0-16,27.713V354.929A32.09,32.09,0,0,0,68.983,382.642ZM272.333,67.38l155.351,89.691V334.449L272.333,246.642ZM256.282,274.327l157.155,88.828-157.1,90.7L99.179,363.125ZM84.983,157.071,240.333,67.38v179.2L84.983,334.39Z"/>
            </svg>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="hover:bg-blue-700 hover:text-white">
          <a href="{{ route('directorResults') }}" class="group flex items-center p-2 space-x-3 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
              class="w-5 h-5 fill-current text-gray-600 transition duration-300 group-hover:invert">
              <path d="M479.6,399.716l-81.084-81.084-62.368-25.767A175.014,175.014,0,0,0,368,192c0-97.047-78.953-176-176-176S16,94.953,16,192,94.953,368,192,368a175.034,175.034,0,0,0,101.619-32.377l25.7,62.2L400.4,478.911a56,56,0,1,0,79.2-79.195ZM48,192c0-79.4,64.6-144,144-144s144,64.6,144,144S271.4,336,192,336,48,271.4,48,192ZM456.971,456.284a24.028,24.028,0,0,1-33.942,0l-76.572-76.572-23.894-57.835L380.4,345.771l76.573,76.572A24.028,24.028,0,0,1,456.971,456.284Z"/>
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

<script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
