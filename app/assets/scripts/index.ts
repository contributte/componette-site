import Alpine from 'alpinejs';
import 'nette-forms';
import 'svgxuse';

import ga from './ga';

document.addEventListener('DOMContentLoaded', () => {
  Alpine.start();
  ga.init();
});
