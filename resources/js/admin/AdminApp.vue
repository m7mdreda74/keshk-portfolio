<template>
  <div class="admin-root">
    <!-- Login Page -->
    <AdminLogin v-if="!authenticated" @login-success="onLoginSuccess" />

    <!-- Dashboard -->
    <div v-else class="dashboard-layout">
      <!-- Sidebar -->
      <AdminSidebar
        :active-section="currentSection"
        @navigate="currentSection = $event"
        @logout="handleLogout"
      />

      <!-- Main Content -->
      <div class="dashboard-main">
        <!-- Top Bar -->
        <div class="topbar">
          <div class="topbar-left">
            <button class="sidebar-toggle" @click="toggleSidebar">
              <i class="bi bi-list"></i>
            </button>
            <h2 class="page-title">{{ sectionTitles[currentSection] }}</h2>
          </div>
          <div class="topbar-right">
            <div class="admin-badge">
              <i class="bi bi-shield-check"></i>
              <span>Admin</span>
            </div>
          </div>
        </div>

        <!-- Views -->
        <div class="content-area">
          <DashboardHome v-if="currentSection === 'home'" @navigate="currentSection = $event" />
          <PersonalInfoView v-else-if="currentSection === 'personal'" />
          <SkillsView v-else-if="currentSection === 'skills'" />
          <ServicesView v-else-if="currentSection === 'services'" />
          <StatsView v-else-if="currentSection === 'stats'" />
          <ProjectsView v-else-if="currentSection === 'projects'" />
          <ResumeView v-else-if="currentSection === 'resume'" />
          <TestimonialsView v-else-if="currentSection === 'testimonials'" />
          <MessagesView v-else-if="currentSection === 'messages'" />
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <Transition name="toast">
      <div v-if="toast.show" :class="['toast-notification', `toast-${toast.type}`]">
        <i :class="['bi', toast.type === 'success' ? 'bi-check-circle' : 'bi-x-circle']"></i>
        <span>{{ toast.message }}</span>
      </div>
    </Transition>
  </div>
</template>

<script>
import { ref, onMounted, provide } from 'vue';
import axios from 'axios';
import AdminLogin from './AdminLogin.vue';
import AdminSidebar from './AdminSidebar.vue';
import DashboardHome from './views/DashboardHome.vue';
import PersonalInfoView from './views/PersonalInfoView.vue';
import SkillsView from './views/SkillsView.vue';
import ServicesView from './views/ServicesView.vue';
import StatsView from './views/StatsView.vue';
import ProjectsView from './views/ProjectsView.vue';
import ResumeView from './views/ResumeView.vue';
import TestimonialsView from './views/TestimonialsView.vue';
import MessagesView from './views/MessagesView.vue';

export default {
  name: 'AdminApp',
  components: {
    AdminLogin, AdminSidebar, DashboardHome,
    PersonalInfoView, SkillsView, ServicesView, StatsView,
    ProjectsView, ResumeView, TestimonialsView, MessagesView,
  },
  setup() {
    const authenticated = ref(false);
    const currentSection = ref('home');

    const sectionTitles = {
      home: 'Dashboard',
      personal: 'Personal Info',
      skills: 'Skills',
      services: 'Services',
      stats: 'Statistics',
      projects: 'Projects',
      resume: 'Resume',
      testimonials: 'Testimonials',
      messages: 'Messages',
    };

    // ── Token helpers ──
    const TOKEN_KEY = 'admin_token';
    const getToken = () => localStorage.getItem(TOKEN_KEY);

    const applyToken = (token) => {
      axios.defaults.headers.common['X-Admin-Token'] = token;
      localStorage.setItem(TOKEN_KEY, token);
    };

    const removeToken = () => {
      delete axios.defaults.headers.common['X-Admin-Token'];
      localStorage.removeItem(TOKEN_KEY);
    };

    // Apply saved token immediately (before any request)
    const savedToken = getToken();
    if (savedToken) axios.defaults.headers.common['X-Admin-Token'] = savedToken;

    // Toast system
    const toast = ref({ show: false, message: '', type: 'success' });
    let toastTimer = null;

    const showToast = (message, type = 'success') => {
      if (toastTimer) clearTimeout(toastTimer);
      toast.value = { show: true, message, type };
      toastTimer = setTimeout(() => { toast.value.show = false; }, 3500);
    };

    provide('showToast', showToast);

    const onLoginSuccess = (token) => {
      applyToken(token);
      authenticated.value = true;
    };

    const handleLogout = async () => {
      await axios.post('/api/admin/logout').catch(() => {});
      removeToken();
      authenticated.value = false;
      currentSection.value = 'home';
    };

    const toggleSidebar = () => {
      document.querySelector('.dashboard-layout').classList.toggle('sidebar-collapsed');
    };

    onMounted(async () => {
      const token = getToken();
      if (token) {
        try {
          const res = await axios.get('/api/admin/check-auth');
          authenticated.value = res.data.authenticated;
          if (!authenticated.value) removeToken();
        } catch { removeToken(); }
      }
    });

    return { authenticated, currentSection, sectionTitles, toast, onLoginSuccess, handleLogout, toggleSidebar };
  }
};
</script>

