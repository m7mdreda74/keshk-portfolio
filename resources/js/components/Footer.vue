<template>
  <footer id="footer" class="footer accent-background">
    <div class="container">
      <div class="copyright text-center">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">{{ personalInfo.name }}</strong> <span>All Rights Reserved 2026</span></p>
      </div>
      <div class="social-links d-flex justify-content-center">
        <a href="https://www.linkedin.com/in/m0hamed-keshk" target="_blank" rel="noopener" title="LinkedIn" class="social-icon linkedin">
          <i class="bi bi-linkedin"></i>
        </a>
        <a href="https://github.com/m7mdreda74" target="_blank" rel="noopener" title="GitHub" class="social-icon github">
          <i class="bi bi-github"></i>
        </a>
        <a href="http://wa.me/201099670724" target="_blank" rel="noopener" title="WhatsApp" class="social-icon whatsapp">
          <i class="bi bi-whatsapp"></i>
        </a>
        <a href="http://t.me/m7md_reda74" target="_blank" rel="noopener" title="Telegram" class="social-icon telegram">
          <i class="bi bi-telegram"></i>
        </a>
        <a href="https://www.facebook.com/share/1JNP9vrxzq/" target="_blank" rel="noopener" title="Facebook" class="social-icon facebook">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://www.instagram.com/m7md_reda74" target="_blank" rel="noopener" title="Instagram" class="social-icon instagram">
          <i class="bi bi-instagram"></i>
        </a>
      </div>
      <div class="credits">
        Designed &amp; Developed by <strong>KESHK</strong>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'FooterComponent',
  props: {
    personalInfo: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style scoped>
/* ── Base icon style ── */
.social-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: rgba(255, 255, 255, 0.7);
  font-size: 17px;
  margin: 0 5px;
  text-decoration: none;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
              color 0.25s ease,
              border-color 0.25s ease,
              box-shadow 0.25s ease,
              background 0.25s ease;

  /* staggered entrance */
  animation: iconPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

/* Stagger delays */
.social-icon:nth-child(1) { animation-delay: 0.05s; }
.social-icon:nth-child(2) { animation-delay: 0.10s; }
.social-icon:nth-child(3) { animation-delay: 0.15s; }
.social-icon:nth-child(4) { animation-delay: 0.20s; }
.social-icon:nth-child(5) { animation-delay: 0.25s; }
.social-icon:nth-child(6) { animation-delay: 0.30s; }

/* ── Brand-colored hovers ── */
.social-icon.linkedin:hover  { color: #fff; background: #0a66c2; border-color: #0a66c2; box-shadow: 0 6px 20px rgba(10,102,194,0.5); transform: translateY(-5px) scale(1.12); }
.social-icon.github:hover    { color: #fff; background: #333;    border-color: #555;    box-shadow: 0 6px 20px rgba(80,80,80,0.5);  transform: translateY(-5px) scale(1.12); }
.social-icon.whatsapp:hover  { color: #fff; background: #25d366; border-color: #25d366; box-shadow: 0 6px 20px rgba(37,211,102,0.5); transform: translateY(-5px) scale(1.12); }
.social-icon.telegram:hover  { color: #fff; background: #229ed9; border-color: #229ed9; box-shadow: 0 6px 20px rgba(34,158,217,0.5); transform: translateY(-5px) scale(1.12); }
.social-icon.facebook:hover  { color: #fff; background: #1877f2; border-color: #1877f2; box-shadow: 0 6px 20px rgba(24,119,242,0.5); transform: translateY(-5px) scale(1.12); }
.social-icon.instagram:hover {
  color: #fff;
  background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
  border-color: #d6249f;
  box-shadow: 0 6px 20px rgba(214,36,159,0.5);
  transform: translateY(-5px) scale(1.12);
}

/* ── Entrance keyframe ── */
@keyframes iconPop {
  from { opacity: 0; transform: scale(0.5) translateY(10px); }
  to   { opacity: 1; transform: scale(1)   translateY(0); }
}
</style>
