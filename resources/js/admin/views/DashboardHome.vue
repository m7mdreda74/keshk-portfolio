<template>
  <div class="dashboard-home">
    <!-- Welcome banner -->
    <div class="welcome-banner">
      <div>
        <h2 class="welcome-title">Welcome back, Keshk 👋</h2>
        <p class="welcome-sub">Manage your portfolio content from here</p>
      </div>
      <div class="welcome-icon">
        <i class="bi bi-stars"></i>
      </div>
    </div>

    <!-- Stats cards -->
    <div class="stats-grid" v-if="!loading">
      <div class="stat-card" v-for="s in overviewStats" :key="s.label">
        <div class="stat-icon" :style="{ background: s.bg, boxShadow: `0 0 20px ${s.glow}` }">
          <i :class="['bi', s.icon]"></i>
        </div>
        <div class="stat-info">
          <span class="stat-num">{{ s.count }}</span>
          <span class="stat-label">{{ s.label }}</span>
        </div>
      </div>
    </div>

    <div v-else class="admin-card text-center" style="padding:3rem;">
      <div class="spinner"></div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card">
      <h3 style="margin-bottom:1.25rem; font-size:1rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.07em;">Quick Actions</h3>
      <div class="quick-actions">
        <a href="/" target="_blank" class="quick-action">
          <i class="bi bi-eye"></i>
          <span>View Portfolio</span>
        </a>
        <button class="quick-action" @click="$emit('navigate', 'personal')">
          <i class="bi bi-person-badge"></i>
          <span>Edit Personal Info</span>
        </button>
        <button class="quick-action" @click="$emit('navigate', 'projects')">
          <i class="bi bi-plus-lg"></i>
          <span>Add New Project</span>
        </button>
        <button class="quick-action" @click="$emit('navigate', 'messages')">
          <i class="bi bi-envelope"></i>
          <span>View Messages</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'DashboardHome',
  emits: ['navigate'],
  setup() {
    const loading = ref(true);
    const overviewStats = ref([]);

    onMounted(async () => {
      try {
        const [skills, services, projects, resume, testimonials, messages] = await Promise.all([
          axios.get('/api/admin/skills'),
          axios.get('/api/admin/services'),
          axios.get('/api/admin/projects'),
          axios.get('/api/admin/resume-items'),
          axios.get('/api/admin/testimonials'),
          axios.get('/api/admin/messages'),
        ]);

        overviewStats.value = [
          { label: 'Skills',       count: skills.data.length,       icon: 'bi-bar-chart-fill',         bg: 'rgba(108,99,255,0.2)',   glow: 'rgba(108,99,255,0.3)' },
          { label: 'Services',     count: services.data.length,     icon: 'bi-gear-wide-connected',    bg: 'rgba(0,210,255,0.15)',   glow: 'rgba(0,210,255,0.2)'  },
          { label: 'Projects',     count: projects.data.length,     icon: 'bi-collection-fill',        bg: 'rgba(255,165,2,0.15)',   glow: 'rgba(255,165,2,0.2)'  },
          { label: 'Resume Items', count: resume.data.length,       icon: 'bi-file-earmark-text-fill', bg: 'rgba(46,213,115,0.15)', glow: 'rgba(46,213,115,0.2)' },
          { label: 'Testimonials', count: testimonials.data.length, icon: 'bi-chat-quote-fill',        bg: 'rgba(255,107,157,0.15)', glow: 'rgba(255,107,157,0.2)' },
          { label: 'Messages',     count: messages.data.length,     icon: 'bi-envelope-fill',          bg: 'rgba(255,71,87,0.15)',  glow: 'rgba(255,71,87,0.2)'  },
        ];
      } catch (e) {
        console.error(e);
      } finally {
        loading.value = false;
      }
    });

    return { loading, overviewStats };
  }
};
</script>

<style scoped>
.dashboard-home {}

.welcome-banner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: linear-gradient(135deg, rgba(108,99,255,0.2) 0%, rgba(108,99,255,0.05) 100%);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 2rem;
  margin-bottom: 1.75rem;
}

.welcome-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.4rem;
}

.welcome-sub {
  color: var(--text-muted);
  font-size: 0.92rem;
}

.welcome-icon {
  font-size: 2.5rem;
  color: var(--accent);
  opacity: 0.7;
}

/* Stats */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 1rem;
  margin-bottom: 1.75rem;
}

.stat-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: transform 0.2s, border-color 0.2s;
}
.stat-card:hover {
  transform: translateY(-3px);
  border-color: var(--accent);
}

.stat-icon {
  width: 48px; height: 48px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}

.stat-num {
  display: block;
  font-size: 1.6rem;
  font-weight: 800;
  line-height: 1;
}

.stat-label {
  display: block;
  font-size: 0.8rem;
  color: var(--text-muted);
  margin-top: 0.2rem;
}

/* Quick actions */
.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 0.75rem;
}

.quick-action {
  display: flex;
  align-items: center;
  gap: 0.7rem;
  padding: 0.85rem 1rem;
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  color: var(--text-primary);
  font-family: 'Inter', sans-serif;
  font-size: 0.88rem;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
}
.quick-action:hover {
  background: var(--bg-card-hover);
  border-color: var(--accent);
  color: var(--accent-light);
}
.quick-action i { font-size: 1rem; color: var(--accent); }
</style>
