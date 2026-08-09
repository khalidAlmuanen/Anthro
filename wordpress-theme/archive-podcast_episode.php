<?php
/**
 * archive-podcast_episode.php — Anthro Theme
 *
 * الصفحة الرئيسية للبودكاست: هيرو + تبويبات المواسم + قائمة الحلقات.
 * الكلاسات مطابقة لـ podcast.css (pod-hero, seasons-tabs, episodes-list-full).
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$total_eps = wp_count_posts( 'podcast_episode' )->publish;

$seasons = get_terms( [
    'taxonomy'   => 'podcast_season',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

$hero_bg      = ANTHRO_URI . '/assets/images/hero_architecture.png';
$spotify_show = get_theme_mod( 'anthro_social_spotify', '' );
$apple_show   = get_theme_mod( 'anthro_social_apple', '' );
?>

<main class="site-main" id="main-content" role="main">

  <section class="pod-hero">
    <div class="pod-hero-bg" style="background-image:url('<?php echo esc_url( $hero_bg ); ?>');" aria-hidden="true"></div>
    <div class="pod-hero-overlay" aria-hidden="true"></div>

    <div class="container">
      <div class="pod-hero-content">

        <div class="pod-eyebrow sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'بودكاست أنثرو', 'anthro' ); ?></span>
        </div>

        <h1 class="pod-hero-title"><?php esc_html_e( 'أصوات من الذاكرة الحية', 'anthro' ); ?></h1>

        <p class="pod-hero-desc">
          <?php esc_html_e( 'حوارات مع باحثين ورواة وشهود على التحولات — نستمع إلى الإنسان السعودي كما يروي نفسه.', 'anthro' ); ?>
        </p>

        <div class="ep-total">
          <?php
          printf(
            esc_html__( '%s حلقة منشورة', 'anthro' ),
            esc_html( anthro_arabic_num( $total_eps ) )
          );
          ?>
        </div>

        <?php if ( $spotify_show || $apple_show ) : ?>
        <div class="pod-hero-platforms platforms-row">
          <span class="platforms-label"><?php esc_html_e( 'اشترك على', 'anthro' ); ?></span>
          <?php if ( $spotify_show ) : ?>
            <a href="<?php echo esc_url( $spotify_show ); ?>" class="platform-btn" target="_blank" rel="noopener noreferrer">Spotify</a>
          <?php endif; ?>
          <?php if ( $apple_show ) : ?>
            <a href="<?php echo esc_url( $apple_show ); ?>" class="platform-btn" target="_blank" rel="noopener noreferrer">Apple Podcasts</a>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="pod-waves" aria-hidden="true">
          <?php for ( $i = 0; $i < 24; $i++ ) : ?>
            <span class="wave-bar" style="height:<?php echo esc_attr( wp_rand( 20, 100 ) ); ?>%"></span>
          <?php endfor; ?>
        </div>

      </div>
    </div>
  </section>

  <?php if ( ! empty( $seasons ) && ! is_wp_error( $seasons ) ) : ?>
  <section class="seasons-section">
    <div class="container">
      <div class="seasons-tabs" role="tablist">

        <a href="<?php echo esc_url( get_post_type_archive_link( 'podcast_episode' ) ); ?>"
           class="season-tab active" role="tab" aria-selected="true">
          <span class="season-num">•</span>
          <span class="season-info">
            <span class="season-name"><?php esc_html_e( 'كل المواسم', 'anthro' ); ?></span>
            <span class="season-count"><?php echo esc_html( anthro_arabic_num( $total_eps ) ); ?> <?php esc_html_e( 'حلقة', 'anthro' ); ?></span>
          </span>
        </a>

        <?php foreach ( $seasons as $i => $season ) : ?>
          <a href="<?php echo esc_url( get_term_link( $season ) ); ?>" class="season-tab" role="tab" aria-selected="false">
            <span class="season-num"><?php echo esc_html( anthro_arabic_num( $i + 1 ) ); ?></span>
            <span class="season-info">
              <span class="season-name"><?php echo esc_html( $season->name ); ?></span>
              <span class="season-count"><?php echo esc_html( anthro_arabic_num( $season->count ) ); ?> <?php esc_html_e( 'حلقة', 'anthro' ); ?></span>
            </span>
          </a>
        <?php endforeach; ?>

      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="all-episodes section-space">
    <div class="container">

      <div class="sec-header">
        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'كل الحلقات', 'anthro' ); ?></span>
        </div>
      </div>

      <?php if ( have_posts() ) : ?>

        <div class="episodes-list-full" id="episodes-list">
          <?php
          while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/card', 'episode' );
          endwhile;
          ?>
        </div>

        <?php anthro_pagination(); ?>

      <?php else : ?>
        <?php get_template_part( 'template-parts/content', 'none' ); ?>
      <?php endif; ?>

    </div>
  </section>

  <?php get_template_part( 'template-parts/newsletter' ); ?>

</main>

<?php get_footer(); ?>
