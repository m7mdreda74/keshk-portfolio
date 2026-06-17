<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-graph-up-arrow" style="color:var(--accent);margin-right:.5rem;"></i>الإحصائيات</h3>
      <button class="btn btn-primary" @click="openModal()" id="add-stat-btn">
        <i class="bi bi-plus-lg"></i> إضافة إحصائية
      </button>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div class="admin-card" v-else>
      <div v-if="!items.length" class="empty-state">
        <i class="bi bi-bar-chart-steps"></i><p>لا توجد إحصائيات بعد</p>
      </div>
      <table v-else class="admin-table">
        <thead><tr><th>#</th><th>التسمية</th><th>العدد</th><th>إجراءات</th></tr></thead>
        <tbody>
          <tr v-for="(item, idx) in items" :key="item.id">
            <td>{{ idx + 1 }}</td>
            <td>{{ item.label }}</td>
            <td><span class="badge badge-accent">{{ item.count }}</span></td>
            <td>
              <div class="action-btns">
                <button class="btn btn-secondary btn-sm" @click="openModal(item)"><i class="bi bi-pencil-fill"></i></button>
                <button class="btn btn-danger btn-sm" @click="confirmDelete(item)"><i class="bi bi-trash-fill"></i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal-box">
          <div class="modal-header">
            <span class="modal-title">{{ editing ? 'تعديل إحصائية' : 'إضافة إحصائية جديدة' }}</span>
            <button class="modal-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">التسمية</label>
              <input v-model="form.label" class="form-control" placeholder="مثال: مشاريع منجزة" id="stat-label-input">
            </div>
            <div class="form-group">
              <label class="form-label">العدد</label>
              <input v-model.number="form.count" class="form-control" type="number" min="0" placeholder="50" id="stat-count-input">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">إلغاء</button>
            <button class="btn btn-primary" @click="saveItem" :disabled="saving" id="stat-save-btn">
              <span v-if="saving" class="spinner"></span>
              <i v-else class="bi bi-floppy-fill"></i>
              {{ saving ? 'جاري الحفظ...' : 'حفظ' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <Transition name="fade">
      <div v-if="deleteTarget" class="modal-overlay" @click.self="deleteTarget = null">
        <div class="modal-box" style="max-width:400px;">
          <div class="modal-header">
            <span class="modal-title" style="color:var(--danger)"><i class="bi bi-exclamation-triangle-fill"></i> تأكيد الحذف</span>
            <button class="modal-close" @click="deleteTarget = null"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <p style="color:var(--text-muted)">حذف إحصائية <strong style="color:var(--text-primary)">"{{ deleteTarget?.label }}"</strong>؟</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">إلغاء</button>
            <button class="btn btn-danger" @click="doDelete" id="stat-delete-confirm-btn"><i class="bi bi-trash-fill"></i> حذف</button>
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
  name: 'StatsView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]); const loading = ref(true); const saving = ref(false);
    const showModal = ref(false); const editing = ref(null); const deleteTarget = ref(null);
    const form = ref({ label: '', count: 0 });

    const load = async () => { try { items.value = (await axios.get('/api/admin/stats')).data; } catch {} finally { loading.value = false; } };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      form.value = item ? { label: item.label, count: item.count } : { label: '', count: 0 };
      showModal.value = true;
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        if (editing.value) {
          const res = await axios.put(`/api/admin/stats/${editing.value.id}`, form.value);
          const idx = items.value.findIndex(i => i.id === editing.value.id);
          if (idx !== -1) items.value[idx] = res.data;
        } else {
          items.value.push((await axios.post('/api/admin/stats', form.value)).data);
        }
        showModal.value = false;
        showToast(editing.value ? 'تم التعديل بنجاح ✓' : 'تمت الإضافة بنجاح ✓');
      } catch (e) { showToast(e.response?.data?.message || 'حدث خطأ', 'error'); }
      finally { saving.value = false; }
    };

    const confirmDelete = (item) => { deleteTarget.value = item; };
    const doDelete = async () => {
      try {
        await axios.delete(`/api/admin/stats/${deleteTarget.value.id}`);
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
.action-btns { display: flex; gap: 0.4rem; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
