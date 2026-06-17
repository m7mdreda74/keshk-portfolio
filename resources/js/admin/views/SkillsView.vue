<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-bar-chart-fill" style="color:var(--accent);margin-right:.5rem;"></i>Skills</h3>
      <button class="btn btn-primary" @click="openModal()" id="add-skill-btn">
        <i class="bi bi-plus-lg"></i> Add Skill
      </button>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div class="admin-card" v-else>
      <div v-if="!items.length" class="empty-state">
        <i class="bi bi-bar-chart"></i>
        <p>No skills added yet</p>
      </div>
      <table v-else class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Skill Name</th>
            <th>Percentage</th>
            <th>Progress</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, idx) in items" :key="item.id">
            <td>{{ idx + 1 }}</td>
            <td><strong>{{ item.name }}</strong></td>
            <td><span class="badge badge-accent">{{ item.percentage }}%</span></td>
            <td style="width:220px;">
              <div class="progress-bar-wrap">
                <div class="progress-bar-fill" :style="{ width: item.percentage + '%' }"></div>
              </div>
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
            <span class="modal-title">{{ editing ? 'Edit Skill' : 'Add New Skill' }}</span>
            <button class="modal-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Skill Name</label>
              <input v-model="form.name" class="form-control" placeholder="e.g. Laravel" id="skill-name-input">
            </div>
            <div class="form-group">
              <label class="form-label">Percentage: <strong style="color:var(--accent)">{{ form.percentage }}%</strong></label>
              <input v-model.number="form.percentage" type="range" min="0" max="100">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">Cancel</button>
            <button class="btn btn-primary" @click="saveItem" :disabled="saving" id="skill-save-btn">
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
            <p style="color:var(--text-muted)">Are you sure you want to delete skill <strong style="color:var(--text-primary)">"{{ deleteTarget?.name }}"</strong>? This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">Cancel</button>
            <button class="btn btn-danger" @click="doDelete" :disabled="saving" id="skill-delete-confirm-btn">
              <i class="bi bi-trash-fill"></i> Delete Permanently
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
  name: 'SkillsView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);
    const editing = ref(null);
    const deleteTarget = ref(null);
    const form = ref({ name: '', percentage: 80 });

    const load = async () => {
      try { items.value = (await axios.get('/api/admin/skills')).data; }
      catch {} finally { loading.value = false; }
    };
    onMounted(load);

    const openModal = (item = null) => {
      editing.value = item;
      form.value = item ? { name: item.name, percentage: item.percentage } : { name: '', percentage: 80 };
      showModal.value = true;
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        if (editing.value) {
          const res = await axios.put(`/api/admin/skills/${editing.value.id}`, form.value);
          const idx = items.value.findIndex(i => i.id === editing.value.id);
          if (idx !== -1) items.value[idx] = res.data;
        } else {
          const res = await axios.post('/api/admin/skills', form.value);
          items.value.push(res.data);
        }
        showModal.value = false;
        showToast(editing.value ? 'Skill updated successfully ✓' : 'Skill added successfully ✓');
      } catch (e) {
        showToast(e.response?.data?.message || 'An error occurred', 'error');
      } finally { saving.value = false; }
    };

    const confirmDelete = (item) => { deleteTarget.value = item; };
    const doDelete = async () => {
      saving.value = true;
      try {
        await axios.delete(`/api/admin/skills/${deleteTarget.value.id}`);
        items.value = items.value.filter(i => i.id !== deleteTarget.value.id);
        deleteTarget.value = null;
        showToast('Skill deleted successfully');
      } catch { showToast('An error occurred', 'error'); }
      finally { saving.value = false; }
    };

    return { items, loading, saving, showModal, editing, deleteTarget, form, openModal, saveItem, confirmDelete, doDelete };
  }
};
</script>

<style scoped>
.action-btns { display: flex; gap: 0.4rem; }
.progress-bar-wrap { height: 6px; background: var(--bg-input); border-radius: 999px; overflow: hidden; }
.progress-bar-fill { height: 100%; background: linear-gradient(90deg, var(--accent), var(--accent-light)); border-radius: 999px; transition: width 0.5s ease; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
