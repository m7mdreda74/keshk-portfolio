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
              <label class="form-label">Project Image (optional)</label>
              <div class="upload-zone" :class="{ 'has-image': imagePreview || form.image }" @click="$refs.fileInput.click()" id="project-image-upload-zone">
                <input ref="fileInput" type="file" accept="image/*" style="display:none" @change="onFileChange" id="project-image-file-input">
                <template v-if="imagePreview || form.image">
                  <img :src="imagePreview || form.image" class="upload-preview" alt="Preview">
                  <button type="button" class="upload-remove-btn" @click.stop="removeImage" title="Remove image">
                    <i class="bi bi-x-circle-fill"></i>
                  </button>
                </template>
                <template v-else>
                  <div class="upload-placeholder">
                    <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                    <span>Click to upload image</span>
                    <small>PNG, JPG, GIF · max 4 MB</small>
                  </div>
                </template>
              </div>
              <div v-if="uploadProgress" class="upload-progress-bar">
                <div class="upload-progress-fill"></div>
              </div>
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
    const imagePreview = ref(null);
    const uploadProgress = ref(false);
    const fileInput = ref(null);

    const load = async () => { try { items.value = (await axios.get('/api/admin/projects')).data; } catch {} finally { loading.value = false; } };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      imagePreview.value = item?.image || null;
      form.value = item
        ? { title: item.title, category: item.category, description: item.description, image: item.image || '', details_link: item.details_link || '' }
        : { title: '', category: '', description: '', image: '', details_link: '' };
      showModal.value = true;
    };

    const compressImage = (file) => new Promise((resolve) => {
      const MAX_W = 800;
      const QUALITY = 0.75;
      const reader = new FileReader();
      reader.onload = (ev) => {
        const img = new Image();
        img.onload = () => {
          const scale = Math.min(1, MAX_W / img.width);
          const canvas = document.createElement('canvas');
          canvas.width  = Math.round(img.width  * scale);
          canvas.height = Math.round(img.height * scale);
          canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
          resolve(canvas.toDataURL('image/jpeg', QUALITY));
        };
        img.src = ev.target.result;
      };
      reader.readAsDataURL(file);
    });

    const onFileChange = async (e) => {
      const file = e.target.files[0];
      if (!file) return;
      uploadProgress.value = true;
      try {
        const compressed = await compressImage(file);
        form.value.image  = compressed;
        imagePreview.value = compressed;
      } finally {
        uploadProgress.value = false;
      }
    };

    const removeImage = () => {
      imagePreview.value = null;
      form.value.image = '';
      if (fileInput.value) fileInput.value.value = '';
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

    return { items, loading, saving, showModal, editing, deleteTarget, form, openModal, saveItem, confirmDelete, doDelete, imagePreview, uploadProgress, onFileChange, removeImage, fileInput };
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

/* Upload Zone */
.upload-zone {
  position: relative;
  border: 2px dashed var(--border);
  border-radius: 12px;
  min-height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
  background: var(--bg-input);
  overflow: hidden;
}
.upload-zone:hover { border-color: var(--accent); background: rgba(var(--accent-rgb, 99,102,241), 0.05); }
.upload-zone.has-image { border-style: solid; min-height: 180px; }

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  color: var(--text-muted);
  pointer-events: none;
  user-select: none;
}
.upload-placeholder span { font-size: 0.9rem; font-weight: 500; }
.upload-placeholder small { font-size: 0.75rem; opacity: 0.7; }
.upload-icon { font-size: 2.2rem; color: var(--accent); }

.upload-preview {
  width: 100%;
  height: 180px;
  object-fit: cover;
  display: block;
  border-radius: 10px;
}

.upload-remove-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  background: rgba(0,0,0,0.6);
  border: none;
  border-radius: 50%;
  color: #fff;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1rem;
  transition: background 0.2s;
  z-index: 2;
}
.upload-remove-btn:hover { background: var(--danger); }

.upload-progress-bar {
  height: 3px;
  background: var(--border);
  border-radius: 2px;
  margin-top: 6px;
  overflow: hidden;
}
.upload-progress-fill {
  height: 100%;
  width: 100%;
  background: var(--accent);
  animation: progress-anim 1.2s ease-in-out infinite;
  transform-origin: left;
}
@keyframes progress-anim {
  0% { transform: scaleX(0); }
  50% { transform: scaleX(0.7); }
  100% { transform: scaleX(1); }
}
</style>
