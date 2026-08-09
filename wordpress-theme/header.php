<?php
/**
 * header.php — Anthro Theme
 * يُستخدم في كل صفحات الموقع
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#686848" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header" role="banner">
  <div class="header-inner container">

    <!-- Logo -->
    <?php anthro_logo( true ); ?>

    <!-- Primary Navigation -->
    <nav class="main-nav" id="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'القائمة الرئيسية', 'anthro' ); ?>">
      <?php
      wp_nav_menu( [
        'theme_location' => 'primary',
        'menu_class'     => 'nav-list',
        'container'      => false,
        'fallback_cb'    => 'anthro_fallback_menu',
        'walker'         => class_exists('Anthro_Walker_Nav') ? new Anthro_Walker_Nav() : null,
        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
      ] );
      ?>
    </nav>

    <!-- Header Actions -->
    <div class="header-actions">
      <!-- Search Button -->
      <button class="icon-btn search-btn" id="search-toggle" aria-label="<?php esc_attr_e( 'بحث', 'anthro' ); ?>" aria-expanded="false" aria-controls="search-overlay">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>

      <!-- Mobile Hamburger -->
      <button class="hamburger" id="menu-toggle" aria-label="<?php esc_attr_e( 'فتح القائمة', 'anthro' ); ?>" aria-expanded="false" aria-controls="mobile-nav">
        <span></span><span></span><span></span>
      </button>
    </div>

  </div><!-- /header-inner -->

  <!-- Search Overlay -->
  <div class="search-overlay" id="search-overlay" role="search" aria-hidden="true">
    <div class="search-inner container">
      <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input
          type="search"
          class="search-input"
          id="search-input"
          name="s"
          placeholder="<?php esc_attr_e( 'ابحث في أنثرو...', 'anthro' ); ?>"
          value="<?php echo get_search_query(); ?>"
          autocomplete="off"
        />
        <input type="hidden" name="post_type" value="any" />
      </form>
      <button class="search-close" id="search-close" aria-label="<?php esc_attr_e( 'إغلاق البحث', 'anthro' ); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
  </div>

</header><!-- /site-header -->

<!-- Mobile Navigation Drawer -->
<div class="mobile-nav" id="mobile-nav" role="navigation" aria-label="<?php esc_attr_e( 'قائمة الموبايل', 'anthro' ); ?>" aria-hidden="true">
  <?php
  wp_nav_menu( [
    'theme_location' => 'primary',
    'menu_class'     => 'mobile-nav-list',
    'container'      => false,
    'fallback_cb'    => false,
    'link_before'    => '',
    'link_after'     => '',
    'add_li_class'   => false,
    'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
    'before'         => '',
    'after'          => '',
  ] );
  ?>
</div>
<div class="nav-overlay" id="nav-overlay" aria-hidden="true"></div>

<?php
/**
 * Fallback menu when no menu is assigned.
 */
function anthro_fallback_menu() {
    echo '<ul class="nav-list">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="nav-link">' . __('الرئيسية', 'anthro') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about')) . '" class="nav-link">' . __('عن أنثرو', 'anthro') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/archive')) . '" class="nav-link">' . __('المقالات', 'anthro') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/podcast')) . '" class="nav-link">' . __('البودكاست', 'anthro') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact')) . '" class="nav-link">' . __('تواصل معنا', 'anthro') . '</a></li>';
    echo '</ul>';
}
