import Navigation from './main/navigation';
import showContent from './main/showcontent';

//import detectIE from './main/detectIE';
//import SmoothScroll from './main/smoothscroll';
//import FormLabel from './main/formlabel';

import Accordeon from './main/accordeon';
import AccordeonBlocks from './main/accordeonBlocks';
import PhotoSwipe from './main/photoswipe';
import lazySizes from 'lazysizes';


document.addEventListener("DOMContentLoaded", function () {

    document.getElementsByTagName("html")[0].className += " inited";
    document.getElementById("main__logo--header").classList.add("show");

    new showContent('showMe', 'show', '-150px 0px');

});