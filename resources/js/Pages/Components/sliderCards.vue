<template>
  <div>
    <swiper
      :slides-per-view="4"
      :space-between="20"
      :breakpoints="breakpoints"
      navigation
      pagination
    >
      <swiper-slide>
        <div class="p-4 bg-green-300 rounded">OPD Card</div>
      </swiper-slide>
      <swiper-slide>
        <div class="p-4 bg-yellow-300 rounded">IPD Card</div>
      </swiper-slide>
      <swiper-slide>
        <div class="p-4 bg-indigo-300 rounded">Revenue</div>
      </swiper-slide>
      <swiper-slide>
        <div class="p-4 bg-red-300 rounded">Expense</div>
      </swiper-slide>
    </swiper>
  </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

const breakpoints = {
  320: { slidesPerView: 1, spaceBetween: 10 },
  640: { slidesPerView: 2, spaceBetween: 15 },
  1024: { slidesPerView: 4, spaceBetween: 20 },
};
</script>
