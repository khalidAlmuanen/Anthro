<?php
/**
 * front-page.php — Anthro Theme
 * صفحة الرئيسية الرئيسية
 */

get_header();

// ── Featured Article
$featured_query = new WP_Query( [
  'post_type'      => 'post',
  'post_status'    => 'publish',
  'posts_per_page' => 1,
  'meta_query'     => [ [ 'key' => '_anthro_featured', 'value' => '1' ] ],
  'orderby'        => 'date',
  'order'          => 'DESC',
] );

// If no featured, fallback to latest
if ( ! $featured_query->have_posts() ) {
  $featured_query = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 1 ] );
}

// ── Stats for Hero (live from DB)
$total_articles  = wp_count_posts('post')->publish;
$total_authors   = count_users()['total_users'];
$total_episodes  = wp_count_posts('podcast_episode')->publish;
$total_cats      = wp_count_terms('category');
?>

<!-- ═══════════════════════════════════════
     HERO SECTION
════════════════════════════════════════ -->
<section class="hero-section" id="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid-overlay"></div>
  <div class="hero-content container">

    <div class="hero-text">
      <div class="hero-eyebrow">
        <div class="eyebrow-dot"></div>
        <?php esc_html_e( 'أنثروبولوجيا سعودية معاصرة', 'anthro' ); ?>
      </div>

      <?php if ( $featured_query->have_posts() ) :
        $featured_query->the_post(); ?>
        <h1 class="hero-title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h1>
        <p class="hero-lead"><?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?></p>

        <div class="hero-meta">
          <?php anthro_category_badge(); ?>
          <div class="hero-author">
            <?php echo get_avatar( get_the_author_meta('ID'), 32, '', '', ['class' => 'av'] ); ?>
            <span><?php the_author(); ?></span>
          </div>
          <span><?php echo anthro_arabic_num(anthro_reading_time()); ?> <?php esc_html_e('دقائق','anthro'); ?></span>
        </div>
        <a href="<?php the_permalink(); ?>" class="btn btn--primary hero-cta" id="hero-cta">
          <?php esc_html_e('اقرأ المقال','anthro'); ?>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      <?php
        wp_reset_postdata();
      endif; ?>
    </div><!-- /hero-text -->

    <!-- Stats -->
    <div class="hero-stats">
      <div class="stat-card">
        <span class="stat-n"><?php echo anthro_arabic_num($total_articles); ?>+</span>
        <span class="stat-l"><?php esc_html_e('مقال','anthro'); ?></span>
      </div>
      <div class="stat-card">
        <span class="stat-n"><?php echo anthro_arabic_num($total_authors); ?>+</span>
        <span class="stat-l"><?php esc_html_e('باحث','anthro'); ?></span>
      </div>
      <div class="stat-card">
        <span class="stat-n"><?php echo anthro_arabic_num($total_episodes); ?>+</span>
        <span class="stat-l"><?php esc_html_e('حلقة','anthro'); ?></span>
      </div>
      <div class="stat-card">
        <span class="stat-n"><?php echo anthro_arabic_num($total_cats); ?></span>
        <span class="stat-l"><?php esc_html_e('تصنيفات','anthro'); ?></span>
      </div>
    </div>

  </div><!-- /hero-content -->
</section><!-- /hero -->

<!-- ═══════════════════════════════════════
     CATEGORIES SECTION
════════════════════════════════════════ -->
<section class="categories-section section-space pattern-sadu" id="categories">
  <div class="container">
    <div class="sec-header">
      <div class="sec-label"><span class="sec-line"></span><span><?php esc_html_e('التصنيفات','anthro'); ?></span></div>
    </div>
    <div class="cats-grid">
      <?php
      $cats = get_categories( [ 'number' => 6, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ] );
      $cat_icons = [ '👤', '🎭', '🌿', '📖', '🏛', '🌍' ];
      foreach ( $cats as $i => $cat ) :
      ?>
        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="cat-card" id="cat-<?php echo esc_attr($cat->slug); ?>">
          <div class="cat-icon"><?php echo $cat_icons[$i % count($cat_icons)]; ?></div>
          <h3 class="cat-name"><?php echo esc_html($cat->name); ?></h3>
          <p class="cat-count"><?php echo anthro_arabic_num($cat->count); ?> <?php esc_html_e('مقال','anthro'); ?></p>
          <div class="cat-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     FEATURED STORIES
════════════════════════════════════════ -->
<section class="stories-section section-space pattern-weave" id="featured-stories">
  <div class="container">
    <div class="sec-header">
      <div class="sec-label"><span class="sec-line"></span><span><?php esc_html_e('مقالات مميزة','anthro'); ?></span></div>
      <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="sec-more"><?php esc_html_e('عرض الكل','anthro'); ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>

    <div class="featured-grid" id="featured-grid">
      <?php
      $featured_posts = new WP_Query( [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
      ] );
      while ( $featured_posts->have_posts() ) : $featured_posts->the_post();
        get_template_part( 'template-parts/card', 'article', [ 'size' => 'large' ] );
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     PHILOSOPHY STRIP
════════════════════════════════════════ -->
<section class="philosophy pattern-palm" id="philosophy">
  <div class="container">
    <div class="philosophy-inner">
      <span class="ph-mark">"</span>
      <blockquote class="ph-quote"><?php esc_html_e('الأنثروبولوجيا ليست دراسة الآخر، بل دراسة الإنسان في كل مكان — بما فيه المرآة.','anthro'); ?></blockquote>
      <cite class="ph-cite">— <?php esc_html_e('كلود ليفي-ستروس','anthro'); ?></cite>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     ALL ARTICLES (with Filter)
