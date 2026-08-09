<?php
/**
 * single.php — Anthro Theme
 * صفحة المقال الفردي
 */

get_header();

// Reading Progress Bar
echo '<div class="reading-progress" id="reading-progress"><div class="reading-bar" id="reading-bar"></div></div>';

while ( have_posts() ) :
  the_post();

  $read_time     = anthro_reading_time();
  $opening_quote = get_post_meta( get_the_ID(), '_anthro_opening_quote', true );
  $cats          = get_the_category();
  $primary_cat   = ! empty( $cats ) ? $cats[0] : null;
  ?>

  <!-- ARTICLE HERO -->
  <section class="article-hero" id="article-hero">
    <div class="article-hero-bg">
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="article-hero-img" style="background-image:url('<?php echo esc_url(get_the_post_thumbnail_url(null,'anthro-hero')); ?>');background-size:cover;background-position:center;"></div>
      <?php else : ?>
        <div class="article-hero-img"></div>
      <?php endif; ?>
      <div class="article-hero-overlay"></div>
    </div>

    <div class="article-hero-content container">
      <!-- Breadcrumb -->
      <nav class="breadcrumb" aria-label="<?php esc_attr_e('مسار التنقل','anthro'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="bc-link"><?php esc_html_e('الرئيسية','anthro'); ?></a>
        <?php if ( $primary_cat ) : ?>
          <span class="bc-sep"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
          <a href="<?php echo esc_url(get_category_link($primary_cat->term_id)); ?>" class="bc-link"><?php echo esc_html($primary_cat->name); ?></a>
        <?php endif; ?>
      </nav>

      <?php if ( $primary_cat ) : ?>
        <div class="article-category-tag"><?php echo esc_html($primary_cat->name); ?></div>
      <?php endif; ?>

      <h1 class="article-main-title"><?php the_title(); ?></h1>

      <?php if ( has_excerpt() ) : ?>
        <p class="article-lead"><?php the_excerpt(); ?></p>
      <?php endif; ?>

      <!-- Meta -->
      <div class="article-hero-meta">
        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="art-author-full">
          <div class="av av--1 av--lg">
            <?php echo get_avatar(get_the_author_meta('ID'), 48, '', get_the_author(), ['class' => 'av av--lg']); ?>
          </div>
          <div class="author-info">
            <span class="author-name-full"><?php the_author(); ?></span>
            <span class="author-title"><?php echo esc_html(get_the_author_meta('description') ?: ''); ?></span>
          </div>
        </a>
        <div class="article-meta-details">
          <div class="meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time>
          </div>
          <div class="meta-item">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span><?php echo anthro_arabic_num($read_time) . ' ' . __('دقائق قراءة', 'anthro'); ?></span>
          </div>
        </div>
      </div>

      <!-- Share -->
      <div class="article-share-bar">
        <span class="share-label"><?php esc_html_e('شارك:','anthro'); ?></span>
        <div class="share-btns">
          <button class="share-btn" id="share-x" aria-label="<?php esc_attr_e('مشاركة على تويتر X','anthro'); ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </button>
          <button class="share-btn" id="share-wa" aria-label="<?php esc_attr_e('مشاركة على واتساب','anthro'); ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          </button>
          <button class="share-btn" id="share-copy" aria-label="<?php esc_attr_e('نسخ الرابط','anthro'); ?>">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- ARTICLE LAYOUT: Sidebar | Content | Right Sidebar -->
  <div class="article-layout container" id="article-layout">

    <!-- Left: Share + TOC -->
    <aside class="article-sidebar" id="article-sidebar" aria-label="<?php esc_attr_e('مشاركة وقائمة المحتوى','anthro'); ?>">
      <div class="sidebar-share sticky-widget">
        <p class="widget-label"><?php esc_html_e('شارك','anthro'); ?></p>
        <div class="sidebar-share-btns">
          <button class="s-share-btn" aria-label="تويتر"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231z"/></svg></button>
          <button class="s-share-btn" aria-label="واتساب"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></button>
          <button class="s-share-btn" aria-label="نسخ"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
        </div>
        <div class="sidebar-divider"></div>
        <button class="s-share-btn s-bookmark-btn" id="bookmark-btn" aria-label="<?php esc_attr_e('حفظ','anthro'); ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
        </button>
      </div>
    </aside>

    <!-- Center: Article Content -->
    <article class="article-content" id="article-content" itemscope itemtype="https://schema.org/Article">
      <meta itemprop="author" content="<?php the_author(); ?>" />
      <meta itemprop="datePublished" content="<?php echo get_the_date('Y-m-d'); ?>" />

      <?php if ( $opening_quote ) : ?>
        <div class="article-opening-quote">
          <blockquote><?php echo esc_html( $opening_quote ); ?></blockquote>
        </div>
      <?php endif; ?>

      <div class="entry-content" itemprop="articleBody">
        <?php the_content(); ?>
      </div>

      <!-- Tags -->
      <?php $tags = get_the_tags(); if ( $tags ) : ?>
        <div class="article-tags">
          <span class="tags-label"><?php esc_html_e('الوسوم:','anthro'); ?></span>
          <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="art-tag-link"><?php echo esc_html($tag->name); ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Author Bio Card -->
      <div class="author-bio-card" id="author-bio">
        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-bio-photo">
          <?php echo get_avatar(get_the_author_meta('ID'), 72, '', '', ['class' => 'av']); ?>
        </a>
        <div class="author-bio-text">
          <div class="author-bio-header">
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-bio-name"><?php the_author(); ?></a>
            <span class="author-bio-title"><?php echo esc_html(get_the_author_meta('job_title') ?: ''); ?></span>
          </div>
          <p class="author-bio-desc"><?php echo esc_html(get_the_author_meta('description')); ?></p>
          <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-bio-link">
            <?php esc_html_e('عرض جميع مقالات الكاتب','anthro'); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </div>

      <!-- Article Navigation -->
      <?php
      $prev = get_previous_post();
      $next = get_next_post();
      if ( $prev || $next ) :
      ?>
        <nav class="article-nav" aria-label="<?php esc_attr_e('التنقل بين المقالات','anthro'); ?>">
          <?php if ( $prev ) : ?>
            <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="art-nav-btn art-nav-btn--prev">
              <div class="art-nav-dir"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><?php esc_html_e('المقال السابق','anthro'); ?></div>
              <h4><?php echo esc_html(get_the_title($prev)); ?></h4>
            </a>
          <?php else : ?>
            <div class="art-nav-btn art-nav-btn--prev" style="visibility:hidden;"></div>
          <?php endif; ?>
          <div class="art-nav-sep"></div>
          <?php if ( $next ) : ?>
            <a href="<?php echo esc_url(get_permalink($next)); ?>" class="art-nav-btn art-nav-btn--next">
              <div class="art-nav-dir"><?php esc_html_e('المقال التالي','anthro'); ?><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>
              <h4><?php echo esc_html(get_the_title($next)); ?></h4>
            </a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>

    </article><!-- /article-content -->

    <!-- Right Sidebar -->
    <aside class="article-sidebar-right" id="sidebar-right" aria-label="<?php esc_attr_e('محتوى ذو صلة','anthro'); ?>">
      <?php if ( is_active_sidebar('sidebar-single') ) : ?>
        <?php dynamic_sidebar('sidebar-single'); ?>
      <?php else : ?>
        <!-- Default: Related Articles -->
        <?php
        $related = new WP_Query( [
          'post_type'      => 'post',
          'posts_per_page' => 3,
          'post__not_in'   => [ get_the_ID() ],
          'category__in'   => wp_get_post_categories( get_the_ID() ),
          'orderby'        => 'rand',
        ] );
        if ( $related->have_posts() ) :
        ?>
          <div class="sidebar-widget">
            <h3 class="widget-heading"><?php esc_html_e('مقالات ذات صلة','anthro'); ?></h3>
            <div class="related-articles">
              <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                <article class="related-card">
                  <a href="<?php the_permalink(); ?>" class="related-img" style="background: linear-gradient(135deg, var(--olive), var(--olive-dk));"></a>
                  <div class="related-body">
                    <?php anthro_category_badge(); ?>
                    <h4 class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <span class="related-time"><?php echo anthro_arabic_num(anthro_reading_time()); ?> <?php esc_html_e('دقائق','anthro'); ?></span>
                  </div>
                </article>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </aside>

  </div><!-- /article-layout -->

<?php endwhile; ?>

<?php get_footer(); ?>
