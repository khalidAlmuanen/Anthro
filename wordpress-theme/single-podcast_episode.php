<?php
/**
 * single-podcast_episode.php — Anthro Theme
 *
 * صفحة الحلقة المفردة.
 * الكلاسات والـ IDs مطابقة تماماً لـ podcast.css و podcast.js
 * (ap-playpause, ap-bar, ap-played, ap-thumb, ap-current,
 *  vinyl-disc, ap-waveform, ap-speed-btn, ap-vol-fill).
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) : the_post();

    $post_id     = get_the_ID();
    $audio_url   = get_post_meta( $post_id, '_anthro_audio_url',   true );
    $ep_number   = get_post_meta( $post_id, '_anthro_ep_number',   true );
    $ep_duration = get_post_meta( $post_id, '_anthro_ep_duration', true );
    $guest_name  = get_post_meta( $post_id, '_anthro_guest_name',  true );
    $spotify_url = get_post_meta( $post_id, '_anthro_spotify_url', true );
    $apple_url   = get_post_meta( $post_id, '_anthro_apple_url',   true );

    $seasons     = get_the_terms( $post_id, 'podcast_season' );
    $season_name = ( $seasons && ! is_wp_error( $seasons ) ) ? $seasons[0]->name : '';

    $art_url = has_post_thumbnail()
        ? get_the_post_thumbnail_url( $post_id, 'anthro-portrait' )
        : ANTHRO_URI . '/assets/images/hero_architecture.png';
?>

<main class="site-main" id="main-content" role="main">

  <article id="episode-<?php echo esc_attr( $post_id ); ?>" <?php post_class( 'single-episode' ); ?>>

    <section class="pod-hero pod-hero--single">
      <div class="pod-hero-bg" style="background-image:url('<?php echo esc_url( $art_url ); ?>');" aria-hidden="true"></div>
      <div class="pod-hero-overlay" aria-hidden="true"></div>

      <div class="container">
        <nav class="cat-bc" aria-label="<?php esc_attr_e( 'مسار التنقل', 'anthro' ); ?>">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link"><?php esc_html_e( 'الرئيسية', 'anthro' ); ?></a>
          <span class="bc-sep">/</span>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'podcast_episode' ) ); ?>" class="bc-link"><?php esc_html_e( 'البودكاست', 'anthro' ); ?></a>
          <span class="bc-sep">/</span>
          <span class="bc-current"><?php the_title(); ?></span>
        </nav>
      </div>
    </section>

    <section class="section-space">
      <div class="container">

        <div class="full-player-card" id="full-player">

          <div class="full-player-art">
            <div class="player-art-img" style="background-image:url('<?php echo esc_url( $art_url ); ?>');">
              <div class="vinyl-disc" id="vinyl-disc">
                <div class="vinyl-center"></div>
              </div>
              <div class="player-art-waves" aria-hidden="true">
                <span></span><span></span><span></span><span></span><span></span>
              </div>
            </div>
          </div>

          <div class="full-player-info">

            <div class="ep-meta">
              <?php if ( $ep_number ) : ?>
                <span class="ep-num">
                  <?php
                  printf( esc_html__( 'الحلقة %s', 'anthro' ), esc_html( anthro_arabic_num( $ep_number ) ) );
                  if ( $season_name ) {
                      echo ' • ' . esc_html( $season_name );
                  }
                  ?>
                </span>
                <span class="meta-sep">•</span>
              <?php endif; ?>

              <?php if ( $ep_duration ) : ?>
                <span class="ep-dur"><?php echo esc_html( anthro_arabic_num( $ep_duration ) ); ?> <?php esc_html_e( 'دقيقة', 'anthro' ); ?></span>
                <span class="meta-sep">•</span>
              <?php endif; ?>

              <span class="ep-date"><?php echo esc_html( get_the_date() ); ?></span>
            </div>

            <h1 class="full-ep-title"><?php the_title(); ?></h1>

            <?php if ( $guest_name ) : ?>
              <p class="ep-guest">
                <?php printf( esc_html__( 'ضيف الحلقة: %s', 'anthro' ), '<strong>' . esc_html( $guest_name ) . '</strong>' ); ?>
              </p>
            <?php endif; ?>

            <?php if ( has_excerpt() ) : ?>
              <p class="full-ep-desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
            <?php endif; ?>

            <?php if ( $audio_url ) : ?>
            <div class="audio-player" id="audio-player" data-audio="<?php echo esc_url( $audio_url ); ?>">

              <audio id="anthro-audio" preload="metadata" src="<?php echo esc_url( $audio_url ); ?>"></audio>

              <div class="ap-waveform" id="ap-waveform"></div>

              <div class="ap-progress-wrap">
                <span class="ap-time" id="ap-current">٠٠:٠٠</span>
                <div class="ap-bar" id="ap-bar">
                  <div class="ap-played" id="ap-played" style="width:0%"></div>
                  <div class="ap-thumb" id="ap-thumb"></div>
                </div>
                <span class="ap-time" id="ap-duration">
                  <?php echo $ep_duration ? esc_html( anthro_arabic_num( $ep_duration ) ) . ':٠٠' : '٠٠:٠٠'; ?>
                </span>
              </div>

              <div class="ap-controls">
                <button class="ap-btn ap-skip" id="ap-back15" aria-label="<?php esc_attr_e( 'تراجع 15 ثانية', 'anthro' ); ?>">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.63"/></svg>
                  <span>١٥</span>
                </button>

                <button class="ap-btn ap-playpause" id="ap-playpause" aria-label="<?php esc_attr_e( 'تشغيل أو إيقاف', 'anthro' ); ?>">
                  <svg class="ap-icon-play" width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                  <svg class="ap-icon-pause" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                </button>

                <button class="ap-btn ap-skip" id="ap-fwd15" aria-label="<?php esc_attr_e( 'تقدم 15 ثانية', 'anthro' ); ?>">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3.63"/></svg>
                  <span>١٥</span>
                </button>

                <div class="ap-speed" id="ap-speed-btn" title="<?php esc_attr_e( 'سرعة التشغيل', 'anthro' ); ?>">١×</div>

                <div class="ap-volume-wrap">
                  <button class="ap-btn ap-vol" id="ap-vol" aria-label="<?php esc_attr_e( 'الصوت', 'anthro' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
                  </button>
                  <div class="ap-vol-bar">
                    <div class="ap-vol-fill" id="ap-vol-fill" style="width:80%"></div>
                  </div>
                </div>
              </div>

            </div>
            <?php else : ?>
              <p class="ep-no-audio"><?php esc_html_e( 'لم يُرفع ملف الصوت لهذه الحلقة بعد.', 'anthro' ); ?></p>
            <?php endif; ?>

            <?php if ( $spotify_url || $apple_url ) : ?>
            <div class="platforms-row">
              <span class="platforms-label"><?php esc_html_e( 'استمع على', 'anthro' ); ?></span>

              <?php if ( $spotify_url ) : ?>
                <a href="<?php echo esc_url( $spotify_url ); ?>" class="platform-btn" target="_blank" rel="noopener noreferrer">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.5 17.3a.75.75 0 0 1-1.03.25c-2.82-1.72-6.37-2.11-10.55-1.16a.75.75 0 1 1-.33-1.46c4.57-1.04 8.5-.59 11.66 1.34.35.22.46.68.25 1.03zm1.47-3.27a.94.94 0 0 1-1.29.31c-3.23-1.98-8.15-2.56-11.97-1.4a.94.94 0 0 1-.54-1.8c4.36-1.32 9.78-.68 13.49 1.6.44.27.58.85.31 1.29zm.13-3.4C15.23 8.34 8.85 8.13 5.15 9.25a1.12 1.12 0 1 1-.65-2.15C8.75 5.81 15.8 6.06 20.2 8.67a1.12 1.12 0 1 1-1.1 1.96z"/></svg>
                  <span>Spotify</span>
                </a>
              <?php endif; ?>

              <?php if ( $apple_url ) : ?>
                <a href="<?php echo esc_url( $apple_url ); ?>" class="platform-btn" target="_blank" rel="noopener noreferrer">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5A10.5 10.5 0 0 0 1.5 12c0 4.9 3.36 9.02 7.9 10.17.2-.6.36-1.3.36-1.94 0-.4-.02-1.6-.03-2.9-2.2.4-2.8-.53-2.98-1.02-.1-.27-.55-1.1-.94-1.32-.32-.17-.78-.6-.01-.6.72-.02 1.24.66 1.41.94.83 1.39 2.15 1 2.68.76.08-.6.32-1 .59-1.23-2.05-.23-4.2-1.02-4.2-4.55 0-1 .36-1.83.94-2.47-.1-.23-.41-1.17.09-2.44 0 0 .77-.24 2.52.94a8.6 8.6 0 0 1 2.29-.31c.78 0 1.56.1 2.29.31 1.75-1.19 2.52-.94 2.52-.94.5 1.27.19 2.21.09 2.44.59.64.94 1.46.94 2.47 0 3.54-2.15 4.32-4.2 4.55.33.29.62.85.62 1.71 0 1.24-.01 2.24-.01 2.54 0 .25.17.53.63.44A10.5 10.5 0 0 0 12 1.5z"/></svg>
                  <span>Apple Podcasts</span>
                </a>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php
            $ep_tags = get_the_terms( $post_id, 'research_area' );
            if ( $ep_tags && ! is_wp_error( $ep_tags ) ) :
            ?>
            <div class="ep-tags">
              <?php foreach ( $ep_tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="ep-tag-item"><?php echo esc_html( $tag->name ); ?></a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

          </div>
        </div>

      </div>
    </section>

    <?php if ( get_the_content() ) : ?>
    <section class="ep-notes-section section-space">
      <div class="container">
        <div class="sec-header">
          <div class="sec-label">
            <span class="sec-line"></span>
            <span><?php esc_html_e( 'ملاحظات الحلقة', 'anthro' ); ?></span>
          </div>
        </div>
        <div class="entry-content article-body ep-notes">
          <?php the_content(); ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php
    $related_args = [
        'post_type'           => 'podcast_episode',
        'posts_per_page'      => 3,
        'post__not_in'        => [ $post_id ],
        'ignore_sticky_posts' => true,
    ];
    if ( $seasons && ! is_wp_error( $seasons ) ) {
        $related_args['tax_query'] = [ [
            'taxonomy' => 'podcast_season',
            'field'    => 'term_id',
            'terms'    => $seasons[0]->term_id,
        ] ];
    }
    $related = new WP_Query( $related_args );
    ?>

    <?php if ( $related->have_posts() ) : ?>
    <section class="all-episodes section-space">
      <div class="container">

        <div class="sec-header">
          <div class="sec-label">
            <span class="sec-line"></span>
            <span><?php esc_html_e( 'حلقات أخرى', 'anthro' ); ?></span>
          </div>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'podcast_episode' ) ); ?>" class="sec-more">
            <?php esc_html_e( 'كل الحلقات', 'anthro' ); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>

        <div class="episodes-list-full">
          <?php
          while ( $related->have_posts() ) :
            $related->the_post();
            get_template_part( 'template-parts/card', 'episode' );
          endwhile;
          wp_reset_postdata();
          ?>
        </div>

      </div>
    </section>
    <?php endif; ?>

  </article>

  <?php get_template_part( 'template-parts/newsletter' ); ?>

</main>

<?php
endwhile;
get_footer();
