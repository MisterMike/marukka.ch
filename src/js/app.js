
import {createApp, ref} from 'vue'
import { createStore } from 'vuex'
import LazyLoad from "vanilla-lazyload"

import InView from './vue/inview.vue'
import SwiperTeaser from './vue/swiper-slide.vue'
import PhotoSwipeComponent from './vue/vue-photoswipe/PhotoSwipe.vue'

// import Swiper things

import SwiperCore, { Navigation, Pagination, Autoplay, A11y } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
// import 'swiper/css';
// import 'swiper/css/navigation';
// import 'swiper/css/pagination';
//import 'swiper/css/scrollbar';

// Swiper Plugins
SwiperCore.use([Navigation, Pagination, Autoplay, A11y]);

function at(e) {
    return function(e) {
        if (Array.isArray(e))
            return ot(e)
    }(e) || function(e) {
        if ("undefined" != typeof Symbol && null != e[Symbol.iterator] || null != e["@@iterator"])
            return Array.from(e)
    }(e) || function(e, t) {
        if (!e)
            return;
        if ("string" == typeof e)
            return ot(e, t);
        var n = Object.prototype.toString.call(e).slice(8, -1);
        "Object" === n && e.constructor && (n = e.constructor.name);
        if ("Map" === n || "Set" === n)
            return Array.from(e);
        if ("Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))
            return ot(e, t)
    }(e) || function() {
        throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
    }()
}
function ot(e, t) {
    (null == t || t > e.length) && (t = e.length);
    for (var n = 0, r = new Array(t); n < t; n++)
        r[n] = e[n];
    return r
}

const $store = createStore({
    state: {
        firstLevelOpen: !1,
        secondLevelOpen: !1,
        thirdLevelOpen: !1,
        secondLevelToggle: !1,
        secondLevelIndex: null,
        thirdLevelIndex: null,
        renderActiveTrail: !0
    },
    mutations: {
        openMainMenu: function(e) {
            e.firstLevelOpen = !0
        },
        closeMainMenu: function(e) {
            e.firstLevelOpen = !1
        },
        openSecondLevel: function(e) {
            e.secondLevelOpen = !0
        },
        openThirdLevel: function(e) {
            e.thirdLevelOpen = !0
        },
        closeSecondLevel: function(e) {
            e.secondLevelOpen = !1
        },
        toggleSecondLevel: function(e) {
            e.secondLevelToggle ? e.secondLevelToggle = !1 : e.secondLevelToggle = !0
        },
        closeThirdLevel: function(e) {
            e.thirdLevelOpen = !1
        },
        setSecondLevelIndex: function(e, t) {
            e.secondLevelIndex = t
        },
        setThirdLevelIndex: function(e, t) {
            e.thirdLevelIndex = t
        },
        resetSecondLevelIndex: function(e) {
            e.secondLevelIndex = null
        },
        resetThirdLevelIndex: function(e) {
            e.thirdLevelIndex = null
        },
        activateRenderActiveTrail: function(e) {
            e.renderActiveTrail = !0
        },
        deactivateRenderActiveTrail: function(e) {
            e.renderActiveTrail = !1
        }
    }
});

const app = createApp({
    components: {
        InView,
        Swiper,
        SwiperSlide,
        SwiperTeaser
    },

    delimiters: ['${', '}'],

    data() {
        return {
            MenuOpen: false,
            flyoutOpen: !1,
            htmlElement: document.querySelector("html"),
            header: ".m-header",
            showHeader: !0,
            lastScrollPosition: 0,
            firstLevelOpen: !1,
            isVisible: !1,
            target: !1,
            isInViewport: "",
            viewportClassName: "is-in-viewport",
            elementHeight: "40px",
            openClass: "search-open",
            hoverFirstLevel: !1,
            hoverSecondLevel: !1,
            hoverThirdLevel: !1,
            videoElement: null,
            paused: null,
            swiperOptions: {
                slidesPerView: 3,
                spaceBetween: 16,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                autoplay: {
                    delay: 2500
                },
                breakpoints: {
                    980: {
                        slidesPerView: 3,
                        spaceBetween: 8
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 8
                    },
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 8
                    }
                },
                grabCursor: !0,
                keyboard: {
                    enabled: !0,
                    onlyInViewport: !0,
                    pageUpDown: !1
                },
                watchSlidesVisibility: !0,
                watchOverflow: !0
            },
            isOpen: !1,
            options: {
                index: 0,
                getThumbBoundsFn: function(e) {
                    var t = document.querySelectorAll(".m-stage-detail-page__image").length;
                    e >= t && (e = t - 1);
                    var n = document.querySelectorAll(".m-stage-detail-page__image")[e],
                        r = window.pageYOffset || document.documentElement.scrollTop,
                        i = n.getBoundingClientRect();
                    return {
                        x: i.left,
                        y: i.top + r,
                        w: i.width
                    }
                },
                showHideOpacity: !0,
                zoomEl: !1,
                shareEl: false,
                fullscreenEl: false,
                history: !1,
                maxSpreadZoom: 1,
                getDoubleTapZoom: function(e, t) {
                    return t.initialZoomLevel
                }
            },
            items: at(this.images)
        }
    },
    computed: {
        computedClass() {
            let className = this.MenuOpen? 'default' : 'modern';

            // More complicated logic to determine what
            // class should be applied

            return className;
        },
        searchParam: function() {
            var e = window.location.search.substring(1);
            return new URLSearchParams(e).get("text")
        },
        mainMenuOpen: function() {
            return this.$store.state.firstLevelOpen
        },
        secondLevelOpen: function() {
            return this.$store.state.secondLevelOpen
        },
        secondLevelToggle: function() {
            return this.$store.state.secondLevelToggle
        },
        thirdLevelOpen: function() {
            return this.$store.state.thirdLevelOpen
        },
        secondLevelIndex: function() {
            return this.$store.state.secondLevelIndex
        },
        thirdLevelIndex: function() {
            return this.$store.state.thirdLevelIndex
        },
        renderActiveTrail: function() {
            return this.$store.state.renderActiveTrail
        }
    },
    props: {
        images: {
            type: Array,
            default: function() {
                return []
            }
        }
    },
    methods: {
        toggleMenu() {
            this.closeItem(),
                this.isOpen = !this.isOpen
        },
        toggleItem(t) {
            this.closeAllItems(),
                this.activeItem !== t ? this.openItem(t) : this.activeItem = null
        },
        closeAllItems() {
            this.items.forEach(t => {
                var n;
                let e = t.querySelector(".submenu");
                if (e) {
                    e.classList.add("menu-hidden");
                    let i = (n = e.previousElementSibling) != null ? n : null;
                    i && (i.classList.remove("active"), i.setAttribute("aria-expanded", !1))
                }
            }),
                this.navItems.classList.remove("open")
        },
        openItem(t) {
            let e = document.querySelector(`.main-menu__list li #${t}`),
                n = e.previousElementSibling;
            e && e.classList.remove("menu-hidden"),
            n && (n.classList.add("active"), n.setAttribute("aria-expanded", !0)),
                this.navItems.classList.add("open"),
                this.activeItem = t
        },
        closeItem() {
            this.closeAllItems(),
                this.activeItem = null
        },
        updatePaused: function(e) {
            this.videoElement = e.target,
                this.paused = e.target.paused
        },
        hoveringFirstLevel: function(e) {
            this.hoverFirstLevel = e,
                e ? this.htmlElement.classList.add("hovering-menu") : this.htmlElement.classList.remove("hovering-menu")
        },
        openSecondLevel: function(e, t) {
            t ? (this.$store.commit("deactivateRenderActiveTrail"), this.$store.commit("openSecondLevel"), this.$store.commit("setSecondLevelIndex", e)) : this.renderActiveTrail && (this.$store.commit("openSecondLevel"), this.$store.commit("setSecondLevelIndex", e))
        },
        toggleSecondLevel: function(e) {
            this.$store.state.secondLevelOpen ? (this.$store.commit("closeSecondLevel"), this.$store.commit("resetSecondLevelIndex")) : (this.$store.commit("openSecondLevel"), this.$store.commit("activateRenderActiveTrail"), this.$store.commit("setSecondLevelIndex", e))
        },
        openThirdLevel: function(e, t) {
            (this.renderActiveTrail || t) && (t && this.$store.commit("deactivateRenderActiveTrail"), this.$store.commit("openThirdLevel"), this.$store.commit("setThirdLevelIndex", e))
        },
        closeSecondLevel: function() {
            this.$store.commit("deactivateRenderActiveTrail"),
                this.$store.commit("closeSecondLevel"),
                this.$store.commit("resetSecondLevelIndex"),
                console.log('2nd closed')
        },
        closeThirdLevel: function() {
            this.$store.commit("deactivateRenderActiveTrail"),
                this.$store.commit("closeThirdLevel"),
                this.$store.commit("resetThirdLevelIndex"),
                console.log('3rd closed')
        },
        handleScroll: function() {
            this.id = window.requestAnimationFrame(this.onScroll)
        },
        toggle: function() {
            this.$store.state.firstLevelOpen ? (this.htmlElement.classList.toggle("open-menu"), this.$store.commit("closeMainMenu"), this.$store.commit("closeSecondLevel"), this.$store.commit("closeThirdLevel"), this.$store.commit("resetSecondLevelIndex"), this.$store.commit("resetThirdLevelIndex")) : (this.htmlElement.classList.toggle("open-menu"), this.$store.commit("openMainMenu"), this.$store.commit("activateRenderActiveTrail"))
        },
        toggleFlyout: function() {
            var e = this;
            this.flyoutOpen = !this.flyoutOpen,
                this.flyoutOpen ? (document.querySelector(this.header).classList.add(this.openClass), setTimeout((function() {
                    e.$refs.searchInput.focus()
                }), 100)) : document.querySelector(this.header).classList.remove(this.openClass)
        },
        toggleAccordeon: function(event) {
            const tgt = event.target.closest("div");
            let myEl = tgt.classList.toggle("is-visible")
            console.log('event.target:'+tgt.classList);
        },
        closeFlyout: function() {
            this.flyoutOpen = !1,
                document.querySelector(this.header).classList.remove(this.openClass)
        },
        onScroll: function() {
            var e = window.pageYOffset || document.documentElement.scrollTop;
            if (e <= 0)
                return document.body.classList.remove("is-scrolling"), document.body.classList.remove("is-scrolling--up"), document.body.classList.remove("is-scrolling--down"), void document.body.classList.remove("is-scrolling--stage");
            document.body.classList.add("is-scrolling");
            var t = document.querySelector(".m-header").offsetHeight + (document.querySelector(".c-infolayer") && document.querySelector(".c-infolayer").offsetHeight || 0),
                n = t + (document.querySelector(".c-headline") && document.querySelector(".c-headline").offsetHeight || 0) + (document.querySelector(".m-stage") && document.querySelector(".m-stage").offsetHeight || 0);
            e < t || (e < this.lastScrollPosition ? (document.body.classList.remove("is-scrolling--down"), document.body.classList.add("is-scrolling--up")) : (document.body.classList.remove("is-scrolling--up"), document.body.classList.add("is-scrolling--down")), e < n ? document.body.classList.remove("is-scrolling--stage") : document.body.classList.add("is-scrolling--stage"), this.showHeader = e < this.lastScrollPosition, this.lastScrollPosition = e)
        },
        viewportChanged: function(e, t) {
            return this.isInViewport = e ? this.viewportClassName : "", t.isIntersecting
        },
        onSwiper(swiper) {
            //console.log(swiper);
        },
        onSlideChange() {
            //console.log('slide change');
        },
        showPhotoSwipe: function(e) {
            console.log("e:"+e);
            this.isOpen = !0,
                this.options["index"] = e
        },
        hidePhotoSwipe: function() {
            this.isOpen = !1
        }
    },
    mounted: function() {
        var e = this;

        this.navItems = document.querySelector(".m-header__nav"),
            this.items = document.querySelectorAll(".main-menu__list > li")

        window.addEventListener("resize", this.throttle);
        window.addEventListener("scroll", this.handleScroll);

        document.body.addEventListener("keyup", (function(t) {
            27 === t.keyCode && e.mainMenuOpen && e.toggle()
        }));


        this.$nextTick(function () {
            setTimeout((function() {
                document.querySelector("body").classList.add("loaded")
            }), 100)
        })

        setTimeout((function() {
            return window.dispatchEvent(new Event("resize"))
        }), 10)

        const getScrollbarWidth = () =>
            window.innerWidth - document.documentElement.clientWidth;
        document.documentElement.style.setProperty('--sbw', `${getScrollbarWidth()}px`)

    }
});

app.component('vue-picture-swipe', PhotoSwipeComponent);
app.use($store).mount('#app')

const lazyLoadOptions = {/* your options here */};
const pageLazyLoad = new LazyLoad(lazyLoadOptions);

export default {
  components: { InView, SwiperTeaser }
}