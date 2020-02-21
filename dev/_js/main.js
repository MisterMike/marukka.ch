import Navigation from './main/navigation';

import detectIE from './main/detectIE';

// import SmoothScroll from './main/smoothscroll';

//import FormLabel from './main/formlabel';

import Accordeon from './main/accordeon';
import AccordeonBlocks from './main/accordeonBlocks';
import PhotoSwipe from './main/photoswipe';
import lazySizes from 'lazysizes';
import AOS from 'aos';

import Inview from './main/inview';

document.addEventListener("DOMContentLoaded", function () {

    document.getElementsByTagName("html")[0].className += " inited";

    AOS.init({
        duration: 800,
    });

    document.getElementById("main__logo--header").classList.add("show")

});