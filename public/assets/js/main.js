/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./dev/_js/main.js":
/*!*************************!*\
  !*** ./dev/_js/main.js ***!
  \*************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _main_navigation__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main/navigation */ "./dev/_js/main/navigation.js");
/* harmony import */ var _main_navigation__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_main_navigation__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _main_showcontent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./main/showcontent */ "./dev/_js/main/showcontent.js");
/* harmony import */ var _main_showcontent__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_main_showcontent__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _main_accordeon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./main/accordeon */ "./dev/_js/main/accordeon.js");
/* harmony import */ var _main_accordeon__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_main_accordeon__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _main_accordeonBlocks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./main/accordeonBlocks */ "./dev/_js/main/accordeonBlocks.js");
/* harmony import */ var _main_accordeonBlocks__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_main_accordeonBlocks__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _main_photoswipe__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./main/photoswipe */ "./dev/_js/main/photoswipe.js");
/* harmony import */ var _main_photoswipe__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_main_photoswipe__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var lazysizes__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! lazysizes */ "./node_modules/lazysizes/lazysizes.js");
/* harmony import */ var lazysizes__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(lazysizes__WEBPACK_IMPORTED_MODULE_5__);

 //import detectIE from './main/detectIE';
//import SmoothScroll from './main/smoothscroll';
//import FormLabel from './main/formlabel';





document.addEventListener("DOMContentLoaded", function () {
  document.getElementsByTagName("html")[0].className += " inited";
  document.getElementById("main__logo--header").classList.add("show");
  new _main_showcontent__WEBPACK_IMPORTED_MODULE_1___default.a('showMe', 'show', '-150px 0px');
});

/***/ }),

/***/ "./dev/_js/main/accordeon.js":
/*!***********************************!*\
  !*** ./dev/_js/main/accordeon.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// Listen for click on the document
document.addEventListener('click', function (event) {
  //Bail if our clicked element doesn't have the class
  if (!event.target.classList.contains('accordeon__item--title')) return; // Get the target content
  //var content = document.querySelector(event.target.attributes.getNamedItem('data-target').value);
  // Return early if there is no target
  //if (!content) return;
  // Prevent default link behavior
  //event.preventDefault();
  // If the content is already expanded, collapse it and quit

  if (event.target.parentElement.classList.contains('open')) {
    event.target.parentElement.classList.remove('open');
    return;
  } // Get all open accordion content, loop through it, and close it


  var accordions = document.querySelectorAll('.accordeon__item.open');

  for (var i = 0; i < accordions.length; i++) {
    accordions[i].classList.remove('open');
  } // Toggle our content


  event.target.parentElement.classList.toggle('open');
});

/***/ }),

/***/ "./dev/_js/main/accordeonBlocks.js":
/*!*****************************************!*\
  !*** ./dev/_js/main/accordeonBlocks.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// Listen for click on the document
document.addEventListener('click', function (event) {
  //Bail if our clicked element doesn't have the class
  if (!event.target.classList.contains('section-teaser__element--header')) return; // Get the target content

  var content = document.querySelector(event.target.attributes.getNamedItem('data-target').value); // Return early if there is no target

  if (!content) return; // Prevent default link behavior

  event.preventDefault(); // If the content is already expanded, collapse it and quit

  if (content.classList.contains('active')) {
    content.classList.remove('active');
    return;
  } // Get all open accordion content, loop through it, and close it


  var accordions = document.querySelectorAll('.section-teaser__element--content--wrapper.active');

  for (var i = 0; i < accordions.length; i++) {
    accordions[i].classList.remove('active');
  } // Toggle our content


  content.classList.toggle('active');
});

/***/ }),

