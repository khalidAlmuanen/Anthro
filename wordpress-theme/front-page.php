<?php
/**
 * front-page.php — Anthro Theme
 * صفحة الرئيسية — مطابقة 100% للنموذج الأولي مع الربط الديناميكي مع ووردبريس
 */

get_header();

// Fetch Hero Featured Article
$featured_query = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'meta_query'     => [ [ 'key' => '_anthro_featured', 'value' => '1' ] ],
]);

if ( ! $featured_query->have_posts() ) {
    $featured_query = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 1 ] );
}

$total_articles = wp_count_posts('post')->publish ?: 240;
$total_episodes = wp_count_posts('podcast_episode')->publish ?: 3;
$total_authors  = count_users()['total_users'] ?: 12;
$total_cats     = wp_count_terms('category') ?: 68;
?>

<main>

  <!-- ============================
       HERO SECTION
  ============================= -->
  <section class="hero" id="hero">
    <div class="hero-bg">
      <div class="hero-img-placeholder" style="background-image: url('<?php echo ANTHRO_URI; ?>/assets/images/hero_architecture.png'); background-size: cover; background-position: center;"></div>
      <div class="hero-overlay"></div>
      <div class="hero-geo-pattern pattern-topography"></div>
    </div>
    <div class="hero-content container">
      <div class="hero-eyebrow">
        <span class="eyebrow-dot"></span>
        <span>الإنسان &bull; الثقافة &bull; المجتمع &bull; الذاكرة الحية</span>
      </div>

      <?php if ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
        <h1 class="hero-title"><?php the_title(); ?></h1>
        <p class="hero-sub"><?php echo wp_trim_words( get_the_excerpt(), 28, '...' ); ?></p>
        <div class="hero-ctas">
          <a href="<?php the_permalink(); ?>" class="btn btn--primary">
            اقرأ المقال المميز
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="#podcast" class="btn btn--ghost">
            <svg width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="rgba(255,255,255,0.12)"/><polygon points="10 8 16 12 10 16 10 8" fill="white"/></svg>
            استمع للبودكاست
          </a>
        </div>
      <?php wp_reset_postdata(); else : ?>
        <h1 class="hero-title">أنثروبولوجيا<br /><em class="hero-em">سعودية</em></h1>
        <p class="hero-sub">نستكشف الإنسان من الداخل، نقرأ الثقافة كنسيج حي،<br />ونحفظ الذاكرة قبل أن يطمسها الزمن.</p>
        <div class="hero-ctas">
          <a href="#featured-stories" class="btn btn--primary">
            استكشف المحتوى
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <a href="#podcast" class="btn btn--ghost">
            <svg width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="rgba(255,255,255,0.12)"/><polygon points="10 8 16 12 10 16 10 8" fill="white"/></svg>
            استمع للبودكاست
          </a>
        </div>
      <?php endif; ?>

      <div class="scroll-hint">
        <div class="scroll-line"><div class="scroll-dot-anim"></div></div>
      </div>
    </div>
    <div class="hero-stats-bar">
      <div class="container">
        <div class="stats-row">
          <div class="stat">
            <span class="stat-n"><?php echo anthro_arabic_num($total_articles); ?><span class="stat-plus">+</span></span>
            <span class="stat-l">مقال أنثروبولوجي</span>
          </div>
          <div class="stat-sep"></div>
          <div class="stat">
            <span class="stat-n"><?php echo anthro_arabic_num($total_episodes); ?></span>
            <span class="stat-l">مواسم بودكاست</span>
          </div>
          <div class="stat-sep"></div>
          <div class="stat">
            <span class="stat-n">٦٨<span class="stat-plus">+</span></span>
            <span class="stat-l">قصة إنسانية</span>
          </div>
          <div class="stat-sep"></div>
          <div class="stat">
            <span class="stat-n"><?php echo anthro_arabic_num($total_authors); ?></span>
            <span class="stat-l">باحث وكاتب</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================
       CATEGORIES SECTION
  ============================= -->
  <section class="cats-section section-space pattern-sadu" id="categories">
    <div class="container">
      <div class="cats-grid">
        <?php
        $cats = get_categories( [ 'number' => 4, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => false ] );
        $default_cats = [
          [ 'name' => 'مقالات وورشات', 'desc' => 'دراسات أنثروبولوجية معمّقة', 'badge' => '٢٤٠ مقال', 'url' => esc_url(get_post_type_archive_link('post')), 'icon' => '<rect x="8" y="10" width="32" height="4" rx="2" fill="currentColor" opacity="0.4"/><rect x="8" y="20" width="32" height="4" rx="2" fill="currentColor"/><rect x="8" y="30" width="22" height="4" rx="2" fill="currentColor" opacity="0.7"/><rect x="8" y="38" width="16" height="4" rx="2" fill="currentColor" opacity="0.4"/>' ],
          [ 'name' => 'بودكاست', 'desc' => 'حوارات مع الباحثين والمجتمعات', 'badge' => '٣ مواسم', 'url' => esc_url(get_post_type_archive_link('podcast_episode')), 'icon' => '<circle cx="24" cy="20" r="8" stroke="currentColor" stroke-width="2.5"/><path d="M12 26c0 6.627 5.373 12 12 12s12-5.373 12-12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><line x1="24" y1="38" x2="24" y2="44" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><line x1="18" y1="44" x2="30" y2="44" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>' ],
          [ 'name' => 'قصص إنسانية', 'desc' => 'من قلب المجتمعات السعودية', 'badge' => '٦٨ قصة', 'url' => esc_url(home_url('/category/human-stories')), 'icon' => '<circle cx="24" cy="16" r="8" stroke="currentColor" stroke-width="2.5"/><path d="M8 42c0-8.837 7.163-16 16-16s16 7.163 16 16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>' ],
          [ 'name' => 'شبكات ووثائقية', 'desc' => 'تعلّم وانغمس في الأنثروبولوجيا', 'badge' => '١٢ برنامج', 'url' => esc_url(home_url('/about#academy')), 'icon' => '<path d="M24 8L42 18v14L24 40 6 32V18L24 8z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><circle cx="24" cy="26" r="5" fill="currentColor" opacity="0.4"/><line x1="24" y1="8" x2="24" y2="21" stroke="currentColor" stroke-width="2" opacity="0.5"/>' ],
        ];

        if ( ! empty( $cats ) ) :
          foreach ( $cats as $i => $cat ) :
            $d = $default_cats[$i % count($default_cats)];
        ?>
            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="cat-card">
              <div class="cat-icon-wrap"><svg viewBox="0 0 48 48" fill="none"><?php echo $d['icon']; ?></svg></div>
              <h3 class="cat-name"><?php echo esc_html($cat->name); ?></h3>
              <p class="cat-desc"><?php echo esc_html($cat->description ?: $d['desc']); ?></p>
              <span class="cat-badge"><?php echo anthro_arabic_num($cat->count); ?> مقال</span>
            </a>
        <?php
          endforeach;
        else :
          foreach ( $default_cats as $d ) :
        ?>
            <a href="<?php echo $d['url']; ?>" class="cat-card">
              <div class="cat-icon-wrap"><svg viewBox="0 0 48 48" fill="none"><?php echo $d['icon']; ?></svg></div>
              <h3 class="cat-name"><?php echo $d['name']; ?></h3>
              <p class="cat-desc"><?php echo $d['desc']; ?></p>
              <span class="cat-badge"><?php echo $d['badge']; ?></span>
            </a>
        <?php
          endforeach;
        endif;
        ?>
      </div>
    </div>
  </section>

  <!-- ============================
       FEATURED STORIES
  ============================= -->
  <section class="featured-section section-space pattern-topography" id="featured-stories">
    <div class="container">
      <div class="sec-header">
        <div class="sec-label"><span class="sec-line"></span><span>دراسات مميّزة</span></div>
        <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="sec-more">عرض الكل <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
      </div>

      <div class="featured-layout">
        <?php
        $stories_query = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ( $stories_query->have_posts() ) :
          $count = 0;
          while ( $stories_query->have_posts() ) : $stories_query->the_post();
            $count++;
            if ( $count === 1 ) :
        ?>
              <article class="art-card art-card--hero">
                <a href="<?php the_permalink(); ?>" class="art-img-link">
                  <div class="art-img" style="background-image: url('<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url() : ANTHRO_URI . '/assets/images/hijazi_roshan.png'; ?>');">
                    <span class="art-tag">دراسة غلاف</span>
                  </div>
                </a>
                <div class="art-body">
                  <div class="art-meta">
                    <span class="art-cat"><?php the_category(' • '); ?></span>
                    <span class="meta-sep">&bull;</span>
                    <time><?php echo get_the_date(); ?></time>
                  </div>
                  <h2 class="art-title art-title--lg"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                  <p class="art-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?></p>
                  <div class="art-footer">
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="art-author">
                      <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'av']); ?>
                      <span><?php the_author(); ?></span>
                    </a>
                    <span class="read-time"><?php echo anthro_arabic_num(anthro_reading_time()); ?> دقائق قراءة</span>
                  </div>
                </div>
              </article>
              <div class="art-side">
        <?php
            else :
        ?>
              <article class="art-card art-card--side">
                <a href="<?php the_permalink(); ?>" class="art-img-link">
                  <div class="art-img" style="background-image: url('<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url() : ($count === 2 ? ANTHRO_URI . '/assets/images/coffee_culture.png' : ANTHRO_URI . '/assets/images/archaeology_alula.png'); ?>');"></div>
                </a>
                <div class="art-body">
                  <div class="art-meta">
                    <span class="art-cat"><?php the_category(' • '); ?></span>
                  </div>
                  <h3 class="art-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <div class="art-footer">
                    <span class="read-time"><?php echo anthro_arabic_num(anthro_reading_time()); ?> دقائق قراءة</span>
                  </div>
                </div>
              </article>
        <?php
            endif;
          endwhile;
          echo '</div>'; // Close .art-side
          wp_reset_postdata();
        else :
        ?>
          <!-- Prototype Rich Fallback Cards -->
          <article class="art-card art-card--hero">
            <a href="#" class="art-img-link">
              <div class="art-img" style="background-image: url('<?php echo ANTHRO_URI; ?>/assets/images/hijazi_roshan.png');">
                <span class="art-tag">دراسة غلاف</span>
              </div>
            </a>
            <div class="art-body">
              <div class="art-meta">
                <span class="art-cat">أنثروبولوجيا العمران</span>
                <span class="meta-sep">&bull;</span>
                <time>١٢ يناير ٢٠٢٦</time>
              </div>
              <h2 class="art-title art-title--lg"><a href="#">روشن الحجاز: هندسة الضوء والخصوصية في المعمار التقليدي</a></h2>
              <p class="art-excerpt">يمثل الروشن الحجازي إحدى أبرز المفردات المعمارية التقليدية في غرب الجزيرة العربية، حيث يدمج بين الوظيفة البيئية لتهوية المنازل والجمالية البصرية.</p>
              <div class="art-footer">
                <a href="#" class="art-author">
                  <div class="av av--1"></div>
                  <span>د. سارة العتيبي</span>
                </a>
                <span class="read-time">٨ دقائق قراءة</span>
              </div>
            </div>
          </article>
          <div class="art-side">
            <article class="art-card art-card--side">
              <a href="#" class="art-img-link">
                <div class="art-img" style="background-image: url('<?php echo ANTHRO_URI; ?>/assets/images/coffee_culture.png');"></div>
              </a>
              <div class="art-body">
                <div class="art-meta"><span class="art-cat">الثقافة المادية</span></div>
                <h3 class="art-title"><a href="#">القهوة السعودية: أنثروبولوجيا الضيافة والرمزية الاجتماعية</a></h3>
                <div class="art-footer"><span class="read-time">٦ دقائق قراءة</span></div>
              </div>
            </article>
            <article class="art-card art-card--side">
              <a href="#" class="art-img-link">
                <div class="art-img" style="background-image: url('<?php echo ANTHRO_URI; ?>/assets/images/archaeology_alula.png');"></div>
              </a>
              <div class="art-body">
                <div class="art-meta"><span class="art-cat">التاريخ الشفهي</span></div>
                <h3 class="art-title"><a href="#">نقوش العلا واللحيانيون: توثيق المعتقدات والرموز القديمة</a></h3>
                <div class="art-footer"><span class="read-time">١٠ دقائق قراءة</span></div>
              </div>
            </article>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ============================
       PHILOSOPHY STRIP
  ============================= -->
  <section class="philosophy pattern-palm" id="philosophy">
    <div class="container">
      <div class="philosophy-inner">
        <span class="ph-mark">"</span>
        <blockquote class="ph-quote">الأنثروبولوجيا ليست دراسة الآخر، بل دراسة الإنسان في كل مكان — بما فيه المرآة.</blockquote>
        <cite class="ph-cite">— كلود ليفي-ستروس</cite>
      </div>
    </div>
  </section>

  <!-- ============================
       ALL ARTICLES WITH FILTER TABS
  ============================= -->
  <section class="grid-section section-space pattern-weave" id="all-articles">
    <div class="container">
      <div class="sec-header">
        <div class="sec-label"><span class="sec-line"></span><span>جميع الدراسات والمقالات</span></div>
        <div class="filter-row" id="filter-bar">
          <button class="flt-btn active" data-f="all">الكل</button>
          <button class="flt-btn" data-f="arch">أنثروبولوجيا العمران</button>
          <button class="flt-btn" data-f="cult">الثقافة المادية</button>
          <button class="flt-btn" data-f="hist">التاريخ الشفهي</button>
        </div>
      </div>

      <div class="art-grid" id="articles-grid">
        <?php
        $all_posts = new WP_Query( [
          'post_type'      => 'post',
          'posts_per_page' => 6,
          'orderby'        => 'date',
          'order'          => 'DESC',
        ] );
        if ( $all_posts->have_posts() ) :
          while ( $all_posts->have_posts() ) : $all_posts->the_post();
            get_template_part( 'template-parts/card', 'article' );
          endwhile;
          wp_reset_postdata();
        else :
        ?>
          <!-- Fallback Cards matching Prototype -->
          <article class="art-card art-card--grid" data-f="arch">
            <a href="#" class="art-img-link"><div class="art-img" style="height:200px; background-image:url('<?php echo ANTHRO_URI; ?>/assets/images/hijazi_roshan.png'); background-size:cover; background-position:center;"></div></a>
            <div class="art-body">
              <div class="art-meta"><span class="art-cat">أنثروبولوجيا العمران</span></div>
              <h3 class="art-title"><a href="#">روشن الحجاز: هندسة الضوء والخصوصية</a></h3>
              <div class="art-footer"><span class="read-time">٨ دقائق</span></div>
            </div>
          </article>

          <article class="art-card art-card--grid" data-f="cult">
            <a href="#" class="art-img-link"><div class="art-img" style="height:200px; background-image:url('<?php echo ANTHRO_URI; ?>/assets/images/coffee_culture.png'); background-size:cover; background-position:center;"></div></a>
            <div class="art-body">
              <div class="art-meta"><span class="art-cat">الثقافة المادية</span></div>
              <h3 class="art-title"><a href="#">القهوة السعودية: أنثروبولوجيا الضيافة</a></h3>
              <div class="art-footer"><span class="read-time">٦ دقائق</span></div>
            </div>
          </article>

          <article class="art-card art-card--grid" data-f="hist">
            <a href="#" class="art-img-link"><div class="art-img" style="height:200px; background-image:url('<?php echo ANTHRO_URI; ?>/assets/images/archaeology_alula.png'); background-size:cover; background-position:center;"></div></a>
            <div class="art-body">
              <div class="art-meta"><span class="art-cat">التاريخ الشفهي</span></div>
              <h3 class="art-title"><a href="#">نقوش العلا واللحيانيون: توثيق الرموز</a></h3>
              <div class="art-footer"><span class="read-time">١٠ دقائق</span></div>
            </div>
          </article>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ============================
       PODCAST SECTION
  ============================= -->
  <section class="podcast-sec section-space pattern-mudbrick" id="podcast">
    <div class="container">
      <div class="pod-intro">
        <span class="pod-season-badge">الموسم الثالث &bull; الحلقة ٠٧</span>
        <h2 class="pod-main-title">صوت الإنسان السعودي</h2>
        <p class="pod-main-desc">حوارات أنثروبولوجية حية مع الباحثين، المؤرخين، وأبناء المجتمعات المحلية في مختلف مناطق المملكة.</p>
      </div>

      <div class="pod-layout">
        <div class="ep-card ep-card--feat">
          <div class="ep-thumb ep-thumb--1">
            <button class="ep-play-btn ep-play-btn--lg" id="pp1" aria-label="تشغيل">
              <svg width="24" height="24" viewBox="0 0 24 24" class="i-play"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg>
              <svg width="24" height="24" viewBox="0 0 24 24" class="i-pause" style="display:none;"><rect x="6" y="4" width="4" height="16" fill="currentColor"/><rect x="14" y="4" width="4" height="16" fill="currentColor"/></svg>
            </button>
          </div>
          <div class="ep-body">
            <div class="ep-meta">
              <span class="ep-num">الحلقة ٠٧</span>
              <span class="meta-sep">&bull;</span>
              <span class="ep-dur">٥٢ دقيقة</span>
            </div>
            <h3 class="ep-title">العمارة الطينية ونمط الحياة القديم في نجد</h3>
            <p class="ep-desc">نستضيف د. نورة المحمد للحوار حول تقنيات البناء بالطين وتأثير البيئة الصحراوية على تكوين المجمعات السكنية.</p>

            <div class="mini-player">
              <div class="mp-track">
                <div class="mp-bar" id="mp-bar-1">
                  <div class="mp-fill" id="mp-fill-1" style="width:35%;"></div>
                  <div class="mp-thumb" id="mp-thumb-1" style="right:65%;"></div>
                </div>
              </div>
              <div class="mp-controls">
                <span class="mp-time">١٨:١٢ / ٥٢:٠٠</span>
              </div>
            </div>
          </div>
        </div>

        <div class="ep-list">
          <article class="ep-card ep-card--row">
            <div class="ep-thumb ep-thumb--sm ep-thumb--2">
              <button class="ep-play-btn" aria-label="تشغيل"><svg width="14" height="14" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg></button>
            </div>
            <div class="ep-body">
              <div class="ep-meta"><span class="ep-num">الحلقة ٠٦</span></div>
              <h4 class="ep-title ep-title--sm">أغاني البحر وطقوس الغوص في الشرقية</h4>
            </div>
          </article>
          <article class="ep-card ep-card--row">
            <div class="ep-thumb ep-thumb--sm ep-thumb--3">
              <button class="ep-play-btn" aria-label="تشغيل"><svg width="14" height="14" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg></button>
            </div>
            <div class="ep-body">
              <div class="ep-meta"><span class="ep-num">الحلقة ٠٥</span></div>
              <h4 class="ep-title ep-title--sm">الوسم والرموز الرعوية في بادية الشمال</h4>
            </div>
          </article>

          <a href="<?php echo esc_url(get_post_type_archive_link('podcast_episode')); ?>" class="all-eps-link">
            عرض جميع الحلقات (٢٤ حلقة)
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================
       AUTHORS SECTION
  ============================= -->
  <section class="authors-sec section-space pattern-sadu" id="authors">
    <div class="container">
      <div class="sec-header">
        <div class="sec-label"><span class="sec-line"></span><span>كُتّاب وباحثو أنثرو</span></div>
      </div>
      <div class="authors-grid">
        <a href="#" class="au-card">
          <div class="au-photo au-photo--1"></div>
          <h4 class="au-name">د. سارة العتيبي</h4>
          <p class="au-field">أنثروبولوجيا العمران والتكيّف المناخي</p>
          <span class="au-count">١٨ مقالاً</span>
        </a>
        <a href="#" class="au-card">
          <div class="au-photo au-photo--2"></div>
          <h4 class="au-name">أ. خالد المانع</h4>
          <p class="au-field">التاريخ الشفهي والذاكرة الاجتماعية</p>
          <span class="au-count">٢٤ مقالاً</span>
        </a>
        <a href="#" class="au-card">
          <div class="au-photo au-photo--3"></div>
          <h4 class="au-name">د. نورة المحمد</h4>
          <p class="au-field">الثقافة المادية والحرف التقليدية</p>
          <span class="au-count">١٤ مقالاً</span>
        </a>
        <a href="#" class="au-card">
          <div class="au-photo au-photo--4"></div>
          <h4 class="au-name">أ. فهد الزهراني</h4>
          <p class="au-field">أنثروبولوجيا الفن والموسيقا الشعبية</p>
          <span class="au-count">١١ مقالاً</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ============================
       NEWSLETTER
  ============================= -->
  <?php get_template_part('template-parts/newsletter'); ?>

</main>

<?php get_footer(); ?>
