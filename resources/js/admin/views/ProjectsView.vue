<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-collection-fill" style="color:var(--accent);margin-right:.5rem;"></i>Projects</h3>
      <button class="btn btn-primary" @click="openModal()" id="add-project-btn">
        <i class="bi bi-plus-lg"></i> Add Project
      </button>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div class="admin-card" v-else>
      <div v-if="!items.length" class="empty-state">
        <i class="bi bi-collection"></i><p>No projects added yet</p>
      </div>
      <table v-else class="admin-table">
        <thead><tr><th>#</th><th>Project</th><th>Category</th><th>Link</th><th>Actions</th></tr></thead>
        <tbody>
          <tr v-for="(item, idx) in items" :key="item.id">
            <td>{{ idx + 1 }}</td>
            <td>
              <div class="project-cell">
                <div v-if="item.image" class="project-thumb">
                  <img :src="item.image" :alt="item.title" @error="e => e.target.style.display='none'">
                </div>
                <div>
                  <strong>{{ item.title }}</strong>
                  <p>{{ item.description?.substring(0, 60) }}...</p>
                </div>
              </div>
            </td>
            <td><span class="badge badge-accent">{{ item.category }}</span></td>
            <td>
              <a v-if="item.details_link" :href="item.details_link" target="_blank" class="btn btn-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i>
              </a>
              <span v-else style="color:var(--text-muted);font-size:0.8rem;">—</span>
            </td>
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

    <!-- Modal -->
    <Transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal-box">
          <div class="modal-header">
            <span class="modal-title">{{ editing ? 'Edit Project' : 'Add New Project' }}</span>
            <button class="modal-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Project Title</label>
              <input v-model="form.title" class="form-control" placeholder="Project Name" id="project-title-input">
            </div>
            <div class="form-group">
              <label class="form-label">Category</label>
              <input v-model="form.category" class="form-control" placeholder="Web App / API / Mobile..." id="project-category-input">
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea v-model="form.description" class="form-control" rows="4" id="project-desc-input"></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Image URL (optional)</label>
              <input v-model="form.image" class="form-control" placeholder="assets/img/projects/..." id="project-image-input">
            </div>
            <div class="form-group">
              <label class="form-label">Project Link (optional)</label>
              <input v-model="form.details_link" class="form-control" placeholder="https://github.com/..." id="project-link-input">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">Cancel</button>
            <button class="btn btn-primary" @click="saveItem" :disabled="saving" id="project-save-btn">
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
            <p style="color:var(--text-muted)">Delete project <strong style="color:var(--text-primary)">"{{ deleteTarget?.title }}"</strong>?</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">Cancel</button>
            <button class="btn btn-danger" @click="doDelete" id="project-delete-confirm-btn"><i class="bi bi-trash-fill"></i> Delete</button>
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
  name: 'ProjectsView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]); const loading = ref(true); const saving = ref(false);
    const showModal = ref(false); const editing = ref(null); const deleteTarget = ref(null);
    const form = ref({ title: '', category: '', description: '', image: '', details_link: '' });

    const load = async () => { try { items.value = (await axios.get('/api/admin/projects')).data; } catch {} finally { loading.value = false; } };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      form.value = item
        ? { title: item.title, category: item.category, description: item.description, image: item.image || '', details_link: item.details_link || '' }
        : { title: '', category: '', description: '', image: '', details_link: '' };
      showModal.value = true;
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        if (editing.value) {
          const res = await axios.put(`/api/admin/projects/${editing.value.id}`, form.value);
          const idx = items.value.findIndex(i => i.id === editing.value.id);
          if (idx !== -1) items.value[idx] = res.data;
        } else {
          items.value.push((await axios.post('/api/admin/projects', form.value)).data);
        }
        showModal.value = false;
        showToast(editing.value ? 'Project updated successfully ✓' : 'Project added successfully ✓');
      } catch (e) { showToast(e.response?.data?.message || 'An error occurred', 'error'); }
      finally { saving.value = false; }
    };

    const confirmDelete = (item) => { deleteTarget.value = item; };
    const doDelete = async () => {
      try {
        await axios.delete(`/api/admin/projects/${deleteTarget.value.id}`);
        items.value = items.value.filter(i => i.id !== deleteTarget.value.id);
        deleteTarget.value = null;
        showToast('Project deleted successfully');
      } catch { showToast('An error occurred', 'error'); }
    };

    return { items, loading, saving, showModal, editing, deleteTarget, form, openModal, saveItem, confirmDelete, doDelete };
  }
};
</script>

<style scoped>
.action-btns { display: flex; gap: 0.4rem; }
.project-cell { display: flex; align-items: center; gap: 0.75rem; }
.project-thumb { width: 44px; height: 44px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: var(--bg-input); border: 1px solid var(--border); }
.project-thumb img { width: 100%; height: 100%; object-fit: cover; }
.project-cell p { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.15rem; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
