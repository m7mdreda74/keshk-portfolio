<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-share-fill" style="color:var(--accent);margin-right:.5rem;"></i>Social & Footer</h3>
      <button class="btn btn-primary" @click="save" :disabled="saving" id="save-social-btn">
        <span v-if="saving" class="spinner"></span>
        <i v-else class="bi bi-floppy-fill"></i>
        {{ saving ? 'Saving...' : 'Save Changes' }}
      </button>
    </div>

    <div v-if="loading" class="admin-card" style="text-align:center;padding:3rem;">
      <div class="spinner"></div>
    </div>

    <template v-else>
      <!-- Social Links -->
      <div class="admin-card">
        <h4 class="sub-title"><i class="bi bi-people-fill"></i> Social Media Links</h4>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label"><i class="bi bi-linkedin" style="color:#0a66c2;margin-right:.4rem;"></i>LinkedIn</label>
            <input v-model="form.linkedin" class="form-control" placeholder="https://linkedin.com/in/..." id="sl-linkedin">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-github" style="color:#e8eaf6;margin-right:.4rem;"></i>GitHub</label>
            <input v-model="form.github" class="form-control" placeholder="https://github.com/..." id="sl-github">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-whatsapp" style="color:#25d366;margin-right:.4rem;"></i>WhatsApp</label>
            <input v-model="form.whatsapp" class="form-control" placeholder="https://wa.me/201..." id="sl-whatsapp">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-telegram" style="color:#0088cc;margin-right:.4rem;"></i>Telegram</label>
            <input v-model="form.telegram" class="form-control" placeholder="https://t.me/..." id="sl-telegram">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-facebook" style="color:#1877f2;margin-right:.4rem;"></i>Facebook</label>
            <input v-model="form.facebook" class="form-control" placeholder="https://facebook.com/..." id="sl-facebook">
          </div>
          <div class="form-group">
            <label class="form-label"><i class="bi bi-instagram" style="color:#e1306c;margin-right:.4rem;"></i>Instagram</label>
            <input v-model="form.instagram" class="form-control" placeholder="https://instagram.com/..." id="sl-instagram">
          </div>
        </div>
      </div>

      <!-- Footer Copyright -->
      <div class="admin-card">
        <h4 class="sub-title"><i class="bi bi-c-circle-fill"></i> Footer Copyright</h4>
        <div class="form-group">
          <label class="form-label">Copyright Text</label>
          <input v-model="form.copyright" class="form-control" placeholder="© 2026 Mohamed Reda Keshk. All Rights Reserved." id="sl-copyright">
          <small class="hint">Shown at the bottom of the portfolio page.</small>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import { ref, onMounted, inject } from 'vue';
import axios from 'axios';

export default {
  name: 'SocialLinksView',
  setup() {
    const showToast = inject('showToast');
    const loading = ref(true);
    const saving = ref(false);
    const form = ref({
      linkedin: '', github: '', whatsapp: '', telegram: '', facebook: '', instagram: '', copyright: ''
    });

    onMounted(async () => {
      try {
        const res = await axios.get('/api/admin/personal-info');
        if (res.data) {
          const { linkedin, github, whatsapp, telegram, facebook, instagram, copyright } = res.data;
          Object.assign(form.value, { linkedin, github, whatsapp, telegram, facebook, instagram, copyright });
        }
      } catch {}
      loading.value = false;
    });

    const save = async () => {
      saving.value = true;
      try {
        // Fetch current data first to merge (avoid overwriting other fields)
        const current = await axios.get('/api/admin/personal-info');
        const merged = { ...current.data, ...form.value };
        await axios.put('/api/admin/personal-info', merged);
        showToast('Social links & footer saved ✓');
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
.sub-title {
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
.hint {
  color: var(--text-muted);
  font-size: 0.78rem;
  margin-top: 0.3rem;
  display: block;
}
@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
</style>
