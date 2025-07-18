// resources/js/sidebar.js

export function initSidebar() {
  const sidebar     = document.getElementById('sidebar');
  const toggleBtn   = document.getElementById('sidebarToggle');
  const profileBtn  = document.getElementById('profileBtn');
  const dropdown    = document.getElementById('dropdownMenu');
  const buttons = Array.from(document.querySelectorAll('#sidebar button'))
  .filter(btn => !btn.classList.contains('sidebar-exempt'));

  const STORAGE_KEY = 'selectedSidebarIndex';

  // Limpiar el localStorage cuando se carga la página 'studentDashboard'
  if (window.location.pathname.includes('studentDashboard')) {
    localStorage.removeItem(STORAGE_KEY);
  }

  // — Sidebar toggle para móvil
  toggleBtn?.addEventListener('click', () => {
    sidebar.classList.toggle('-translate-x-full');
  });
  document.addEventListener('click', e => {
    if (window.innerWidth < 768 &&
        !sidebar.contains(e.target) &&
        !toggleBtn.contains(e.target)) {
      sidebar.classList.add('-translate-x-full');
    }
  });

  // — Dropdown de perfil
  profileBtn?.addEventListener('click', e => {
    e.stopPropagation();
    dropdown.classList.toggle('hidden');
  });
  document.addEventListener('click', e => {
    if (!profileBtn.contains(e.target) &&
        !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });

  // — Restaurar selección de sidebar desde localStorage
  const saved = localStorage.getItem(STORAGE_KEY);

  // Si no hay valor guardado en localStorage, desmarcamos todos los botones
  if (saved === null) {
    buttons.forEach(unmark); // Desmarcamos todos los botones
  } else {
    // Si hay un valor guardado, marcamos el botón correspondiente
    const savedButton = buttons[saved];
    if (savedButton) {
      mark(savedButton);
    }
  }

  // — Click en cada botón de curso
  buttons.forEach((btn, idx) => {
    btn.addEventListener('click', () => {
      buttons.forEach(unmark); // Desmarcar todos los botones
      mark(btn); // Marcar el botón actual
      localStorage.setItem(STORAGE_KEY, idx); // Guardar la selección en localStorage
    });
  });

  // Función para marcar el botón
  function mark(el) {
    el.classList.add('bg-blue-500', 'text-white', 'md:text-white');
    el.classList.remove('text-blue-500', 'md:text-black');
    el.querySelector('svg')
      .classList.replace('text-blue-500', 'text-white');
  }

  // Función para desmarcar el botón
  function unmark(el) {
    el.classList.remove('bg-blue-500', 'text-white', 'md:text-white');
    el.classList.add('text-blue-500', 'md:text-black');
    el.querySelector('svg')
      .classList.replace('text-white', 'text-blue-500');
  }

  
}
