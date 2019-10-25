
import sal from 'sal.js';

import SmoothAnimate from './plugins/smoothanimate';

import Plyr from 'plyr';

import Glide from '@glidejs/glide';


sal({
    threshold: .4
});

if (document.querySelector('.main__content').classList.contains('glide')) {
    new Glide('.glide').mount();
}