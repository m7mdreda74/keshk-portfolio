<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-person-badge-fill" style="color:var(--accent);margin-right:.5rem;"></i>Personal Info</h3>
      <button class="btn btn-primary" @click="save" :disabled="saving" id="save-personal-btn">
        <span v-if="saving" class="spinner"></span>
        <i v-else class="bi bi-floppy-fill"></i>
        {{ saving ? 'Saving...' : 'Save Changes' }}
      </button>
    </div>

    <div v-if="loading" class="admin-card" style="text-align:center;padding:3rem;">
      <div class="spinner"></div>
    </div>

    <div v-else class="admin-card">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Full Name</label>
          <input v-model="form.name" class="form-control" placeholder="Mohamed Reda Keshk" id="pi-name">
        </div>
        <div class="form-group">
          <label class="form-label">Job Title</label>
          <input v-model="form.profile" class="form-control" placeholder="Backend Developer" id="pi-profile">
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input v-model="form.email" class="form-control" type="email" placeholder="example@gmail.com" id="pi-email">
        </div>
        <div class="form-group">
          <label class="form-label">Phone</label>
          <input v-model="form.phone" class="form-control" placeholder="+20 1xx xxx xxxx" id="pi-phone">
        </div>
        <div class="form-group form-full">
          <label class="form-label">Bio</label>
          <textarea v-model="form.bio" class="form-control" rows="5" placeholder="Write something about yourself..." id="pi-bio"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Profile Image URL</label>
          <input v-model="form.profile_image" class="form-control" placeholder="assets/img/profile.jpg" id="pi-profile-image">
          <div v-if="form.profile_image" class="img-preview">
            <img :src="form.profile_image" alt="Profile" @error="e => e.target.style.display='none'">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Hero Background Image URL</label>
          <input v-model="form.hero_image" class="form-control" placeholder="assets/img/hero.jpg" id="pi-hero-image">
          <div v-if="form.hero_image" class="img-preview">
            <img :src="form.hero_image" alt="Hero" @error="e => e.target.style.display='none'">
          </div>
        </div>
        <div class="form-group form-full">
          <label class="form-label">Resume / CV Download Link</label>
          <input v-model="form.resume_link" class="form-control" placeholder="https://... or assets/files/resume.pdf" id="pi-resume-link">
          <small style="color:var(--text-muted);font-size:0.78rem;margin-top:0.3rem;display:block;">This link is used by the "Download Resume" button on your portfolio.</small>
        </div>
      </div>
    </div>

    <!-- Social Links -->
    <div v-if="!loading" class="admin-card">
      <h4 class="sub-section-title"><i class="bi bi-share-fill"></i> Social Media Links</h4>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label"><i class="bi bi-linkedin" style="color:#0a66c2;margin-right:.4rem;"></i>LinkedIn</label>
          <input v-model="form.linkedin" class="form-control" placeholder="https://linkedin.com/in/..." id="pi-linkedin">
        </div>
        <div class="form-group">
          <label class="form-label"><i class="bi bi-github" style="color:#e8eaf6;margin-right:.4rem;"></i>GitHub</label>
          <input v-model="form.github" class="form-control" placeholder="https://github.com/..." id="pi-github">
        </div>
        <div class="form-group">
          <label class="form-label"><i class="bi bi-whatsapp" style="color:#25d366;margin-right:.4rem;"></i>WhatsApp</label>
          <input v-model="form.whatsapp" class="form-control" placeholder="https://wa.me/20..." id="pi-whatsapp">
        </div>
        <div class="form-group">
          <label class="form-label"><i class="bi bi-telegram" style="color:#0088cc;margin-right:.4rem;"></i>Telegram</label>
          <input v-model="form.telegram" class="form-control" placeholder="https://t.me/..." id="pi-telegram">
        </div>
        <div class="form-group">
          <label class="form-label"><i class="bi bi-facebook" style="color:#1877f2;margin-right:.4rem;"></i>Facebook</label>
          <input v-model="form.facebook" class="form-control" placeholder="https://facebook.com/..." id="pi-facebook">
        </div>
        <div class="form-group">
          <label class="form-label"><i class="bi bi-instagram" style="color:#e1306c;margin-right:.4rem;"></i>Instagram</label>
          <input v-model="form.instagram" class="form-control" placeholder="https://instagram.com/..." id="pi-instagram">
        </div>
      </div>
    </div>

    <!-- Footer Copyright -->
    <div v-if="!loading" class="admin-card">
      <h4 class="sub-section-title"><i class="bi bi-c-circle-fill"></i> Footer Copyright</h4>
      <div class="form-group">
        <label class="form-label">Copyright Text</label>
        <input v-model="form.copyright" class="form-control" placeholder="© 2026 Mohamed Reda Keshk. All Rights Reserved." id="pi-copyright">
        <small style="color:var(--text-muted);font-size:0.78rem;margin-top:0.3rem;display:block;">Shown at the bottom of the portfolio page.</small>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, inject } from 'vue';
import axios from 'axios';

export default {
  name: 'PersonalInfoView',
  setup() {
    const showToast = inject('showToast');
    const loading = ref(true);
    const saving = ref(false);
    const form = ref({
      name: '', profile: '', email: '', phone: '', bio: '',
      profile_image: '', hero_image: '', resume_link: '',
      linkedin: '', github: '', whatsapp: '', telegram: '', facebook: '', instagram: '',
      copyright: ''
    });

    onMounted(async () => {
      try {
        const res = await axios.get('/api/admin/personal-info');
        if (res.data) Object.assign(form.value, res.data);
      } catch {}
      loading.value = false;
    });

    const save = async () => {
      saving.value = true;
      try {
        await axios.put('/api/admin/personal-info', form.value);
        showToast('Personal info saved successfully ✓');
      } catch (e) {
        showToast(e.response?.data?.message || 'An error occurred', 'error');
      } finally {
        saving.value = false;
      }
    };

    return { loading, saving, form, save };
  }
};
</script>

<style scoped>
.sub-section-title {
  font-size: 0.92rem;
  font-weight: 600;
  color: var(--accent-light);
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0 1.5rem;
}
.form-full { grid-column: 1 / -1; }
.img-preview {
  margin-top: 0.75rem;
  width: 80px; height: 80px;
  border-radius: var(--radius-sm);
  overflow: hidden;
  border: 1px solid var(--border);
}
.img-preview img { width: 100%; height: 100%; object-fit: cover; }
@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
</style>
