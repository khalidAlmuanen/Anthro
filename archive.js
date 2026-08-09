document.addEventListener('DOMContentLoaded', () => {

  // ===========================
  // VIEW TOGGLE (Grid / List)
  // ===========================
  const viewGrid = document.getElementById('view-grid');
  const viewList = document.getElementById('view-list');
  const timeline = document.getElementById('archive-timeline');

  viewGrid?.addEventListener('click', () => {
    viewGrid.classList.add('active');
    viewList.classList.remove('active');
    timeline?.classList.remove('list-view');
  });

  viewList?.addEventListener('click', () => {
    viewList.classList.add('active');
    viewGrid.classList.remove('active');
    timeline?.classList.add('list-view');
  });

  // ===========================
  // FILTER ITEMS
  // ===========================
  const filterItems = document.querySelectorAll('.filter-item');
  const activeFilters = { cat: 'all', author: null, year: null, time: null };
  const afc = document.getElementById('active-filters-bar');
  const afcTags = document.getElementById('afc-tags');
  const resultsCount = document.getElementById('results-count');

  filterItems.forEach(item => {
    item.addEventListener('click', () => {
      const cat = item.dataset.cat;
      const author = item.dataset.author;
      const year = item.dataset.year;
      const time = item.dataset.time;

      if (cat) {
        document.querySelectorAll('[data-cat]').forEach(b => b.classList.remove('active'));
        item.classList.add('active');
        activeFilters.cat = cat;
      }
      if (author) {
        document.querySelectorAll('[data-author]').forEach(b => b.classList.remove('active'));
        if (activeFilters.author === author) {
          activeFilters.author = null;
        } else {
          item.classList.add('active');
          activeFilters.author = author;
        }
      }
      if (year) {
        document.querySelectorAll('[data-year]').forEach(b => b.classList.remove('active'));
        if (activeFilters.year === year) {
          activeFilters.year = null;
        } else {
          item.classList.add('active');
          activeFilters.year = year;
        }
      }
      if (time) {
        document.querySelectorAll('[data-time]').forEach(b => b.classList.remove('active'));
        if (activeFilters.time === time) {
          activeFilters.time = null;
        } else {
          item.classList.add('active');
          activeFilters.time = time;
        }
      }

      applyFilters();
      updateActiveBadges();
    });
  });

  function applyFilters() {
    const cards = document.querySelectorAll('.archive-card');
    let visible = 0;

    cards.forEach(card => {
      const cardCat = card.dataset.cat;
      const cardAuthor = card.dataset.author;
      const cardYear = card.dataset.year;

      const catMatch = activeFilters.cat === 'all' || cardCat === activeFilters.cat;
      const authorMatch = !activeFilters.author || cardAuthor === activeFilters.author;
      const yearMatch = !activeFilters.year || cardYear === activeFilters.year;

      const show = catMatch && authorMatch && yearMatch;

      if (show) {
        card.style.display = '';
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';
        visible++;
        requestAnimationFrame(() => {
          card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        });
      } else {
        card.style.opacity = '0';
        card.style.transform = 'translateY(8px)';
        setTimeout(() => { card.style.display = 'none'; }, 250);
      }
    });

    // Update month groups — hide empty ones
    setTimeout(() => {
      document.querySelectorAll('.timeline-month').forEach(month => {
        const visibleCards = month.querySelectorAll('.archive-card:not([style*="display: none"])');
        month.style.display = visibleCards.length === 0 ? 'none' : '';
      });

      if (resultsCount) {
        const arNum = String(visible).replace(/\d/g, d => '٠١٢٣٤٥٦٧٨٩'[d]);
        resultsCount.innerHTML = `عرض <strong>${arNum}</strong> مقال`;
      }
    }, 300);
  }

  function updateActiveBadges() {
    if (!afcTags || !afc) return;
    afcTags.innerHTML = '';
    const hasFilters = activeFilters.cat !== 'all' || activeFilters.author || activeFilters.year || activeFilters.time;
    afc.style.display = hasFilters ? 'flex' : 'none';

    const labels = {
      cat: { all: null, society: 'المجتمع', culture: 'الثقافة', human: 'الإنسان', memory: 'الذاكرة', place: 'المكان' },
      author: { noura: 'د. نورة المحمد', faisal: 'أ. فيصل العتيبي', salma: 'سلمى الغامدي', khalid: 'د. خالد الشهري' },
      year: { '2026': '٢٠٢٦', '2025': '٢٠٢٥', '2024': '٢٠٢٤' },
      time: { short: 'أقل من ٥ دقائق', medium: '٥–١٠ دقائق', long: 'أكثر من ١٠ دقائق' }
    };

    const addBadge = (key, val, label) => {
      if (!val || val === 'all') return;
      const tag = document.createElement('div');
      tag.className = 'afc-tag';
      tag.innerHTML = `<span>${label}</span><span class="afc-remove" data-key="${key}">✕</span>`;
      tag.querySelector('.afc-remove').addEventListener('click', () => {
        activeFilters[key] = key === 'cat' ? 'all' : null;
        document.querySelectorAll(`[data-${key}]`).forEach(b => b.classList.remove('active'));
        if (key === 'cat') document.querySelector('[data-cat="all"]')?.classList.add('active');
        applyFilters(); updateActiveBadges();
      });
      afcTags.appendChild(tag);
    };

    addBadge('cat', activeFilters.cat, labels.cat[activeFilters.cat]);
    if (activeFilters.author) addBadge('author', activeFilters.author, labels.author[activeFilters.author]);
    if (activeFilters.year) addBadge('year', activeFilters.year, labels.year[activeFilters.year]);
    if (activeFilters.time) addBadge('time', activeFilters.time, labels.time[activeFilters.time]);
  }

  // Clear all filters
  document.getElementById('clear-filters')?.addEventListener('click', () => {
    activeFilters.cat = 'all';
    activeFilters.author = null;
    activeFilters.year = null;
    activeFilters.time = null;
    filterItems.forEach(b => b.classList.remove('active'));
    document.querySelector('[data-cat="all"]')?.classList.add('active');
    applyFilters(); updateActiveBadges();
  });

  // ===========================
  // SORT BUTTONS
  // ===========================
  const sortBtns = document.querySelectorAll('.archive-toolbar .sort-btn');
  sortBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      sortBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // ===========================
  // QUICK FILTER SEARCH
  // ===========================
  const searchInput = document.getElementById('archive-search-input');
  let searchTimer;

  searchInput?.addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      const q = searchInput.value.trim().toLowerCase();
      document.querySelectorAll('.archive-card').forEach(card => {
        const title = card.querySelector('.ac-title')?.textContent.toLowerCase() || '';
        const excerpt = card.querySelector('.ac-excerpt')?.textContent.toLowerCase() || '';
        const match = !q || title.includes(q) || excerpt.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) {
          card.style.opacity = '1'; card.style.transform = 'translateY(0)';
        }
      });

      document.querySelectorAll('.timeline-month').forEach(month => {
        const visible = [...month.querySelectorAll('.archive-card')].some(c => c.style.display !== 'none');
        month.style.display = visible ? '' : 'none';
      });
    }, 250);
  });

});
