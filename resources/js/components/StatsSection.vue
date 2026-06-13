<template>
  <section id="stats" class="stats section accent-background">
    <img :src="'/assets/img/stats-bg.png'" alt="" data-aos="fade-in">

    <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div v-for="stat in stats" :key="stat.id" class="col-lg-3 col-md-6">
          <div class="stats-item text-center w-100 h-100">
            <span :data-purecounter-start="0" 
                  :data-purecounter-end="stat.count" 
                  data-purecounter-duration="1" 
                  class="purecounter">{{ stat.count }}</span>
            <p>{{ stat.label }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { onMounted } from 'vue';

export default {
  name: 'StatsSection',
  props: {
    stats: {
      type: Array,
      required: true,
    },
  },
  setup() {
    onMounted(() => {
      setTimeout(() => {
        if (window.PureCounter) {
          new window.PureCounter();
        }
      }, 200);
    });
  },
};
</script>
