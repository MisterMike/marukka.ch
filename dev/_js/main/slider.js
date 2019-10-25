// $(".owl-carousel").not('.owl-initialized').owlCarousel({
//     infinite: true,
//     slidesToShow: 1,
//     slidesToScroll: 1,
//     lazyLoad: 'ondemand',
//     dots: true,
//     arrows: true,
// });

document.addEventListener("DOMContentLoaded",function(){

    $(".owl-carousel").owlCarousel({
        items: 1,
        dots: !0,
        nav: !0,
        loop: !0,
        autoplay: !0,
        smartSpeed: 1e3,
        autoplayHoverPause: !0,
        autoplayTimeout: 1e4,
        loadingClass: "owl-loading",
        navText: ['<svg class=\'owl-nav__icon\' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.1 23"><path class="st0" d="M0 11.5c3.8 3.8 7.6 7.6 11.5 11.5l1.4-1.4 -10-10L13.1 1.4c-0.5-0.5-0.9-0.9-1.4-1.4C7.8 3.8 3.9 7.7 0 11.5z"/></svg>\n', '<svg class=\'owl-nav__icon\' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.1 23"><path class="st0" d="M13.1 11.5C9.3 7.7 5.5 3.9 1.6 0L0.2 1.4l10 10L0 21.6c0.5 0.5 0.9 0.9 1.4 1.4C5.3 19.2 9.2 15.3 13.1 11.5z"/></svg>']
    });

    //setTimeout(function(){ $("body").addClass("inited");}, 1000);
});


