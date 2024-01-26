<template>
  <div class="element fade-in" v-observe-visibility="{callback: visibilityChanged,once: true}" :class="[isVisible ? 'element--visible' : null]">
    <slot :headerNav="headerNav" />
  </div>
</template>

<script>

export default {
  data() {
    return {
      isOpen: !1,
      items: null,
      navItems: null,
      activeItem: null,
      nextItem: null
    }
  },
  mounted() {
    this.navItems = this.$el.querySelector(".nav-wrapper"),
    this.items = this.$el.querySelectorAll(".nav-items > li")
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
      let e = document.querySelector(`.nav-items li #${t}`),
          n = e.previousElementSibling;
      e && e.classList.remove("menu-hidden"),
      n && (n.classList.add("active"), n.setAttribute("aria-expanded", !0)),
          this.navItems.classList.add("open"),
          this.activeItem = t
    },
    closeItem() {
      this.closeAllItems(),
          this.activeItem = null
    }
  }
}

</script>