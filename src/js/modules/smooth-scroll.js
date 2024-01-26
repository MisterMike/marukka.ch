import SmoothScroll from "smooth-scroll";

export default function() {
    // scroll links
    new SmoothScroll('a[href*="#"]', {
        speed: 500,
        speedAsDuration: true,
        topOnEmptyHash: false,
        updateURL: false,
        offset: function(anchor, toggle) {
            if(anchor.id == "aanbod") {
                return 60;
            } else {
                return 0;
            }
        }
    });

    // scroll after form submit
    var href = window.location.href;
    var splitit = href.split("#")[1]; //split url to get twee name
    if (splitit !== "" || splitit !== "undefined") {
        var el = document.getElementById(splitit);
        if (el) {
            var scroll = new SmoothScroll();
            scroll.animateScroll(el);
        }
    }
}
