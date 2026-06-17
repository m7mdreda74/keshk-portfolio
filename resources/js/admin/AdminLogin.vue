<template>
  <div class="login-page">
    <!-- Animated background blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="login-card">
      <!-- Logo / Icon -->
      <div class="login-icon">
        <i class="bi bi-shield-lock"></i>
      </div>

      <h1 class="login-title">Admin Dashboard</h1>
      <p class="login-sub">Keshk Portfolio Control Panel</p>

      <form @submit.prevent="doLogin" class="login-form">
        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="pw-wrapper">
            <input
              :type="showPw ? 'text' : 'password'"
              v-model="password"
              class="form-control"
              placeholder="Enter your password"
              required
              autofocus
              id="admin-password-input"
            />
            <button type="button" class="pw-toggle" @click="showPw = !showPw" tabindex="-1">
              <i :class="['bi', showPw ? 'bi-eye-slash' : 'bi-eye']"></i>
            </button>
          </div>
        </div>

        <p v-if="error" class="login-error">
          <i class="bi bi-exclamation-triangle"></i> {{ error }}
        </p>

        <button
          type="submit"
          class="btn btn-primary login-btn"
          :disabled="loading"
          id="admin-login-btn"
        >
          <span v-if="loading" class="spinner"></span>
          <i v-else class="bi bi-box-arrow-in-right"></i>
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
  name: 'AdminLogin',
  emits: ['login-success'],
  setup(_, { emit }) {
    const password = ref('');
    const loading = ref(false);
    const error = ref('');
    const showPw = ref(false);

    const doLogin = async () => {
      error.value = '';
      loading.value = true;
      try {
        await axios.post('/api/admin/login', { password: password.value });
        emit('login-success');
      } catch (e) {
        error.value = e.response?.data?.message || 'Connection error';
      } finally {
        loading.value = false;
      }
    };

    return { password, loading, error, showPw, doLogin };
  }
};
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bg-dark);
  position: relative;
  overflow: hidden;
  padding: 2rem;
}

/* Animated Blobs */
.blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.12;
  animation: floatBlob 8s ease-in-out infinite alternate;
}
.blob-1 {
  width: 500px; height: 500px;
  background: var(--accent);
  top: -100px; left: -150px;
  animation-delay: 0s;
}
.blob-2 {
  width: 400px; height: 400px;
  background: #ff6b9d;
  bottom: -100px; right: -100px;
  animation-delay: 3s;
}
.blob-3 {
  width: 300px; height: 300px;
  background: #00d2ff;
  top: 50%; left: 60%;
  animation-delay: 6s;
}
@keyframes floatBlob {
  from { transform: translate(0, 0) scale(1); }
  to   { transform: translate(30px, -30px) scale(1.1); }
}

.login-card {
  position: relative;
  z-index: 1;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 2.5rem;
  width: 100%;
  max-width: 420px;
  text-align: center;
  box-shadow: 0 24px 64px rgba(0,0,0,0.5), 0 0 0 1px rgba(108,99,255,0.1);
  animation: cardIn 0.5s ease;
}
@keyframes cardIn {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: none; }
}

.login-icon {
  width: 72px; height: 72px;
  background: var(--accent-glow);
  border: 2px solid var(--accent);
  border-radius: 20px;
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem;
  color: var(--accent-light);
  margin: 0 auto 1.5rem;
  box-shadow: 0 0 24px var(--accent-glow);
}

.login-title {
  font-size: 1.6rem;
  font-weight: 700;
  margin-bottom: 0.3rem;
  background: linear-gradient(135deg, #fff 0%, var(--accent-light) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.login-sub {
  font-size: 0.88rem;
  color: var(--text-muted);
  margin-bottom: 2rem;
}

.login-form { text-align: left; }

.pw-wrapper { position: relative; }
.pw-toggle {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  font-size: 1rem;
  padding: 0.25rem;
  transition: color 0.2s;
}
.pw-toggle:hover { color: var(--text-primary); }

.login-error {
  color: var(--danger);
  font-size: 0.85rem;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  background: rgba(255,71,87,0.1);
  padding: 0.6rem 0.8rem;
  border-radius: var(--radius-sm);
  border: 1px solid rgba(255,71,87,0.3);
}

.login-btn {
  width: 100%;
  justify-content: center;
  padding: 0.8rem;
  font-size: 0.95rem;
  border-radius: var(--radius-sm);
  margin-top: 0.25rem;
}
</style>
