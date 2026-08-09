<?php
/**
 * template-parts/card-episode.php — Anthro Theme
 *
 * بطاقة حلقة بودكاست. الكلاسات مطابقة لـ podcast.css
 * (ep-item, ep-item-num, ep-item-thumb, ep-item-play, ...).
 *
 * @package Anthro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$ep_id       = get_the_ID();
$ep_number   = get_post_meta( $ep_id, '_anthro_ep_number',   true );
$ep_duration = get_post_meta( $ep_id, '_anthro_ep_duration', true );
$audio_url   = get_post_meta( $ep_id, '_anthro_audio_url',   true );

$thumb_url = has_post_thumbnail()
    ? get_the_post_thumbnail_url( $ep_id, 'anthro-thumb' )
    : ANTHRO_URI . '/assets/images/hero_architecture.png';

// شارة "جديد" للحلقات المنشورة خلال 14 يوماً
$is_new = ( time() - get_post_time( 'U', true, $ep_id ) ) < ( 14 * DAY_IN_SECONDS );
?>
<article class="ep-item" id="epi-<?php echo esc_attr( $ep_id ); ?>" data-audio="<?php echo esc_url( $audio_url ); ?>">

  <?php if ( $ep_number ) : ?>
    <div class="ep-item-num"><?php echo esc_html( anthro_arabic_num( $ep_number ) ); ?></div>
  <?php endif; ?>

  <div class="ep-item-thumb" style="background-image:url('<?php echo esc_url( $thumb_url ); ?>'); background-size:cover; background-position:center;">
    <a href="<?php the_permalink(); ?>" class="ep-item-play" aria-label="<?php echo esc_attr( sprintf( __( 'تشغيل: %s', 'anthro' ), get_the_title() ) ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
    </a>
  </div>

  <div class="ep-item-body">
    <h3 class="ep-item-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <?php if ( has_excerpt() ) : ?>
      <p class="ep-item-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '...' ) ); ?></p>
    <?php endif; ?>

    <div class="ep-item-meta">
      <span class="epm-date"><?php echo esc_html( get_the_date() ); ?></span>
      <?php if ( $ep_duration ) : ?>
        <span class="meta-sep">•</span>
        <span class="epm-dur"><?php echo esc_html( anthro_arabic_num( $ep_duration ) ); ?> <?php esc_html_e( 'دقيقة', 'anthro' ); ?></span>
      <?php endif; ?>
    </div>
  </div>

  <div class="ep-item-actions">
    <button class="ep-save-btn" aria-label="<?php esc_attr_e( 'حفظ الحلقة', 'anthro' ); ?>" data-id="<?php echo esc_attr( $ep_id ); ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
    </button>
    <?php if ( $is_new ) : ?>
      <span class="ep-new-badge"><?php esc_html_e( 'جديد', 'anthro' ); ?></span>
    <?php endif; ?>
  </div>

</article>
