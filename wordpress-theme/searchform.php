<?php
/**
 * searchform.php — Anthro Theme
 *
 * نموذج البحث المخصص. يُستدعى تلقائياً عبر get_search_form().
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$unique_id = wp_unique_id( 'anthro-search-' );
?>
<form role="search" method="get" class="anthro-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>">
    <?php esc_html_e( 'ابحث في أنثرو', 'anthro' ); ?>
  </label>
  <div class="sf-field">
    <span class="sf-icon" aria-hidden="true">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
    </span>
    <input
      type="search"
      id="<?php echo esc_attr( $unique_id ); ?>"
      class="sf-input"
      name="s"
      value="<?php echo get_search_query(); ?>"
      placeholder="<?php esc_attr_e( 'ابحث في أنثرو...', 'anthro' ); ?>"
      autocomplete="off"
    />
    <button type="submit" class="btn btn--primary sf-submit">
      <?php esc_html_e( 'بحث', 'anthro' ); ?>
    </button>
  </div>
</form>
