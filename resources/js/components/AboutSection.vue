<template>
  <section id="about" class="about section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div class="col-md-6">
          <div class="row justify-content-between gy-4">
            <div class="col-lg-5 d-flex justify-content-center">
              <!-- Profile image with animated glow ring -->
              <div class="profile-img-wrapper">
                <div class="profile-ring"></div>
                <img
                  :src="personalInfo.profile_image ? '/' + personalInfo.profile_image : '/assets/img/profile-img.png'"
                  class="profile-img"
                  alt="Profile Photo"
                >
              </div>
            </div>
            <div class="col-lg-7 about-info">
              <p><strong>Name: </strong> <span>{{ personalInfo.name }}</span></p>
              <p><strong>Profile: </strong> <span>{{ personalInfo.profile }}</span></p>
              <p><strong>Email: </strong> <a :href="'mailto:' + personalInfo.email" class="about-email-link">{{ personalInfo.email }}</a></p>
              <p><strong>Phone: </strong> <a :href="'tel:' + personalInfo.phone" class="about-phone-link">{{ personalInfo.phone }}</a></p>
              <div class="mt-4">
                <a href="https://drive.google.com/file/d/1dcQc63AwdEhMHx2B2CPoxNplwJoF8nLf/view?usp=sharing" target="_blank" rel="noopener" class="about-resume-btn">
                  <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download Resume
                </a>
              </div>
            </div>
          </div>

          <div class="skills-content skills-animation">
            <h5>Skills</h5>
            <div v-for="skill in skills" :key="skill.id" class="progress">
              <span class="skill"><span>{{ skill.name }}</span> <i class="val">{{ skill.percentage }}%</i></span>
              <div class="progress-bar-wrap">
                <div class="progress-bar" role="progressbar" :style="{ width: animateWidth ? skill.percentage + '%' : '0%' }" :aria-valuenow="skill.percentage" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="about-me">
            <h4>About me</h4>
            <p v-for="(para, index) in paragraphs" :key="index">
              {{ para }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, onMounted, computed } from 'vue';

export default {
  name: 'AboutSection',
  props: {
    personalInfo: {
      type: Object,
      required: true,
    },
    skills: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const animateWidth = ref(false);

    const paragraphs = computed(() => {
      if (!props.personalInfo.bio) return [];
      return props.personalInfo.bio.split('\n');
    });

    onMounted(() => {
      // Trigger progress bar expand transitions
      setTimeout(() => {
        animateWidth.value = true;
      }, 100);
    });

    return {
      animateWidth,
      paragraphs,
    };
  },
};
</script>

<style scoped>
/* ── Progress bar ── */
.progress-bar {
  transition: width 1.5s ease-in-out;
}

/* ── Mailto & Phone links ── */
.about-email-link,
.about-phone-link {
  color: #c084fc;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: color 0.25s ease, border-color 0.25s ease;
}

.about-email-link:hover,
.about-phone-link:hover {
  color: #9063ff;
  border-bottom-color: #9063ff;
}

/* ── Resume button ── */
.about-resume-btn {
  display: inline-flex;
  align-items: center;
  padding: 10px 24px;
  border-radius: 50px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  background: linear-gradient(135deg, #9063ff, #c084fc);
  color: #fff;
  box-shadow: 0 4px 15px rgba(144, 99, 255, 0.3);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.about-resume-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(144, 99, 255, 0.5);
  color: #fff;
}

/* ── Profile image wrapper ── */
.profile-img-wrapper {
  position: relative;
  width: 200px;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* The actual image — circular */
.profile-img {
  width: 176px;
  height: 176px;
  border-radius: 50%;
  object-fit: cover;
  object-position: center top;
  position: relative;
  z-index: 2;
  border: 3px solid rgba(144, 99, 255, 0.4);
  box-shadow: 0 0 24px rgba(144, 99, 255, 0.35);
  transition: box-shadow 0.4s ease, transform 0.4s ease;
}

.profile-img:hover {
  transform: scale(1.04);
  box-shadow: 0 0 40px rgba(144, 99, 255, 0.6);
}

/* Rotating gradient ring behind the image */
.profile-ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  z-index: 1;

  /* conic-gradient that spins */
  background: conic-gradient(
    from 0deg,
    #9063ff,
    #c084fc,
    #38bdf8,
    #818cf8,
    #9063ff
  );

  animation: spinRing 4s linear infinite;

  /* mask: only show the outer ring (~12px), hide the centre */
  -webkit-mask:
    radial-gradient(farthest-side, transparent calc(100% - 4px), white calc(100% - 3px));
  mask:
    radial-gradient(farthest-side, transparent calc(100% - 4px), white calc(100% - 3px));
}

/* Soft glow layer that pulses */
.profile-ring::after {
  content: '';
  position: absolute;
  inset: -4px;
  border-radius: 50%;
  background: conic-gradient(
    from 0deg,
    #9063ff,
    #c084fc,
    #38bdf8,
    #818cf8,
    #9063ff
  );
  filter: blur(10px);
  opacity: 0.45;
  z-index: 0;
  animation: spinRing 4s linear infinite, pulseOpacity 3s ease-in-out infinite;
}

/* ── Keyframes ── */
@keyframes spinRing {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

@keyframes pulseOpacity {
  0%, 100% { opacity: 0.35; }
  50%       { opacity: 0.65; }
}
</style>
