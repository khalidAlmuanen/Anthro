document.addEventListener('DOMContentLoaded', () => {

  const searchInput = document.getElementById('main-search-input');
  const clearBtn = document.getElementById('sb-clear');
  const typeBtns = document.querySelectorAll('.stf-btn');
  const sortBtns = document.querySelectorAll('.results-header .sort-btn');
  const resultGroups = document.querySelectorAll('.results-group');
  const noResults = document.getElementById('no-results');
  const resultsSection = document.getElementById('search-results-section');
  const resultsSummary = document.getElementById('results-summary');

  // ===========================
  // CLEAR BUTTON
  // ===========================
  clearBtn?.addEventListener('click', () => {
    if (searchInput) searchInput.value = '';
    searchInput?.focus();
  });

  // ===========================
  // TYPE FILTER TABS
  // ===========================
  typeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      typeBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const type = btn.dataset.type;

      if (type === 'all') {
        resultGroups.forEach(g => { g.style.display = ''; });
      } else {
        resultGroups.forEach(g => {
          const match = g.id === `rg-${type}`;
          g.style.display = match ? '' : 'none';
        });
      }
    });
  });

  // ===========================
  // SORT BUTTONS
  // ===========================
  sortBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      sortBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // ===========================
  // LIVE SEARCH (simulate)
  // ===========================
  let searchTimer;
  searchInput?.addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      const q = searchInput.value.trim();

      if (!q) {
        // Show all
        resultGroups.forEach(g => { g.style.opacity = '1'; });
        if (noResults) noResults.style.display = 'none';
        if (resultsSection) resultsSection.style.display = '';
        return;
      }

      // In production: AJAX call to WP search API
      // For prototype: highlight the query term
      const allTitles = document.querySelectorAll('.result-title a, .arc-name');
      let hasResult = false;

      allTitles.forEach(el => {
        const card = el.closest('.result-card, .author-result-card');
        if (!card) return;
        const text = el.textContent.toLowerCase();
        if (text.includes(q.toLowerCase())) {
          card.style.display = '';
          hasResult = true;
        } else {
          card.style.display = 'none';
        }
      });

      // Show/hide empty groups
      resultGroups.forEach(g => {
        const visible = [...g.querySelectorAll('.result-card, .author-result-card')]
          .some(c => c.style.display !== 'none');
        g.style.display = visible ? '' : 'none';
      });

      if (!hasResult) {
        if (noResults) noResults.style.display = '';
        if (resultsSection) resultsSection.style.display = 'none';
      } else {
        if (noResults) noResults.style.display = 'none';
        if (resultsSection) resultsSection.style.display = '';
      }

    }, 200);
  });

  // ===========================
  // PODCAST PLAY BUTTON IN RESULTS
  // ===========================
  document.querySelectorAll('.rp-play').forEach(btn => {
    btn.addEventListener('click', () => {
      const isPlaying = btn.dataset.playing === 'true';
      // Reset all
      document.querySelectorAll('.rp-play').forEach(b => {
        b.dataset.playing = 'false';
        b.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
        b.style.background = 'rgba(255,255,255,0.15)';
      });
      if (!isPlaying) {
        btn.dataset.playing = 'true';
        btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>';
        btn.style.background = 'var(--copper)';
      }
    });
  });

  // ===========================
  // SUGGESTIONS HIDE ON OUTSIDE CLICK
  // ===========================
  document.addEventListener('click', (e) => {
    const wrap = document.querySelector('.search-bar-wrap');
    if (!wrap?.contains(e.target)) {
      document.querySelector('.search-suggestions')?.classList.remove('force-show');
    }
  });

});
