const showContent = (function(selectorClass, showClass, animMargin) {
    
    var divElem = document.querySelectorAll(selectorClass),
        divElemCount = divElem.length,
        myObserver = void 0;

        console.log(divElem.length);
    
    if ("IntersectionObserver" in window) {

        myObserver = new IntersectionObserver(function(selectorClass) {
            0 === divElemCount && myObserver.paragraphDisconnect();
            for (var looper = 0; looper < selectorClass.length; looper++) {
                var currentElem = selectorClass[looper];
                currentElem.intersectionRatio > 0 && (divElemCount--, myObserver.unobserve(currentElem.target), makeItSo(currentElem.target, showClass))
            }
        }, {
            rootMargin: animMargin,
            threshold: .01
        });

        for (var cntr1 = 0; cntr1 < divElem.length; cntr1++) {
            var loopElem = divElem[cntr1];
            loopElem.classList.contains(showClass) || myObserver.observe(loopElem)
        }

    } else
        !function(selectorClass, showClass) {
            for (var cntr2 = 0; cntr2 < selectorClass.length; cntr2++) {
                var currentIeElem = selectorClass[cntr2];
                currentIeElem.classList.add(showClass)
            }
        }(divElem);

    function makeItSo(targetDiv, showClass) {
        targetDiv.classList.add(showClass)
        console.log('makeItSo:'+showClass);
    }
});

module.exports = showContent;
