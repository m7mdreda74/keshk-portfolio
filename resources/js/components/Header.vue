<template>
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid px-lg-5 px-3 position-relative d-flex align-items-center justify-content-between">
      <a href="#hero" class="logo d-flex align-items-center">
        <img :src="'/assets/img/logo.png'" alt="Keshk">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" :class="{ active: activeSection === 'hero' }" @click.prevent="selectMenu('hero')">Home</a></li>
          <li><a href="#about" :class="{ active: activeSection === 'about' }" @click.prevent="selectMenu('about')">About</a></li>
          <li><a href="#resume" :class="{ active: activeSection === 'resume' }" @click.prevent="selectMenu('resume')">Resume</a></li>
          <li><a href="#services" :class="{ active: activeSection === 'services' }" @click.prevent="selectMenu('services')">Services</a></li>
          <li><a href="#portfolio" :class="{ active: activeSection === 'portfolio' }" @click.prevent="selectMenu('portfolio')">Portfolio</a></li>
          <li><a href="#contact" :class="{ active: activeSection === 'contact' }" @click.prevent="selectMenu('contact')">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi" 
           :class="mobileActive ? 'bi-x' : 'bi-list'" 
           @click="toggleMobileNav"></i>
      </nav>
    </div>
  </header>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
  name: 'HeaderComponent',
  props: {
    personalInfo: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const mobileActive = ref(false);
    const activeSection = ref('hero');

    const toggleMobileNav = () => {
      mobileActive.value = !mobileActive.value;
      const body = document.querySelector('body');
      if (body) {
        body.classList.toggle('mobile-nav-active');
      }
    };

    const selectMenu = (sectionId) => {
      activeSection.value = sectionId;
      if (mobileActive.value) {
        toggleMobileNav();
      }
      const element = document.getElementById(sectionId);
      if (element) {
        // Adjust for header height
        const headerOffset = 80;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    };

    const handleScrollSpy = () => {
      const sections = ['hero', 'about', 'resume', 'services', 'portfolio', 'contact'];
      const scrollPosition = window.scrollY + 200;

      for (const sectionId of sections) {
        const el = document.getElementById(sectionId);
        if (el) {
          const offsetTop = el.offsetTop;
          const offsetHeight = el.offsetHeight;
          if (scrollPosition >= offsetTop && scrollPosition <= offsetTop + offsetHeight) {
            activeSection.value = sectionId;
          }
        }
      }
    };

    onMounted(() => {
      window.addEventListener('scroll', handleScrollSpy);
      handleScrollSpy();
    });

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScrollSpy);
    });

    return {
      mobileActive,
      activeSection,
      toggleMobileNav,
      selectMenu,
    };
  },
};
</script>

<style scoped>
</style>
