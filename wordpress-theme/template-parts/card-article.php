<?php
/**
 * template-parts/card-article.php
 * بطاقة المقال — تُستخدم في كل صفحات الموقع
 *
 * @param array $args  اختياري: [ 'size' => 'large' ]
 */

$size = $args['size'] ?? 'normal';
$read_time = anthro_reading_time();
?>
<article class="art-card art-card--grid" id="art-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>">
  <a href="<?php the_permalink(); ?>" class="art-img-link" aria-hidden="true" tabindex="-1">
    <?php 
    $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url(null,'anthro-card') : ANTHRO_URI . '/assets/images/hero_architecture.png';
    ?>
    <div class="art-img" style="height:<?php echo $size === 'large' ? '260' : '200'; ?>px; background-image:url('<?php echo esc_url($thumb_url); ?>'); background-size:cover; background-position:center;">
      <?php anthro_category_badge(); ?>
    </div>
  </a>
  <div class="art-body">
    <div class="art-meta">
      <?php anthro_category_badge(); ?>
      <span class="meta-sep">•</span>
      <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time>
    </div>
    <h3 class="art-title<?php echo $size === 'large' ? ' art-title--md' : ''; ?>">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <?php if ( $size === 'large' ) : ?>
      <p class="art-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?></p>
    <?php endif; ?>
    <div class="art-footer">
      <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="art-author">
        <?php echo get_avatar(get_the_author_meta('ID'), 28, '', '', ['class' => 'av']); ?>
        <span><?php the_author(); ?></span>
      </a>
      <span class="read-time"><?php echo anthro_arabic_num($read_time) . ' ' . __('دقائق','anthro'); ?></span>
    </div>
  </div>
</article>
