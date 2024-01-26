const SELECTORS = {
    accordion: '.js-accordion',
    item: '.js-accordion-item',
    header: '.js-accordion-header',
    panel: '.js-accordion-panel'
}

const CLASSES = {
    initialized: 'accordion--initialized',
    itemClosed: 'accordion__item--closed'
}

class AccordionItem {
    constructor($item, initiallyOpen = false) {
        this.$item = $item
        this.$header = $item.querySelector(SELECTORS.header)
        this.$panel = $item.querySelector(SELECTORS.panel)
        this.maxHeight = this.$panel.offsetHeight
        this.isOpen = false

        this.$panel.style.maxHeight = `${this.maxHeight}px`
        this.$header.addEventListener('click', () => this.toggle())

        initiallyOpen ? this.open() : this.close()
    }

    toggle() {
        this.isOpen ? this.close() : this.open()
    }

    open() {
        this.isOpen = true
        this.$header.setAttribute('aria-expanded', String(this.isOpen))
        this.$item.classList.remove(CLASSES.itemClosed)
    }

    close() {
        this.isOpen = false
        this.$header.setAttribute('aria-expanded', String(this.isOpen))
        this.$item.classList.add(CLASSES.itemClosed)
    }
}

class Accordion {
    constructor(el) {
        this.$accordion = el
        this.$items = Array.from(el.querySelectorAll(SELECTORS.item))

        this.initiallyOpen = !!el.dataset.open
        this.init()
    }

    init() {
        this.$items.map(($item) => new AccordionItem($item, this.initiallyOpen))
        window.setTimeout(() => {
            this.$accordion.classList.add(CLASSES.initialized)
        }, 10)
    }
}

const allAccordions = document.querySelectorAll(SELECTORS.accordion)

if (allAccordions) {
    Array.from(allAccordions).map((el) => new Accordion(el))
}
