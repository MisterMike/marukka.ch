<template>
  <swiper>
    <slot :swiperSlides="swiperSlides"  />
  </swiper>
</template>
<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';
import SwiperCore, { Navigation, Pagination, Autoplay, A11y } from 'swiper';

// Import Swiper styles
import 'swiper/css';

SwiperCore.use([Navigation, Pagination, Autoplay, A11y]);


export default {
  components: {
    Swiper,
    SwiperSlide
  },
  setup() {
    const onSwiper = (swiper) => {
      console.log(swiper);
    };
    const onSlideChange = () => {
      console.log('slide change');
    };
    return {
      onSwiper,
      onSlideChange,

    };
  }
};
</script>