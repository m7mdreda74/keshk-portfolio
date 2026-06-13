<template>
  <section id="portfolio" class="portfolio section">
    <img :src="'/assets/img/prtf-bg.png'" alt="" class="portfolio-bg" data-aos="fade-in">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Portfolio</h2>
      <p>A showcase of my recent design and development projects.</p>
    </div><!-- End Section Title -->

    <div class="container">
      <div class="isotope-layout">
        <!-- Portfolio Filters -->
        <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
          <li :class="{ 'filter-active': selectedFilter === '*' }" @click="setFilter('*')">All</li>
          <li v-for="cat in categories" :key="cat" :class="{ 'filter-active': selectedFilter === cat }" @click="setFilter(cat)">
            {{ capitalize(cat) }}
          </li>
        </ul><!-- End Portfolio Filters -->

        <!-- Portfolio Items -->
        <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
          <div v-for="project in filteredProjects" 
               :key="project.id" 
               class="col-lg-4 col-md-6 portfolio-item isotope-item">
            <img :src="project.image ? '/' + project.image : '/assets/img/portfolio/serv5.jpg'" class="img-fluid" :alt="project.title">
            <div class="portfolio-info">
              <h4>{{ project.title }}</h4>
              <p>{{ project.description }}</p>
              <a :href="project.image ? '/' + project.image : '/assets/img/portfolio/serv5.jpg'" 
                 :title="project.title" 
                 data-gallery="portfolio-gallery" 
                 class="glightbox preview-link">
                <i class="bi bi-zoom-in"></i>
              </a>
              <a :href="project.details_link || '#'" 
                 :target="project.details_link && project.details_link !== '#' ? '_blank' : '_self'"
                 rel="noopener" 
                 class="details-link"
                 title="View Project Website">
                <i class="bi bi-eye"></i>
              </a>
            </div>
          </div>
        </div><!-- End Portfolio Container -->
      </div>
    </div>
  </section>
</template>

<script>
import { ref, computed, onMounted, nextTick } from 'vue';

export default {
  name: 'PortfolioSection',
  props: {
    projects: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const selectedFilter = ref('*');
    let lightboxInstance = null;

    const categories = computed(() => {
      const cats = props.projects.map(p => p.category);
      return [...new Set(cats)];
    });

    const filteredProjects = computed(() => {
      if (selectedFilter.value === '*') return props.projects;
      return props.projects.filter(p => p.category === selectedFilter.value);
    });

    const initLightbox = () => {
      if (lightboxInstance) {
        lightboxInstance.destroy();
      }
      if (window.GLightbox) {
        lightboxInstance = window.GLightbox({
          selector: '.glightbox'
        });
      }
    };

    const setFilter = (filterVal) => {
      selectedFilter.value = filterVal;
      // Re-init lightbox after DOM update
      nextTick(() => {
        initLightbox();
      });
    };

    const capitalize = (str) => {
      if (!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1);
    };

    onMounted(() => {
      initLightbox();
    });

    return {
      selectedFilter,
      categories,
      filteredProjects,
      setFilter,
      capitalize,
    };
  },
};
</script>

<style scoped>
.portfolio-item {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
}

.portfolio-item img {
  width: 100%;
  aspect-ratio: 16 / 10;
  object-fit: cover;
  transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  border-radius: 12px;
  display: block;
}

.portfolio-item:hover img {
  transform: scale(1.08);
}

/* ── Portfolio Section Background & Overlay ── */
.portfolio {
  position: relative;
}

.portfolio-bg {
  position: absolute;
  inset: 0;
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 1;
  -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 18%, black 82%, transparent 100%);
  mask-image: linear-gradient(to bottom, transparent 0%, black 18%, black 82%, transparent 100%);
}

.portfolio::before {
  content: "";
  background: rgba(6, 6, 6, 0.75);
  position: absolute;
  inset: 0;
  z-index: 2;
}

.portfolio .container {
  position: relative;
  z-index: 3;
}
</style>
