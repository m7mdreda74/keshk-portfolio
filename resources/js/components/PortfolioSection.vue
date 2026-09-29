<template>
  <section id="portfolio" class="portfolio section">
    <img :src="'/assets/img/prtf-bg.png'" alt="" class="portfolio-bg" data-aos="fade-in">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Portfolio</h2>
      <p>A curated showcase of scalable SaaS platforms, enterprise ERP systems, REST APIs, and full-stack web applications.</p>
    </div><!-- End Section Title -->

    <div class="container">
      <div class="isotope-layout">
        <!-- Portfolio Filters -->
        <div class="portfolio-filters-wrapper" data-aos="fade-up" data-aos-delay="100">
          <ul class="portfolio-filters isotope-filters">
            <li :class="{ 'filter-active': selectedFilter === '*' }" @click="setFilter('*')">
              <span class="filter-label">All</span>
              <span class="filter-count">{{ projects.length }}</span>
            </li>
            <li v-for="cat in categories" 
                :key="cat" 
                :class="{ 'filter-active': selectedFilter === cat }" 
                @click="setFilter(cat)">
              <i :class="getCategoryIcon(cat)" class="filter-icon"></i>
              <span class="filter-label">{{ capitalize(cat) }}</span>
              <span class="filter-count">{{ getCategoryCount(cat) }}</span>
            </li>
          </ul>
        </div><!-- End Portfolio Filters -->

        <!-- Portfolio Items -->
        <div class="row gy-5 isotope-container" data-aos="fade-up" data-aos-delay="200">
          <div v-for="project in filteredProjects" 
               :key="project.id" 
               class="col-lg-4 col-md-6 portfolio-item isotope-item">
            
            <div class="portfolio-card-inner">
              <!-- Image Wrapper with hover overlay for Title & Links -->
              <div class="portfolio-img-wrap">
                <!-- Floating Category Badge -->
                <div class="portfolio-card-badge" :class="'badge-' + (project.category || '').toLowerCase()">
                  <i :class="getCategoryIcon(project.category)" class="me-1"></i>
                  {{ capitalize(project.category) }}
                </div>

                <img :src="project.image || '/assets/img/portfolio/serv5.jpg'" class="img-fluid" :alt="project.title">
                
                <!-- Overlay (Visible on Hover over Image) -->
                <div class="portfolio-img-overlay">
                  <h4 class="portfolio-project-title">{{ project.title }}</h4>
                  <div class="portfolio-action-links">
                    <a :href="project.image || '/assets/img/portfolio/serv5.jpg'" 
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
                  <span class="desc-category" :class="'cat-' + (project.category || '').toLowerCase()">
                    <i :class="getCategoryIcon(project.category)" class="me-1"></i>
                    {{ capitalize(project.category) }}
                  </span>
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

    // Explicit order for categories: SaaS -> ERP -> API -> Web
    const categoryOrder = ['saas', 'erp', 'api', 'web'];

    const categories = computed(() => {
      const cats = [...new Set(props.projects.map(p => (p.category || '').toLowerCase()))];
      return cats.sort((a, b) => {
        const idxA = categoryOrder.indexOf(a);
        const idxB = categoryOrder.indexOf(b);
        return (idxA !== -1 ? idxA : 99) - (idxB !== -1 ? idxB : 99);
      });
    });

    const getCategoryCount = (cat) => {
      if (cat === '*') return props.projects.length;
      return props.projects.filter(p => (p.category || '').toLowerCase() === cat.toLowerCase()).length;
    };

    const getCategoryIcon = (cat) => {
      const c = (cat || '').toLowerCase();
      if (c === 'saas') return 'bi bi-cloud-check-fill';
      if (c === 'erp') return 'bi bi-cpu-fill';
      if (c === 'api') return 'bi bi-code-slash';
      return 'bi bi-globe';
    };

    const filteredProjects = computed(() => {
      if (selectedFilter.value === '*') return props.projects;
      return props.projects.filter(p => (p.category || '').toLowerCase() === selectedFilter.value.toLowerCase());
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
      const lower = str.toLowerCase();
      if (lower === 'saas') return 'SaaS';
      if (lower === 'erp') return 'ERP';
      if (lower === 'api') return 'API';
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
      getCategoryCount,
      getCategoryIcon,
    };
  },
};
</script>

<style scoped>
/* Filter Pills Wrapper */
.portfolio-filters-wrapper {
  display: flex;
  justify-content: center;
  margin-bottom: 40px;
}

.portfolio-filters {
  display: inline-flex !important;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  padding: 6px 10px;
  background: rgba(18, 14, 28, 0.75);
  border: 1px solid rgba(144, 99, 255, 0.22);
  border-radius: 40px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  list-style: none;
  margin: 0 !important;
}

