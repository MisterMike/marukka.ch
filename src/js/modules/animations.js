/**
 * viewport element animator
 * changes classes of elements depending on their position relative to the viewport..
 * use css to animate them !
 */

 const CLASSES = {
    animPlanned: 'animation-planned',
    animScroll: 'animation-scroll',
    animLoad: 'animation-load',
    animPrepared:'animation-planned-hide'
}

function init() {
    this.header = document.getElementById("siteHeader"),
    this.navigation = document.querySelector(".js-navigation"),
    this.view = document.querySelector("#mainView"),
    this.vState = H(),
    this.scrollPosition = window.scrollY || window.pageYOffset,
    this.initialHeaderHeight = this.header.clientHeight,
    //this.header.classList.add("js-ready"),
    window.requestAnimationFrame(showHideHeader),
    window.requestAnimationFrame(animateComponents),
    handleScroll();
}

function handleScroll()
{
    this.isScrolling = window.scrollY > this.lastScrollPosition,
    this.isScrolling && (this.userScrolled || initialHideComponents(), this.userScrolled = !0),
    window.scrollY !== this.lastScrollPosition && (this.lastScrollPosition = window.scrollY, animateComponents()),
        window.requestAnimationFrame(handleScroll)
}

function showHideHeader() {

    const t = this.vState(this.view);

    // get current scroll position (y)
    let currentScrollPos = window.scrollY || window.pageYOffset;


    // reset to zero if negative
    currentScrollPos < 0 && (currentScrollPos = 0),
    t.bottomProgress < .95 && (this.scrollPosition < currentScrollPos ? this.scrollPosition > this.initialHeaderHeight && this.header.classList.add("hideme") : this.scrollPosition > currentScrollPos && this.header.classList.remove("hideme"), this.scrollPosition > this.initialHeaderHeight ? this.navigation.classList.contains("scrolled") && this.header.classList.contains("hideme") ? this.timeout || (this.timeout = setTimeout(() => {
        this.header.classList.add("scrolled"),
        this.navigation && this.navigation.classList.add("scrolled"),
            this.timeout = null
    }, 400)) : (this.header.classList.add("scrolled"), this.navigation && this.navigation.classList.add("scrolled")) : this.scrollPosition < 10 && (this.header.classList.remove("scrolled"), this.navigation && this.navigation.classList.remove("scrolled"))),
        this.scrollPosition = currentScrollPos,
        window.requestAnimationFrame(showHideHeader)
}

// function showModalOnScroll() {
//
//     const t = H();
//
//     if(t.bottomProgress <)
//
//
//
// }

function animateComponents() {

    const t = H(),
          animElem = document.querySelectorAll('.'+CLASSES.animPlanned);


    for (const currentElem of animElem) {
        const e = t(currentElem).topProgress > 0;
        if (this.isScrolling)
            e && setTimeout(() => {
                currentElem.classList.add(CLASSES.animScroll),
                    currentElem.classList.remove(CLASSES.animPrepared),
                    currentElem.classList.remove(CLASSES.animPlanned),
                    currentElem.classList.remove(CLASSES.animLoad)
            }, 100 * getRandomTimeoutMultiplier(1, 10));
        else {
            const t = currentElem.classList.contains("animate-immediately") ? 0 : 100 * getRandomTimeoutMultiplier(4, 7);
            setTimeout(() => {
                currentElem.classList.add(CLASSES.animLoad),
                e && currentElem.classList.remove(CLASSES.animPlanned)
            }, t)
        }
    }
}

function initialHideComponents() {

    const t = H();
    const plannedElements = document.querySelectorAll('.'+CLASSES.animPlanned);

    for (const currentElement of plannedElements) {
        t(currentElement).topProgress > 0 || (currentElement.classList.add(CLASSES.animPrepared),currentElement.classList.remove(CLASSES.animLoad) )
    }
}

function getRandomTimeoutMultiplier(t, e) {
    return Math.floor(Math.random() * e + t)
}


function H(t=0, e=t) {
    return function(n, i=window) {
        const r = i === window || void 0 == i ? window.pageYOffset : i.scrollTop,
            o = i === window || void 0 == i ? window.innerHeight : i.offsetHeight,
            s = function(t, e=window) {
                let n = t.offsetTop;
                for (; null !== (t = t.offsetParent) && t !== e;)
                    n += t.offsetTop;
                return n
            }(n),
            a = 1 - (s - (r + t)) / (o - t - e),
            c = 1 - (s + n.offsetHeight - (r + t)) / (o - t - e);
        return {
            topPosition: s,
            topProgress: a,
            bottomProgress: c,
            ahead: a < 0,
            entering: a > 0 && a < 1 && c < 0,
            contained: a < 1 && c > 0 || a > 1 && c < 0,
            exiting: a > 1 && c > 0 && c < 1,
            behind: c > 1
        }
    }
}

init();