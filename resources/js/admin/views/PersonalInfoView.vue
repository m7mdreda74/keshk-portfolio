<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-person-badge-fill" style="color:var(--accent);margin-right:.5rem;"></i>المعلومات الشخصية</h3>
      <button class="btn btn-primary" @click="save" :disabled="saving" id="save-personal-btn">
        <span v-if="saving" class="spinner"></span>
        <i v-else class="bi bi-floppy-fill"></i>
        {{ saving ? 'جاري الحفظ...' : 'حفظ التغييرات' }}
      </button>
    </div>

    <div v-if="loading" class="admin-card" style="text-align:center;padding:3rem;">
      <div class="spinner"></div>
    </div>

    <div v-else class="admin-card">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">الاسم الكامل</label>
          <input v-model="form.name" class="form-control" placeholder="محمد رضا كشك" id="pi-name">
        </div>
        <div class="form-group">
          <label class="form-label">المسمى الوظيفي</label>
          <input v-model="form.profile" class="form-control" placeholder="Backend Developer" id="pi-profile">
        </div>
        <div class="form-group">
          <label class="form-label">البريد الإلكتروني</label>
          <input v-model="form.email" class="form-control" type="email" placeholder="example@gmail.com" id="pi-email">
        </div>
        <div class="form-group">
          <label class="form-label">رقم الهاتف</label>
          <input v-model="form.phone" class="form-control" placeholder="+20 1xx xxx xxxx" id="pi-phone">
        </div>
        <div class="form-group form-full">
          <label class="form-label">نبذة شخصية (Bio)</label>
          <textarea v-model="form.bio" class="form-control" rows="5" placeholder="اكتب نبذة عن نفسك..." id="pi-bio"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">رابط صورة البروفايل</label>
          <input v-model="form.profile_image" class="form-control" placeholder="assets/img/profile.jpg" id="pi-profile-image">
          <div v-if="form.profile_image" class="img-preview">
            <img :src="form.profile_image" alt="Profile" @error="e => e.target.style.display='none'">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">رابط صورة الخلفية (Hero)</label>
          <input v-model="form.hero_image" class="form-control" placeholder="assets/img/hero.jpg" id="pi-hero-image">
          <div v-if="form.hero_image" class="img-preview">
            <img :src="form.hero_image" alt="Hero" @error="e => e.target.style.display='none'">
          </div>
        </div>
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
      name: '', profile: '', email: '', phone: '', bio: '', profile_image: '', hero_image: ''
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
        showToast('تم حفظ المعلومات الشخصية بنجاح ✓');
      } catch (e) {
        showToast(e.response?.data?.message || 'حدث خطأ أثناء الحفظ', 'error');
      } finally {
        saving.value = false;
      }
    };

    return { loading, saving, form, save };
  }
};
</script>

<style scoped>
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
