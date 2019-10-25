

document.addEventListener("DOMContentLoaded",function(){

    // basic items
    inView('.inview__item').on("enter", function(t) {
        inView.offset(50);
        return t.classList.add("inview")
    })

});

