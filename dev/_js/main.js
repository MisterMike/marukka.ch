import Navigation from './main/navigation';

import detectIE from './main/detectIE';

import SmoothScroll from './main/smoothscroll';

import FormLabel from './main/formlabel';

import Accordeon from './main/accordeon';

import AccordeonBlocks from './main/accordeonBlocks';


import PhotoSwipe from './main/photoswipe';

import lazySizes from 'lazysizes';

import Rellax from 'rellax';

const Carousel = require("vanilla-js-carousel");


document.addEventListener("DOMContentLoaded", function () {

    document.getElementsByTagName("html")[0].className += " inited";

    var rellax = new Rellax('.rellax', {
        speed: .5,
        center: false,
        wrapper: null,
        round: true,
        vertical: true,
        horizontal: false
    });

    var carousel = new Carousel({
        elem: 'miniCarousel',    // id of the carousel container
        autoplay: true,     // starts the rotation automatically
        infinite: true,      // enables the infinite mode
        interval: 2500,      // interval between slide changes
        initial: 0,          // slide to start with
        dots: false,          // show navigation dots
        arrows: false,        // show navigation arrows
        buttons: false,      // hide play/stop buttons,
        btnStopText: 'Pause' // STOP button text
    });
});