<style>
/* =========================================================
   Admin Dashboard Global Styles
   ========================================================= */
* { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg-dark:       #0d0f1a;
  --bg-card:       #13162a;
  --bg-card-hover: #181c30;
  --bg-input:      #1a1e33;
  --accent:        #6c63ff;
  --accent-light:  #8b84ff;
  --accent-glow:   rgba(108,99,255,0.25);
  --danger:        #ff4757;
  --success:       #2ed573;
  --warning:       #ffa502;
  --text-primary:  #e8eaf6;
  --text-muted:    #8892b0;
  --border:        rgba(108,99,255,0.15);
  --sidebar-w:     260px;
  --radius:        12px;
  --radius-sm:     8px;
}

.admin-root {
  font-family: 'Inter', sans-serif;
  background: var(--bg-dark);
  color: var(--text-primary);
  min-height: 100vh;
}

/* ── Layout ── */
.dashboard-layout {
  display: flex;
  min-height: 100vh;
}

.dashboard-main {
  flex: 1;
  margin-left: var(--sidebar-w);
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  transition: margin-left 0.3s ease;
}

.dashboard-layout.sidebar-collapsed .dashboard-main {
  margin-left: 0;
}

/* ── Topbar ── */
.topbar {
  position: sticky;
  top: 0;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.75rem;
  background: rgba(13,15,26,0.9);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
}

.topbar-left { display: flex; align-items: center; gap: 1rem; }

.page-title {
  font-size: 1.15rem;
  font-weight: 600;
  color: var(--text-primary);
}

.sidebar-toggle {
  background: var(--bg-card);
  border: 1px solid var(--border);
  color: var(--text-primary);
  width: 38px;
  height: 38px;
  border-radius: var(--radius-sm);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: background 0.2s;
}
.sidebar-toggle:hover { background: var(--bg-card-hover); }

.admin-badge {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--accent-glow);
  border: 1px solid var(--accent);
  color: var(--accent-light);
  padding: 0.4rem 0.9rem;
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 500;
}

/* ── Content Area ── */
.content-area {
  flex: 1;
  padding: 2rem 1.75rem;
  overflow-y: auto;
}

/* ── Cards ── */
.admin-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

/* ── Table ── */
.admin-table {
  width: 100%;
  border-collapse: collapse;
}
.admin-table th {
  padding: 0.75rem 1rem;
  text-align: left;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  color: var(--text-muted);
  border-bottom: 1px solid var(--border);
}
.admin-table td {
  padding: 0.85rem 1rem;
  border-bottom: 1px solid rgba(108,99,255,0.07);
  font-size: 0.9rem;
  color: var(--text-primary);
  vertical-align: middle;
}
.admin-table tr:hover td { background: var(--bg-card-hover); }
.admin-table tr:last-child td { border-bottom: none; }

