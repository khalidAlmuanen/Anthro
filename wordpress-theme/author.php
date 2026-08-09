<?php
/**
 * author.php — Anthro Theme
 *
 * صفحة الباحث/الكاتب: نبذة، إحصاءات، روابط تواصل،
 * مقالاته وحلقات البودكاست المرتبطة به.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$author_id    = get_queried_object_id();
$author       = get_userdata( $author_id );
$post_count   = count_user_posts( $author_id, 'post' );
$ep_count     = count_user_posts( $author_id, 'podcast_episode' );
$bio          = get_the_author_meta( 'description', $author_id );
$website      = get_the_author_meta( 'user_url', $author_id );
$twitter      = get_the_author_meta( 'twitter', $author_id );

// حساب إجمالي دقائق القراءة لكل مقالات الكاتب
$all_posts = get_posts( [
    'author'         => $author_id,
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'fields'         => 'ids',
] );
$total_minutes = 0;
foreach ( $all_posts as $pid ) {
    $total_minutes += anthro_reading_time( $pid );
}
?>

<main class="site-main" id="main-content" role="main">

  <section class="author-hero">
    <div class="author-hero-bg" aria-hidden="true"></div>
    <div class="container">

      <nav class="author-breadcrumb cat-bc" aria-label="<?php esc_attr_e( 'مسار التنقل', 'anthro' ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link"><?php esc_html_e( 'الرئيسية', 'anthro' ); ?></a>
        <span class="bc-sep">/</span>
        <span class="bc-current"><?php echo esc_html( $author->display_name ); ?></span>
      </nav>

      <div class="author-hero-inner">

        <div class="author-hero-photo">
          <?php echo get_avatar( $author_id, 160, '', esc_attr( $author->display_name ), [ 'class' => 'au-photo-lg' ] ); ?>
        </div>

        <div class="author-hero-text">
          <span class="author-badge"><?php esc_html_e( 'باحث في أنثرو', 'anthro' ); ?></span>
          <h1 class="author-hero-name"><?php echo esc_html( $author->display_name ); ?></h1>

          <?php if ( $bio ) : ?>
            <p class="author-hero-bio"><?php echo wp_kses_post( $bio ); ?></p>
          <?php endif; ?>

          <div class="author-hero-stats">
            <div class="au-stat">
              <span class="au-stat-n"><?php echo esc_html( anthro_arabic_num( $post_count ) ); ?></span>
              <span class="au-stat-l"><?php esc_html_e( 'مقال', 'anthro' ); ?></span>
            </div>
            <span class="au-stat-sep" aria-hidden="true"></span>
            <div class="au-stat">
              <span class="au-stat-n"><?php echo esc_html( anthro_arabic_num( $ep_count ) ); ?></span>
              <span class="au-stat-l"><?php esc_html_e( 'حلقة', 'anthro' ); ?></span>
            </div>
            <span class="au-stat-sep" aria-hidden="true"></span>
            <div class="au-stat">
              <span class="au-stat-n"><?php echo esc_html( anthro_arabic_num( $total_minutes ) ); ?></span>
              <span class="au-stat-l"><?php esc_html_e( 'دقيقة قراءة', 'anthro' ); ?></span>
            </div>
          </div>

          <?php if ( $website || $twitter ) : ?>
          <div class="author-hero-links">
            <?php if ( $website ) : ?>
              <a href="<?php echo esc_url( $website ); ?>" class="au-social-link" target="_blank" rel="noopener noreferrer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span><?php esc_html_e( 'الموقع الشخصي', 'anthro' ); ?></span>
              </a>
            <?php endif; ?>
            <?php if ( $twitter ) : ?>
              <a href="<?php echo esc_url( 'https://x.com/' . ltrim( $twitter, '@' ) ); ?>" class="au-social-link" target="_blank" rel="noopener noreferrer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.1 8.1L23 22h-6.6l-5.2-6.8L5.3 22H2.2l7.6-8.7L1.5 2h6.7l4.7 6.2L18.9 2zm-1.1 18h1.7L7.3 3.8H5.5L17.8 20z"/></svg>
                <span><?php echo esc_html( '@' . ltrim( $twitter, '@' ) ); ?></span>
              </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </section>

  <section class="grid-section section-space">
    <div class="container">

      <div class="sec-header">
        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'المنشورات', 'anthro' ); ?></span>
        </div>
      </div>

      <?php if ( have_posts() ) : ?>

        <div class="art-grid" id="author-posts-grid">
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
