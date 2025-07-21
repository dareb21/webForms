import './bootstrap';

import { initSidebar } from './sidebar';
import { modalChartComponent } from './graficopopup';


window.modalChartComponent = modalChartComponent;

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
});
