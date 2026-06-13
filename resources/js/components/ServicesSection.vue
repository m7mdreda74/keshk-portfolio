<template>
  <section id="services" class="services section">
    <img :src="'/assets/img/srvc-img.png'" alt="" class="services-bg" data-aos="fade-in">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Services</h2>
      <p>Professional services that I offer to clients and companies.</p>
    </div><!-- End Section Title -->

    <div class="container">
      <div class="row gy-4">
        <div v-for="service in services" :key="service.id" class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="service-item position-relative">
            <div class="icon">
              <i :class="service.icon || 'bi bi-activity'"></i>
            </div>
            <a href="#" class="stretched-link" @click.prevent>
              <h3>{{ service.title }}</h3>
            </a>
            <p>{{ service.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'ServicesSection',
  props: {
    services: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>
/* ── Services Section Background & Overlay ── */
.services {
  position: relative;
}

.services-bg {
  position: absolute;
  inset: 0;
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 1;
  -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 18%, black 82%, transparent 100%);
  mask-image: linear-gradient(to bottom, transparent 0%, black 18%, black 82%, transparent 100%);
  animation: slowZoom 18s ease-in-out infinite alternate;
  transform-origin: center center;
}

@keyframes slowZoom {
  0%   { transform: scale(1); }
  100% { transform: scale(1.08); }
}

.services::before {
  content: "";
  background: rgba(6, 6, 6, 0.75);
  position: absolute;
  inset: 0;
  z-index: 2;
}

.services .container {
  position: relative;
  z-index: 3;
}
</style>