/* ── Buttons ── */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 1rem;
  border-radius: var(--radius-sm);
  border: none;
  cursor: pointer;
  font-family: 'Inter', sans-serif;
  font-size: 0.88rem;
  font-weight: 500;
  transition: all 0.2s ease;
}
.btn-primary {
  background: var(--accent);
  color: #fff;
}
.btn-primary:hover { background: var(--accent-light); transform: translateY(-1px); box-shadow: 0 4px 15px var(--accent-glow); }
.btn-danger {
  background: rgba(255,71,87,0.15);
  color: var(--danger);
  border: 1px solid rgba(255,71,87,0.3);
}
.btn-danger:hover { background: rgba(255,71,87,0.25); }
.btn-secondary {
  background: var(--bg-input);
  color: var(--text-muted);
  border: 1px solid var(--border);
}
.btn-secondary:hover { color: var(--text-primary); }
.btn-success {
  background: rgba(46,213,115,0.15);
  color: var(--success);
  border: 1px solid rgba(46,213,115,0.3);
}
.btn-success:hover { background: rgba(46,213,115,0.25); }
.btn-sm { padding: 0.3rem 0.7rem; font-size: 0.82rem; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; }

/* ── Form ── */
.form-group {
  margin-bottom: 1.25rem;
}
.form-label {
  display: block;
  font-size: 0.82rem;
  font-weight: 500;
  color: var(--text-muted);
  margin-bottom: 0.4rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.form-control {
  width: 100%;
  padding: 0.65rem 0.9rem;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  color: var(--text-primary);
  font-family: 'Inter', sans-serif;
  font-size: 0.9rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--accent-glow);
}
.form-control::placeholder { color: var(--text-muted); }
textarea.form-control { resize: vertical; min-height: 90px; }
select.form-control option { background: var(--bg-card); }

/* ── Range slider ── */
input[type="range"] {
  -webkit-appearance: none;
  width: 100%;
  height: 6px;
  background: var(--bg-input);
  border-radius: 999px;
  outline: none;
  border: 1px solid var(--border);
}
input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 18px; height: 18px;
  border-radius: 50%;
  background: var(--accent);
  cursor: pointer;
  box-shadow: 0 0 8px var(--accent-glow);
}

/* ── Modal ── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.7);
  backdrop-filter: blur(4px);
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}
.modal-box {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  width: 100%;
  max-width: 560px;
  max-height: 90vh;
  overflow-y: auto;
  animation: modalIn 0.25s ease;
}
@keyframes modalIn {
  from { opacity: 0; transform: translateY(-20px) scale(0.97); }
  to   { opacity: 1; transform: none; }
}
.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border);
}
.modal-title { font-size: 1rem; font-weight: 600; }
.modal-close {
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  font-size: 1.3rem;
  line-height: 1;
  padding: 0.2rem;
  border-radius: 4px;
  transition: color 0.2s;
}
.modal-close:hover { color: var(--danger); }
.modal-body { padding: 1.5rem; }
.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--border);
}

/* ── Section Header ── */
.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}
.section-header h3 {
  font-size: 1.1rem;
  font-weight: 600;
}

/* ── Badge ── */
.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.2rem 0.6rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}
.badge-accent { background: var(--accent-glow); color: var(--accent-light); border: 1px solid var(--accent); }
.badge-success { background: rgba(46,213,115,0.15); color: var(--success); }
.badge-warning { background: rgba(255,165,2,0.15); color: var(--warning); }
.badge-danger  { background: rgba(255,71,87,0.15); color: var(--danger); }

/* ── Stars ── */
.stars { color: var(--warning); letter-spacing: 2px; font-size: 1rem; }

/* ── Empty state ── */
.empty-state {
  text-align: center;
  padding: 3rem;
  color: var(--text-muted);
}
.empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }

/* ── Loading Spinner ── */
.spinner {
  display: inline-block;
  width: 20px; height: 20px;
  border: 3px solid var(--border);
  border-top-color: var(--accent);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Toast ── */
.toast-notification {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.9rem 1.5rem;
  border-radius: var(--radius);
  font-size: 0.92rem;
  font-weight: 500;
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  min-width: 260px;
}
.toast-success { background: rgba(46,213,115,0.15); border: 1px solid var(--success); color: var(--success); }
.toast-error   { background: rgba(255,71,87,0.15);  border: 1px solid var(--danger);  color: var(--danger); }

.toast-enter-active, .toast-leave-active { transition: all 0.35s ease; }
.toast-enter-from { opacity: 0; transform: translateX(80px); }
.toast-leave-to   { opacity: 0; transform: translateX(80px); }

/* ── Responsive ── */
@media (max-width: 768px) {
  .dashboard-main { margin-left: 0; }
  .content-area { padding: 1.25rem 1rem; }
}
</style>
