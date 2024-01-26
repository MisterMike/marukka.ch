/*
 * Open image in gLightbox (https://github.com/mcstudios/glightbox)
 *
 * Usage:
 * <a hre="picture.jpg" class="js-image">Open picture</a>
 */

import GLightbox from 'glightbox';
require('../../../node_modules/glightbox/dist/css/glightbox.css');

document.addEventListener("DOMContentLoaded", function () {

    const lightbox = GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });

});