/***/ "./dev/_js/main/navigation.js":
/*!************************************!*\
  !*** ./dev/_js/main/navigation.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var navElem = '.main__nav--items--level1';
var n = document.querySelectorAll(".main__nav--items--level1 [aria-haspopup], .search-menu [aria-haspopup], .nav-sidebar [aria-haspopup]");
window.NodeList && !NodeList.prototype.forEach && (NodeList.prototype.forEach = Array.prototype.forEach), n.forEach(function (e) {
  var t = document.getElementById(e.getAttribute("aria-controls")),
      n = function n() {
    e.setAttribute("aria-expanded", "false"), t.setAttribute("hidden", "true");
  };

  e.addEventListener("mouseover", function (i) {
    // menu click
    i.preventDefault(), "true" === e.getAttribute("aria-expanded") ? n() : (e.setAttribute("aria-expanded", "true"), t.removeAttribute("hidden"));
  }), window.addEventListener("resize", n);
}), window.addEventListener("keydown", function e(t) {
  9 === t.keyCode && (document.body.classList.add("user-is-tabbing"), document.getElementById("search_modal").classList.add("user-is-tabbing"), window.removeEventListener("keydown", e));
});
var i = document.querySelectorAll(".main__nav--items--level1 [aria-haspopup]");

function d() {
  c && c.hasAttribute("hidden") && (c.removeAttribute("hidden"), u.setAttribute("aria-expanded", !0), document.querySelector(".nav-main").classList.add("is-open"));
}

function r() {
  c.setAttribute("hidden", !0), u.setAttribute("aria-expanded", !1), document.querySelector(".nav-main").classList.remove("is-open");
}

function tabletWidth() {
  return window.matchMedia("(min-width: 900px)").matches;
}

function a() {
  tabletWidth() ? d() : r();
}

i.forEach(function (e) {
  e.addEventListener("click", function (t) {
    for (var _t = 0; _t < i.length; _t++) {
      if (i[_t] !== e && i[_t]) {
        i[_t].setAttribute("aria-expanded", "false");

        var _e = document.getElementById(i[_t].getAttribute("aria-controls"));

        _e && _e.setAttribute("hidden", "true");
      }
    }
  });
});
var s = {};
window.addEventListener("keyup", function (e) {
  s[e.keyCode] = !1;
}), window.addEventListener("keydown", function (e) {
  s[e.keyCode] = !0;
});
var c = document.querySelector('.main__nav--items--level1'),
    u = document.querySelector('.nav-main-link');
/* Sidebar navigation, currently unused */

function l() {
  v && v.hasAttribute("hidden") && (v.removeAttribute("hidden"), !0 !== tabletWidth() && (h.setAttribute("aria-expanded", !0), document.querySelector(".nav-sidebar").classList.add("is-open")));
}

function m() {
  !0 !== tabletWidth() && v && (v.setAttribute("hidden", !0), h.setAttribute("aria-expanded", !1), document.querySelector(".nav-sidebar").classList.remove("is-open"));
}

function sidebarWideLink() {
  if (!h || !h.length) {
    return;
  }

  tabletWidth() ? (l(), document.querySelector(".nav-sidebar-link").classList.remove("wide")) : m();
}

u && u.addEventListener("click", function (e) {
  e.preventDefault(), "false" === this.getAttribute("aria-expanded") ? d() : r();
}), window.addEventListener("resize", a), window.addEventListener("load", a), a();
s = {};
window.addEventListener("keyup", function (e) {
  s[e.keyCode] = !1;
}), window.addEventListener("keydown", function (e) {
  s[e.keyCode] = !0;
});
var v = document.querySelector(".nav-sidebar"),
    h = document.querySelector(".nav-sidebar-link");
h && h.length && h.addEventListener("click", function (e) {
  e.preventDefault(), "false" === this.getAttribute("aria-expanded") ? l() : m();
}), window.addEventListener("resize", sidebarWideLink), window.addEventListener("load", sidebarWideLink);
/* Search modal functionality */

var searchOpen = document.getElementById("search_modal"),
    mainBody = document.body;
mainBody.parentNode.insertBefore(searchOpen, mainBody), function () {
  "use strict";

  var e,
      searchModal = document.getElementById("search_modal"),
      searchOpen = document.getElementById("search_open"),
      mainBody = searchModal.querySelector(".close-button"),
      searchModalContent = searchModal.querySelector(".modal-content"),
      r = !1;

  function o(searchOpen) {
    !r || searchOpen.keyCode && 27 !== searchOpen.keyCode || (searchModal.setAttribute("aria-hidden", "true"), searchModal.setAttribute("tabindex", "-1"), r = !1, e.focus());
  }

  searchModal.addEventListener("click", function (e) {
    e.target == searchModalContent.parentNode && o(e);
  }, !1), searchOpen.addEventListener("click", function () {
    e = document.activeElement, searchModal.setAttribute("aria-hidden", "false"), r = !0, searchModal.setAttribute("tabindex", "0"), searchModal.focus(), search.focus();
  }), mainBody.addEventListener("click", o), document.addEventListener("keydown", function (e) {
    r && !searchModalContent.contains(e.target) && e.target !== mainBody && (e.stopPropagation(), search.focus());
  }), document.addEventListener("keydown", o);
}();

/***/ }),

