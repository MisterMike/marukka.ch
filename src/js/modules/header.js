export default function() {
    // Navigation

    var header = document.getElementById("siteHeader");
    var lastScrollTop = 0;

    document.getElementById("menu-open").addEventListener("click", function(e) {
        document.body.classList.toggle("menu-open");
        e.preventDefault();
    });

    // document.getElementById("menu-close").addEventListener("click", function(e) {
    //     e.preventDefault();
    //     document.body.classList.remove("menu-open");
    // });

    // Navigation search
    var search = document.getElementById("search");
    var searchOpen = document.getElementById("search-trigger");

    if (search && searchOpen) {
        searchOpen.addEventListener("click", function(e) {
            if (search.parentNode.classList.contains("search--show")) {
                search.parentNode.classList.remove("search--show");
                e.srcElement.classList.remove("search-close");
            } else {
                search.parentNode.classList.add("search--show");
                e.srcElement.classList.add("search-close");
            }
            return false;
        });
    }

    //Hide nav
    window.addEventListener("scroll", function() {
        const st = window.scrollY || window.pageYOffset;
        const treshold = 10;

        if (st > lastScrollTop + treshold && st > 0) {
            // downscroll code;
            header.classList.add("hidden");
            lastScrollTop = st;
        } else if (st < lastScrollTop - treshold) {
            // upscroll code
            header.classList.remove("hidden");
            lastScrollTop = st;
        }
    });
}
