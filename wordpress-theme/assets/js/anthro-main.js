/* ===================================
   ANTHRO — JavaScript
=================================== */

document.addEventListener('DOMContentLoaded', () => {

  // ===========================
  // 1. HEADER SCROLL BEHAVIOR
  // ===========================
  const header = document.getElementById('site-header');
  let lastScroll = 0;

  window.addEventListener('scroll', () => {
    const currentScroll = window.scrollY;

    if (currentScroll > 60) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    lastScroll = currentScroll;
  }, { passive: true });


  // ===========================
  // 2. SEARCH TOGGLE
  // ===========================
  const searchToggle = document.getElementById('search-toggle');
  const searchOverlay = document.getElementById('search-overlay');
  const searchClose = document.getElementById('search-close');
  const searchInput = document.getElementById('search-input');

  if (searchToggle && searchOverlay) {
    searchToggle.addEventListener('click', () => {
      searchOverlay.classList.toggle('open');
      if (searchOverlay.classList.contains('open')) {
        setTimeout(() => searchInput?.focus(), 300);
      }
    });

    searchClose?.addEventListener('click', () => {
      searchOverlay.classList.remove('open');
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') searchOverlay.classList.remove('open');
    });
  }


  // ===========================
  // 3. MOBILE MENU
  // ===========================
  const menuToggle = document.getElementById('menu-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  const navOverlay = document.getElementById('nav-overlay');

  function openMenu() {
    mobileNav?.classList.add('open');
    navOverlay?.classList.add('show');
    menuToggle?.classList.add('open');
    menuToggle?.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    mobileNav?.classList.remove('open');
    navOverlay?.classList.remove('show');
    menuToggle?.classList.remove('open');
    menuToggle?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  menuToggle?.addEventListener('click', () => {
    mobileNav?.classList.contains('open') ? closeMenu() : openMenu();
  });

  navOverlay?.addEventListener('click', closeMenu);

  // Close mobile nav when link clicked
  document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', closeMenu);
  });


  // ===========================
  // 4. MINI PODCAST PLAYER
  // ===========================
  const ppBtn = document.getElementById('pp1');
  const mpFill = document.getElementById('mp-fill-1');
  const mpThumb = document.getElementById('mp-thumb-1');
  const mpBar = document.getElementById('mp-bar-1');

  let isPlaying = false;
  let progress = 35; // percent
  let animFrame;

  if (ppBtn) {
    ppBtn.addEventListener('click', () => {
      isPlaying = !isPlaying;
      const iPlay = ppBtn.querySelector('.i-play');
      const iPause = ppBtn.querySelector('.i-pause');

      if (isPlaying) {
        iPlay.style.display = 'none';
        iPause.style.display = 'block';
        animatePlayer();
      } else {
        iPlay.style.display = 'block';
        iPause.style.display = 'none';
        cancelAnimationFrame(animFrame);
      }
    });
  }

  function animatePlayer() {
    if (!isPlaying) return;
    progress += 0.008;
    if (progress >= 100) progress = 0;
    updatePlayer(progress);
    animFrame = requestAnimationFrame(animatePlayer);
  }

  function updatePlayer(pct) {
    if (mpFill) mpFill.style.width = pct + '%';
    if (mpThumb) mpThumb.style.right = (100 - pct) + '%';
  }

  // Click on progress bar to seek
  mpBar?.addEventListener('click', (e) => {
    const rect = mpBar.getBoundingClientRect();
    // RTL: click from right
    const clickX = rect.right - e.clientX;
    const pct = (clickX / rect.width) * 100;
    progress = Math.max(0, Math.min(100, pct));
    updatePlayer(progress);
  });


  // ===========================
  // 5. FILTER TABS
  // ===========================
  const filterBtns = document.querySelectorAll('.flt-btn, .filter-btn');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.cat || btn.dataset.f || '0';
      const artCards = document.querySelectorAll('#articles-grid .art-card, #art-grid .art-card, .art-grid .art-card');

      artCards.forEach(card => {
        const cat = card.dataset.cat || '0';
        const matches = filter === 'all' || filter === '0' || cat === filter;

        if (matches) {
          card.style.display = '';
          card.style.opacity = '0';
          card.style.transform = 'translateY(12px)';
          requestAnimationFrame(() => {
            card.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          });
        } else {
          card.style.opacity = '0';
          card.style.transform = 'translateY(8px)';
          setTimeout(() => { card.style.display = 'none'; }, 300);
        }
      });
    });
  });



  // ===========================
  // 6. INTERSECTION OBSERVER (FADE IN)
  // ===========================
  const fadeEls = document.querySelectorAll(
    '.cat-card, .art-card, .au-card, .ep-card, .stat'
  );

  fadeEls.forEach((el, i) => {
    el.classList.add('fade-in');
    el.style.transitionDelay = `${(i % 4) * 80}ms`;
  });

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  fadeEls.forEach(el => observer.observe(el));


  // ===========================
  // 7. BACK TO TOP
  // ===========================
  const backTop = document.getElementById('back-top');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 400) {
      backTop?.classList.add('show');
    } else {
      backTop?.classList.remove('show');
    }
  }, { passive: true });

  backTop?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });


  // ===========================
  // 8. NEWSLETTER FORM
  // ===========================
  const nlForm = document.getElementById('nl-form');
  const nlEmail = document.getElementById('nl-email');

  nlForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = nlEmail?.value?.trim();

    if (!email || !email.includes('@')) {
      nlEmail.style.borderColor = 'rgba(200,60,60,0.5)';
      nlEmail.placeholder = 'يرجى إدخال بريد إلكتروني صحيح';
      setTimeout(() => {
        nlEmail.style.borderColor = '';
        nlEmail.placeholder = 'بريدك الإلكتروني';
      }, 2500);
      return;
    }

    // Success feedback
    const btn = nlForm.querySelector('button');
    if (btn) {
      btn.textContent = '✓ تم الاشتراك!';
      btn.style.background = '#4a7c59';
      nlEmail.value = '';
      setTimeout(() => {
        btn.textContent = 'اشترك';
        btn.style.background = '';
      }, 3500);
    }
  });


  // ===========================
  // 9. EPISODE ROW PLAY BUTTONS
  // ===========================
  document.querySelectorAll('.ep-play-btn:not(#ep-feat .ep-play-btn)').forEach(btn => {
    btn.addEventListener('click', () => {
      const svg = btn.querySelector('svg');
      const isPlaying = btn.dataset.playing === 'true';

      // Reset all
      document.querySelectorAll('.ep-play-btn').forEach(b => {
        b.dataset.playing = 'false';
        b.style.background = '';
      });

      if (!isPlaying) {
        btn.dataset.playing = 'true';
        btn.style.background = 'rgba(196,122,68,0.9)';
      }
    });
  });


  // ===========================
  // 10. SMOOTH SCROLLING
  // ===========================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const offset = target.getBoundingClientRect().top + window.scrollY - 80;
        window.scrollTo({ top: offset, behavior: 'smooth' });
      }
    });
  });


  // ===========================
  // 11. HERO PARALLAX
  // ===========================
  const heroBg = document.querySelector('.hero-img-placeholder');

  if (heroBg && window.innerWidth > 768) {
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      heroBg.style.transform = `translateY(${scrolled * 0.3}px)`;
    }, { passive: true });
  }

  console.log('%c أنثرو — أنثروبولوجيا سعودية 🌿', 'font-family: sans-serif; font-size: 18px; color: #686848; font-weight: bold;');
});
