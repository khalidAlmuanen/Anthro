<?php
/**
 * category.php — Anthro Theme
 *
 * أرشيف التصنيف. مفصول عن archive.php لأن أنثرو يعرض
 * أيقونة التصنيف + شريط تنقل بين التصنيفات + شريط فرز،
 * على غرار الأرشيفات الفرعية في sapiens.org.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$cat        = get_queried_object();
$total      = $GLOBALS['wp_query']->found_posts;
$all_cats   = get_categories( [ 'hide_empty' => true ] );
$current_orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'date';
?>

<main class="site-main" id="main-content" role="main">

  <section class="cat-hero">
    <div class="container">

      <nav class="cat-bc" aria-label="<?php esc_attr_e( 'مسار التنقل', 'anthro' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link"><?php esc_html_e( 'الرئيسية', 'anthro' ); ?></a>
        <span class="bc-sep">/</span>
        <span class="bc-current"><?php echo esc_html( $cat->name ); ?></span>
      </nav>

      <div class="cat-hero-inner">
        <div class="cat-hero-icon" aria-hidden="true">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
          </svg>
        </div>

        <h1 class="cat-hero-title"><?php echo esc_html( $cat->name ); ?></h1>

        <?php if ( $cat->description ) : ?>
          <p class="cat-hero-desc"><?php echo wp_kses_post( $cat->description ); ?></p>
        <?php endif; ?>

        <div class="cat-hero-meta">
          <span class="cat-art-count">
            <?php echo esc_html( anthro_arabic_num( $total ) ); ?>
            <?php esc_html_e( 'مقال', 'anthro' ); ?>
          </span>
        </div>
      </div>

    </div>
  </section>

  <?php if ( count( $all_cats ) > 1 ) : ?>
  <nav class="all-cats-nav" aria-label="<?php esc_attr_e( 'التنقل بين التصنيفات', 'anthro' ); ?>">
    <div class="container">
      <div class="cats-nav-scroll">
        <?php foreach ( $all_cats as $c ) : ?>
          <a
            href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>"
            class="cat-nav-item<?php echo ( $c->term_id === $cat->term_id ) ? ' active' : ''; ?>"
            <?php echo ( $c->term_id === $cat->term_id ) ? 'aria-current="page"' : ''; ?>
          >
            <?php echo esc_html( $c->name ); ?>
            <span class="cat-nav-count"><?php echo esc_html( anthro_arabic_num( $c->count ) ); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </nav>
  <?php endif; ?>

  <section class="grid-section section-space">
    <div class="container">

      <?php if ( have_posts() ) : ?>

        <div class="cat-sort-bar">
          <span class="sort-label"><?php esc_html_e( 'ترتيب حسب', 'anthro' ); ?></span>
          <div class="sort-btns">
            <?php
            $sorts = [
              'date'  => __( 'الأحدث', 'anthro' ),
              'title' => __( 'أبجدياً', 'anthro' ),
              'rand'  => __( 'عشوائي', 'anthro' ),
            ];
            foreach ( $sorts as $key => $label ) :
              $url = add_query_arg( 'orderby', $key, get_category_link( $cat->term_id ) );
            ?>
              <a href="<?php echo esc_url( $url ); ?>" class="sort-btn<?php echo ( $current_orderby === $key ) ? ' active' : ''; ?>">
                <?php echo esc_html( $label ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

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
