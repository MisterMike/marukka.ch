const SELECTORS = {
    navmenu: '.main-menu__list',
    toggler: '.submenu-toggler',
    item: '.main-menu__list--item',
    submenu: '.main-menu__list--level2'
}

const CLASSES = {
    itemOpen: 'is-visible'
}

class navigationItem {
    constructor($item, initiallyOpen = false) {
        this.$item = $item
        this.$submenu = $item.querySelector(SELECTORS.submenu)
        this.$link = $item.querySelector(SELECTORS.toggler)
        this.isOpen = false

        console.log('menu item')

        if(this.$link) {
            this.$link.addEventListener('click', (e) => this.toggle(e))
        }
    }

    toggle(e) {
        e.preventDefault();
        
        this.isOpen ? this.close() : this.open()
e.stopPropagation();
    }

    open() {
        this.isOpen = true
        this.$submenu.setAttribute('aria-expanded', String(this.isOpen))
        this.$item.classList.add(CLASSES.itemOpen)
    }

    close() {
        this.isOpen = false
        this.$submenu.setAttribute('aria-expanded', String(this.isOpen))
        this.$item.classList.remove(CLASSES.itemOpen)
    }
}

class Navigation {
    constructor(el) {
        this.$navigation = el
        this.$items = Array.from(el.querySelectorAll(SELECTORS.item))

        this.initiallyOpen = !!el.dataset.open
        this.init()
    }

    init() {
        this.$items.map(($item) => new navigationItem($item, this.initiallyOpen))
        // window.setTimeout(() => {
        //     this.$navigation.classList.add(CLASSES.initialized)
        // }, 10)
    }
}

const allNavItems = document.querySelectorAll(SELECTORS.navmenu)

if (allNavItems) {
    Array.from(allNavItems).map((el) => new Navigation(el))
}
