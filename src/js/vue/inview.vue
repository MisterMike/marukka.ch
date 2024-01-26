<template>
	<div class="element fade-in" v-observe-visibility="{callback: visibilityChanged,once: true}" :class="[isVisible ? 'element--visible' : null]">
  	<slot :inView="inView" />
	</div>
</template>

<script>
import { ObserveVisibility } from "vue-observe-visibility";

export default {
  props: {
    tag: {
      type: String,
      default: 'div'
    },

    threshold: {
      type: Number,
      default: 0
    },
    rootMargin: {
      type: String,
      default: '0px 0px 0px 0px'
    }
  },
  name: "InView", 
  directives: {
    ObserveVisibility
  },
  data() {
    return {
    	isVisible: !1,
    	once: 1,
      inView: undefined
    }
  },
  methods: {
    visibilityChanged: function(visible, element) {
        return this.isVisible = visible, element.isIntersecting
    }
	}
}
</script>