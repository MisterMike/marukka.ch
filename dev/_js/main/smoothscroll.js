$(() => {
    $('#button__scroll--top').click(function() {
        var smooth = new SmoothAnimate(document.getElementsByTagName('body'), {
            scrollTop: 0
        }, {
            duration: 1000,
            easing: 'easeInOutQuint'
        });
    });

    $('.scroll').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            let target = $(this.hash);
            target = target.length ? target : $(`[name=${this.hash.slice(1)}]`);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });
});