.portfolio-filters li {
  display: inline-flex !important;
  align-items: center !important;
  gap: 7px !important;
  color: #cbd5e1 !important;
  background: transparent !important;
  border: 1px solid transparent !important;
  padding: 8px 18px !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  border-radius: 30px !important;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
  user-select: none;
  box-shadow: none !important;
}

.portfolio-filters li:hover {
  color: #ffffff !important;
  background: rgba(144, 99, 255, 0.16) !important;
  border-color: rgba(144, 99, 255, 0.35) !important;
  transform: translateY(-2px) !important;
}

.portfolio-filters li.filter-active {
  background: linear-gradient(135deg, #7c3aed 0%, #9063ff 100%) !important;
  color: #ffffff !important;
  border-color: rgba(192, 132, 252, 0.6) !important;
  box-shadow: 0 6px 20px rgba(124, 58, 237, 0.45) !important;
  transform: translateY(-2px) !important;
}

.filter-icon {
  font-size: 13px;
  opacity: 0.9;
}

.filter-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  padding: 0 6px;
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.12);
  color: inherit;
  transition: all 0.3s ease;
}

.portfolio-filters li.filter-active .filter-count {
  background: rgba(255, 255, 255, 0.28);
  color: #ffffff;
}

/* Floating Category Badge on Project Card */
.portfolio-card-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  z-index: 5;
  display: inline-flex;
  align-items: center;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  padding: 4px 10px;
  border-radius: 20px;
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.45);
  transition: all 0.3s ease;
}

.portfolio-card-badge.badge-saas {
  background: rgba(124, 58, 237, 0.35);
  border: 1px solid rgba(192, 132, 252, 0.55);
  color: #e9d5ff;
}

.portfolio-card-badge.badge-erp {
  background: rgba(16, 185, 129, 0.28);
  border: 1px solid rgba(52, 211, 153, 0.55);
  color: #a7f3d0;
}

.portfolio-card-badge.badge-api {
  background: rgba(245, 158, 11, 0.28);
  border: 1px solid rgba(251, 191, 36, 0.55);
  color: #fde68a;
}

.portfolio-card-badge.badge-web {
  background: rgba(56, 189, 248, 0.25);
  border: 1px solid rgba(56, 189, 248, 0.55);
  color: #bae6fd;
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
  border: 1px solid rgba(144, 99, 255, 0.3);
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.3s ease;
  font-size: 16px;
  backdrop-filter: blur(4px);
}

.portfolio-action-links a:hover {
  background: var(--accent-color, #9063ff);
  color: #ffffff;
  border-color: var(--accent-color, #9063ff);
  transform: scale(1.1);
  box-shadow: 0 0 15px rgba(144, 99, 255, 0.6);
}

/* Description Dropdown (Hidden initially, slides down on card hover) */
.portfolio-desc-dropdown {
  position: absolute;
  top: 100%;
  left: -1px;
  right: -1px;
  background: #110e1b;
  border: 1px solid rgba(144, 99, 255, 0.45);
  border-top: none;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  padding: 0 20px;
  max-height: 0;
  opacity: 0;
  visibility: hidden;
  overflow: hidden;
  transition: max-height 0.45s cubic-bezier(0.16, 1, 0.3, 1),
              opacity 0.35s ease,
              padding 0.35s ease;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.65);
  z-index: 9999;
}

.portfolio-item:hover .portfolio-desc-dropdown {
  max-height: 280px;
  opacity: 1;
  visibility: visible;
  padding: 18px 20px 22px 20px;
}

.portfolio-desc-content {
  display: flex;
  flex-direction: column;
}

.desc-title {
  font-size: 16px;
  font-weight: 700;
  color: #f8fafc;
  margin: 0 0 4px 0;
}

.desc-category {
  display: inline-flex;
  align-items: center;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  margin-bottom: 12px;
  padding: 2px 8px;
  border-radius: 4px;
  width: fit-content;
}

.desc-category.cat-saas {
  color: #c084fc;
  background: rgba(192, 132, 252, 0.15);
}

.desc-category.cat-erp {
  color: #34d399;
  background: rgba(52, 211, 153, 0.15);
}

.desc-category.cat-api {
  color: #fbbf24;
  background: rgba(251, 191, 36, 0.15);
}

.desc-category.cat-web {
  color: #38bdf8;
  background: rgba(56, 189, 248, 0.15);
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
  overflow: visible;
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
</style>

<style>
/* Global overrides to prevent clipping and overlay issues in isotope grid */
.portfolio .container {
  position: relative;
  z-index: 10 !important;
}

.portfolio .portfolio-item {
  overflow: visible !important;
  z-index: 1;
}

.portfolio .portfolio-item:hover {
  z-index: 9999 !important;
}
</style>
