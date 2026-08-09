/**
 * anthro-podcast.js — Anthro Theme
 *
 * مشغّل صوت حقيقي يعمل على عنصر <audio> فعلي.
 * يحل محل المنطق الوهمي في podcast.js (الذي كان يستخدم
 * مؤقتاً ثابتاً 52 دقيقة بلا صوت).
 *
 * يحافظ على نفس الـ IDs والكلاسات في podcast.css.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    var audio = document.getElementById('anthro-audio');
    if (!audio) return;

    var playPause  = document.getElementById('ap-playpause');
    var bar        = document.getElementById('ap-bar');
    var played     = document.getElementById('ap-played');
    var thumb      = document.getElementById('ap-thumb');
    var currentEl  = document.getElementById('ap-current');
    var durationEl = document.getElementById('ap-duration');
    var waveform   = document.getElementById('ap-waveform');
    var vinyl      = document.getElementById('vinyl-disc');
    var speedBtn   = document.getElementById('ap-speed-btn');
    var volFill    = document.getElementById('ap-vol-fill');
    var volBar     = document.querySelector('.ap-vol-bar');
    var isRTL      = document.documentElement.dir === 'rtl';

    /* ---------- تحويل الأرقام للعربية ---------- */
    function toArabic(str) {
      return String(str).replace(/\d/g, function (d) {
        return '٠١٢٣٤٥٦٧٨٩'[d];
      });
    }

    function formatTime(secs) {
      if (!isFinite(secs) || secs < 0) secs = 0;
      var m = Math.floor(secs / 60);
      var s = Math.floor(secs % 60);
      return toArabic(m) + ':' + toArabic(String(s).padStart(2, '0'));
    }

    /* ---------- بناء الموجة الصوتية ---------- */
    var BAR_COUNT = 80;
    if (waveform && !waveform.children.length) {
      var frag = document.createDocumentFragment();
      for (var i = 0; i < BAR_COUNT; i++) {
        var b = document.createElement('div');
        b.className = 'ap-wf-bar';
        b.style.height = (10 + Math.random() * 38) + 'px';
        b.dataset.idx = i;
        frag.appendChild(b);
      }
      waveform.appendChild(frag);
    }

    function paintWaveform(pct) {
      if (!waveform) return;
      var bars = waveform.querySelectorAll('.ap-wf-bar');
      var upTo = Math.floor(bars.length * pct / 100);
      for (var i = 0; i < bars.length; i++) {
        bars[i].classList.toggle('played', i < upTo);
      }
    }

    /* ---------- تحديث واجهة التقدم ---------- */
    function render() {
      var dur = audio.duration;
      if (!isFinite(dur) || dur === 0) return;

      var pct = (audio.currentTime / dur) * 100;

      if (played) played.style.width = pct + '%';
      if (thumb) {
        // في RTL يتحرك المؤشر من اليمين
        thumb.style[isRTL ? 'right' : 'left'] = pct + '%';
      }
      if (currentEl) currentEl.textContent = formatTime(audio.currentTime);
      paintWaveform(pct);
    }

    audio.addEventListener('timeupdate', render);

    audio.addEventListener('loadedmetadata', function () {
      if (durationEl) durationEl.textContent = formatTime(audio.duration);
      render();
    });

    audio.addEventListener('error', function () {
      if (currentEl) currentEl.textContent = '—';
      if (durationEl) durationEl.textContent = '—';
      console.warn('[Anthro] تعذّر تحميل ملف الصوت:', audio.currentSrc);
    });

    /* ---------- تشغيل / إيقاف ---------- */
    function syncPlayIcons() {
      if (!playPause) return;
      var iconPlay  = playPause.querySelector('.ap-icon-play');
      var iconPause = playPause.querySelector('.ap-icon-pause');
      var playing   = !audio.paused;

      if (iconPlay)  iconPlay.style.display  = playing ? 'none' : '';
      if (iconPause) iconPause.style.display = playing ? '' : 'none';

      playPause.setAttribute('aria-pressed', playing ? 'true' : 'false');

      if (vinyl) vinyl.classList.toggle('spinning', playing);

      document.querySelectorAll('.player-art-waves span').forEach(function (s) {
        s.style.animationPlayState = playing ? 'running' : 'paused';
      });
    }

    if (playPause) {
      playPause.addEventListener('click', function () {
        if (audio.paused) {
          var p = audio.play();
          if (p && p.catch) {
            p.catch(function (err) {
              console.warn('[Anthro] فشل التشغيل:', err);
            });
          }
        } else {
          audio.pause();
        }
      });
    }

    audio.addEventListener('play',  syncPlayIcons);
    audio.addEventListener('pause', syncPlayIcons);
    audio.addEventListener('ended', function () {
      audio.currentTime = 0;
      syncPlayIcons();
      render();
    });

    /* ---------- السحب على شريط التقدم (RTL-aware) ---------- */
    function seekFromEvent(el, e) {
      var dur = audio.duration;
      if (!isFinite(dur) || dur === 0) return;

      var rect = el.getBoundingClientRect();
      var x    = e.clientX - rect.left;
      var ratio = isRTL ? (1 - x / rect.width) : (x / rect.width);
      ratio = Math.min(1, Math.max(0, ratio));

      audio.currentTime = ratio * dur;
      render();
    }

    if (bar) {
      bar.addEventListener('click', function (e) { seekFromEvent(bar, e); });
    }
    if (waveform) {
      waveform.addEventListener('click', function (e) { seekFromEvent(waveform, e); });
    }

    /* ---------- تقديم وتأخير 15 ثانية ---------- */
    var back = document.getElementById('ap-back15');
    var fwd  = document.getElementById('ap-fwd15');

    if (back) back.addEventListener('click', function () {
      audio.currentTime = Math.max(0, audio.currentTime - 15);
    });

    if (fwd) fwd.addEventListener('click', function () {
      audio.currentTime = Math.min(audio.duration || 0, audio.currentTime + 15);
    });

    /* ---------- سرعة التشغيل ---------- */
    var SPEEDS = [1, 1.25, 1.5, 2, 0.75];
    var speedIdx = 0;

    if (speedBtn) {
      speedBtn.addEventListener('click', function () {
        speedIdx = (speedIdx + 1) % SPEEDS.length;
        audio.playbackRate = SPEEDS[speedIdx];
        speedBtn.textContent = toArabic(SPEEDS[speedIdx]) + '×';
      });
    }

    /* ---------- التحكم بالصوت (RTL-aware) ---------- */
    if (volBar) {
      volBar.addEventListener('click', function (e) {
        var rect = volBar.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var ratio = isRTL ? (1 - x / rect.width) : (x / rect.width);
        ratio = Math.min(1, Math.max(0, ratio));

        audio.volume = ratio;
        if (volFill) volFill.style.width = (ratio * 100) + '%';
      });
    }

    var volBtn = document.getElementById('ap-vol');
    var lastVolume = 0.8;

    if (volBtn) {
      volBtn.addEventListener('click', function () {
        if (audio.volume > 0) {
          lastVolume = audio.volume;
          audio.volume = 0;
          if (volFill) volFill.style.width = '0%';
        } else {
          audio.volume = lastVolume;
          if (volFill) volFill.style.width = (lastVolume * 100) + '%';
        }
      });
    }

    audio.volume = 0.8;

    /* ---------- اختصارات لوحة المفاتيح ---------- */
    document.addEventListener('keydown', function (e) {
      var tag = (e.target.tagName || '').toLowerCase();
      if (tag === 'input' || tag === 'textarea' || e.target.isContentEditable) return;

      if (e.code === 'Space') {
        e.preventDefault();
        audio.paused ? audio.play() : audio.pause();
      } else if (e.code === 'ArrowRight') {
        audio.currentTime = Math.min(audio.duration || 0, audio.currentTime + 5);
      } else if (e.code === 'ArrowLeft') {
        audio.currentTime = Math.max(0, audio.currentTime - 5);
      }
    });

    syncPlayIcons();
  });
})();
