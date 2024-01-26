const SELECTORS = {
    zoomContainer: '.js-hero',
    scaleBasis: 1e-4,
}

var zoomContainer = '.js-hero';
var zoomElement = document.querySelectorAll(zoomContainer) ?? '';
var scaleBasis = 1e-4;

window.addEventListener("scroll", function() {
    if (zoomElement.length > 0) {
        heroWrapper = document.querySelector(zoomContainer),
        heroImage = heroWrapper.querySelector(".hero-image");
        
        var t = null;
        
        window.requestAnimationFrame(function e(n) {
            t || (t = n);
            var i = n - t,
                scrollOffset = window.scrollY || window.pageYOffset || document.documentElement.scrollTop,
                scaleFactor = 1 + scaleBasis * scrollOffset;
                
            scrollOffset < heroWrapper.offsetTop + 1.5 * heroWrapper.clientHeight && (heroImage.style.transform = "scale(" + scaleFactor + ")"), 
            i < (scaleFactor*10) && window.requestAnimationFrame(e)
        })
    } else {
        console.log('no js-hero found');
    }
});