/***/ "./dev/_js/main/photoswipe.js":
/*!************************************!*\
  !*** ./dev/_js/main/photoswipe.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

document.addEventListener("DOMContentLoaded", function () {
  var initPhotoSwipeFromDOM = function initPhotoSwipeFromDOM(gallerySelector) {
    // parse slide data (url, title, size ...) from DOM elements
    // (children of gallerySelector)
    var parseThumbnailElements = function parseThumbnailElements(el) {
      var thumbElements = el.childNodes,
          numNodes = thumbElements.length,
          items = [],
          figureEl,
          linkEl,
          size,
          item;

      for (var i = 0; i < numNodes; i++) {
        figureEl = thumbElements[i]; // <figure> element
        // include only element nodes

        if (figureEl.nodeType !== 1) {
          continue;
        } //divEl = figureEl.children[1]; // surrounding <div> element
        //linkEl = divEl.children[0]; // <a> element inside another div


        linkEl = figureEl.children[0]; // <a> element

        size = linkEl.getAttribute('data-size').split('x'); // create slide object

        item = {
          src: linkEl.getAttribute('href'),
          w: parseInt(size[0], 10),
          h: parseInt(size[1], 10)
        };

        if (figureEl.children.length > 1) {
          // <figcaption> content
          item.title = figureEl.children[1].innerHTML;
        }

        if (linkEl.children.length > 0) {
          // <img> thumbnail element, retrieving thumbnail url
          item.msrc = linkEl.children[0].getAttribute('src');
        }

        item.el = figureEl; // save link to element for getThumbBoundsFn

        items.push(item);
      }

      return items;
    }; // find nearest parent element


    var closest = function closest(el, fn) {
      return el && (fn(el) ? el : closest(el.parentNode, fn));
    }; // triggers when user clicks on thumbnail


    var onThumbnailsClick = function onThumbnailsClick(e) {
      e = e || window.event;
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
      var eTarget = e.target || e.srcElement; // find root element of slide

      var clickedListItem = closest(eTarget, function (el) {
        return el.tagName && el.tagName.toUpperCase() === 'FIGURE';
      });

      if (!clickedListItem) {
        return;
      } // find index of clicked item by looping through all child nodes
      // alternatively, you may define index via data- attribute


      var clickedGallery = clickedListItem.parentNode,
          childNodes = clickedListItem.parentNode.childNodes,
          numChildNodes = childNodes.length,
          nodeIndex = 0,
          index;

      for (var i = 0; i < numChildNodes; i++) {
        if (childNodes[i].nodeType !== 1) {
          continue;
        }

        if (childNodes[i] === clickedListItem) {
          index = nodeIndex;
          break;
        }

        nodeIndex++;
      }

      if (index >= 0) {
        // open PhotoSwipe if valid index found
        openPhotoSwipe(index, clickedGallery);
      }

      return false;
    }; // parse picture index and gallery index from URL (#&pid=1&gid=2)


    var photoswipeParseHash = function photoswipeParseHash() {
      var hash = window.location.hash.substring(1),
          params = {};

      if (hash.length < 5) {
        return params;
      }

      var vars = hash.split('&');

      for (var i = 0; i < vars.length; i++) {
        if (!vars[i]) {
          continue;
        }

        var pair = vars[i].split('=');

        if (pair.length < 2) {
          continue;
        }

        params[pair[0]] = pair[1];
      }

      if (params.gid) {
        params.gid = parseInt(params.gid, 10);
      }

      return params;
    };

    var openPhotoSwipe = function openPhotoSwipe(index, galleryElement, disableAnimation, fromURL) {
      var pswpElement = document.querySelectorAll('.pswp')[0],
          gallery,
          options,
          items;
      items = parseThumbnailElements(galleryElement); // define options (if needed)

      options = {
        // define gallery index (for URL)
        galleryUID: galleryElement.getAttribute('data-pswp-uid'),
        getThumbBoundsFn: function getThumbBoundsFn(index) {
          // See Options -> getThumbBoundsFn section of documentation for more info
          var thumbnail = items[index].el.getElementsByTagName('img')[0],
              // find thumbnail
          pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
              rect = thumbnail.getBoundingClientRect();
          return {
            x: rect.left,
            y: rect.top + pageYScroll,
            w: rect.width
          };
        }
      }; // PhotoSwipe opened from URL

      if (fromURL) {
        if (options.galleryPIDs) {
          // parse real index when custom PIDs are used
          // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
          for (var j = 0; j < items.length; j++) {
            if (items[j].pid == index) {
              options.index = j;
              break;
            }
          }
        } else {
          // in URL indexes start from 1
          options.index = parseInt(index, 10) - 1;
        }
      } else {
        options.index = parseInt(index, 10);
      } // exit if index not found


      if (isNaN(options.index)) {
        return;
      }

      if (disableAnimation) {
        options.showAnimationDuration = 0;
      } // Pass data to PhotoSwipe and initialize it


      gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
      gallery.init();
    }; // loop through all gallery elements and bind events


    var galleryElements = document.querySelectorAll(gallerySelector);

    for (var i = 0, l = galleryElements.length; i < l; i++) {
      galleryElements[i].setAttribute('data-pswp-uid', i + 1);
      galleryElements[i].onclick = onThumbnailsClick;
    } // Parse URL and open gallery if it contains #&pid=3&gid=1


    var hashData = photoswipeParseHash();

    if (hashData.pid && hashData.gid) {
      openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
    }
  };

  initPhotoSwipeFromDOM('.gallery');
});

/***/ }),

