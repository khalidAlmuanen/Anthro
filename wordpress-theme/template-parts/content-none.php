<?php
/**
 * template-parts/content-none.php — Anthro Theme
 *
 * تُعرض عندما لا يُرجع الاستعلام أي نتائج.
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="empty-state">
  <div class="empty-icon" aria-hidden="true">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
      <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
      <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
    </svg>
  </div>

  <h2 class="empty-title"><?php esc_html_e( 'لا يوجد محتوى هنا بعد', 'anthro' ); ?></h2>

  <p class="empty-desc">
    <?php esc_html_e( 'لم نجد مقالات في هذا القسم حتى الآن. تصفّح الأقسام الأخرى أو عد لاحقاً.', 'anthro' ); ?>
  </p>

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--outline">
    <?php esc_html_e( 'العودة للرئيسية', 'anthro' ); ?>
  </a>
</div>
