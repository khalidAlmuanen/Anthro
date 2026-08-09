<?php
/**
 * template-parts/newsletter.php
 * قسم النشرة البريدية — يُستخدم في كل الصفحات
 */
?>
<section class="nl-section" id="newsletter">
  <div class="nl-pattern"></div>
  <div class="container">
    <div class="nl-inner">
      <div class="nl-icon">
        <svg viewBox="0 0 48 48" fill="none">
          <path d="M40 10H8C5.8 10 4 11.8 4 14v20c0 2.2 1.8 4 4 4h32c2.2 0 4-1.8 4-4V14c0-2.2-1.8-4-4-4z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
          <polyline points="4 14 24 27 44 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="nl-text">
        <h2 class="nl-title"><?php esc_html_e( 'لا تفوّت شيئاً', 'anthro' ); ?></h2>
        <p class="nl-desc"><?php esc_html_e( 'اشترك في نشرة أنثرو الأسبوعية — مقال واحد، قصة واحدة، سؤال أنثروبولوجي واحد كل أسبوع في بريدك.', 'anthro' ); ?></p>
      </div>
      <form class="nl-form" id="nl-form" novalidate>
        <?php wp_nonce_field( 'anthro_newsletter', 'nl_nonce' ); ?>
        <div class="nl-field">
          <input
            type="email"
            class="nl-input"
            id="nl-email"
            name="email"
            placeholder="<?php esc_attr_e( 'بريدك الإلكتروني', 'anthro' ); ?>"
            required
            autocomplete="email"
          />
          <button type="submit" class="btn btn--primary" id="nl-submit">
            <?php esc_html_e( 'اشترك', 'anthro' ); ?>
          </button>
        </div>
        <p class="nl-note"><?php esc_html_e( 'لن نرسل إليك بريداً مزعجاً. إلغاء الاشتراك في أي وقت.', 'anthro' ); ?></p>
      </form>
    </div>
  </div>
</section>
