<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-file-earmark-text-fill" style="color:var(--accent);margin-right:.5rem;"></i>Resume</h3>
      <button class="btn btn-primary" @click="openModal()" id="add-resume-btn">
        <i class="bi bi-plus-lg"></i> Add Item
      </button>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div v-else class="resume-cols">
      <!-- Education -->
      <div class="admin-card">
        <h4 class="resume-section-title"><i class="bi bi-mortarboard-fill"></i> Education</h4>
        <div v-if="!educationItems.length" class="empty-state" style="padding:2rem;"><i class="bi bi-mortarboard"></i><p>No education items</p></div>
        <div v-for="item in educationItems" :key="item.id" class="resume-item-card">
          <div class="resume-item-header">
            <div>
              <strong>{{ item.title }}</strong>
              <p>{{ item.organization }}</p>
              <span class="badge badge-accent">{{ item.duration }}</span>
            </div>
            <div class="action-btns">
              <button class="btn btn-secondary btn-sm" @click="openModal(item)"><i class="bi bi-pencil-fill"></i></button>
              <button class="btn btn-danger btn-sm" @click="confirmDelete(item)"><i class="bi bi-trash-fill"></i></button>
            </div>
          </div>
          <p class="resume-desc">{{ item.description }}</p>
        </div>
      </div>

      <!-- Experience -->
      <div class="admin-card">
        <h4 class="resume-section-title"><i class="bi bi-briefcase-fill"></i> Work Experience</h4>
        <div v-if="!experienceItems.length" class="empty-state" style="padding:2rem;"><i class="bi bi-briefcase"></i><p>No experience items</p></div>
        <div v-for="item in experienceItems" :key="item.id" class="resume-item-card">
          <div class="resume-item-header">
            <div>
              <strong>{{ item.title }}</strong>
              <p>{{ item.organization }}</p>
              <span class="badge badge-success">{{ item.duration }}</span>
            </div>
            <div class="action-btns">
              <button class="btn btn-secondary btn-sm" @click="openModal(item)"><i class="bi bi-pencil-fill"></i></button>
              <button class="btn btn-danger btn-sm" @click="confirmDelete(item)"><i class="bi bi-trash-fill"></i></button>
            </div>
          </div>
          <p class="resume-desc">{{ item.description }}</p>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal-box">
          <div class="modal-header">
            <span class="modal-title">{{ editing ? 'Edit Item' : 'Add New Item' }}</span>
            <button class="modal-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Type</label>
              <select v-model="form.type" class="form-control" id="resume-type-select">
                <option value="education">Education</option>
                <option value="experience">Experience</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Title / Role</label>
              <input v-model="form.title" class="form-control" placeholder="e.g. BSc Computer Science" id="resume-title-input">
            </div>
            <div class="form-group">
              <label class="form-label">Institution / Company</label>
              <input v-model="form.organization" class="form-control" placeholder="University / Company name..." id="resume-org-input">
            </div>
            <div class="form-group">
              <label class="form-label">Duration</label>
              <input v-model="form.duration" class="form-control" placeholder="2020 - 2024" id="resume-duration-input">
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea v-model="form.description" class="form-control" rows="4" id="resume-desc-input"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">Cancel</button>
            <button class="btn btn-primary" @click="saveItem" :disabled="saving" id="resume-save-btn">
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
            <p style="color:var(--text-muted)">Delete <strong style="color:var(--text-primary)">"{{ deleteTarget?.title }}"</strong>?</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">Cancel</button>
            <button class="btn btn-danger" @click="doDelete" id="resume-delete-confirm-btn"><i class="bi bi-trash-fill"></i> Delete</button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import { ref, computed, onMounted, inject } from 'vue';
import axios from 'axios';

export default {
  name: 'ResumeView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]); const loading = ref(true); const saving = ref(false);
    const showModal = ref(false); const editing = ref(null); const deleteTarget = ref(null);
    const form = ref({ type: 'education', title: '', organization: '', duration: '', description: '' });

    const educationItems = computed(() => items.value.filter(i => i.type === 'education'));
    const experienceItems = computed(() => items.value.filter(i => i.type === 'experience'));

    const load = async () => { try { items.value = (await axios.get('/api/admin/resume-items')).data; } catch {} finally { loading.value = false; } };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      form.value = item
        ? { type: item.type, title: item.title, organization: item.organization, duration: item.duration, description: item.description }
        : { type: 'education', title: '', organization: '', duration: '', description: '' };
      showModal.value = true;
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        if (editing.value) {
          const res = await axios.put(`/api/admin/resume-items/${editing.value.id}`, form.value);
          const idx = items.value.findIndex(i => i.id === editing.value.id);
          if (idx !== -1) items.value[idx] = res.data;
        } else {
          items.value.push((await axios.post('/api/admin/resume-items', form.value)).data);
        }
        showModal.value = false;
        showToast(editing.value ? 'Item updated successfully ✓' : 'Item added successfully ✓');
      } catch (e) { showToast(e.response?.data?.message || 'An error occurred', 'error'); }
      finally { saving.value = false; }
    };

    const confirmDelete = (item) => { deleteTarget.value = item; };
    const doDelete = async () => {
      try {
        await axios.delete(`/api/admin/resume-items/${deleteTarget.value.id}`);
        items.value = items.value.filter(i => i.id !== deleteTarget.value.id);
        deleteTarget.value = null;
        showToast('Item deleted successfully');
      } catch { showToast('An error occurred', 'error'); }
    };

    return { items, loading, saving, showModal, editing, deleteTarget, form, educationItems, experienceItems, openModal, saveItem, confirmDelete, doDelete };
  }
};
</script>

<style scoped>
.resume-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.resume-section-title { font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent-light); }
.resume-item-card { padding: 1rem; background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-sm); margin-bottom: 0.75rem; }
.resume-item-header { display: flex; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.5rem; }
.resume-item-header p { font-size: 0.82rem; color: var(--text-muted); margin-top: 0.15rem; }
.resume-item-header .badge { margin-top: 0.4rem; }
.resume-desc { font-size: 0.82rem; color: var(--text-muted); line-height: 1.5; }
.action-btns { display: flex; gap: 0.4rem; flex-shrink: 0; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
@media (max-width: 700px) { .resume-cols { grid-template-columns: 1fr; } }
</style>
