<?php
/**
 * index.php — Anthro Theme
 *
 * القالب الاحتياطي الإلزامي في ووردبريس.
 * بدون هذا الملف يرفض ووردبريس تفعيل الثيم ويعرض "Broken Theme".
 * يعمل كشبكة أمان لأي استعلام لا يجد قالباً أكثر تخصصاً.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main class="site-main" id="main-content" role="main">

  <section class="archive-header">
    <div class="container">
      <div class="archive-header-inner">
        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'أنثرو', 'anthro' ); ?></span>
        </div>
        <h1 class="archive-title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
        <?php if ( get_bloginfo( 'description' ) ) : ?>
          <p class="archive-desc"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
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