/***/ "./dev/_js/main/showcontent.js":
/*!*************************************!*\
  !*** ./dev/_js/main/showcontent.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var showContent = function showContent(selectorClass, showClass) {
  var animMargin = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "-150px 0px";
  var divElem = document.querySelectorAll(selectorClass),
      divElemCount = divElem.length,
      myObserver = void 0;

  if ("IntersectionObserver" in window) {
    myObserver = new IntersectionObserver(function (selectorClass) {
      0 === divElemCount && myObserver.paragraphDisconnect();

      for (var looper = 0; looper < selectorClass.length; looper++) {
        var currentElem = selectorClass[looper];
        currentElem.intersectionRatio > 0 && (divElemCount--, myObserver.unobserve(currentElem.target), makeItSo(currentElem.target, showClass));
      }
    }, {
      rootMargin: animMargin,
      threshold: .01
    });

    for (var cntr1 = 0; cntr1 < divElem.length; cntr1++) {
      var loopElem = divElem[cntr1];
      loopElem.classList.contains(showClass) || myObserver.observe(loopElem);
    }
  } else !function (selectorClass, showClass) {
    for (var cntr2 = 0; cntr2 < selectorClass.length; cntr2++) {
      var currentIeElem = selectorClass[cntr2];
      currentIeElem.classList.add(showClass);
    }
  }(divElem);

  function makeItSo(targetDiv, showClass) {
    targetDiv.classList.add(showClass);
  }
};

/***/ }),

