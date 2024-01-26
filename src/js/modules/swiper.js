//import Swiper from "swiper";

import {Swiper as Swiper, Pagination, Navigation} from 'swiper/swiper.esm.js'

// Swiper modules
Swiper.use([Pagination, Navigation])

function makeId(length) {
    var result = "";
    var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function positionNextArrow(swiperInstance) {
    const carousel = swiperInstance.$el[0];

    if (carousel) {
        const images = Array.from(carousel.querySelectorAll("img"));
        const img = images.filter((image) => image.height > 0)[0] || null;
        const btn = carousel.querySelector(".swiper-button-next");

        if (img && btn) {
            let imgHeight = img.height;
            let imgWidth = img.width;
            let btnHeight = btn.clientHeight;

            let offsetTop = `${imgHeight / 2 - btnHeight / 2}px`;
            let offsetLeft = `${imgWidth + swiperInstance.passedParams.spaceBetween}px`;
            btn.style.top = offsetTop;
            btn.style.left = offsetLeft;
        }
    }
}

export default function () {
    /**
     * Global
     */

    // Global homepage swiper (might need changes for default site)
    const homepageSliderImagesElement = document.querySelector(
        ".swiper-container.hero-slider-images"
    );

    if (homepageSliderImagesElement) {
        const homepageSliderImages = new Swiper(homepageSliderImagesElement, {
            loop: true,
            speed: 600,
            // autoplay: {
            //     delay: 8000
            // },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            on: {
                slideChangeTransitionEnd: function () {
                    let slides = [...homepageSliderImagesElement.querySelectorAll(".hero-slide")];
                    // Loop through all slides and reset styling and contents
                    slides.forEach((slide) => {
                        let videoLink = slide.querySelector(".video-link");
                        let videoFrame = slide.querySelector(".video");

                        if (videoLink && videoFrame) {
                            videoLink.classList.remove("playing");
                            const dummy = document.createElement("div");
                            dummy.setAttribute("id", makeId(10));
                            dummy.setAttribute("class", "video");
                            videoFrame.parentNode.replaceChild(dummy, videoFrame);
                        }
                    });

                    /*
                     * Solution below doesn't work (when sliding prev slide when on first slide, video won't stop)
                     */
                    // let lastSlide = homepageSliderImagesElement.querySelectorAll(".hero-slide")[
                    //     this.previousIndex
                    // ];
                    // let videoLink = lastSlide.querySelector(".video-link");
                    // let videoFrame = lastSlide.querySelector(".video");

                    // if (videoLink && videoFrame) {
                    //     videoLink.classList.remove("playing");
                    //     const dummy = document.createElement("div");
                    //     dummy.setAttribute("id", videoFrame.id);
                    //     dummy.setAttribute("class", "video");
                    //     videoFrame.parentNode.replaceChild(dummy, videoFrame);
                    // }
                },
            },
        });
    }
    

    const mediaSliderElement = document.querySelector(".swiper-container.media-slider");
    const mediaThumbnailElement = document.querySelector(".swiper-container.media-thumbnails");
    const mediaSlideCount = document.querySelectorAll(".swiper-container.media-slider .media-slide")
        .length;

    if (mediaSliderElement && mediaThumbnailElement) {
        const mediaSlider = new Swiper(mediaSliderElement, {
            loop: true,
            speed: 600,
            slidesPerView: "auto",
            loopedSlides: mediaSlideCount,
            navigation: {
                nextEl: ".swiper-button-next",
            },
        });

        const mediaThumbnails = new Swiper(mediaThumbnailElement, {
            direction: "vertical",
            loop: true,
            slidesPerView: 3,
            // slideToClickedSlide: true,
            loopedSlides: mediaSlideCount - 1,
            initialSlide: 1,
            spaceBetween: 24,
            on: {
                click: function (e) {
                    // Need to fake "slideToClickedSlide" to increase index with 1.
                    e.preventDefault();
                    mediaSlider.slideTo(mediaThumbnails.clickedIndex + 1);
                },
            },
        });

        mediaSlider.controller.control = mediaThumbnails;
        mediaThumbnails.controller.control = mediaSlider;
    }

    /**
     * Specific sliders
     */

    //  featured Slider Component
    
    const featuredSliderElement = document.querySelector(".container--featured-projects .swiper-container");

    if (featuredSliderElement) {
        const featuredSlider = new Swiper(featuredSliderElement, {
            loop: true,
            spaceBetween: 24,
            slidesPerView: 1,
            centeredSlides: false,
            breakpoints: {
                1000: {
                    slidesPerView: 2,
                },
                1400: {
                    slidesPerView: 3,
                },
                1600: {
                    slidesPerView: 4,
                }
            },
            // navigation: {
            //     nextEl: ".swiper-button-next",
            //     prevEl: ".swiper-button-prev",
            // },
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            }
        });
    }



    // Services Slider

    const servicesSliderElement = document.querySelector(".block--services .swiper-container");

    if (servicesSliderElement) {

        console.log('found servicesSliderElement!');

        const servicesSlider = new Swiper(servicesSliderElement, {
            autoHeight: true,
            loop: true,
            pagination: {
                el: ".services__bullets .swiper-pagination",
                clickable: true,
                type: 'bullets',
                renderBullet: function (index, className) {
                    const currentBullet = this.el.querySelectorAll(".swiper-slide")[index];

                    //const currentBullet = this.el.querySelector('.swiper-slide[data-swiper-slide-index="${index}"]');
                    
                    const title = currentBullet.getAttribute("data-title");
                    const number = currentBullet.getAttribute("data-number");
                    return '<span class="'+className+'"><span class="number">'+number+'</span><span class="title">'+title+'</span></span>';
                },
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        //console.log('servicesSlider: ' + JSON.stringify(servicesSlider));

    } else {
        console.log('no servicesSliderElement!');
    }

    // References

    // const referentiesSliderElements = document.querySelectorAll(
    //     ".references-carousel .swiper-container"
    // );
    // if (referentiesSliderElements.length) {
    //     console.log("ref sliders found");
    //     const referentiesSlider = new Swiper(referentiesSliderElements, {
    //         loop: true,
    //         spaceBetween: 24,
    //         slidesPerView: 1.6,
    //         navigation: {
    //             nextEl: ".swiper-button-next",
    //         },
    //         breakpoints: {
    //             900: {
    //                 slidesPerView: 2.6,
    //             },
    //             1600: {
    //                 slidesPerView: 3.6,
    //             },
    //         },
    //         on: {
    //             resize: function () {
    //                 positionNextArrow(this);
    //             },
    //             init: function () {
    //                 positionNextArrow(this);
    //             },
    //             imagesReady: function () {
    //                 positionNextArrow(this);
    //             },
    //         },
    //     });
    // }
}
