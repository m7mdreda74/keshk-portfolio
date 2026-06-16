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
        <div class="row gy-5 isotope-container" data-aos="fade-up" data-aos-delay="200">
          <div v-for="project in filteredProjects" 
               :key="project.id" 
               class="col-lg-4 col-md-6 portfolio-item isotope-item">
            
            <div class="portfolio-card-inner">
              <!-- Image Wrapper with hover overlay for Title & Links -->
              <div class="portfolio-img-wrap">
                <img :src="project.image ? '/' + project.image : '/assets/img/portfolio/serv5.jpg'" class="img-fluid" :alt="project.title">
                
                <!-- Overlay (Visible on Hover over Image) -->
                <div class="portfolio-img-overlay">
                  <h4 class="portfolio-project-title">{{ project.title }}</h4>
                  <div class="portfolio-action-links">
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
              </div>

              <!-- Description Dropdown (Slides down on Hover over Card) -->
              <div class="portfolio-desc-dropdown">
                <div class="portfolio-desc-content">
                  <h5 class="desc-title">{{ project.title }}</h5>
                  <span class="desc-category">{{ capitalize(project.category) }}</span>
                  <p class="desc-text">{{ project.description }}</p>
                  <div class="desc-footer" v-if="project.details_link && project.details_link !== '#'">
                    <a :href="project.details_link" target="_blank" rel="noopener" class="desc-btn">
                      Visit Project <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
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
  overflow: visible; /* Need overflow visible to allow absolute dropdown to show */
  z-index: 1;
  transition: z-index 0.3s ease;
}

.portfolio-item:hover {
  z-index: 10; /* Bring hovered item card to front */
}

/* Card inner container */
.portfolio-card-inner {
  position: relative;
  background: rgba(18, 18, 18, 0.65);
  border-radius: 12px;
  border: 1px solid rgba(144, 99, 255, 0.15);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.portfolio-item:hover .portfolio-card-inner {
  border-color: rgba(144, 99, 255, 0.45);
  box-shadow: 0 12px 35px rgba(144, 99, 255, 0.25);
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
}

/* Image container */
.portfolio-img-wrap {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  aspect-ratio: 16 / 10;
  display: block;
}

.portfolio-item:hover .portfolio-img-wrap {
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
}

.portfolio-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  display: block;
}

.portfolio-item:hover .portfolio-img-wrap img {
  transform: scale(1.08);
}

/* Image overlay on hover */
.portfolio-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(10, 6, 18, 0.95) 0%, rgba(10, 6, 18, 0.5) 60%, rgba(10, 6, 18, 0.1) 100%);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 20px;
  opacity: 0;
  transform: translateY(12px);
  transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1),
              transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.portfolio-item:hover .portfolio-img-overlay {
  opacity: 1;
  transform: translateY(0);
}

.portfolio-project-title {
  font-size: 18px;
  font-weight: 700;
  color: #ffffff !important;
  margin: 0 0 12px 0;
  text-shadow: 0 2px 8px rgba(0, 0, 0, 0.85);
}

.portfolio-action-links {
  display: flex;
  gap: 12px;
}

.portfolio-action-links a {
  color: rgba(255, 255, 255, 0.85);
  background: rgba(144, 99, 255, 0.2);
  border: 1px solid rgba(144, 99, 255, 0.4);
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  transition: all 0.3s ease;
  text-decoration: none;
}

.portfolio-action-links a:hover {
  background: #9063ff;
  border-color: #9063ff;
  color: #ffffff;
  transform: translateY(-2px);
}

/* Description Dropdown box */
.portfolio-desc-dropdown {
  position: absolute;
  top: calc(100% - 2px);
  left: -1px;
  right: -1px;
  z-index: 5;
  background: rgba(15, 10, 25, 0.96);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(144, 99, 255, 0.45);
  border-top: none;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  max-height: 0;
  opacity: 0;
  overflow: hidden;
  transform: translateY(-6px);
  transition: max-height 0.5s cubic-bezier(0.16, 1, 0.3, 1),
              opacity 0.4s ease,
              transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
}

.portfolio-item:hover .portfolio-desc-dropdown {
  max-height: 380px;
  opacity: 1;
  transform: translateY(0);
}

.portfolio-desc-content {
  padding: 18px 20px;
}

.desc-title {
  font-size: 15px;
  font-weight: 700;
  color: #ffffff;
  margin: 0 0 5px 0;
}

.desc-category {
  display: inline-block;
  font-size: 10px;
  font-weight: 700;
  color: #c084fc;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  margin-bottom: 12px;
  background: rgba(192, 132, 252, 0.12);
  padding: 2px 8px;
  border-radius: 4px;
}

.desc-text {
  font-size: 13px;
  line-height: 1.6;
  color: #cbd5e1;
  margin: 0;
  max-height: 140px;
  overflow-y: auto;
  scrollbar-width: thin;
  padding-right: 6px;
}

/* Custom scrollbar styling for description text */
.desc-text::-webkit-scrollbar {
  width: 4px;
}
.desc-text::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.03);
}
.desc-text::-webkit-scrollbar-thumb {
  background: rgba(144, 99, 255, 0.3);
  border-radius: 2px;
}

.desc-footer {
  margin-top: 15px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  padding-top: 10px;
  display: flex;
  justify-content: flex-end;
}

.desc-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  font-weight: 700;
  color: #38bdf8;
  text-decoration: none;
  transition: all 0.2s ease;
}

.desc-btn:hover {
  color: #0ea5e9;
  transform: translateX(3px);
}

/* Background elements */
.portfolio {
  position: relative;
  overflow: visible; /* Allow absolute elements to pop out */
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
  background: rgba(6, 6, 6, 0.78);
  position: absolute;
  inset: 0;
  z-index: 2;
}

.portfolio .container {
  position: relative;
  z-index: 3;
}
</style>