════════════════════════════════════════ -->
<section class="all-articles section-space" id="all-articles">
  <div class="container">
    <div class="sec-header">
      <div class="sec-label"><span class="sec-line"></span><span><?php esc_html_e('جميع المقالات','anthro'); ?></span></div>
      <div class="filter-bar" id="filter-bar">
        <button class="filter-btn active" data-cat="0" id="fb-all"><?php esc_html_e('الكل','anthro'); ?></button>
        <?php
        $cats_filter = get_categories( [ 'number' => 5, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ] );
        foreach ( $cats_filter as $cat ) :
          echo '<button class="filter-btn" data-cat="' . $cat->term_id . '" id="fb-' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</button>';
        endforeach;
        ?>
      </div>
    </div>

    <div class="art-grid" id="articles-grid">
      <?php
      $per_page = (int) get_theme_mod( 'anthro_articles_per_page', 12 );
      $all_posts = new WP_Query( [
        'post_type'      => 'post',
        'posts_per_page' => $per_page,
        'orderby'        => 'date',
        'order'          => 'DESC',
      ] );
      while ( $all_posts->have_posts() ) : $all_posts->the_post();
        get_template_part( 'template-parts/card', 'article' );
      endwhile;
      wp_reset_postdata();
      ?>
    </div>

    <?php if ( $all_posts->max_num_pages > 1 ) : ?>
      <div class="load-wrap">
        <button class="btn btn--outline" id="load-more"
          data-page="2"
          data-max="<?php echo $all_posts->max_num_pages; ?>"
          data-nonce="<?php echo wp_create_nonce('anthro_nonce'); ?>">
          <?php esc_html_e('تحميل المزيد','anthro'); ?>
        </button>
      </div>
    <?php endif; ?>

  </div>
</section>

<!-- ═══════════════════════════════════════
     PODCAST SECTION
════════════════════════════════════════ -->
<section class="podcast-section section-space pattern-mudbrick" id="podcast" style="background: var(--olive-dk);">
  <div class="container">
    <div class="sec-header">
      <div class="sec-label sec-label--lt"><span class="sec-line sec-line--lt"></span><span><?php esc_html_e('أحدث الحلقات','anthro'); ?></span></div>
      <a href="<?php echo esc_url(get_post_type_archive_link('podcast_episode')); ?>" class="sec-more sec-more--lt"><?php esc_html_e('جميع الحلقات','anthro'); ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>
    <div class="episodes-row" id="episodes-row">
      <?php
      $episodes = new WP_Query( [
        'post_type'      => 'podcast_episode',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
      ] );
      while ( $episodes->have_posts() ) : $episodes->the_post();
        $ep_num  = get_post_meta( get_the_ID(), '_anthro_ep_number',   true );
        $ep_dur  = get_post_meta( get_the_ID(), '_anthro_ep_duration', true );
        ?>
        <article class="ep-card" id="ep-<?php the_ID(); ?>">
          <div class="ep-thumb">
            <?php if ( has_post_thumbnail() ) the_post_thumbnail('anthro-thumb', ['class' => 'ep-cover']); ?>
            <button class="ep-play-btn" aria-label="<?php esc_attr_e('تشغيل','anthro'); ?>">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </button>
          </div>
          <div class="ep-body">
            <div class="ep-meta">
              <?php if ($ep_num) : ?><span class="ep-num"><?php echo __('الحلقة','anthro') . ' ' . anthro_arabic_num($ep_num); ?></span><?php endif; ?>
              <?php if ($ep_dur) : ?><span class="ep-dur"><?php echo esc_html($ep_dur); ?></span><?php endif; ?>
            </div>
            <h3 class="ep-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="ep-desc"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     AUTHORS SECTION
════════════════════════════════════════ -->
<section class="authors-section section-space" id="authors">
  <div class="container">
    <div class="sec-header">
      <div class="sec-label"><span class="sec-line"></span><span><?php esc_html_e('الكتّاب والباحثون','anthro'); ?></span></div>
    </div>
    <div class="authors-grid">
      <?php
      $authors = get_users( [
        'capability' => [ 'edit_posts' ],
        'number'     => 4,
        'orderby'    => 'post_count',
        'order'      => 'DESC',
      ] );
      foreach ( $authors as $author ) :
        $post_count = count_user_posts( $author->ID, 'post' );
        if ( $post_count < 1 ) continue;
      ?>
        <a href="<?php echo esc_url(get_author_posts_url($author->ID)); ?>" class="au-card" id="au-<?php echo esc_attr($author->ID); ?>">
          <div class="au-photo">
            <?php echo get_avatar( $author->ID, 80, '', '', ['class' => 'au-photo-img'] ); ?>
          </div>
          <h4 class="au-name"><?php echo esc_html($author->display_name); ?></h4>
          <p class="au-field"><?php echo esc_html( get_the_author_meta('description', $author->ID) ); ?></p>
          <span class="au-count"><?php echo anthro_arabic_num($post_count); ?> <?php esc_html_e('مقالات','anthro'); ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════
     NEWSLETTER
════════════════════════════════════════ -->
<?php get_template_part('template-parts/newsletter'); ?>

<?php get_footer(); ?>
