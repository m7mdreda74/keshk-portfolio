<template>
  <section id="testimonials" class="testimonials section accent-background">
    <img :src="'/assets/img/testimonials-bg.jpg'" class="testimonials-bg" alt="">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper init-swiper">
        <div class="swiper-wrapper">
          <div v-for="testimonial in testimonials" :key="testimonial.id" class="swiper-slide">
            <div class="testimonial-item">
              <img :src="testimonial.image ? '/' + testimonial.image : '/assets/img/testimonials/testimonials-1.jpg'" class="testimonial-img" alt="">
              <h3>{{ testimonial.name }}</h3>
              <h4>{{ testimonial.role }}</h4>
              <div class="stars">
                <i v-for="star in testimonial.stars" :key="star" class="bi bi-star-fill"></i>
              </div>
              <p>
                <i class="bi bi-quote quote-icon-left"></i>
                <span>{{ testimonial.quote }}</span>
                <i class="bi bi-quote quote-icon-right"></i>
              </p>
            </div>
          </div><!-- End testimonial item -->
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>
</template>

<script>
import { onMounted } from 'vue';

export default {
  name: 'TestimonialsSection',
  props: {
    testimonials: {
      type: Array,
      required: true,
    },
  },
  setup() {
    onMounted(() => {
      setTimeout(() => {
        if (window.Swiper) {
          new window.Swiper('.init-swiper', {
            loop: true,
            speed: 600,
            autoplay: {
              delay: 5000
            },
            slidesPerView: "auto",
            pagination: {
              el: ".swiper-pagination",
              type: "bullets",
              clickable: true
            }
          });
        }
      }, 300);
    });
  },
};
</script>
