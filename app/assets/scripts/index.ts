import 'alpinejs';
import 'nette-forms';
import 'svgxuse';

import chosen from './chosen';
import ga from './ga';

document.addEventListener('DOMContentLoaded', () => {
  chosen.init();
  ga.init();
});
