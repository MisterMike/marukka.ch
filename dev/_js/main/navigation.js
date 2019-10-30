
const navElem = '.main__nav--items--level1';
const n = document.querySelectorAll(".main__nav--items--level1 [aria-haspopup], .search-menu [aria-haspopup], .nav-sidebar [aria-haspopup]");

window.NodeList && !NodeList.prototype.forEach && (NodeList.prototype.forEach = Array.prototype.forEach), n.forEach(function(e) {
  let t = document.getElementById(e.getAttribute("aria-controls")),
      n = function() {
        e.setAttribute("aria-expanded", "false"), t.setAttribute("hidden", "true")
      };
  e.addEventListener("click", function(i) {
    i.preventDefault(), "true" === e.getAttribute("aria-expanded") ? n() : (e.setAttribute("aria-expanded", "true"), t.removeAttribute("hidden"))
  }), window.addEventListener("resize", n)
}), window.addEventListener("keydown", function e(t) {
  9 === t.keyCode && (document.body.classList.add("user-is-tabbing"), document.getElementById("search_modal").classList.add("user-is-tabbing"), window.removeEventListener("keydown", e))
});

const i = document.querySelectorAll(".main__nav--items--level1 [aria-haspopup]");

function d() {
  c && c.hasAttribute("hidden") && (c.removeAttribute("hidden"), u.setAttribute("aria-expanded", !0), document.querySelector(".navigation").classList.add("is-open"))
}

function r() {
  c.setAttribute("hidden", !0), u.setAttribute("aria-expanded", !1), document.querySelector(".navigation").classList.remove("is-open")
}

function o2() {
  return window.matchMedia("(min-width: 900px)").matches
}

function a() {
  o2() ? d() : r()
}

i.forEach(function(e) {
  e.addEventListener("click", function(t) {
    for (let t = 0; t < i.length; t++)
      if (i[t] !== e && i[t]) {
        i[t].setAttribute("aria-expanded", "false");
        let e = document.getElementById(i[t].getAttribute("aria-controls"));
        e && e.setAttribute("hidden", "true")
      }
  })
});
var s = {};
window.addEventListener("keyup", function(e) {
  s[e.keyCode] = !1
}), window.addEventListener("keydown", function(e) {
  s[e.keyCode] = !0
});

let c = document.querySelector('.main__nav--items--level1'),
    u = document.querySelector('.main__nav--items--level1');

function o() {
  return window.matchMedia("(min-width: 900px)").matches
}

function l() {
  v && v.hasAttribute("hidden") && (v.removeAttribute("hidden"), !0 !== o() && (h.setAttribute("aria-expanded", !0), document.querySelector(".nav-sidebar").classList.add("is-open")))
}

function m() {
  !0 !== o() && v && (v.setAttribute("hidden", !0), h.setAttribute("aria-expanded", !1), document.querySelector(".nav-sidebar").classList.remove("is-open"))
}

function f() {
  o2() ? (l(), document.querySelector(".nav-sidebar-link").classList.remove("wide")) : m()
}
u && u.addEventListener("click", function(e) {
  e.preventDefault(), "false" === this.getAttribute("aria-expanded") ? d() : r()
}), window.addEventListener("resize", a), window.addEventListener("load", a), a();
s = {};
window.addEventListener("keyup", function(e) {
  s[e.keyCode] = !1
}), window.addEventListener("keydown", function(e) {
  s[e.keyCode] = !0
});

let v = document.querySelector(".nav-sidebar"),
    h = document.querySelector(".nav-sidebar-link");

h && h.addEventListener("click", function(e) {
  e.preventDefault(), "false" === this.getAttribute("aria-expanded") ? l() : m()
}), window.addEventListener("resize", f), window.addEventListener("load", f)