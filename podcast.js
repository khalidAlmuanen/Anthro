/* ===================================
   ANTHRO — Podcast Page JavaScript
=================================== */

document.addEventListener('DOMContentLoaded', () => {

  // ===========================
  // FULL AUDIO PLAYER
  // ===========================
  const apPlayPause = document.getElementById('ap-playpause');
  const apBar = document.getElementById('ap-bar');
  const apPlayed = document.getElementById('ap-played');
  const apThumb = document.getElementById('ap-thumb');
  const apCurrent = document.getElementById('ap-current');
  const vinylDisc = document.getElementById('vinyl-disc');
  const apWaveform = document.getElementById('ap-waveform');

  let isPlaying = false;
  let progress = 0;
  let rafId;
  const totalSeconds = 52 * 60; // 52 minutes
  let currentSeconds = 0;

  // Generate waveform bars
  if (apWaveform) {
    const wfBars = 80;
    for (let i = 0; i < wfBars; i++) {
      const bar = document.createElement('div');
      bar.className = 'ap-wf-bar';
      const h = 10 + Math.random() * 38;
      bar.style.height = h + 'px';
      bar.dataset.idx = i;
      apWaveform.appendChild(bar);
    }
  }

  function updateWaveform(pct) {
    if (!apWaveform) return;
    const bars = apWaveform.querySelectorAll('.ap-wf-bar');
    const playedCount = Math.floor(bars.length * pct / 100);
    bars.forEach((bar, i) => {
      bar.classList.toggle('played', i < playedCount);
    });
  }

  function formatTime(secs) {
    const m = Math.floor(secs / 60);
    const s = Math.floor(secs % 60);
    const mAr = m.toString().replace(/\d/g, d => '٠١٢٣٤٥٦٧٨٩'[d]);
    const sAr = s.toString().padStart(2, '0').replace(/\d/g, d => '٠١٢٣٤٥٦٧٨٩'[d]);
    return `${mAr}:${sAr}`;
  }

  function updatePlayer() {
    if (!isPlaying) return;
    currentSeconds += 0.05;
    if (currentSeconds >= totalSeconds) {
      currentSeconds = 0;
      isPlaying = false;
      togglePlayPause();
    }
    const pct = (currentSeconds / totalSeconds) * 100;
    if (apPlayed) apPlayed.style.width = pct + '%';
    if (apThumb) apThumb.style.right = (100 - pct) + '%';
    if (apCurrent) apCurrent.textContent = formatTime(currentSeconds);
    updateWaveform(pct);
    rafId = requestAnimationFrame(updatePlayer);
  }

  function togglePlayPause() {
    if (!apPlayPause) return;
    isPlaying = !isPlaying;
    const iconPlay = apPlayPause.querySelector('.ap-icon-play');
    const iconPause = apPlayPause.querySelector('.ap-icon-pause');

    if (isPlaying) {
      iconPlay.style.display = 'none';
      iconPause.style.display = 'block';
      vinylDisc?.classList.add('spinning');
      document.querySelectorAll('.player-art-waves span').forEach(s => {
        s.style.animationPlayState = 'running';
      });
      rafId = requestAnimationFrame(updatePlayer);
    } else {
      iconPlay.style.display = 'block';
      iconPause.style.display = 'none';
      vinylDisc?.classList.remove('spinning');
      cancelAnimationFrame(rafId);
    }
  }

  apPlayPause?.addEventListener('click', togglePlayPause);

  // Click on progress bar to seek
  apBar?.addEventListener('click', (e) => {
    const rect = apBar.getBoundingClientRect();
    const clickX = rect.right - e.clientX;
    const pct = (clickX / rect.width) * 100;
    const clamped = Math.max(0, Math.min(100, pct));
    currentSeconds = (clamped / 100) * totalSeconds;
    if (apPlayed) apPlayed.style.width = clamped + '%';
    if (apThumb) apThumb.style.right = (100 - clamped) + '%';
    if (apCurrent) apCurrent.textContent = formatTime(currentSeconds);
    updateWaveform(clamped);
  });

  // Waveform click to seek
  apWaveform?.addEventListener('click', (e) => {
    const rect = apWaveform.getBoundingClientRect();
    const clickX = e.clientX - rect.left;
    // LTR waveform
    const pct = (clickX / rect.width) * 100;
    currentSeconds = (pct / 100) * totalSeconds;
    if (apPlayed) apPlayed.style.width = pct + '%';
    if (apCurrent) apCurrent.textContent = formatTime(currentSeconds);
    updateWaveform(pct);
  });

  // Skip buttons
  document.getElementById('ap-back15')?.addEventListener('click', () => {
    currentSeconds = Math.max(0, currentSeconds - 15);
    const pct = (currentSeconds / totalSeconds) * 100;
    if (apPlayed) apPlayed.style.width = pct + '%';
    if (apCurrent) apCurrent.textContent = formatTime(currentSeconds);
    updateWaveform(pct);
  });
  document.getElementById('ap-fwd15')?.addEventListener('click', () => {
    currentSeconds = Math.min(totalSeconds, currentSeconds + 15);
    const pct = (currentSeconds / totalSeconds) * 100;
    if (apPlayed) apPlayed.style.width = pct + '%';
    if (apCurrent) apCurrent.textContent = formatTime(currentSeconds);
    updateWaveform(pct);
  });

  // Speed toggle
  const speedBtn = document.getElementById('ap-speed-btn');
  const speeds = ['١×', '١.٢٥×', '١.٥×', '١.٧٥×', '٢×'];
  let speedIdx = 0;
  speedBtn?.addEventListener('click', () => {
    speedIdx = (speedIdx + 1) % speeds.length;
    speedBtn.textContent = speeds[speedIdx];
  });

  // Volume
  const volFill = document.getElementById('ap-vol-fill');
  const volBar = document.querySelector('.ap-vol-bar');
  volBar?.addEventListener('click', (e) => {
    const rect = volBar.getBoundingClientRect();
    const pct = ((e.clientX - rect.left) / rect.width) * 100;
    if (volFill) volFill.style.width = Math.max(0, Math.min(100, pct)) + '%';
  });


  // ===========================
  // SEASON TABS
  // ===========================
  const seasonTabs = document.querySelectorAll('.season-tab');

  seasonTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      seasonTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      // Visual feedback — in production this would load new episodes
      const epList = document.getElementById('episodes-list');
      if (epList) {
        epList.style.opacity = '0.5';
        epList.style.transition = 'opacity 0.3s';
        setTimeout(() => {
          epList.style.opacity = '1';
        }, 400);
      }
    });
  });


  // ===========================
  // EPISODE SAVE BUTTONS
  // ===========================
  document.querySelectorAll('.ep-save-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.classList.toggle('saved');
      const isSaved = btn.classList.contains('saved');
      btn.innerHTML = isSaved
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>';
      btn.style.transform = 'scale(1.3)';
      setTimeout(() => { btn.style.transform = ''; }, 200);
    });
  });


  // ===========================
  // EPISODE ROW PLAY BUTTONS
  // ===========================
  document.querySelectorAll('.ep-item-play').forEach(btn => {
    btn.addEventListener('click', () => {
      // Scroll to player and start
      document.getElementById('featured-ep')?.scrollIntoView({ behavior: 'smooth' });
      setTimeout(() => {
        if (!isPlaying) togglePlayPause();
      }, 700);
    });
  });

});
