<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Primary Meta Tags -->
  <title>Mohamed Keshk — Backend Developer | Laravel & Vue.js Portfolio</title>
  <meta name="description" content="Portfolio of Mohamed Keshk (كشك), a Backend Developer specializing in Laravel, PHP, RESTful APIs, and SaaS architecture. Available for freelance and full-time opportunities.">
  <meta name="keywords" content="Mohamed Keshk, Keshk, كشك, محمد كشك, Backend Developer, Laravel Developer Egypt, PHP Developer, Vue.js, RESTful API, SaaS, Portfolio">
  <meta name="author" content="Mohamed Reda Keshk">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="https://keshk-portfolio.vercel.app/">

  <!-- JSON-LD: Person schema (tells Google exactly who you are) -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Mohamed Reda Keshk",
    "alternateName": ["Keshk", "كشك", "محمد كشك"],
    "url": "https://keshk-portfolio.vercel.app/",
    "image": "https://keshk-portfolio.vercel.app/assets/img/profile-img.png",
    "jobTitle": "Backend Developer",
    "description": "Backend Developer specializing in Laravel, PHP, RESTful APIs, and SaaS architecture.",
    "knowsAbout": ["Laravel", "PHP", "Vue.js", "RESTful API", "SaaS", "MySQL", "Backend Development"],
    "nationality": "Egyptian",
    "sameAs": [
      "https://github.com/m7mdreda74",
      "https://keshk-portfolio.vercel.app/"
    ],
    "worksFor": {
      "@type": "Organization",
      "name": "Freelance"
    }
  }
  </script>

  <!-- JSON-LD: WebSite schema (enables sitelinks search box) -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Mohamed Keshk Portfolio",
    "alternateName": "Keshk Portfolio",
    "url": "https://keshk-portfolio.vercel.app/",
    "description": "Portfolio of Mohamed Keshk, Backend Developer specializing in Laravel and PHP.",
    "author": {
      "@type": "Person",
      "name": "Mohamed Reda Keshk"
    }
  }
  </script>


  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://keshk-portfolio.vercel.app/">
  <meta property="og:title" content="Mohamed Keshk — Backend Developer Portfolio">
  <meta property="og:description" content="Backend Developer specializing in Laravel, PHP, RESTful APIs, and SaaS architecture.">
  <meta property="og:image" content="https://keshk-portfolio.vercel.app/assets/img/hero-img.png">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Mohamed Keshk — Backend Developer Portfolio">
  <meta name="twitter:description" content="Backend Developer specializing in Laravel, PHP, RESTful APIs, and SaaS architecture.">
  <meta name="twitter:image" content="https://keshk-portfolio.vercel.app/assets/img/hero-img.png">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Preload hero image (LCP element) -->
  <link rel="preload" as="image" href="/assets/img/hero-img.png">

  <!-- Preconnect for fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- Non-render-blocking Google Fonts (preload + onload trick) -->
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>

  <!-- Critical CSS only (needed for first paint) -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

  <!-- Non-critical CSS → loaded async (not needed for first paint) -->
  <link rel="preload" as="style" href="{{ asset('assets/vendor/aos/aos.css') }}" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" as="style" href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" as="style" href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" onload="this.onload=null;this.rel='stylesheet'">

  @vite(['resources/js/app.js', 'resources/css/app.css'])

  <style>
    /* Prevent white flash — dark background shows immediately */
    html, body { overflow-x: hidden !important; max-width: 100vw; background: #0a0612; }

    /* ── Static hero shown instantly before Vue loads ── */
    #static-hero {
      position: relative;
      min-height: 100vh;
      background: #0a0612;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
    }
    #static-hero img.sh-bg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.45;
    }
    #static-hero .sh-content {
      position: relative;
      z-index: 2;
      color: #fff;
      padding: 20px;
    }
    #static-hero .sh-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 18px;
      border-radius: 999px;
      border: 1px solid rgba(144,99,255,.35);
      background: rgba(144,99,255,.08);
      color: #c084fc;
      font-size: 13px;
      margin-bottom: 20px;
    }
    #static-hero .sh-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: #4ade80;
      box-shadow: 0 0 8px rgba(74,222,128,.7);
      animation: sh-blink 1.8s ease-in-out infinite;
    }
    #static-hero h1 {
      font-size: clamp(2rem, 5vw, 3.6rem);
      font-weight: 800;
      color: #fff !important;
      margin-bottom: 12px;
    }
    #static-hero h1 span { color: #c084fc; }
    #static-hero p {
      font-size: clamp(1rem, 2.5vw, 1.4rem);
      color: rgba(255,255,255,.7);
      margin-bottom: 30px;
    }
    #static-hero .sh-btns a {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 28px;
      border-radius: 50px;
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      margin: 6px;
    }
    #static-hero .sh-btn-primary {
      background: linear-gradient(135deg,#9063ff,#c084fc);
      color: #fff;
      box-shadow: 0 6px 24px rgba(144,99,255,.45);
    }
    #static-hero .sh-btn-outline {
      border: 1.5px solid rgba(144,99,255,.45);
      color: #c084fc;
      background: rgba(144,99,255,.06);
    }
    #static-hero .sh-tagline {
      font-size: 12px;
      color: rgba(255,255,255,.5);
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 24px;
    }
    @keyframes sh-blink {
      0%, 100% { opacity: 1; }
      50%       { opacity: 0.3; }
    }
    /* Hide static hero once Vue mounts content */
    #app:not(:empty) ~ #static-hero { display: none; }
  </style>
</head>
<body class="index-page">

  <div id="app"></div>

  <!-- ⚡ Static hero — visible INSTANTLY, replaced by Vue when ready -->
  <div id="static-hero" aria-hidden="true">
    <img class="sh-bg" src="/assets/img/hero-img.png" alt="" fetchpriority="high">
    <div class="sh-content">
      <div class="sh-badge">
        <span class="sh-dot"></span>
        Available for work
      </div>
      <h1>I'm <span>Mohamed Reda Keshk</span></h1>
      <p>A Backend Developer</p>
      <p class="sh-tagline">Building Robust Systems · Clean APIs · Scalable Architectures</p>
      <div class="sh-btns">
        <a href="#about" class="sh-btn-primary"><i class="bi bi-person-lines-fill"></i> About Me</a>
        <a href="#contact" class="sh-btn-outline"><i class="bi bi-envelope-fill"></i> Hire Me</a>
      </div>
    </div>
  </div>

  <!-- Vendor JS — deferred -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}" defer></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}" defer></script>

</body>
</html>
