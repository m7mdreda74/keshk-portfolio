<template>
  <section id="resume" class="resume section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Resume</h2>
      <p>My academic credentials and professional work experience history timeline.</p>
    </div><!-- End Section Title -->

    <div class="container">
      <div class="row">
        <!-- Education Column -->
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
          <h3 class="resume-title">Education</h3>
          <div v-for="item in educationItems" :key="item.id" class="resume-item">
            <h4>{{ item.title }}</h4>
            <h5>{{ item.duration }}</h5>
            <p><em>{{ item.organization }}</em></p>
            <p>{{ item.description }}</p>
          </div>
        </div>

        <!-- Experience Column -->
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
          <h3 class="resume-title">Professional Experience</h3>
          <div v-for="item in experienceItems" :key="item.id" class="resume-item">
            <h4>{{ item.title }}</h4>
            <h5>{{ item.duration }}</h5>
            <p><em>{{ item.organization }}</em></p>
            <p>{{ item.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'ResumeSection',
  props: {
    resumeItems: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const educationItems = computed(() => {
      return props.resumeItems.filter(item => item.type === 'education');
    });

    const experienceItems = computed(() => {
      return props.resumeItems.filter(item => item.type === 'experience');
    });

    return {
      educationItems,
      experienceItems,
    };
  },
};
</script>
