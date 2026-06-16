<template>
  <section id="hero" class="hero section dark-background">

    <!-- Background image -->
    <img
      :src="personalInfo.hero_image ? '/' + personalInfo.hero_image : '/assets/img/hero-img.png'"
      alt=""
      data-aos="fade-in"
    >

    <!-- Main content -->
    <div class="hero-content container d-flex flex-column align-items-center justify-content-center text-center">

      <!-- Greeting badge -->
      <div class="hero-badge" data-aos="fade-down" data-aos-delay="50">
        <span class="hero-badge__dot"></span>
        Available for work
      </div>

      <!-- Name -->
      <h2 class="hero-name">
        <span class="hero-name__prefix">I'm&nbsp;</span>
        <span class="hero-name__highlight">
          <span
            v-for="(char, idx) in nameChars"
            :key="idx"
            class="char-reveal"
            :style="{ animationDelay: `${0.1 + idx * 0.035}s` }"
          >{{ char === ' ' ? '\u00A0' : char }}</span>
        </span>
      </h2>

      <!-- Typed roles -->
      <p class="hero-typed-wrap" data-aos="fade-up" data-aos-delay="200">
        <span class="hero-role-prefix">A </span>
        <span
          class="typed hero-typed"
          data-typed-items="Backend Developer,Laravel and PHP Expert,RESTful API Builder,SaaS Architect,Problem Solver"
        ></span>
      </p>

      <!-- Short tagline -->
      <p class="hero-tagline" data-aos="fade-up" data-aos-delay="300">
        Building robust systems · Clean APIs · Scalable architectures
      </p>

      <!-- CTA buttons -->
      <div class="hero-cta" data-aos="fade-up" data-aos-delay="400">
        <a href="#about" class="hero-btn hero-btn--primary">
          <i class="bi bi-person-lines-fill me-2"></i>About Me
        </a>
        <a href="https://drive.google.com/file/d/1tUSqTA0OpZpJMyQ2YsC132RfSmYnycxh/view?usp=sharing" target="_blank" rel="noopener" class="hero-btn hero-btn--resume">
          <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download Resume
        </a>
        <a href="#contact" class="hero-btn hero-btn--outline">
          <i class="bi bi-envelope-fill me-2"></i>Hire Me
        </a>
      </div>

      <!-- Social Links -->
      <div class="hero-socials" data-aos="fade-up" data-aos-delay="450">
        <a href="https://www.linkedin.com/in/m0hamed-keshk" target="_blank" rel="noopener" title="LinkedIn" class="hero-social-icon linkedin">
          <i class="bi bi-linkedin"></i>
        </a>
        <a href="https://github.com/m7mdreda74" target="_blank" rel="noopener" title="GitHub" class="hero-social-icon github">
          <i class="bi bi-github"></i>
        </a>
        <a href="http://wa.me/201099670724" target="_blank" rel="noopener" title="WhatsApp" class="hero-social-icon whatsapp">
          <i class="bi bi-whatsapp"></i>
        </a>
        <a href="http://t.me/m7md_reda74" target="_blank" rel="noopener" title="Telegram" class="hero-social-icon telegram">
          <i class="bi bi-telegram"></i>
        </a>
        <a href="https://www.facebook.com/share/1JNP9vrxzq/" target="_blank" rel="noopener" title="Facebook" class="hero-social-icon facebook">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://www.instagram.com/m7md_reda74" target="_blank" rel="noopener" title="Instagram" class="hero-social-icon instagram">
          <i class="bi bi-instagram"></i>
        </a>
      </div>

      <!-- Scroll hint -->
      <div class="hero-scroll" data-aos="fade-up" data-aos-delay="500">
        <div class="hero-scroll__mouse">
          <div class="hero-scroll__wheel"></div>
        </div>
      </div>

    </div>
  </section>
</template>

<script>
import { onMounted, computed } from 'vue';

export default {
  name: 'HeroSection',
  props: {
    personalInfo: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const nameChars = computed(() => {
      if (!props.personalInfo || !props.personalInfo.name) return [];
      return props.personalInfo.name.split('');
    });

    onMounted(() => {
      setTimeout(() => {
        const selectTyped = document.querySelector('.typed');
        if (selectTyped && window.Typed) {
          let typed_strings = selectTyped.getAttribute('data-typed-items');
          typed_strings = typed_strings.split(',');
          new window.Typed('.typed', {
            strings: typed_strings,
            loop: true,
            typeSpeed: 80,
            backSpeed: 40,
            backDelay: 2200,
          });
        }
      }, 100);
    });

    return {
      nameChars,
    };
  },
};
</script>

<style scoped>
/* ═══════════════════════════════════════
   HERO SECTION — scoped styles
   All animations use transform/opacity only (GPU-composited)
   No filter:blur animations to avoid frame drops
   ═══════════════════════════════════════ */

/* ── Main content wrapper ── */
.hero-content {
  position: relative;
  z-index: 2;
  gap: 20px;
}

/* ── Badge ── */
.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 18px;
  border-radius: 999px;
  border: 1px solid rgba(144, 99, 255, 0.35);
  background: rgba(144, 99, 255, 0.08);
  color: #c084fc;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.5px;
  backdrop-filter: blur(8px);
  animation: fadeSlideDown 0.8s ease both;
}

.hero-badge__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #4ade80;
  box-shadow: 0 0 8px rgba(74, 222, 128, 0.7);
  animation: blink 1.8s ease-in-out infinite;
}

