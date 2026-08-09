/* ===================================
   ANTHRO — Single Article JavaScript
=================================== */

document.addEventListener('DOMContentLoaded', () => {

  // ===========================
  // READING PROGRESS BAR
  // ===========================
  const readingBar = document.getElementById('reading-bar');
  const articleContent = document.getElementById('article-content');

  if (readingBar && articleContent) {
    window.addEventListener('scroll', () => {
      const articleTop = articleContent.offsetTop;
      const articleHeight = articleContent.offsetHeight;
      const scrolled = window.scrollY - articleTop;
      const total = articleHeight - window.innerHeight;
      const pct = Math.min(100, Math.max(0, (scrolled / total) * 100));
      readingBar.style.width = pct + '%';
    }, { passive: true });
  }


  // ===========================
  // TABLE OF CONTENTS — ACTIVE LINK
  // ===========================
  const tocLinks = document.querySelectorAll('.toc-link');
  const sections = document.querySelectorAll('.article-content section[id]');

  if (tocLinks.length && sections.length) {
    const tocObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const id = entry.target.id;
          tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${id}`) {
              link.classList.add('active');
            }
          });
        }
      });
    }, { rootMargin: '-20% 0px -70% 0px' });

    sections.forEach(sec => tocObserver.observe(sec));
  }


  // ===========================
  // BOOKMARK BUTTON
  // ===========================
  const bookmarkBtn = document.getElementById('bookmark-btn');

  if (bookmarkBtn) {
    const savedState = localStorage.getItem('anthro-bookmark-current');
    if (savedState === 'true') {
      bookmarkBtn.classList.add('bookmarked');
    }

    bookmarkBtn.addEventListener('click', () => {
      const isBookmarked = bookmarkBtn.classList.toggle('bookmarked');
      localStorage.setItem('anthro-bookmark-current', isBookmarked);

      // Visual feedback
      bookmarkBtn.style.transform = 'scale(1.3)';
      setTimeout(() => { bookmarkBtn.style.transform = ''; }, 200);
    });
  }


  // ===========================
  // SHARE BUTTONS
  // ===========================
  const shareX = document.getElementById('share-x');
  const shareWa = document.getElementById('share-wa');
  const shareCopy = document.getElementById('share-copy');

  const pageUrl = window.location.href;
  const pageTitle = document.title;

  shareX?.addEventListener('click', () => {
    window.open(`https://x.com/intent/tweet?text=${encodeURIComponent(pageTitle)}&url=${encodeURIComponent(pageUrl)}`, '_blank');
  });

  shareWa?.addEventListener('click', () => {
    window.open(`https://wa.me/?text=${encodeURIComponent(pageTitle + ' ' + pageUrl)}`, '_blank');
  });

  shareCopy?.addEventListener('click', () => {
    navigator.clipboard.writeText(pageUrl).then(() => {
      const original = shareCopy.innerHTML;
      shareCopy.innerHTML = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
      shareCopy.style.background = 'var(--olive)';
      shareCopy.style.color = 'var(--cream)';
      setTimeout(() => {
        shareCopy.innerHTML = original;
        shareCopy.style.background = '';
        shareCopy.style.color = '';
      }, 2000);
    });
  });

  // Same for sidebar share buttons
  document.querySelectorAll('.s-share-btn').forEach((btn, i) => {
    if (i === 2) { // copy button
      btn.addEventListener('click', () => {
        navigator.clipboard.writeText(pageUrl).then(() => {
          btn.style.background = 'var(--olive)';
          btn.style.color = 'var(--cream)';
          setTimeout(() => { btn.style.background = ''; btn.style.color = ''; }, 1500);
        });
      });
    }
  });


  // ===========================
  // STICKY SIDEBAR SCROLL
  // ===========================
  const sidebarShare = document.getElementById('sidebar-share');
  const tocWidget = document.getElementById('toc-widget');

  // Handled by CSS position:sticky — no JS needed


  // ===========================
  // NEWSLETTER SIDEBAR FORM
  // ===========================
  const sidebarNlForm = document.getElementById('sidebar-nl-form');
  sidebarNlForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    const input = sidebarNlForm.querySelector('input');
    const btn = sidebarNlForm.querySelector('button');
    if (input?.value?.includes('@')) {
      btn.textContent = '✓ تم!';
      input.value = '';
      setTimeout(() => { btn.textContent = 'اشترك'; }, 3000);
    }
  });


  // ===========================
  // ARTICLE HERO PARALLAX
  // ===========================
  const heroImg = document.querySelector('.article-hero-img');
  if (heroImg && window.innerWidth > 768) {
    window.addEventListener('scroll', () => {
      heroImg.style.transform = `translateY(${window.scrollY * 0.25}px)`;
    }, { passive: true });
  }

});
