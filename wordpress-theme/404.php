<?php
/**
 * 404.php — Anthro Theme
 *
 * صفحة "غير موجود". تحوّل الخطأ إلى فرصة اكتشاف
 * عبر عرض تصنيفات وأحدث المقالات بدلاً من طريق مسدود.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main class="site-main error-404-main" id="main-content" role="main">

  <section class="error-hero">
    <div class="container">
      <div class="error-inner">

        <div class="error-code" aria-hidden="true">
          <?php echo esc_html( anthro_arabic_num( 404 ) ); ?>
        </div>

        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'صفحة غير موجودة', 'anthro' ); ?></span>
        </div>

        <h1 class="error-title"><?php esc_html_e( 'يبدو أن هذا المسار لم يُكتشف بعد', 'anthro' ); ?></h1>

        <p class="error-desc">
          <?php esc_html_e( 'الصفحة التي تبحث عنها غير متاحة، أو ربما نُقلت إلى مكان آخر. جرّب البحث أو تصفّح ما نشرناه مؤخراً.', 'anthro' ); ?>
        </p>

        <div class="error-search">
          <?php get_search_form(); ?>
        </div>

        <div class="error-actions">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
            <?php esc_html_e( 'العودة للرئيسية', 'anthro' ); ?>
          </a>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>" class="btn btn--outline">
            <?php esc_html_e( 'تصفّح المقالات', 'anthro' ); ?>
          </a>
        </div>

      </div>
    </div>
  </section>

  <?php
  $recent = new WP_Query( [
      'post_type'           => 'post',
      'posts_per_page'      => 3,
      'ignore_sticky_posts' => true,
  ] );
  ?>

  <?php if ( $recent->have_posts() ) : ?>
  <section class="grid-section section-space">
    <div class="container">

      <div class="sec-header">
        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'أحدث المقالات', 'anthro' ); ?></span>
        </div>
      </div>

      <div class="art-grid">
        <?php
        while ( $recent->have_posts() ) :
          $recent->the_post();
          get_template_part( 'template-parts/card', 'article' );
        endwhile;
        wp_reset_postdata();
        ?>
      </div>

    </div>
  </section>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
