import '../css/app.css';
import Alpine from 'alpinejs';
import navigation from './components/navigation.js';
import codeCopy from './components/codeCopy.js';
import { highlightCode } from './components/highlightCode.js';

Alpine.data('navigation', navigation);
Alpine.data('codeCopy', codeCopy);
Alpine.start();
document.documentElement.classList.add('alpine-ready');
if (document.querySelector('pre code')) highlightCode();
