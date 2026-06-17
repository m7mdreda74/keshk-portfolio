<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-gear-wide-connected" style="color:var(--accent);margin-right:.5rem;"></i>Services</h3>
      <button class="btn btn-primary" @click="openModal()" id="add-service-btn">
        <i class="bi bi-plus-lg"></i> Add Service
      </button>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div v-else class="services-grid">
      <div v-if="!items.length" class="admin-card">
        <div class="empty-state"><i class="bi bi-gear"></i><p>No services added yet</p></div>
      </div>

      <div v-for="item in items" :key="item.id" class="service-card">
        <div class="service-icon-wrap">
          <i :class="['bi', item.icon]"></i>
        </div>
        <div class="service-info">
          <h4>{{ item.title }}</h4>
          <p>{{ item.description }}</p>
          <span class="icon-hint">Icon: <code>{{ item.icon }}</code></span>
        </div>
        <div class="service-actions">
          <button class="btn btn-secondary btn-sm" @click="openModal(item)"><i class="bi bi-pencil-fill"></i></button>
          <button class="btn btn-danger btn-sm" @click="confirmDelete(item)"><i class="bi bi-trash-fill"></i></button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal-box">
          <div class="modal-header">
            <span class="modal-title">{{ editing ? 'Edit Service' : 'Add New Service' }}</span>
            <button class="modal-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Service Title</label>
              <input v-model="form.title" class="form-control" placeholder="Web Development" id="service-title-input">
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea v-model="form.description" class="form-control" rows="4" id="service-desc-input"></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Bootstrap Icon Class <a href="https://icons.getbootstrap.com/" target="_blank" style="color:var(--accent);font-size:0.8rem;margin-left:.5rem;">Browse Icons ↗</a></label>
              <input v-model="form.icon" class="form-control" placeholder="bi-code-slash" id="service-icon-input">
              <div v-if="form.icon" style="margin-top:.75rem;color:var(--text-muted);">
                Preview: <i :class="['bi', form.icon]" style="font-size:1.5rem;color:var(--accent);margin-left:.5rem;"></i>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">Cancel</button>
            <button class="btn btn-primary" @click="saveItem" :disabled="saving" id="service-save-btn">
              <span v-if="saving" class="spinner"></span>
              <i v-else class="bi bi-floppy-fill"></i>
              {{ saving ? 'Saving...' : 'Save' }}
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
            <span class="modal-title" style="color:var(--danger)"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete</span>
            <button class="modal-close" @click="deleteTarget = null"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <p style="color:var(--text-muted)">Are you sure you want to delete <strong style="color:var(--text-primary)">"{{ deleteTarget?.title }}"</strong>?</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">Cancel</button>
            <button class="btn btn-danger" @click="doDelete" id="service-delete-confirm-btn">
              <i class="bi bi-trash-fill"></i> Delete
            </button>
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
  name: 'ServicesView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);
    const editing = ref(null);
    const deleteTarget = ref(null);
    const form = ref({ title: '', description: '', icon: '' });

    const load = async () => {
      try { items.value = (await axios.get('/api/admin/services')).data; }
      catch {} finally { loading.value = false; }
    };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      form.value = item ? { title: item.title, description: item.description, icon: item.icon } : { title: '', description: '', icon: '' };
      showModal.value = true;
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        if (editing.value) {
          const res = await axios.put(`/api/admin/services/${editing.value.id}`, form.value);
          const idx = items.value.findIndex(i => i.id === editing.value.id);
          if (idx !== -1) items.value[idx] = res.data;
        } else {
          items.value.push((await axios.post('/api/admin/services', form.value)).data);
        }
        showModal.value = false;
        showToast(editing.value ? 'Service updated successfully ✓' : 'Service added successfully ✓');
      } catch (e) { showToast(e.response?.data?.message || 'An error occurred', 'error'); }
      finally { saving.value = false; }
    };

    const confirmDelete = (item) => { deleteTarget.value = item; };
    const doDelete = async () => {
      try {
        await axios.delete(`/api/admin/services/${deleteTarget.value.id}`);
        items.value = items.value.filter(i => i.id !== deleteTarget.value.id);
        deleteTarget.value = null;
        showToast('Service deleted successfully');
      } catch { showToast('An error occurred', 'error'); }
    };

    return { items, loading, saving, showModal, editing, deleteTarget, form, openModal, saveItem, confirmDelete, doDelete };
  }
};
</script>

<style scoped>
.services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }
.service-card {
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 1.25rem;
  display: flex; gap: 1rem; align-items: flex-start;
  transition: border-color 0.2s;
}
.service-card:hover { border-color: var(--accent); }
.service-icon-wrap {
  width: 46px; height: 46px; flex-shrink: 0;
  background: var(--accent-glow); border: 1px solid var(--accent);
  border-radius: 12px; display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; color: var(--accent-light);
}
.service-info { flex: 1; min-width: 0; }
.service-info h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.35rem; }
.service-info p { font-size: 0.82rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 0.5rem;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.icon-hint { font-size: 0.75rem; color: var(--text-muted); }
.icon-hint code { color: var(--accent-light); background: var(--bg-input); padding: 0.1rem 0.3rem; border-radius: 4px; font-size: 0.75rem; }
.service-actions { display: flex; flex-direction: column; gap: 0.4rem; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