/***/ "./dev/_sass/_vendors/photoswipe/css/main.scss":
/*!*****************************************************!*\
  !*** ./dev/_sass/_vendors/photoswipe/css/main.scss ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./dev/_sass/styles.scss":
/*!*******************************!*\
  !*** ./dev/_sass/styles.scss ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./node_modules/lazysizes/lazysizes.js":
/*!*********************************************!*\
  !*** ./node_modules/lazysizes/lazysizes.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

(function(window, factory) {
	var lazySizes = factory(window, window.document);
	window.lazySizes = lazySizes;
	if( true && module.exports){
		module.exports = lazySizes;
	}
}(window, function l(window, document) {
	'use strict';
	/*jshint eqnull:true */
	if(!document.getElementsByClassName){return;}

	var lazysizes, lazySizesConfig;

	var docElem = document.documentElement;

	var Date = window.Date;

	var supportPicture = window.HTMLPictureElement;

	var _addEventListener = 'addEventListener';

	var _getAttribute = 'getAttribute';

	var addEventListener = window[_addEventListener];

	var setTimeout = window.setTimeout;

	var requestAnimationFrame = window.requestAnimationFrame || setTimeout;

	var requestIdleCallback = window.requestIdleCallback;

	var regPicture = /^picture$/i;

	var loadEvents = ['load', 'error', 'lazyincluded', '_lazyloaded'];

	var regClassCache = {};

	var forEach = Array.prototype.forEach;

	var hasClass = function(ele, cls) {
		if(!regClassCache[cls]){
			regClassCache[cls] = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		}
		return regClassCache[cls].test(ele[_getAttribute]('class') || '') && regClassCache[cls];
	};

	var addClass = function(ele, cls) {
		if (!hasClass(ele, cls)){
			ele.setAttribute('class', (ele[_getAttribute]('class') || '').trim() + ' ' + cls);
		}
	};

	var removeClass = function(ele, cls) {
		var reg;
		if ((reg = hasClass(ele,cls))) {
			ele.setAttribute('class', (ele[_getAttribute]('class') || '').replace(reg, ' '));
		}
	};

	var addRemoveLoadEvents = function(dom, fn, add){
		var action = add ? _addEventListener : 'removeEventListener';
		if(add){
			addRemoveLoadEvents(dom, fn);
		}
		loadEvents.forEach(function(evt){
			dom[action](evt, fn);
		});
	};

	var triggerEvent = function(elem, name, detail, noBubbles, noCancelable){
		var event = document.createEvent('Event');

		if(!detail){
			detail = {};
		}

		detail.instance = lazysizes;

		event.initEvent(name, !noBubbles, !noCancelable);

		event.detail = detail;

		elem.dispatchEvent(event);
		return event;
	};

	var updatePolyfill = function (el, full){
		var polyfill;
		if( !supportPicture && ( polyfill = (window.picturefill || lazySizesConfig.pf) ) ){
			if(full && full.src && !el[_getAttribute]('srcset')){
				el.setAttribute('srcset', full.src);
			}
			polyfill({reevaluate: true, elements: [el]});
		} else if(full && full.src){
			el.src = full.src;
		}
	};

	var getCSS = function (elem, style){
		return (getComputedStyle(elem, null) || {})[style];
	};

	var getWidth = function(elem, parent, width){
		width = width || elem.offsetWidth;

		while(width < lazySizesConfig.minSize && parent && !elem._lazysizesWidth){
			width =  parent.offsetWidth;
			parent = parent.parentNode;
		}

		return width;
	};

	var rAF = (function(){
		var running, waiting;
		var firstFns = [];
		var secondFns = [];
		var fns = firstFns;

		var run = function(){
			var runFns = fns;

			fns = firstFns.length ? secondFns : firstFns;

			running = true;
			waiting = false;

			while(runFns.length){
				runFns.shift()();
			}

			running = false;
		};

		var rafBatch = function(fn, queue){
			if(running && !queue){
				fn.apply(this, arguments);
			} else {
				fns.push(fn);

				if(!waiting){
					waiting = true;
					(document.hidden ? setTimeout : requestAnimationFrame)(run);
				}
			}
		};

		rafBatch._lsFlush = run;

		return rafBatch;
	})();

	var rAFIt = function(fn, simple){
		return simple ?
			function() {
				rAF(fn);
			} :
			function(){
				var that = this;
				var args = arguments;
				rAF(function(){
					fn.apply(that, args);
				});
			}
		;
	};

	var throttle = function(fn){
		var running;
		var lastTime = 0;
		var gDelay = lazySizesConfig.throttleDelay;
		var rICTimeout = lazySizesConfig.ricTimeout;
		var run = function(){
			running = false;
			lastTime = Date.now();
			fn();
		};
		var idleCallback = requestIdleCallback && rICTimeout > 49 ?
			function(){
				requestIdleCallback(run, {timeout: rICTimeout});

				if(rICTimeout !== lazySizesConfig.ricTimeout){
					rICTimeout = lazySizesConfig.ricTimeout;
				}
			} :
			rAFIt(function(){
				setTimeout(run);
			}, true)
		;

		return function(isPriority){
			var delay;

			if((isPriority = isPriority === true)){
				rICTimeout = 33;
			}

			if(running){
				return;
			}

			running =  true;

			delay = gDelay - (Date.now() - lastTime);

			if(delay < 0){
				delay = 0;
			}

			if(isPriority || delay < 9){
				idleCallback();
			} else {
				setTimeout(idleCallback, delay);
			}
		};
	};

	//based on http://modernjavascript.blogspot.de/2013/08/building-better-debounce.html
	var debounce = function(func) {
		var timeout, timestamp;
		var wait = 99;
		var run = function(){
			timeout = null;
			func();
		};
		var later = function() {
			var last = Date.now() - timestamp;

			if (last < wait) {
				setTimeout(later, wait - last);
			} else {
				(requestIdleCallback || run)(run);
			}
		};

		return function() {
			timestamp = Date.now();

			if (!timeout) {
				timeout = setTimeout(later, wait);
			}
		};
	};

	(function(){
		var prop;

		var lazySizesDefaults = {
			lazyClass: 'lazyload',
			loadedClass: 'lazyloaded',
			loadingClass: 'lazyloading',
			preloadClass: 'lazypreload',
			errorClass: 'lazyerror',
			//strictClass: 'lazystrict',
			autosizesClass: 'lazyautosizes',
			srcAttr: 'data-src',
			srcsetAttr: 'data-srcset',
			sizesAttr: 'data-sizes',
			//preloadAfterLoad: false,
			minSize: 40,
			customMedia: {},
			init: true,
			expFactor: 1.5,
			hFac: 0.8,
			loadMode: 2,
			loadHidden: true,
			ricTimeout: 0,
			throttleDelay: 125,
		};

		lazySizesConfig = window.lazySizesConfig || window.lazysizesConfig || {};

		for(prop in lazySizesDefaults){
			if(!(prop in lazySizesConfig)){
				lazySizesConfig[prop] = lazySizesDefaults[prop];
			}
		}

		window.lazySizesConfig = lazySizesConfig;

		setTimeout(function(){
			if(lazySizesConfig.init){
				init();
			}
		});
	})();

	var loader = (function(){
		var preloadElems, isCompleted, resetPreloadingTimer, loadMode, started;

		var eLvW, elvH, eLtop, eLleft, eLright, eLbottom, isBodyHidden;

		var regImg = /^img$/i;
		var regIframe = /^iframe$/i;

		var supportScroll = ('onscroll' in window) && !(/(gle|ing)bot/.test(navigator.userAgent));

		var shrinkExpand = 0;
		var currentExpand = 0;

		var isLoading = 0;
		var lowRuns = -1;

		var resetPreloading = function(e){
			isLoading--;
			if(!e || isLoading < 0 || !e.target){
				isLoading = 0;
			}
		};

		var isVisible = function (elem) {
			if (isBodyHidden == null) {
				isBodyHidden = getCSS(document.body, 'visibility') == 'hidden';
			}

			return isBodyHidden || (getCSS(elem.parentNode, 'visibility') != 'hidden' && getCSS(elem, 'visibility') != 'hidden');
		};

		var isNestedVisible = function(elem, elemExpand){
			var outerRect;
			var parent = elem;
			var visible = isVisible(elem);

			eLtop -= elemExpand;
			eLbottom += elemExpand;
			eLleft -= elemExpand;
			eLright += elemExpand;

			while(visible && (parent = parent.offsetParent) && parent != document.body && parent != docElem){
				visible = ((getCSS(parent, 'opacity') || 1) > 0);

				if(visible && getCSS(parent, 'overflow') != 'visible'){
					outerRect = parent.getBoundingClientRect();
					visible = eLright > outerRect.left &&
						eLleft < outerRect.right &&
						eLbottom > outerRect.top - 1 &&
						eLtop < outerRect.bottom + 1
					;
				}
			}

			return visible;
		};

		var checkElements = function() {
			var eLlen, i, rect, autoLoadElem, loadedSomething, elemExpand, elemNegativeExpand, elemExpandVal,
				beforeExpandVal, defaultExpand, preloadExpand, hFac;
			var lazyloadElems = lazysizes.elements;

			if((loadMode = lazySizesConfig.loadMode) && isLoading < 8 && (eLlen = lazyloadElems.length)){

				i = 0;

				lowRuns++;

				defaultExpand = (!lazySizesConfig.expand || lazySizesConfig.expand < 1) ?
					docElem.clientHeight > 500 && docElem.clientWidth > 500 ? 500 : 370 :
					lazySizesConfig.expand;

				lazysizes._defEx = defaultExpand;

				preloadExpand = defaultExpand * lazySizesConfig.expFactor;
				hFac = lazySizesConfig.hFac;
				isBodyHidden = null;

				if(currentExpand < preloadExpand && isLoading < 1 && lowRuns > 2 && loadMode > 2 && !document.hidden){
					currentExpand = preloadExpand;
					lowRuns = 0;
				} else if(loadMode > 1 && lowRuns > 1 && isLoading < 6){
					currentExpand = defaultExpand;
				} else {
					currentExpand = shrinkExpand;
				}

				for(; i < eLlen; i++){

					if(!lazyloadElems[i] || lazyloadElems[i]._lazyRace){continue;}

					if(!supportScroll){unveilElement(lazyloadElems[i]);continue;}

					if(!(elemExpandVal = lazyloadElems[i][_getAttribute]('data-expand')) || !(elemExpand = elemExpandVal * 1)){
						elemExpand = currentExpand;
					}

					if(beforeExpandVal !== elemExpand){
						eLvW = innerWidth + (elemExpand * hFac);
						elvH = innerHeight + elemExpand;
						elemNegativeExpand = elemExpand * -1;
						beforeExpandVal = elemExpand;
					}

					rect = lazyloadElems[i].getBoundingClientRect();

					if ((eLbottom = rect.bottom) >= elemNegativeExpand &&
						(eLtop = rect.top) <= elvH &&
						(eLright = rect.right) >= elemNegativeExpand * hFac &&
						(eLleft = rect.left) <= eLvW &&
						(eLbottom || eLright || eLleft || eLtop) &&
						(lazySizesConfig.loadHidden || isVisible(lazyloadElems[i])) &&
						((isCompleted && isLoading < 3 && !elemExpandVal && (loadMode < 3 || lowRuns < 4)) || isNestedVisible(lazyloadElems[i], elemExpand))){
						unveilElement(lazyloadElems[i]);
						loadedSomething = true;
						if(isLoading > 9){break;}
					} else if(!loadedSomething && isCompleted && !autoLoadElem &&
						isLoading < 4 && lowRuns < 4 && loadMode > 2 &&
						(preloadElems[0] || lazySizesConfig.preloadAfterLoad) &&
						(preloadElems[0] || (!elemExpandVal && ((eLbottom || eLright || eLleft || eLtop) || lazyloadElems[i][_getAttribute](lazySizesConfig.sizesAttr) != 'auto')))){
						autoLoadElem = preloadElems[0] || lazyloadElems[i];
					}
				}

				if(autoLoadElem && !loadedSomething){
					unveilElement(autoLoadElem);
				}
			}
		};

		var throttledCheckElements = throttle(checkElements);

		var switchLoadingClass = function(e){
			var elem = e.target;

			if (elem._lazyCache) {
				delete elem._lazyCache;
				return;
			}

			resetPreloading(e);
			addClass(elem, lazySizesConfig.loadedClass);
			removeClass(elem, lazySizesConfig.loadingClass);
			addRemoveLoadEvents(elem, rafSwitchLoadingClass);
			triggerEvent(elem, 'lazyloaded');
		};
		var rafedSwitchLoadingClass = rAFIt(switchLoadingClass);
		var rafSwitchLoadingClass = function(e){
			rafedSwitchLoadingClass({target: e.target});
		};

		var changeIframeSrc = function(elem, src){
			try {
				elem.contentWindow.location.replace(src);
			} catch(e){
				elem.src = src;
			}
		};

		var handleSources = function(source){
			var customMedia;

			var sourceSrcset = source[_getAttribute](lazySizesConfig.srcsetAttr);

			if( (customMedia = lazySizesConfig.customMedia[source[_getAttribute]('data-media') || source[_getAttribute]('media')]) ){
				source.setAttribute('media', customMedia);
			}

			if(sourceSrcset){
				source.setAttribute('srcset', sourceSrcset);
			}
		};

		var lazyUnveil = rAFIt(function (elem, detail, isAuto, sizes, isImg){
			var src, srcset, parent, isPicture, event, firesLoad;

			if(!(event = triggerEvent(elem, 'lazybeforeunveil', detail)).defaultPrevented){

				if(sizes){
					if(isAuto){
						addClass(elem, lazySizesConfig.autosizesClass);
					} else {
						elem.setAttribute('sizes', sizes);
					}
				}

				srcset = elem[_getAttribute](lazySizesConfig.srcsetAttr);
				src = elem[_getAttribute](lazySizesConfig.srcAttr);

				if(isImg) {
					parent = elem.parentNode;
					isPicture = parent && regPicture.test(parent.nodeName || '');
				}

				firesLoad = detail.firesLoad || (('src' in elem) && (srcset || src || isPicture));

				event = {target: elem};

				addClass(elem, lazySizesConfig.loadingClass);

				if(firesLoad){
					clearTimeout(resetPreloadingTimer);
					resetPreloadingTimer = setTimeout(resetPreloading, 2500);
					addRemoveLoadEvents(elem, rafSwitchLoadingClass, true);
				}

				if(isPicture){
					forEach.call(parent.getElementsByTagName('source'), handleSources);
				}

				if(srcset){
					elem.setAttribute('srcset', srcset);
				} else if(src && !isPicture){
					if(regIframe.test(elem.nodeName)){
						changeIframeSrc(elem, src);
					} else {
						elem.src = src;
					}
				}

				if(isImg && (srcset || isPicture)){
					updatePolyfill(elem, {src: src});
				}
			}

			if(elem._lazyRace){
				delete elem._lazyRace;
			}
			removeClass(elem, lazySizesConfig.lazyClass);

			rAF(function(){
				// Part of this can be removed as soon as this fix is older: https://bugs.chromium.org/p/chromium/issues/detail?id=7731 (2015)
				var isLoaded = elem.complete && elem.naturalWidth > 1;

				if( !firesLoad || isLoaded){
					if (isLoaded) {
						addClass(elem, 'ls-is-cached');
					}
					switchLoadingClass(event);
					elem._lazyCache = true;
					setTimeout(function(){
						if ('_lazyCache' in elem) {
							delete elem._lazyCache;
						}
					}, 9);
				}
			}, true);
		});

		var unveilElement = function (elem){
			var detail;

			var isImg = regImg.test(elem.nodeName);

			//allow using sizes="auto", but don't use. it's invalid. Use data-sizes="auto" or a valid value for sizes instead (i.e.: sizes="80vw")
			var sizes = isImg && (elem[_getAttribute](lazySizesConfig.sizesAttr) || elem[_getAttribute]('sizes'));
			var isAuto = sizes == 'auto';

			if( (isAuto || !isCompleted) && isImg && (elem[_getAttribute]('src') || elem.srcset) && !elem.complete && !hasClass(elem, lazySizesConfig.errorClass) && hasClass(elem, lazySizesConfig.lazyClass)){return;}

			detail = triggerEvent(elem, 'lazyunveilread').detail;

			if(isAuto){
				 autoSizer.updateElem(elem, true, elem.offsetWidth);
			}

			elem._lazyRace = true;
			isLoading++;

			lazyUnveil(elem, detail, isAuto, sizes, isImg);
		};

		var onload = function(){
			if(isCompleted){return;}
			if(Date.now() - started < 999){
				setTimeout(onload, 999);
				return;
			}
			var afterScroll = debounce(function(){
				lazySizesConfig.loadMode = 3;
				throttledCheckElements();
			});

			isCompleted = true;

			lazySizesConfig.loadMode = 3;

			throttledCheckElements();

			addEventListener('scroll', function(){
				if(lazySizesConfig.loadMode == 3){
					lazySizesConfig.loadMode = 2;
				}
				afterScroll();
			}, true);
		};

		return {
			_: function(){
				started = Date.now();

				lazysizes.elements = document.getElementsByClassName(lazySizesConfig.lazyClass);
				preloadElems = document.getElementsByClassName(lazySizesConfig.lazyClass + ' ' + lazySizesConfig.preloadClass);

				addEventListener('scroll', throttledCheckElements, true);

				addEventListener('resize', throttledCheckElements, true);

				if(window.MutationObserver){
					new MutationObserver( throttledCheckElements ).observe( docElem, {childList: true, subtree: true, attributes: true} );
				} else {
					docElem[_addEventListener]('DOMNodeInserted', throttledCheckElements, true);
					docElem[_addEventListener]('DOMAttrModified', throttledCheckElements, true);
					setInterval(throttledCheckElements, 999);
				}

				addEventListener('hashchange', throttledCheckElements, true);

				//, 'fullscreenchange'
				['focus', 'mouseover', 'click', 'load', 'transitionend', 'animationend', 'webkitAnimationEnd'].forEach(function(name){
					document[_addEventListener](name, throttledCheckElements, true);
				});

				if((/d$|^c/.test(document.readyState))){
					onload();
				} else {
					addEventListener('load', onload);
					document[_addEventListener]('DOMContentLoaded', throttledCheckElements);
					setTimeout(onload, 20000);
				}

				if(lazysizes.elements.length){
					checkElements();
					rAF._lsFlush();
				} else {
					throttledCheckElements();
				}
			},
			checkElems: throttledCheckElements,
			unveil: unveilElement
		};
	})();


	var autoSizer = (function(){
		var autosizesElems;

		var sizeElement = rAFIt(function(elem, parent, event, width){
			var sources, i, len;
			elem._lazysizesWidth = width;
			width += 'px';

			elem.setAttribute('sizes', width);

			if(regPicture.test(parent.nodeName || '')){
				sources = parent.getElementsByTagName('source');
				for(i = 0, len = sources.length; i < len; i++){
					sources[i].setAttribute('sizes', width);
				}
			}

			if(!event.detail.dataAttr){
				updatePolyfill(elem, event.detail);
			}
		});
		var getSizeElement = function (elem, dataAttr, width){
			var event;
			var parent = elem.parentNode;

			if(parent){
				width = getWidth(elem, parent, width);
				event = triggerEvent(elem, 'lazybeforesizes', {width: width, dataAttr: !!dataAttr});

				if(!event.defaultPrevented){
					width = event.detail.width;

					if(width && width !== elem._lazysizesWidth){
						sizeElement(elem, parent, event, width);
					}
				}
			}
		};

		var updateElementsSizes = function(){
			var i;
			var len = autosizesElems.length;
			if(len){
				i = 0;

				for(; i < len; i++){
					getSizeElement(autosizesElems[i]);
				}
			}
		};

		var debouncedUpdateElementsSizes = debounce(updateElementsSizes);

		return {
			_: function(){
				autosizesElems = document.getElementsByClassName(lazySizesConfig.autosizesClass);
				addEventListener('resize', debouncedUpdateElementsSizes);
			},
			checkElems: debouncedUpdateElementsSizes,
			updateElem: getSizeElement
		};
	})();

	var init = function(){
		if(!init.i){
			init.i = true;
			autoSizer._();
			loader._();
		}
	};

	lazysizes = {
		cfg: lazySizesConfig,
		autoSizer: autoSizer,
		loader: loader,
		init: init,
		uP: updatePolyfill,
		aC: addClass,
		rC: removeClass,
		hC: hasClass,
		fire: triggerEvent,
		gW: getWidth,
		rAF: rAF,
	};

	return lazysizes;
}
));


/***/ }),

/***/ 0:
/*!*****************************************************************************************************!*\
  !*** multi ./dev/_js/main.js ./dev/_sass/styles.scss ./dev/_sass/_vendors/photoswipe/css/main.scss ***!
  \*****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Webcode/marukka.ch/dev/_js/main.js */"./dev/_js/main.js");
__webpack_require__(/*! /Webcode/marukka.ch/dev/_sass/styles.scss */"./dev/_sass/styles.scss");
module.exports = __webpack_require__(/*! /Webcode/marukka.ch/dev/_sass/_vendors/photoswipe/css/main.scss */"./dev/_sass/_vendors/photoswipe/css/main.scss");


/***/ })

/******/ });