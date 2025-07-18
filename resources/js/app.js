import './bootstrap';

// resources/js/app.js
import { initSidebar } from './sidebar';

// Un único listener para arrancar TODO
document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  // aquí podrías llamar a initOtroMódulo(), initChart(), etc.
});
