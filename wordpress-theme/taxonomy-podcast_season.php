<?php
/**
 * taxonomy-podcast_season.php — Anthro Theme
 *
 * أرشيف الموسم الواحد. يعيد استخدام نفس تبويبات المواسم
 * مع تعليم الموسم الحالي كنشط.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$current    = get_queried_object();
$season_eps = $GLOBALS['wp_query']->found_posts;

$seasons = get_terms( [
    'taxonomy'   => 'podcast_season',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

$total_eps = wp_count_posts( 'podcast_episode' )->publish;
$hero_bg   = ANTHRO_URI . '/assets/images/hero_architecture.png';
?>

<main class="site-main" id="main-content" role="main">

  <section class="pod-hero pod-hero--season">
    <div class="pod-hero-bg" style="background-image:url('<?php echo esc_url( $hero_bg ); ?>');" aria-hidden="true"></div>
    <div class="pod-hero-overlay" aria-hidden="true"></div>

    <div class="container">

      <nav class="cat-bc" aria-label="<?php esc_attr_e( 'مسار التنقل', 'anthro' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link"><?php esc_html_e( 'الرئيسية', 'anthro' ); ?></a>
        <span class="bc-sep">/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'podcast_episode' ) ); ?>" class="bc-link"><?php esc_html_e( 'البودكاست', 'anthro' ); ?></a>
        <span class="bc-sep">/</span>
        <span class="bc-current"><?php echo esc_html( $current->name ); ?></span>
      </nav>

      <div class="pod-hero-content">

        <div class="pod-eyebrow sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'موسم', 'anthro' ); ?></span>
        </div>

        <h1 class="pod-hero-title"><?php echo esc_html( $current->name ); ?></h1>

        <?php if ( $current->description ) : ?>
          <p class="pod-hero-desc"><?php echo wp_kses_post( $current->description ); ?></p>
        <?php endif; ?>

        <div class="ep-total">
          <?php
          printf(
            esc_html__( '%s حلقة في هذا الموسم', 'anthro' ),
            esc_html( anthro_arabic_num( $season_eps ) )
          );
          ?>
        </div>

      </div>
    </div>
  </section>

  <?php if ( ! empty( $seasons ) && ! is_wp_error( $seasons ) ) : ?>
  <section class="seasons-section">
    <div class="container">
      <div class="seasons-tabs" role="tablist">

        <a href="<?php echo esc_url( get_post_type_archive_link( 'podcast_episode' ) ); ?>" class="season-tab" role="tab" aria-selected="false">
          <span class="season-num">•</span>
          <span class="season-info">
            <span class="season-name"><?php esc_html_e( 'كل المواسم', 'anthro' ); ?></span>
            <span class="season-count"><?php echo esc_html( anthro_arabic_num( $total_eps ) ); ?> <?php esc_html_e( 'حلقة', 'anthro' ); ?></span>
          </span>
        </a>

        <?php foreach ( $seasons as $i => $season ) :
          $is_active = ( $season->term_id === $current->term_id );
        ?>
          <a href="<?php echo esc_url( get_term_link( $season ) ); ?>"
             class="season-tab<?php echo $is_active ? ' active' : ''; ?>"
             role="tab" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
             <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
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
