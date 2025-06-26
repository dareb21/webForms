// resources/js/sidebar.js

export function initSidebar() {
  const sidebar     = document.getElementById('sidebar');
  const toggleBtn   = document.getElementById('sidebarToggle');
  const profileBtn  = document.getElementById('profileBtn');
  const dropdown    = document.getElementById('dropdownMenu');
  const buttons     = Array.from(document.querySelectorAll('#sidebar button'));
  const STORAGE_KEY = 'selectedSidebarIndex';

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
  if (saved !== null && buttons[saved]) {
    mark(buttons[saved]);
  }

  // — Click en cada botón de curso
  buttons.forEach((btn, idx) => {
    btn.addEventListener('click', () => {
      buttons.forEach(unmark);
      mark(btn);
      localStorage.setItem(STORAGE_KEY, idx);
    });
  });

  function mark(el) {
    el.classList.add('bg-blue-500', 'text-white', 'md:text-white');
    el.classList.remove('text-blue-500', 'md:text-black');
    el.querySelector('svg')
      .classList.replace('text-blue-500', 'text-white');
  }
  function unmark(el) {
    el.classList.remove('bg-blue-500', 'text-white', 'md:text-white');
    el.classList.add('text-blue-500', 'md:text-black');
    el.querySelector('svg')
      .classList.replace('text-white', 'text-blue-500');
  }
}
