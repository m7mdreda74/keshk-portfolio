<template>
  <div>
    <div class="section-header">
      <h3><i class="bi bi-envelope-fill" style="color:var(--accent);margin-right:.5rem;"></i>رسائل التواصل</h3>
      <span class="badge badge-accent" v-if="items.length">{{ items.length }} رسالة</span>
    </div>

    <div class="admin-card" v-if="loading" style="text-align:center;padding:3rem;"><div class="spinner"></div></div>

    <div class="admin-card" v-else>
      <div v-if="!items.length" class="empty-state">
        <i class="bi bi-envelope-open"></i><p>لا توجد رسائل بعد</p>
      </div>

      <table v-else class="admin-table">
        <thead><tr><th>#</th><th>الاسم</th><th>البريد</th><th>الموضوع</th><th>الرسالة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
        <tbody>
          <tr v-for="(msg, idx) in items" :key="msg.id">
            <td>{{ idx + 1 }}</td>
            <td><strong>{{ msg.name }}</strong></td>
            <td>
              <a :href="`mailto:${msg.email}`" class="email-link">{{ msg.email }}</a>
            </td>
            <td><span class="badge badge-accent">{{ msg.subject }}</span></td>
            <td>
              <button class="btn btn-secondary btn-sm" @click="viewMessage(msg)">
                <i class="bi bi-eye"></i> عرض
              </button>
            </td>
            <td style="font-size:0.8rem;color:var(--text-muted);">{{ formatDate(msg.created_at) }}</td>
            <td>
              <button class="btn btn-danger btn-sm" @click="confirmDelete(msg)" :id="`delete-msg-${msg.id}`">
                <i class="bi bi-trash-fill"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- View Message Modal -->
    <Transition name="fade">
      <div v-if="viewTarget" class="modal-overlay" @click.self="viewTarget = null">
        <div class="modal-box">
          <div class="modal-header">
            <span class="modal-title"><i class="bi bi-envelope-open-fill" style="color:var(--accent);margin-right:.5rem;"></i>{{ viewTarget.subject }}</span>
            <button class="modal-close" @click="viewTarget = null"><i class="bi bi-x-lg"></i></button>
          </div>
          <div class="modal-body">
            <div class="msg-meta">
              <div><span class="msg-meta-label">من:</span> {{ viewTarget.name }}</div>
              <div><span class="msg-meta-label">البريد:</span>
                <a :href="`mailto:${viewTarget.email}`" style="color:var(--accent)">{{ viewTarget.email }}</a>
              </div>
              <div><span class="msg-meta-label">التاريخ:</span> {{ formatDate(viewTarget.created_at) }}</div>
            </div>
            <div class="msg-body">{{ viewTarget.message }}</div>
            <div style="margin-top:1.5rem;">
              <a :href="`mailto:${viewTarget.email}?subject=Re: ${viewTarget.subject}`" class="btn btn-primary">
                <i class="bi bi-reply-fill"></i> رد على الرسالة
              </a>
            </div>
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
            <p style="color:var(--text-muted)">حذف رسالة من <strong style="color:var(--text-primary)">{{ deleteTarget?.name }}</strong>؟</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="deleteTarget = null">إلغاء</button>
            <button class="btn btn-danger" @click="doDelete" id="msg-delete-confirm-btn"><i class="bi bi-trash-fill"></i> حذف</button>
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
  name: 'MessagesView',
  setup() {
    const showToast = inject('showToast');
    const items = ref([]); const loading = ref(true);
    const viewTarget = ref(null); const deleteTarget = ref(null);

    const load = async () => { try { items.value = (await axios.get('/api/admin/messages')).data; } catch {} finally { loading.value = false; } };
    onMounted(load);

    const viewMessage = (msg) => { viewTarget.value = msg; };
    const confirmDelete = (msg) => { deleteTarget.value = msg; };
    const doDelete = async () => {
      try {
        await axios.delete(`/api/admin/messages/${deleteTarget.value.id}`);
        items.value = items.value.filter(i => i.id !== deleteTarget.value.id);
        deleteTarget.value = null;
        showToast('تم حذف الرسالة بنجاح');
      } catch { showToast('حدث خطأ', 'error'); }
    };

    const formatDate = (dt) => {
      if (!dt) return '';
      const d = new Date(dt);
      return d.toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    };

    return { items, loading, viewTarget, deleteTarget, viewMessage, confirmDelete, doDelete, formatDate };
  }
};
</script>

<style scoped>
.email-link { color: var(--accent-light); text-decoration: none; font-size: 0.85rem; }
.email-link:hover { text-decoration: underline; }
.msg-meta { background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; }
.msg-meta-label { color: var(--text-muted); margin-right: 0.5rem; }
.msg-body { background: var(--bg-input); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1rem; font-size: 0.9rem; line-height: 1.7; white-space: pre-wrap; color: var(--text-primary); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
