<?php
/**
 * search.php — Anthro Theme
 *
 * صفحة نتائج البحث. تعرض نتائج المقالات وحلقات البودكاست،
 * بالإضافة إلى الكُتّاب المطابقين للاستعلام.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$query_term = get_search_query();
$total      = $GLOBALS['wp_query']->found_posts;

// البحث عن كُتّاب مطابقين للاستعلام
$matched_authors = [];
if ( $query_term ) {
    $matched_authors = get_users( [
        'search'         => '*' . esc_attr( $query_term ) . '*',
        'search_columns' => [ 'display_name', 'user_nicename' ],
        'has_published_posts' => true,
        'number'         => 4,
    ] );
}
?>

<main class="site-main" id="main-content" role="main">

  <section class="archive-header">
    <div class="container">
      <div class="archive-header-inner">

        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'نتائج البحث', 'anthro' ); ?></span>
        </div>

        <h1 class="archive-title">
          <?php if ( $query_term ) : ?>
            <?php printf( esc_html__( 'نتائج البحث عن: %s', 'anthro' ), '<span class="search-term">' . esc_html( $query_term ) . '</span>' ); ?>
          <?php else : ?>
            <?php esc_html_e( 'ابحث في أنثرو', 'anthro' ); ?>
          <?php endif; ?>
        </h1>

        <?php if ( $query_term ) : ?>
          <p class="articles-count-badge">
            <?php
            if ( $total ) {
                printf(
                    esc_html__( 'عُثر على %s نتيجة', 'anthro' ),
                    esc_html( anthro_arabic_num( $total ) )
                );
            } else {
                esc_html_e( 'لا توجد نتائج مطابقة', 'anthro' );
            }
            ?>
          </p>
        <?php endif; ?>

        <div class="search-page-form">
          <?php get_search_form(); ?>
        </div>

      </div>
    </div>
  </section>

  <?php if ( ! empty( $matched_authors ) ) : ?>
  <section class="author-results-section section-space">
    <div class="container">
      <div class="sec-header">
        <div class="sec-label">
          <span class="sec-line"></span>
          <span><?php esc_html_e( 'باحثون', 'anthro' ); ?></span>
        </div>
      </div>
      <div class="author-results-row">
        <?php foreach ( $matched_authors as $author ) : ?>
          <a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>" class="author-result-card">
            <?php echo get_avatar( $author->ID, 56, '', '', [ 'class' => 'arc-photo' ] ); ?>
            <div class="arc-info">
              <span class="arc-name"><?php echo esc_html( $author->display_name ); ?></span>
              <span class="arc-count">
                <?php echo esc_html( anthro_arabic_num( count_user_posts( $author->ID ) ) ); ?>
                <?php esc_html_e( 'مقال', 'anthro' ); ?>
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="grid-section section-space">
    <div class="container">

      <?php if ( have_posts() ) : ?>

        <div class="art-grid" id="search-results-grid">
          <?php
          while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/card', 'article' );
          endwhile;
          ?>
        </div>

        <?php anthro_pagination(); ?>

      <?php else : ?>

        <div class="no-results-block">
          <div class="no-results-icon" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
          </div>
          <h2 class="no-results-title"><?php esc_html_e( 'لم نعثر على ما تبحث عنه', 'anthro' ); ?></h2>
          <p class="no-results-desc">
            <?php esc_html_e( 'جرّب كلمات مفتاحية أخرى، أو تصفّح التصنيفات أدناه.', 'anthro' ); ?>
          </p>

          <div class="no-results-cats">
            <?php
            $cats = get_categories( [ 'hide_empty' => true, 'number' => 6 ] );
            foreach ( $cats as $c ) :
            ?>
              <a href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>" class="cat-nav-item">
                <?php echo esc_html( $c->name ); ?>
              </a>
            <?php endforeach; ?>
          </div>

          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
            <?php esc_html_e( 'العودة للرئيسية', 'anthro' ); ?>
          </a>
        </div>

      <?php endif; ?>

    </div>
  </section>

  <?php get_template_part( 'template-parts/newsletter' ); ?>

</main>

<?php get_footer(); ?>
