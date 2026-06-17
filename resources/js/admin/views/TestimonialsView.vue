<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-chat-quote-fill" style="color:var(--accent);margin-right:.5rem;"></i>التوصيات</h3>
      <button class="btn btn-primary" @click="openModal()" id="add-testimonial-btn">
        <i class="bi bi-plus-lg"></i> إضافة توصية
      </button>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div v-else class="testimonials-grid">
      <div v-if="!items.length" class="admin-card">
        <div class="empty-state"><i class="bi bi-chat-quote"></i><p>لا توجد توصيات بعد</p></div>
      </div>

      <div v-for="item in items" :key="item.id" class="testimonial-card">
        <div class="t-stars">
          <span v-for="n in 5" :key="n" :style="{ color: n <= item.stars ? 'var(--warning)' : 'var(--border)' }">★</span>
        </div>
        <p class="t-quote">"{{ item.quote }}"</p>
        <div class="t-person">
          <div v-if="item.image" class="t-avatar">
            <img :src="item.image" :alt="item.name" @error="e => e.target.style.display='none'">
          </div>
          <div v-else class="t-avatar t-avatar-placeholder">
            <i class="bi bi-person-fill"></i>
          </div>
          <div>
            <strong>{{ item.name }}</strong>
            <span>{{ item.role }}</span>
          </div>
        </div>
        <div class="t-actions">
          <button class="btn btn-secondary btn-sm" @click="openModal(item)"><i class="bi bi-pencil-fill"></i> تعديل</button>
          <button class="btn btn-danger btn-sm" @click="confirmDelete(item)"><i class="bi bi-trash-fill"></i> حذف</button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal-box">
          <div class="modal-header">
            <span class="modal-title">{{ editing ? 'تعديل توصية' : 'إضافة توصية جديدة' }}</span>
            <button class="modal-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">الاسم</label>
              <input v-model="form.name" class="form-control" placeholder="اسم الشخص" id="testimonial-name-input">
            </div>
            <div class="form-group">
              <label class="form-label">المسمى الوظيفي</label>
              <input v-model="form.role" class="form-control" placeholder="CEO at XYZ" id="testimonial-role-input">
            </div>
            <div class="form-group">
              <label class="form-label">التوصية</label>
              <textarea v-model="form.quote" class="form-control" rows="4" id="testimonial-quote-input"></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">التقييم: <strong style="color:var(--warning)">{{ '★'.repeat(form.stars) }}</strong> ({{ form.stars }}/5)</label>
              <input v-model.number="form.stars" type="range" min="1" max="5" step="1">
            </div>
            <div class="form-group">
              <label class="form-label">رابط الصورة (اختياري)</label>
              <input v-model="form.image" class="form-control" placeholder="https://..." id="testimonial-image-input">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">إلغاء</button>
            <button class="btn btn-primary" @click="saveItem" :disabled="saving" id="testimonial-save-btn">
              <span v-if="saving" class="spinner"></span>
              <i v-else class="bi bi-floppy-fill"></i>
              {{ saving ? 'جاري الحفظ...' : 'حفظ' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Delete Confirm -->
    <Transition name="fade">
      <div v-if="deleteTarget" class="modal-overlay" @click.self="deleteTarget = null">
        <div class="modal-box" style="max-width:400px;">
          <div class="modal-header">
            <span class="modal-title" style="color:var(--danger)"><i class="bi bi-exclamation-triangle-fill"></i> تأكيد الحذف</span>
            <button class="modal-close" @click="deleteTarget = null"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <p style="color:var(--text-muted)">حذف توصية <strong style="color:var(--text-primary)">"{{ deleteTarget?.name }}"</strong>؟</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">إلغاء</button>
            <button class="btn btn-danger" @click="doDelete" id="testimonial-delete-confirm-btn"><i class="bi bi-trash-fill"></i> حذف</button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import { ref, onMounted, inject } from 'vue';
import axios from 'axios';

export default {
  name: 'TestimonialsView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]); const loading = ref(true); const saving = ref(false);
    const showModal = ref(false); const editing = ref(null); const deleteTarget = ref(null);
    const form = ref({ name: '', role: '', quote: '', stars: 5, image: '' });

    const load = async () => { try { items.value = (await axios.get('/api/admin/testimonials')).data; } catch {} finally { loading.value = false; } };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      form.value = item
        ? { name: item.name, role: item.role, quote: item.quote, stars: item.stars, image: item.image || '' }
        : { name: '', role: '', quote: '', stars: 5, image: '' };
      showModal.value = true;
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        if (editing.value) {
          const res = await axios.put(`/api/admin/testimonials/${editing.value.id}`, form.value);
          const idx = items.value.findIndex(i => i.id === editing.value.id);
          if (idx !== -1) items.value[idx] = res.data;
        } else {
          items.value.push((await axios.post('/api/admin/testimonials', form.value)).data);
        }
        showModal.value = false;
        showToast(editing.value ? 'تم التعديل بنجاح ✓' : 'تمت الإضافة بنجاح ✓');
      } catch (e) { showToast(e.response?.data?.message || 'حدث خطأ', 'error'); }
      finally { saving.value = false; }
    };

    const confirmDelete = (item) => { deleteTarget.value = item; };
    const doDelete = async () => {
      try {
        await axios.delete(`/api/admin/testimonials/${deleteTarget.value.id}`);
        items.value = items.value.filter(i => i.id !== deleteTarget.value.id);
        deleteTarget.value = null;
        showToast('تم الحذف بنجاح');
      } catch { showToast('حدث خطأ', 'error'); }
    };

    return { items, loading, saving, showModal, editing, deleteTarget, form, openModal, saveItem, confirmDelete, doDelete };
  }
};
</script>

<style scoped>
.testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }
.testimonial-card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 1.5rem;
  transition: border-color 0.2s;
}
.testimonial-card:hover { border-color: var(--accent); }
.t-stars { font-size: 1.1rem; margin-bottom: 0.75rem; letter-spacing: 2px; }
.t-quote { color: var(--text-muted); font-size: 0.88rem; line-height: 1.6; margin-bottom: 1rem; font-style: italic; }
.t-person { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
.t-avatar { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 2px solid var(--border); }
.t-avatar img { width: 100%; height: 100%; object-fit: cover; }
.t-avatar-placeholder { background: var(--bg-input); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.1rem; }
.t-person strong { display: block; font-size: 0.9rem; }
.t-person span { font-size: 0.78rem; color: var(--text-muted); }
.t-actions { display: flex; gap: 0.5rem; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
