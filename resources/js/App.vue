<template>
  <div>
    <!-- Preloader -->
    <div v-if="loading" id="preloader"></div>

    <div v-else>
      <HeaderComponent :personalInfo="portfolioData.personal_info" />

      <main class="main">
        <HeroSection :personalInfo="portfolioData.personal_info" />
        <AboutSection :personalInfo="portfolioData.personal_info" :skills="portfolioData.skills" />
        <ResumeSection :resumeItems="portfolioData.resume_items" />
        <ServicesSection :services="portfolioData.services" />
        <StatsSection :stats="portfolioData.stats" />
        <PortfolioSection :projects="portfolioData.projects" />
        <ContactSection :personalInfo="portfolioData.personal_info" />
      </main>

      <FooterComponent :personalInfo="portfolioData.personal_info" />

      <!-- Scroll Top -->
      <a href="#" 
         id="scroll-top" 
         class="scroll-top d-flex align-items-center justify-content-center" 
         :class="{ 'active': isScrolled }"
         @click.prevent="scrollToTop">
        <i class="bi bi-arrow-up-short"></i>
      </a>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import HeaderComponent from './components/Header.vue';
import HeroSection from './components/HeroSection.vue';
import AboutSection from './components/AboutSection.vue';
import ResumeSection from './components/ResumeSection.vue';
import ServicesSection from './components/ServicesSection.vue';
import StatsSection from './components/StatsSection.vue';
import PortfolioSection from './components/PortfolioSection.vue';
import ContactSection from './components/ContactSection.vue';
import FooterComponent from './components/Footer.vue';

export default {
  name: 'App',
  components: {
    HeaderComponent,
    HeroSection,
    AboutSection,
    ResumeSection,
    ServicesSection,
    StatsSection,
    PortfolioSection,
    ContactSection,
    FooterComponent,
  },
  setup() {
    const loading = ref(true);
    const portfolioData = ref(null);
    const isScrolled = ref(false);

    const handleScroll = () => {
      const selectBody = document.querySelector('body');
      if (window.scrollY > 100) {
        isScrolled.value = true;
        if (selectBody) selectBody.classList.add('scrolled');
      } else {
        isScrolled.value = false;
        if (selectBody) selectBody.classList.remove('scrolled');
      }
    };

    const scrollToTop = () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    };

    onMounted(async () => {
      try {
        // 1. Show cached data immediately (makes repeat visits instant)
        const cached = localStorage.getItem('keshk-portfolio-data');
        if (cached) {
          try {
            portfolioData.value = JSON.parse(cached);
            loading.value = false;
          } catch (e) {
            localStorage.removeItem('keshk-portfolio-data');
          }
        }

        // 2. Always fetch fresh data in background
        const response = await axios.get('/api/portfolio-data');
        portfolioData.value = response.data;
        loading.value = false;

        // 3. Update cache (filter out large base64 images to keep cache small)
        try {
          const cacheable = {
            ...response.data,
            projects: response.data.projects?.map(p => ({ ...p, image: null })),
            testimonials: response.data.testimonials?.map(t => ({ ...t, image: null })),
          };
          localStorage.setItem('keshk-portfolio-data', JSON.stringify(cacheable));
        } catch (e) { /* localStorage quota exceeded — skip caching */ }

        // 4. Init scroll + AOS
        window.addEventListener('scroll', handleScroll);
        handleScroll();
        setTimeout(() => {
          if (window.AOS) {
            window.AOS.init({ duration: 600, easing: 'ease-in-out', once: true, mirror: false });
          }
        }, 300);

      } catch (error) {
        console.error('Failed to load portfolio data:', error);
        // If we have cached data already showing, don't blank the screen
        if (!portfolioData.value) loading.value = false;
      }
    });

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll);
    });

    return {
      loading,
      portfolioData,
      isScrolled,
      scrollToTop,
    };
  },
};
</script>

<style>
/* You can define scoped styles here if needed, or rely on main.css */
</style>
