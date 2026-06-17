<template>
  <aside class="admin-sidebar" :class="{ collapsed: isCollapsed }">
    <!-- Brand -->
    <div class="sidebar-brand">
      <div class="brand-icon">
        <i class="bi bi-lightning-charge-fill"></i>
      </div>
      <div class="brand-text" v-show="!isCollapsed">
        <span class="brand-name">Keshk</span>
        <span class="brand-sub">Admin Panel</span>
      </div>
    </div>

    <!-- Toggle collapse -->
    <button class="collapse-btn" @click="isCollapsed = !isCollapsed" title="Toggle Sidebar">
      <i :class="['bi', isCollapsed ? 'bi-chevron-right' : 'bi-chevron-left']"></i>
    </button>

    <!-- Nav -->
    <nav class="sidebar-nav">
      <div class="nav-section-label" v-show="!isCollapsed">MAIN</div>
      <button
        v-for="item in navItems"
        :key="item.key"
        :class="['nav-item', { active: activeSection === item.key }]"
        @click="$emit('navigate', item.key)"
        :title="isCollapsed ? item.label : ''"
        :id="`nav-${item.key}`"
      >
        <i :class="['bi', item.icon]"></i>
        <span v-show="!isCollapsed">{{ item.label }}</span>
        <span v-show="!isCollapsed && item.badge" class="nav-badge">{{ item.badge }}</span>
      </button>
    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
      <button class="nav-item nav-logout" @click="$emit('logout')" title="Logout" id="admin-logout-btn">
        <i class="bi bi-box-arrow-right"></i>
        <span v-show="!isCollapsed">Logout</span>
      </button>
    </div>
  </aside>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'AdminSidebar',
  props: { activeSection: String },
  emits: ['navigate', 'logout'],
  setup() {
    const isCollapsed = ref(false);

    const navItems = [
      { key: 'home',         label: 'Dashboard',        icon: 'bi-grid-1x2-fill' },
      { key: 'personal',     label: 'Personal Info',     icon: 'bi-person-badge-fill' },
      { key: 'skills',       label: 'Skills',            icon: 'bi-bar-chart-fill' },
      { key: 'services',     label: 'Services',          icon: 'bi-gear-wide-connected' },
      { key: 'stats',        label: 'Statistics',        icon: 'bi-graph-up-arrow' },
      { key: 'projects',     label: 'Projects',          icon: 'bi-collection-fill' },
      { key: 'resume',       label: 'Resume',            icon: 'bi-file-earmark-text-fill' },
      { key: 'testimonials', label: 'Testimonials',      icon: 'bi-chat-quote-fill' },
      { key: 'messages',     label: 'Messages',          icon: 'bi-envelope-fill' },
    ];

    return { isCollapsed, navItems };
  }
};
</script>

<style scoped>
.admin-sidebar {
  position: fixed;
  top: 0; left: 0;
  width: var(--sidebar-w);
  height: 100vh;
  background: var(--bg-card);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  z-index: 200;
  transition: width 0.3s ease;
  overflow: hidden;
}

.admin-sidebar.collapsed {
  width: 68px;
}

/* Brand */
.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 0.9rem;
  padding: 1.4rem 1.1rem;
  border-bottom: 1px solid var(--border);
  min-height: 70px;
}

.brand-icon {
  width: 38px; height: 38px;
  background: var(--accent);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem;
  color: #fff;
  flex-shrink: 0;
  box-shadow: 0 4px 12px var(--accent-glow);
}

.brand-name {
  display: block;
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-primary);
  white-space: nowrap;
}
.brand-sub {
  display: block;
  font-size: 0.72rem;
  color: var(--text-muted);
  white-space: nowrap;
}

/* Collapse button */
.collapse-btn {
  position: absolute;
  top: 50%;
  right: -14px;
  width: 28px; height: 28px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--text-muted);
  font-size: 0.75rem;
  transition: all 0.2s;
  z-index: 10;
}
.collapse-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

/* Nav */
.sidebar-nav {
  flex: 1;
  overflow-y: auto;
  padding: 1rem 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-track { background: transparent; }
.sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

.nav-section-label {
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  color: var(--text-muted);
  padding: 0.4rem 0.5rem 0.6rem;
  opacity: 0.6;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.65rem 0.85rem;
  background: none;
  border: none;
  border-radius: var(--radius-sm);
  color: var(--text-muted);
  cursor: pointer;
  font-family: 'Inter', sans-serif;
  font-size: 0.88rem;
  font-weight: 500;
  text-align: left;
  transition: all 0.2s ease;
  white-space: nowrap;
  position: relative;
}

.nav-item i {
  font-size: 1rem;
  flex-shrink: 0;
  width: 20px;
  text-align: center;
}

.nav-item:hover {
  background: var(--bg-card-hover);
  color: var(--text-primary);
}

.nav-item.active {
  background: var(--accent-glow);
  color: var(--accent-light);
  border: 1px solid rgba(108,99,255,0.2);
}

.nav-item.active i { color: var(--accent); }

.nav-badge {
  margin-left: auto;
  background: var(--danger);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 0.1rem 0.45rem;
  border-radius: 999px;
}

/* Footer */
.sidebar-footer {
  padding: 0.75rem;
  border-top: 1px solid var(--border);
}

.nav-logout {
  color: var(--danger) !important;
}
.nav-logout:hover {
  background: rgba(255,71,87,0.1) !important;
  color: var(--danger) !important;
}

/* Mobile overlay */
@media (max-width: 768px) {
  .admin-sidebar {
    transform: translateX(-100%);
    width: var(--sidebar-w) !important;
  }
  .admin-sidebar.mobile-open {
    transform: translateX(0);
  }
}
</style>
