<?php
/**
 * archive.php — Anthro Theme
 *
 * الأرشيف العام: التصنيفات، الوسوم، التواريخ، أنواع المحتوى المخصصة.
 * يتعامل مع podcast_episode و research_area تلقائياً.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$is_podcast = is_post_type_archive( 'podcast_episode' );
$total      = $GLOBALS['wp_query']->found_posts;
?>

<main class="site-main" id="main-content" role="main">

  <section class="archive-header<?php echo $is_podcast ? ' archive-header--podcast' : ''; ?>">
    <div class="container">

      <nav class="archive-breadcrumb cat-bc" aria-label="<?php esc_attr_e( 'مسار التنقل', 'anthro' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link"><?php esc_html_e( 'الرئيسية', 'anthro' ); ?></a>
        <span class="bc-sep">/</span>
        <span class="bc-current"><?php echo wp_kses_post( get_the_archive_title() ); ?></span>
      </nav>

      <div class="archive-header-inner">
        <div class="sec-label">
          <span class="sec-line"></span>
          <span>
            <?php
            if ( is_category() )      esc_html_e( 'تصنيف', 'anthro' );
            elseif ( is_tag() )       esc_html_e( 'وسم', 'anthro' );
            elseif ( is_tax() )       echo esc_html( get_taxonomy( get_queried_object()->taxonomy )->labels->singular_name );
            elseif ( is_date() )      esc_html_e( 'أرشيف زمني', 'anthro' );
            elseif ( $is_podcast )    esc_html_e( 'البودكاست', 'anthro' );
            else                      esc_html_e( 'الأرشيف', 'anthro' );
            ?>
          </span>
        </div>

        <h1 class="archive-title"><?php echo wp_kses_post( get_the_archive_title() ); ?></h1>

        <?php $desc = get_the_archive_description(); ?>
        <?php if ( $desc ) : ?>
          <div class="archive-desc"><?php echo wp_kses_post( $desc ); ?></div>
        <?php endif; ?>

        <?php if ( $total ) : ?>
          <p class="articles-count-badge">
            <?php
            printf(
              esc_html__( '%s %s', 'anthro' ),
              esc_html( anthro_arabic_num( $total ) ),
              $is_podcast ? esc_html__( 'حلقة', 'anthro' ) : esc_html__( 'مقال', 'anthro' )
            );
            ?>
          </p>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <section class="grid-section section-space">
    <div class="container">

      <?php if ( have_posts() ) : ?>

        <div class="art-grid" id="posts-grid">
          <?php
          while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/card', 'article' );
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