/* ── Name ── */
.hero-name {
  font-size: clamp(2rem, 5vw, 3.6rem);
  font-weight: 800;
  color: #ffffff !important;
  letter-spacing: -0.5px;
  line-height: 1.15;
}

.hero-name__prefix {
  display: inline-block;
  opacity: 0;
  transform: translateY(8px);
  animation: revealPrefix 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

/* Static gradient — no background-position animation */
.hero-name__highlight {
  color: #c084fc !important;
  display: inline-block;
}

.char-reveal {
  display: inline-block;
  opacity: 0;
  transform: translateY(8px) scale(0.95);
  animation: revealLetter 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

/* ── Typed line ── */
.hero-typed-wrap {
  font-size: clamp(1.1rem, 2.5vw, 1.5rem);
  font-weight: 500;
  color: #a4b3d6 !important;
  animation: fadeSlideUp 0.9s ease both 0.2s !important;
}

.hero-role-prefix {
  color: #64748b;
}

.hero-typed {
  color: #c084fc !important;
  font-weight: 600;
}

/* ── Tagline ── */
.hero-tagline {
  font-size: 13px;
  color: rgba(255, 255, 255, 0.75) !important;
  letter-spacing: 2px;
  text-transform: uppercase;
  text-shadow: 0 1px 8px rgba(0, 0, 0, 0.8);
  animation: fadeSlideUp 0.9s ease both 0.3s !important;
}

/* ── CTA Buttons ── */
.hero-cta {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  justify-content: center;
  animation: fadeSlideUp 0.9s ease both 0.4s !important;
}

.hero-btn {
  display: inline-flex;
  align-items: center;
  padding: 12px 28px;
  border-radius: 50px;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  letter-spacing: 0.3px;
}

.hero-btn--primary {
  background: linear-gradient(135deg, #9063ff, #c084fc);
  color: #fff;
  box-shadow: 0 6px 24px rgba(144, 99, 255, 0.45);
}

.hero-btn--primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(144, 99, 255, 0.65);
  color: #fff;
}

.hero-btn--resume {
  background: linear-gradient(135deg, #38bdf8, #0ea5e9);
  color: #fff;
  box-shadow: 0 6px 24px rgba(14, 165, 233, 0.4);
}

.hero-btn--resume:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(14, 165, 233, 0.6);
  color: #fff;
}

.hero-btn--outline {
  border: 1.5px solid rgba(144, 99, 255, 0.45);
  color: #c084fc;
  background: rgba(144, 99, 255, 0.06);
  backdrop-filter: blur(8px);
}

.hero-btn--outline:hover {
  background: rgba(144, 99, 255, 0.15);
  border-color: #9063ff;
  transform: translateY(-3px);
  color: #fff;
}

/* ── Hero Socials ── */
.hero-socials {
  display: flex;
  gap: 16px;
  justify-content: center;
  margin-top: 10px;
  margin-bottom: 5px;
}

.hero-social-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: 1px solid rgba(144, 99, 255, 0.35);
  background: rgba(144, 99, 255, 0.08);
  color: rgba(255, 255, 255, 0.75);
  font-size: 18px;
  text-decoration: none;
  backdrop-filter: blur(8px);
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
              color 0.25s ease,
              border-color 0.25s ease,
              background 0.25s ease,
              box-shadow 0.25s ease;
}

.hero-social-icon:hover {
  color: #fff;
  transform: translateY(-5px) scale(1.12);
}

.hero-social-icon.linkedin:hover  { background: #0a66c2; border-color: #0a66c2; box-shadow: 0 6px 20px rgba(10,102,194,0.45); }
.hero-social-icon.github:hover    { background: #333;    border-color: #555;    box-shadow: 0 6px 20px rgba(80,80,80,0.45); }
.hero-social-icon.whatsapp:hover  { background: #25d366; border-color: #25d366; box-shadow: 0 6px 20px rgba(37,211,102,0.45); }
.hero-social-icon.telegram:hover  { background: #229ed9; border-color: #229ed9; box-shadow: 0 6px 20px rgba(34,158,217,0.45); }
.hero-social-icon.facebook:hover  { background: #1877f2; border-color: #1877f2; box-shadow: 0 6px 20px rgba(24,119,242,0.45); }
.hero-social-icon.instagram:hover {
  background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
  border-color: #d6249f;
  box-shadow: 0 6px 20px rgba(214,36,159,0.45);
}

/* ── Scroll mouse indicator ── */
.hero-scroll {
  margin-top: 8px;
  animation: fadeSlideUp 0.9s ease both 0.5s !important;
}

.hero-scroll__mouse {
  width: 24px;
  height: 38px;
  border: 2px solid rgba(144, 99, 255, 0.45);
  border-radius: 12px;
  margin: 0 auto;
  display: flex;
  justify-content: center;
  padding-top: 5px;
}

.hero-scroll__wheel {
  width: 4px;
  height: 8px;
  background: #9063ff;
  border-radius: 2px;
  animation: scrollWheel 1.8s ease-in-out infinite;
}

/* ═══════════════════════════════════════
   KEYFRAMES — transform & opacity only
   ═══════════════════════════════════════ */
@keyframes fadeSlideDown {
  from { opacity: 0; transform: translateY(-16px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes blink {
  0%, 100% { opacity: 1; }
  50%       { opacity: 0.3; }
}

@keyframes scrollWheel {
  0%   { transform: translateY(0); opacity: 1; }
  100% { transform: translateY(12px); opacity: 0; }
}

@keyframes revealPrefix {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes revealLetter {
  from {
    opacity: 0;
    transform: translateY(8px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
