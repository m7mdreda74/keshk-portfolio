<template>
  <section id="contact" class="contact section">
    <img :src="'/assets/img/cnt-bg.png'" alt="" class="contact-bg" data-aos="fade-in">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Contact</h2>
      <p>Have a question or project idea? Feel free to send a message!</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="info-wrap" data-aos="fade-up" data-aos-delay="200">
        <div class="row gy-5">
          <div class="col-lg-4">
            <div class="info-item d-flex align-items-center">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Address</h3>
                <p>Mansoura, Egypt</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-4">
            <div class="info-item d-flex align-items-center">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p><a :href="'tel:' + personalInfo.phone" class="contact-phone-link">{{ personalInfo.phone }}</a></p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-4">
            <div class="info-item d-flex align-items-center">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email Us</h3>
                <p><a :href="'mailto:' + personalInfo.email" class="contact-email-link">{{ personalInfo.email }}</a></p>
              </div>
            </div>
          </div><!-- End Info Item -->
        </div>
      </div>

      <form @submit.prevent="handleSubmit" class="php-email-form" data-aos="fade-up" data-aos-delay="300">
        <div class="row gy-4">
          <div class="col-md-6">
            <input v-model="form.name" type="text" name="name" class="form-control" placeholder="Your Name" required>
          </div>

          <div class="col-md-6">
            <input v-model="form.email" type="email" class="form-control" name="email" placeholder="Your Email" required>
          </div>

          <div class="col-md-12">
            <input v-model="form.subject" type="text" class="form-control" name="subject" placeholder="Subject" required>
          </div>

          <div class="col-md-12">
            <textarea v-model="form.message" class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
          </div>

          <div class="col-md-12 text-center">
            <div v-if="loading" class="loading d-block">Loading...</div>
            <div v-if="errorMessage" class="error-message d-block">{{ errorMessage }}</div>
            <div v-if="successMessage" class="sent-message d-block">{{ successMessage }}</div>

            <button type="submit" :disabled="loading">Send Message</button>
          </div>
        </div>
      </form><!-- End Contact Form -->
    </div>
  </section>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
  name: 'ContactSection',
  props: {
    personalInfo: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const form = ref({
      name: '',
      email: '',
      subject: '',
      message: '',
    });
    const loading = ref(false);
    const errorMessage = ref('');
    const successMessage = ref('');

    const handleSubmit = async () => {
      loading.value = true;
      errorMessage.value = '';
      successMessage.value = '';

      try {
        const response = await axios.post('/api/contact', form.value);
        successMessage.value = response.data.message || 'Your message has been sent. Thank you!';
        // Reset form
        form.value = {
          name: '',
          email: '',
          subject: '',
          message: '',
        };
      } catch (error) {
        console.error('Submit contact message failed:', error);
        if (error.response && error.response.data && error.response.data.message) {
          errorMessage.value = error.response.data.message;
        } else {
          errorMessage.value = 'An error occurred while sending your message. Please try again.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      errorMessage,
      successMessage,
      handleSubmit,
    };
  },
};
</script>

<style scoped>
.contact-email-link,
.contact-phone-link {
  color: #c084fc;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: color 0.25s ease, border-color 0.25s ease;
}

.contact-email-link:hover,
.contact-phone-link:hover {
  color: #9063ff;
  border-bottom-color: #9063ff;
}

/* ── Contact Section Background & Overlay ── */
.contact {
  position: relative;
}

.contact-bg {
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

.contact::before {
  content: "";
  background: rgba(6, 6, 6, 0.75);
  position: absolute;
  inset: 0;
  z-index: 2;
}

.contact .container {
  position: relative;
  z-index: 3;
}
</style>
