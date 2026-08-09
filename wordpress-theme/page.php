<?php
/**
 * page.php — Anthro Theme
 *
 * قالب الصفحات الثابتة (عن أنثرو، تواصل معنا، سياسة الخصوصية...).
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main class="site-main" id="main-content" role="main">

  <?php while ( have_posts() ) : the_post(); ?>

    <article id="page-<?php the_ID(); ?>" <?php post_class( 'anthro-page' ); ?>>

      <header class="page-hero about-hero">
        <div class="container">

          <?php if ( ! is_front_page() ) : ?>
          <nav class="cat-bc" aria-label="<?php esc_attr_e( 'مسار التنقل', 'anthro' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link"><?php esc_html_e( 'الرئيسية', 'anthro' ); ?></a>
            <span class="bc-sep">/</span>
            <span class="bc-current"><?php the_title(); ?></span>
          </nav>
          <?php endif; ?>

          <div class="about-hero-content">
            <div class="about-eyebrow sec-label">
              <span class="sec-line"></span>
              <span><?php esc_html_e( 'أنثرو', 'anthro' ); ?></span>
            </div>

            <h1 class="about-title"><?php the_title(); ?></h1>

            <?php if ( has_excerpt() ) : ?>
              <p class="about-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
            <?php endif; ?>
          </div>

        </div>
      </header>

      <?php if ( has_post_thumbnail() ) : ?>
        <div class="page-featured-img container">
          <?php the_post_thumbnail( 'anthro-hero', [ 'class' => 'page-thumb', 'loading' => 'eager' ] ); ?>
        </div>
      <?php endif; ?>

      <div class="page-content section-space">
        <div class="container">
          <div class="entry-content article-body">
            <?php
            the_content();

            wp_link_pages( [
              'before' => '<div class="page-links pagination">',
              'after'  => '</div>',
            ] );
            ?>
          </div>
        </div>
      </div>

    </article>

  <?php endwhile; ?>

  <?php if ( comments_open() || get_comments_number() ) : ?>
    <div class="container"><?php comments_template(); ?></div>
  <?php endif; ?>

  <?php get_template_part( 'template-parts/newsletter' ); ?>

</main>

<?php get_footer(); ?>
