<?php
/**
 * footer.php — Anthro Theme
 */
?>

  <!-- FOOTER -->
  <footer class="site-footer" id="footer" role="contentinfo">
    <div class="ft-top">
      <div class="container">
        <div class="ft-grid">

          <!-- Brand Column -->
          <div class="ft-brand">
            <?php anthro_logo( true, 'logo--ft' ); ?>
            <p class="ft-tagline">
              <?php echo esc_html( get_theme_mod( 'anthro_hero_tagline', __( 'أنثروبولوجيا سعودية', 'anthro' ) ) ); ?><br />
              <?php esc_html_e( 'الإنسان • الثقافة • المجتمع • الذاكرة الحية', 'anthro' ); ?>
            </p>
            <!-- Social Links -->
            <div class="ft-social">
              <?php if ( $url = anthro_social_url('twitter') ) : ?>
                <a href="<?php echo esc_url($url); ?>" class="ft-social-link" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('تويتر X', 'anthro'); ?>">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
              <?php endif; ?>
              <?php if ( $url = anthro_social_url('instagram') ) : ?>
                <a href="<?php echo esc_url($url); ?>" class="ft-social-link" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('إنستقرام', 'anthro'); ?>">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                </a>
              <?php endif; ?>
              <?php if ( $url = anthro_social_url('spotify') ) : ?>
                <a href="<?php echo esc_url($url); ?>" class="ft-social-link" target="_blank" rel="noopener" aria-label="<?php esc_attr_e('سبوتيفاي', 'anthro'); ?>">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12.5c2.5-1 5.5-.5 7 1M7.5 9.5c3-1.5 7-1 9 1.5M8.5 15.5c2-1 4.5-.5 6 1"/></svg>
                </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Nav Columns -->
          <div class="ft-col">
            <h5 class="ft-head"><?php esc_html_e( 'المحتوى', 'anthro' ); ?></h5>
            <?php
            wp_nav_menu( [
              'theme_location' => 'footer-1',
              'container'      => false,
              'items_wrap'     => '<ul>%3$s</ul>',
              'fallback_cb'    => function() {
                echo '<ul>';
                echo '<li><a href="' . esc_url(get_post_type_archive_link('post')) . '">' . __('المقالات', 'anthro') . '</a></li>';
                echo '<li><a href="' . esc_url(get_post_type_archive_link('podcast_episode')) . '">' . __('البودكاست', 'anthro') . '</a></li>';
                echo '</ul>';
              },
            ] );
            ?>
          </div>

          <div class="ft-col">
            <h5 class="ft-head"><?php esc_html_e( 'التصنيفات', 'anthro' ); ?></h5>
            <?php
            $cats = get_categories( [ 'number' => 4, 'orderby' => 'count', 'order' => 'DESC' ] );
            if ( $cats ) :
              echo '<ul>';
              foreach ( $cats as $cat ) {
                echo '<li><a href="' . esc_url( get_category_link($cat->term_id) ) . '">' . esc_html( $cat->name ) . '</a></li>';
              }
              echo '</ul>';
            endif;
            ?>
          </div>

          <div class="ft-col">
            <h5 class="ft-head"><?php esc_html_e( 'أنثرو', 'anthro' ); ?></h5>
            <?php
            wp_nav_menu( [
              'theme_location' => 'footer-3',
              'container'      => false,
              'items_wrap'     => '<ul>%3$s</ul>',
              'fallback_cb'    => function() {
                echo '<ul>';
                echo '<li><a href="' . esc_url(home_url('/about')) . '">' . __('عن أنثرو', 'anthro') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/contact')) . '">' . __('تواصل معنا', 'anthro') . '</a></li>';
                echo '</ul>';
              },
            ] );
            ?>
          </div>

        </div><!-- /ft-grid -->
      </div><!-- /container -->
    </div><!-- /ft-top -->

    <div class="ft-bottom">
      <div class="container">
        <p class="ft-copy">
          <?php echo wp_kses_post( get_theme_mod( 'anthro_footer_copyright', '© ' . date('Y') . ' ' . get_bloginfo('name') . ' — جميع الحقوق محفوظة' ) ); ?>
        </p>
        <div class="ft-legal">
          <?php
          $privacy = get_privacy_policy_url();
          if ( $privacy ) {
            echo '<a href="' . esc_url($privacy) . '">' . __('سياسة الخصوصية', 'anthro') . '</a>';
            echo '<span>·</span>';
          }
          ?>
          <a href="<?php echo esc_url( home_url('/terms') ); ?>"><?php esc_html_e( 'شروط الاستخدام', 'anthro' ); ?></a>
        </div>
      </div>
    </div><!-- /ft-bottom -->

  </footer><!-- /site-footer -->

  <!-- Back to Top Button -->
  <button class="back-top" id="back-top" aria-label="<?php esc_attr_e( 'العودة للأعلى', 'anthro' ); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
  </button>

<?php wp_footer(); ?>
</body>
</html>
