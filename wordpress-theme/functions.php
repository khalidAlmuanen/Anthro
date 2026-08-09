<?php
/**
 * Anthro Theme — functions.php
 * الملف الرئيسي لإعداد الثيم وتسجيل كل الوظائف
 *
 * @package Anthro
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/* =============================================
   1. CONSTANTS
============================================= */
define( 'ANTHRO_VER',  '1.0.0' );
define( 'ANTHRO_DIR',  get_template_directory() );
define( 'ANTHRO_URI',  get_template_directory_uri() );
define( 'ANTHRO_TEXT', 'anthro' );


/* =============================================
   2. THEME SETUP
============================================= */
function anthro_setup() {

    // Translations
    load_theme_textdomain( ANTHRO_TEXT, ANTHRO_DIR . '/languages' );

    // Title tag
    add_theme_support( 'title-tag' );

    // Post thumbnails
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'anthro-hero',     1440, 600,  true );
    add_image_size( 'anthro-card',     600,  400,  true );
    add_image_size( 'anthro-thumb',    300,  200,  true );
    add_image_size( 'anthro-portrait', 400,  500,  true );
    add_image_size( 'anthro-avatar',   200,  200,  true );

    // HTML5
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );

    // Custom logo
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 180,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => [ 'site-title', 'site-description' ],
    ] );

    // Custom background
    add_theme_support( 'custom-background', [
        'default-color' => 'F0F3EC',
    ] );

    // Selective refresh for Customizer
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Feed links
    add_theme_support( 'automatic-feed-links' );

    // Block editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );

    // Wide/Full alignment in block editor
    add_theme_support( 'align-wide' );

    // Menus
    register_nav_menus( [
        'primary'  => __( 'القائمة الرئيسية', ANTHRO_TEXT ),
        'footer-1' => __( 'الفوتر — العمود الأول', ANTHRO_TEXT ),
        'footer-2' => __( 'الفوتر — العمود الثاني', ANTHRO_TEXT ),
        'footer-3' => __( 'الفوتر — العمود الثالث', ANTHRO_TEXT ),
    ] );
}
add_action( 'after_setup_theme', 'anthro_setup' );


/* =============================================
   3. CONTENT WIDTH & BODY CLASSES
============================================= */
function anthro_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'anthro_content_width', 860 );
}
add_action( 'after_setup_theme', 'anthro_content_width', 0 );

function anthro_body_classes( $classes ) {
    $classes[] = 'pattern-topography';
    return $classes;
}
add_filter( 'body_class', 'anthro_body_classes' );



/* =============================================
   4. ENQUEUE SCRIPTS & STYLES
============================================= */
function anthro_scripts() {

    // Google Fonts — Cairo
    wp_enqueue_style(
        'anthro-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap',
        [],
        null
    );

    // Main stylesheet (compiled from our prototype CSS)
    wp_enqueue_style(
        'anthro-main',
        ANTHRO_URI . '/assets/css/anthro-main.css',
        [ 'anthro-fonts' ],
        ANTHRO_VER
    );

    // RTL specific overrides
    if ( is_rtl() ) {
        wp_enqueue_style(
            'anthro-rtl',
            ANTHRO_URI . '/assets/css/anthro-rtl.css',
            [ 'anthro-main' ],
            ANTHRO_VER
        );
    }

    // Main JS
    wp_enqueue_script(
        'anthro-main',
        ANTHRO_URI . '/assets/js/anthro-main.js',
        [],
        ANTHRO_VER,
        true // Load in footer
    );

    // Single article JS
    if ( is_singular( 'post' ) ) {
        wp_enqueue_script(
            'anthro-single',
            ANTHRO_URI . '/assets/js/anthro-single.js',
            [ 'anthro-main' ],
            ANTHRO_VER,
            true
        );
    }

    // Podcast page JS
    if ( is_singular( 'podcast_episode' ) || is_page_template( 'template-podcast.php' ) ) {
        wp_enqueue_script(
            'anthro-podcast',
            ANTHRO_URI . '/assets/js/anthro-podcast.js',
            [ 'anthro-main' ],
            ANTHRO_VER,
            true
        );
    }

    // Localize script — pass WP data to JS
    wp_localize_script( 'anthro-main', 'anthroData', [
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'anthro_nonce' ),
        'siteUrl'   => home_url(),
        'isRTL'     => is_rtl() ? 'true' : 'false',
        'strings'   => [
            'bookmarked'   => __( 'تم الحفظ', ANTHRO_TEXT ),
            'unbookmarked' => __( 'تم الإلغاء', ANTHRO_TEXT ),
            'copied'       => __( 'تم النسخ!', ANTHRO_TEXT ),
            'subscribed'   => __( 'تم الاشتراك!', ANTHRO_TEXT ),
            'readMore'     => __( 'اقرأ المزيد', ANTHRO_TEXT ),
        ],
    ] );

    // Comments reply script
    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'anthro_scripts' );


/* =============================================
   5. WIDGETS
============================================= */
function anthro_widgets_init() {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-heading">',
        'after_title'   => '</h3>',
    ];

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'الشريط الجانبي — المقال', ANTHRO_TEXT ),
        'id'   => 'sidebar-single',
        'description' => __( 'يظهر على يمين صفحة المقال الفردي.', ANTHRO_TEXT ),
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'الشريط الجانبي — الأرشيف', ANTHRO_TEXT ),
        'id'   => 'sidebar-archive',
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'الفوتر — العمود الأول', ANTHRO_TEXT ),
        'id'   => 'footer-1',
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name' => __( 'الفوتر — العمود الثاني', ANTHRO_TEXT ),
        'id'   => 'footer-2',
    ] ) );
}
add_action( 'widgets_init', 'anthro_widgets_init' );


/* =============================================
   6. CUSTOM POST TYPE: PODCAST EPISODE
============================================= */
function anthro_register_cpts() {

    // ---- Podcast Episode ----
    register_post_type( 'podcast_episode', [
        'labels' => [
            'name'               => __( 'حلقات البودكاست', ANTHRO_TEXT ),
            'singular_name'      => __( 'حلقة بودكاست', ANTHRO_TEXT ),
            'add_new'            => __( 'حلقة جديدة', ANTHRO_TEXT ),
            'add_new_item'       => __( 'إضافة حلقة جديدة', ANTHRO_TEXT ),
            'edit_item'          => __( 'تعديل الحلقة', ANTHRO_TEXT ),
            'view_item'          => __( 'عرض الحلقة', ANTHRO_TEXT ),
            'search_items'       => __( 'البحث في الحلقات', ANTHRO_TEXT ),
            'not_found'          => __( 'لا توجد حلقات', ANTHRO_TEXT ),
            'menu_name'          => __( '🎙 البودكاست', ANTHRO_TEXT ),
        ],
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'podcast' ],
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'          => 'dashicons-microphone',
        'menu_position'      => 6,
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ] );

    // ---- Podcast Season (Taxonomy) ----
    register_taxonomy( 'podcast_season', 'podcast_episode', [
        'labels' => [
            'name'          => __( 'المواسم', ANTHRO_TEXT ),
            'singular_name' => __( 'موسم', ANTHRO_TEXT ),
            'menu_name'     => __( 'المواسم', ANTHRO_TEXT ),
        ],
        'hierarchical'  => true,
        'rewrite'       => [ 'slug' => 'season' ],
        'show_in_rest'  => true,
    ] );
}
add_action( 'init', 'anthro_register_cpts' );


/* =============================================
   7. CUSTOM TAXONOMIES FOR POSTS
============================================= */
function anthro_register_taxonomies() {

    // Article Categories — enhanced (not replacing default)
    // Using built-in category, just register custom display names

    // Article Tags — using built-in post_tag

    // Research Areas (for academic filtering)
    register_taxonomy( 'research_area', 'post', [
        'labels' => [
            'name'          => __( 'مجالات البحث', ANTHRO_TEXT ),
            'singular_name' => __( 'مجال البحث', ANTHRO_TEXT ),
            'menu_name'     => __( 'مجالات البحث', ANTHRO_TEXT ),
        ],
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'research' ],
        'show_in_rest' => true,
        'show_ui'      => true,
    ] );
}
add_action( 'init', 'anthro_register_taxonomies' );


/* =============================================
   8. META BOXES — ARTICLE
============================================= */
function anthro_add_meta_boxes() {
    add_meta_box(
        'anthro_article_meta',
        __( '⚙️ إعدادات المقال | أنثرو', ANTHRO_TEXT ),
        'anthro_article_meta_cb',
        'post',
        'normal',
        'high'
    );

    add_meta_box(
        'anthro_podcast_meta',
        __( '🎙 معلومات الحلقة', ANTHRO_TEXT ),
        'anthro_podcast_meta_cb',
        'podcast_episode',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'anthro_add_meta_boxes' );

// Article Meta Box — Callback
function anthro_article_meta_cb( $post ) {
    wp_nonce_field( 'anthro_article_meta', 'anthro_meta_nonce' );
    $featured     = get_post_meta( $post->ID, '_anthro_featured',     true );
    $opening_quote= get_post_meta( $post->ID, '_anthro_opening_quote',true );
    $read_time    = get_post_meta( $post->ID, '_anthro_read_time',    true );
    ?>
    <style>
        .anthro-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 8px 0; }
        .anthro-meta-field label { display: block; font-weight: 600; margin-bottom: 6px; color: #1e1e1e; }
        .anthro-meta-field input[type="text"],
        .anthro-meta-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; font-family: 'Cairo', sans-serif; direction: rtl; }
        .anthro-meta-field textarea { min-height: 80px; }
        .anthro-meta-check { display: flex; align-items: center; gap: 8px; }
    </style>
    <div class="anthro-meta-grid">
        <div class="anthro-meta-field" style="grid-column: 1/-1;">
            <label for="anthro_opening_quote"><?php _e( 'الاقتباس الافتتاحي', ANTHRO_TEXT ); ?></label>
            <textarea id="anthro_opening_quote" name="anthro_opening_quote"><?php echo esc_textarea( $opening_quote ); ?></textarea>
        </div>
        <div class="anthro-meta-field">
            <label for="anthro_read_time"><?php _e( 'وقت القراءة (دقائق)', ANTHRO_TEXT ); ?></label>
            <input type="text" id="anthro_read_time" name="anthro_read_time" value="<?php echo esc_attr( $read_time ); ?>" placeholder="8" />
        </div>
        <div class="anthro-meta-field" style="display:flex; align-items:center; gap:8px; padding-top:24px;">
            <input type="checkbox" id="anthro_featured" name="anthro_featured" value="1" <?php checked( $featured, '1' ); ?> />
            <label for="anthro_featured"><?php _e( '⭐ مقال مميز في الرئيسية', ANTHRO_TEXT ); ?></label>
        </div>
    </div>
    <?php
}

// Podcast Meta Box — Callback
function anthro_podcast_meta_cb( $post ) {
    wp_nonce_field( 'anthro_podcast_meta', 'anthro_pod_nonce' );
    $audio_url   = get_post_meta( $post->ID, '_anthro_audio_url',    true );
    $ep_number   = get_post_meta( $post->ID, '_anthro_ep_number',    true );
    $ep_duration = get_post_meta( $post->ID, '_anthro_ep_duration',  true );
    $guest_name  = get_post_meta( $post->ID, '_anthro_guest_name',   true );
    $spotify_url = get_post_meta( $post->ID, '_anthro_spotify_url',  true );
    $apple_url   = get_post_meta( $post->ID, '_anthro_apple_url',    true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="anthro_audio_url"><?php _e( 'رابط الصوت (MP3 / SoundCloud)', ANTHRO_TEXT ); ?></label></th>
            <td><input type="url" id="anthro_audio_url" name="anthro_audio_url" value="<?php echo esc_attr( $audio_url ); ?>" class="regular-text" dir="ltr" /></td>
        </tr>
        <tr>
            <th><label for="anthro_ep_number"><?php _e( 'رقم الحلقة', ANTHRO_TEXT ); ?></label></th>
            <td><input type="number" id="anthro_ep_number" name="anthro_ep_number" value="<?php echo esc_attr( $ep_number ); ?>" class="small-text" min="1" /></td>
        </tr>
        <tr>
            <th><label for="anthro_ep_duration"><?php _e( 'مدة الحلقة (مثال: 52:30)', ANTHRO_TEXT ); ?></label></th>
            <td><input type="text" id="anthro_ep_duration" name="anthro_ep_duration" value="<?php echo esc_attr( $ep_duration ); ?>" class="regular-text" placeholder="52:30" /></td>
        </tr>
        <tr>
            <th><label for="anthro_guest_name"><?php _e( 'اسم الضيف', ANTHRO_TEXT ); ?></label></th>
            <td><input type="text" id="anthro_guest_name" name="anthro_guest_name" value="<?php echo esc_attr( $guest_name ); ?>" class="regular-text" dir="rtl" /></td>
        </tr>
        <tr>
            <th><label for="anthro_spotify_url"><?php _e( 'رابط Spotify', ANTHRO_TEXT ); ?></label></th>
            <td><input type="url" id="anthro_spotify_url" name="anthro_spotify_url" value="<?php echo esc_attr( $spotify_url ); ?>" class="regular-text" dir="ltr" /></td>
        </tr>
        <tr>
            <th><label for="anthro_apple_url"><?php _e( 'رابط Apple Podcasts', ANTHRO_TEXT ); ?></label></th>
            <td><input type="url" id="anthro_apple_url" name="anthro_apple_url" value="<?php echo esc_attr( $apple_url ); ?>" class="regular-text" dir="ltr" /></td>
        </tr>
    </table>
    <?php
}

// Save Meta Boxes
function anthro_save_meta( $post_id ) {
    // Security checks
    if ( ! isset( $_POST['anthro_meta_nonce'] ) && ! isset( $_POST['anthro_pod_nonce'] ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Article meta
    if ( isset( $_POST['anthro_meta_nonce'] ) && wp_verify_nonce( $_POST['anthro_meta_nonce'], 'anthro_article_meta' ) ) {
        update_post_meta( $post_id, '_anthro_opening_quote', sanitize_textarea_field( $_POST['anthro_opening_quote'] ?? '' ) );
        update_post_meta( $post_id, '_anthro_read_time',     absint( $_POST['anthro_read_time'] ?? 0 ) );
        update_post_meta( $post_id, '_anthro_featured',      isset( $_POST['anthro_featured'] ) ? '1' : '0' );
    }

    // Podcast meta
    if ( isset( $_POST['anthro_pod_nonce'] ) && wp_verify_nonce( $_POST['anthro_pod_nonce'], 'anthro_podcast_meta' ) ) {
        update_post_meta( $post_id, '_anthro_audio_url',   esc_url_raw( $_POST['anthro_audio_url']   ?? '' ) );
        update_post_meta( $post_id, '_anthro_ep_number',   absint( $_POST['anthro_ep_number']         ?? 0 ) );
        update_post_meta( $post_id, '_anthro_ep_duration', sanitize_text_field( $_POST['anthro_ep_duration'] ?? '' ) );
        update_post_meta( $post_id, '_anthro_guest_name',  sanitize_text_field( $_POST['anthro_guest_name']  ?? '' ) );
        update_post_meta( $post_id, '_anthro_spotify_url', esc_url_raw( $_POST['anthro_spotify_url'] ?? '' ) );
        update_post_meta( $post_id, '_anthro_apple_url',   esc_url_raw( $_POST['anthro_apple_url']   ?? '' ) );
    }
}
add_action( 'save_post', 'anthro_save_meta' );


/* =============================================
   9. THEME CUSTOMIZER
============================================= */
function anthro_customize_register( $wp_customize ) {

    // ---- PANEL: Anthro Theme Options ----
    $wp_customize->add_panel( 'anthro_options', [
        'title'    => __( '🌿 إعدادات أنثرو', ANTHRO_TEXT ),
        'priority' => 30,
    ] );

    // === SECTION: Colors ===
    $wp_customize->add_section( 'anthro_colors', [
        'title' => __( 'الألوان', ANTHRO_TEXT ),
        'panel' => 'anthro_options',
    ] );

    $colors = [
        'olive'  => [ 'label' => __( 'اللون الزيتوني (الرئيسي)', ANTHRO_TEXT ), 'default' => '#606746' ],
        'copper' => [ 'label' => __( 'اللون النحاسي (الثانوي)', ANTHRO_TEXT ),  'default' => '#C47A44' ],
        'cream'  => [ 'label' => __( 'لون الخلفية', ANTHRO_TEXT ),             'default' => '#F6F3EC' ],
        'dark'   => [ 'label' => __( 'اللون الداكن (النصوص)', ANTHRO_TEXT ),   'default' => '#1F1F1F' ],
    ];

    foreach ( $colors as $key => $data ) {
        $wp_customize->add_setting( "anthro_color_{$key}", [
            'default'           => $data['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ] );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "anthro_color_{$key}", [
            'label'   => $data['label'],
            'section' => 'anthro_colors',
        ] ) );
    }

    // === SECTION: Social Media ===
    $wp_customize->add_section( 'anthro_social', [
        'title' => __( 'وسائل التواصل الاجتماعي', ANTHRO_TEXT ),
        'panel' => 'anthro_options',
    ] );

    $socials = [
        'twitter'   => __( 'تويتر X (رابط)', ANTHRO_TEXT ),
        'instagram' => __( 'إنستقرام (رابط)', ANTHRO_TEXT ),
        'spotify'   => __( 'سبوتيفاي (رابط البودكاست)', ANTHRO_TEXT ),
        'apple'     => __( 'Apple Podcasts (رابط)', ANTHRO_TEXT ),
        'youtube'   => __( 'يوتيوب (رابط)', ANTHRO_TEXT ),
    ];

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( "anthro_social_{$key}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( "anthro_social_{$key}", [
            'label'   => $label,
            'section' => 'anthro_social',
            'type'    => 'url',
        ] );
    }

    // === SECTION: Homepage ===
    $wp_customize->add_section( 'anthro_homepage', [
        'title' => __( 'الصفحة الرئيسية', ANTHRO_TEXT ),
        'panel' => 'anthro_options',
    ] );

    $wp_customize->add_setting( 'anthro_hero_tagline', [
        'default'           => __( 'الإنسان • الثقافة • المجتمع • الذاكرة الحية', ANTHRO_TEXT ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'anthro_hero_tagline', [
        'label'   => __( 'عنوان الهيرو', ANTHRO_TEXT ),
        'section' => 'anthro_homepage',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'anthro_articles_per_page', [
        'default'           => 12,
        'sanitize_callback' => 'absint',
    ] );
    $wp_customize->add_control( 'anthro_articles_per_page', [
        'label'   => __( 'عدد المقالات في الصفحة', ANTHRO_TEXT ),
        'section' => 'anthro_homepage',
        'type'    => 'number',
    ] );

    // === SECTION: Footer ===
    $wp_customize->add_section( 'anthro_footer', [
        'title' => __( 'الفوتر', ANTHRO_TEXT ),
        'panel' => 'anthro_options',
    ] );

    $wp_customize->add_setting( 'anthro_footer_copyright', [
        'default'           => '© ٢٠٢٦ أنثرو — جميع الحقوق محفوظة',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'anthro_footer_copyright', [
        'label'   => __( 'نص حقوق الفوتر', ANTHRO_TEXT ),
        'section' => 'anthro_footer',
        'type'    => 'textarea',
    ] );
}
add_action( 'customize_register', 'anthro_customize_register' );

// Output Customizer CSS as CSS variables
function anthro_customizer_css() {
    $olive  = get_theme_mod( 'anthro_color_olive',  '#686848' );
    $copper = get_theme_mod( 'anthro_color_copper', '#C47A44' );
    $cream  = get_theme_mod( 'anthro_color_cream',  '#F0F3EC' );
    $dark   = get_theme_mod( 'anthro_color_dark',   '#1F1F1F' );
    ?>
    <style id="anthro-customizer-css">
        :root {
            --olive:  <?php echo esc_attr( $olive );  ?>;
            --copper: <?php echo esc_attr( $copper ); ?>;
            --cream:  <?php echo esc_attr( $cream );  ?>;
            --dark:   <?php echo esc_attr( $dark );   ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'anthro_customizer_css' );


/* =============================================
   10. TEMPLATE TAGS & HELPERS
============================================= */

/**
 * Get reading time for a post.
 */
function anthro_reading_time( $post_id = null ) {
    $post_id  = $post_id ?? get_the_ID();
    $saved    = get_post_meta( $post_id, '_anthro_read_time', true );
    if ( $saved ) return absint( $saved );

    $content  = get_post_field( 'post_content', $post_id );
    $word_cnt = str_word_count( strip_tags( $content ) );
    return max( 1, ceil( $word_cnt / 200 ) ); // ~200 WPM Arabic
}

/**
 * Get Arabic numeral string.
 */
function anthro_arabic_num( $num ) {
    $eastern = [ '٠','١','٢','٣','٤','٥','٦','٧','٨','٩' ];
    return strtr( (string) $num, array_combine( range(0,9), $eastern ) );
}

/**
 * Render the Anthro SVG logo (Official Brand Vector).
 */
function anthro_logo_svg( $width = 38, $height = 42 ) {
    ?>
    <svg width="<?php echo $width; ?>" height="<?php echo $height; ?>" viewBox="0 0 40 46" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <!-- Top Diamond -->
        <polygon points="20,1 25.5,6.5 20,12 14.5,6.5" fill="currentColor"/>
        <!-- Left Leg -->
        <line x1="19" y1="13.5" x2="6" y2="42" stroke="currentColor" stroke-width="4.5" stroke-linecap="square"/>
        <!-- Right Leg -->
        <line x1="21" y1="13.5" x2="34" y2="42" stroke="currentColor" stroke-width="4.5" stroke-linecap="square"/>
        <!-- Extended Crossbar -->
        <line x1="2" y1="31" x2="38" y2="31" stroke="currentColor" stroke-width="4.2" stroke-linecap="square"/>
        <!-- Bottom Solid Rectangle -->
        <rect x="12" y="36.5" width="16" height="5" fill="currentColor"/>
    </svg>
    <?php
}

/**
 * Render logo block (icon + text).
 */
function anthro_logo( $link = true, $classes = '' ) {
    $custom_logo = get_custom_logo();
    $name = get_bloginfo( 'name' );
    $out = '';

    if ( $link ) $out .= '<a href="' . esc_url( home_url('/') ) . '" class="logo ' . esc_attr($classes) . '" rel="home">';
    $out .= '<div class="logo-icon">';
    if ( $custom_logo ) {
        $out .= $custom_logo;
    } else {
        ob_start(); anthro_logo_svg(); $out .= ob_get_clean();
    }
    $out .= '</div>';
    $out .= '<div class="logo-text"><span class="logo-en">Anthro</span><span class="logo-ar">أنثرو</span></div>';
    if ( $link ) $out .= '</a>';

    echo $out;
}

/**
 * Get social URL from Customizer.
 */
function anthro_social_url( $platform ) {
    return get_theme_mod( "anthro_social_{$platform}", '' );
}

/**
 * Render post category badge.
 */
function anthro_category_badge( $post_id = null ) {
    $cats = get_the_category( $post_id );
    if ( empty( $cats ) ) return;
    $cat = $cats[0];
    echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="art-cat">' . esc_html( $cat->name ) . '</a>';
}


/* =============================================
   11. AJAX — LOAD MORE POSTS
============================================= */
function anthro_load_more_posts() {
    check_ajax_referer( 'anthro_nonce', 'nonce' );

    $page     = absint( $_POST['page']     ?? 1 );
    $cat_id   = absint( $_POST['category'] ?? 0 );
    $per_page = absint( get_theme_mod( 'anthro_articles_per_page', 12 ) );

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];
    if ( $cat_id ) $args['cat'] = $cat_id;

    $query = new WP_Query( $args );
    $posts = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            ob_start();
            get_template_part( 'template-parts/card', 'article' );
            $posts[] = ob_get_clean();
        }
        wp_reset_postdata();
    }

    wp_send_json_success( [
        'posts'    => $posts,
        'has_more' => $query->max_num_pages > $page,
        'total'    => $query->found_posts,
    ] );
}
add_action( 'wp_ajax_anthro_load_more',        'anthro_load_more_posts' );
add_action( 'wp_ajax_nopriv_anthro_load_more', 'anthro_load_more_posts' );


/* =============================================
   12. ADMIN CUSTOMIZATIONS
============================================= */

// Welcome dashboard widget
function anthro_dashboard_widget() {
    wp_add_dashboard_widget(
        'anthro_welcome',
        '🌿 مرحباً في لوحة تحكم أنثرو',
        'anthro_dashboard_widget_cb'
    );
}
add_action( 'wp_dashboard_setup', 'anthro_dashboard_widget' );

function anthro_dashboard_widget_cb() {
    ?>
    <div style="font-family: 'Cairo', sans-serif; direction: rtl; padding: 8px 0; line-height: 1.8;">
        <p>مرحباً بك في <strong>أنثرو</strong> — منصة الأنثروبولوجيا السعودية.</p>
        <h4 style="color:#686848; margin: 16px 0 8px;">روابط سريعة:</h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <a href="<?php echo admin_url('post-new.php?post_type=post'); ?>" style="background:#686848; color:#F0F3EC; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:600; font-size:13px;">✍️ مقال جديد</a>
            <a href="<?php echo admin_url('post-new.php?post_type=podcast_episode'); ?>" style="background:#C47A44; color:#F0F3EC; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:600; font-size:13px;">🎙 حلقة جديدة</a>
            <a href="<?php echo admin_url('edit.php'); ?>" style="background:#F0F3EC; color:#686848; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:600; font-size:13px; border:1px solid #686848;">📋 كل المقالات</a>
            <a href="<?php echo admin_url('customize.php'); ?>" style="background:#F0F3EC; color:#686848; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:600; font-size:13px; border:1px solid #686848;">🎨 التخصيص</a>
        </div>
        <p style="color:#888; font-size:12px; margin-top: 16px;">الإصدار <?php echo ANTHRO_VER; ?> • لأي مساعدة تقنية راسلنا على dev@anthro.sa</p>
    </div>
    <?php
}

// Remove default dashboard widgets for cleaner admin
function anthro_remove_dashboard_widgets() {
    remove_meta_box( 'dashboard_quick_press',  'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary',      'dashboard', 'side' );
    remove_meta_box( 'dashboard_activity',     'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'anthro_remove_dashboard_widgets', 20 );

// Admin bar custom color
function anthro_admin_bar_css() {
    ?>
    <style>
        #wpadminbar { background: #3d3e28 !important; }
        #wpadminbar .ab-item { font-family: 'Cairo', sans-serif !important; }
    </style>
    <?php
}
add_action( 'admin_head', 'anthro_admin_bar_css' );
add_action( 'wp_head',    'anthro_admin_bar_css' );


/* =============================================
   13. SEO — BASIC META (if no SEO plugin)
============================================= */
function anthro_meta_description() {
    if ( is_singular() ) {
        $excerpt = get_the_excerpt();
        if ( $excerpt ) {
            echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $excerpt ) ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'anthro_meta_description' );


/* =============================================
   14. PERFORMANCE
============================================= */
// Remove emoji scripts (saves ~20kb)
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );

// Remove RSD link, wlwmanifest
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' ); // Hide WP version


/* =============================================
   13. PAGINATION  —  ترقيم الصفحات
   تستدعيها: index.php / archive.php / category.php
             / search.php / author.php
============================================= */

if ( ! function_exists( 'anthro_pagination' ) ) :
/**
 * ترقيم صفحات متوافق مع RTL وبأرقام عربية.
 *
 * @param WP_Query|null $query استعلام مخصص، أو null للاستعلام الرئيسي.
 */
function anthro_pagination( $query = null ) {

    global $wp_query;
    $q = $query instanceof WP_Query ? $query : $wp_query;

    if ( $q->max_num_pages <= 1 ) {
        return;
    }

    $current = max( 1, get_query_var( 'paged' ) ?: get_query_var( 'page' ) ?: 1 );

    // في RTL يُعكس اتجاه الأسهم بصرياً عبر CSS، لا عبر تبديل النصوص.
    $links = paginate_links( [
        'base'      => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => $q->max_num_pages,
        'mid_size'  => 1,
        'end_size'  => 1,
        'type'      => 'array',
        'prev_text' => '<span class="page-btn page-btn--prev" aria-hidden="true">'
                     . '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>'
                     . '</span><span class="screen-reader-text">' . esc_html__( 'السابق', 'anthro' ) . '</span>',
        'next_text' => '<span class="page-btn page-btn--next" aria-hidden="true">'
                     . '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>'
                     . '</span><span class="screen-reader-text">' . esc_html__( 'التالي', 'anthro' ) . '</span>',
    ] );

    if ( empty( $links ) ) {
        return;
    }

    echo '<nav class="pagination" role="navigation" aria-label="' . esc_attr__( 'ترقيم الصفحات', 'anthro' ) . '">';

    foreach ( $links as $link ) {
        // تحويل الأرقام اللاتينية إلى عربية داخل الروابط فقط.
        $link = preg_replace_callback(
            '/>(\d+)</',
            function ( $m ) { return '>' . anthro_arabic_num( $m[1] ) . '<'; },
            $link
        );
        echo wp_kses_post( $link );
    }

    echo '</nav>';
}
endif;


/* =============================================
   14. SEARCH QUERY REFINEMENTS  —  ضبط البحث
============================================= */

/**
 * توسيع البحث ليشمل حلقات البودكاست، وترتيب النتائج بالصلة.
 */
function anthro_search_include_cpts( $query ) {

    if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
        return;
    }

    $query->set( 'post_type', [ 'post', 'page', 'podcast_episode' ] );

    // دعم الفرز عبر ?orderby= في صفحات التصنيف
    if ( isset( $_GET['orderby'] ) ) {
        $orderby = sanitize_key( $_GET['orderby'] );
        if ( in_array( $orderby, [ 'date', 'title', 'rand' ], true ) ) {
            $query->set( 'orderby', $orderby );
            $query->set( 'order', $orderby === 'title' ? 'ASC' : 'DESC' );
        }
    }
}
add_action( 'pre_get_posts', 'anthro_search_include_cpts' );


/**
 * تطبيق الفرز على أرشيفات التصنيفات.
 */
function anthro_category_orderby( $query ) {

    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( ( $query->is_category() || $query->is_tax() || $query->is_archive() ) && isset( $_GET['orderby'] ) ) {
        $orderby = sanitize_key( $_GET['orderby'] );
        if ( in_array( $orderby, [ 'date', 'title', 'rand' ], true ) ) {
            $query->set( 'orderby', $orderby );
            $query->set( 'order', $orderby === 'title' ? 'ASC' : 'DESC' );
        }
    }
}
add_action( 'pre_get_posts', 'anthro_category_orderby' );


/* =============================================
   15. AUTHOR PROFILE FIELDS  —  حقول الباحث
============================================= */

/**
 * إضافة حقل تويتر/إكس لملف المستخدم (يستخدمه author.php).
 */
function anthro_author_contact_fields( $fields ) {
    $fields['twitter']   = __( 'حساب X (تويتر)', 'anthro' );
    $fields['scholar']   = __( 'Google Scholar', 'anthro' );
    $fields['institute'] = __( 'الجهة الأكاديمية', 'anthro' );
    return $fields;
}
add_filter( 'user_contactmethods', 'anthro_author_contact_fields' );


/* =============================================
   16. PERFORMANCE  —  تحسينات الأداء
============================================= */

/**
 * تفعيل التحميل الكسول والأبعاد الصريحة للصور.
 */
function anthro_image_defaults( $attr ) {
    if ( ! isset( $attr['loading'] ) ) {
        $attr['loading'] = 'lazy';
    }
    if ( ! isset( $attr['decoding'] ) ) {
        $attr['decoding'] = 'async';
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'anthro_image_defaults' );


/**
 * إزالة الـ emoji scripts غير المستخدمة (توفير ~12KB).
 */
function anthro_disable_emojis() {
    remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles',     'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles',  'print_emoji_styles' );
}
add_action( 'init', 'anthro_disable_emojis' );


/* =============================================
   17. PODCAST PLAYER SCRIPT  —  ضمان التحميل
   ملاحظة: functions.php يسجّل الهاندل 'anthro-podcast'
   مسبقاً في anthro_scripts(). لا نكرر التسجيل — نتحقق فقط
   من أنه فعلاً مُحمّل على صفحات الحلقات، ونضيفه إن غاب.
============================================= */

function anthro_podcast_scripts() {

    if ( ! is_singular( 'podcast_episode' ) ) {
        return;
    }

    // إن كان مسجلاً بالفعل من anthro_scripts() فلا نفعل شيئاً.
    if ( wp_script_is( 'anthro-podcast', 'enqueued' ) || wp_script_is( 'anthro-podcast', 'registered' ) ) {
        return;
    }

    wp_enqueue_script(
        'anthro-podcast',
        ANTHRO_URI . '/assets/js/anthro-podcast.js',
        [],
        defined( 'ANTHRO_VER' ) ? ANTHRO_VER : '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'anthro_podcast_scripts', 20 );


/* =============================================
   18. PODCAST QUERY  —  ترتيب الحلقات
============================================= */

/**
 * ترتيب الحلقات تنازلياً حسب رقم الحلقة، لا حسب التاريخ.
 */
function anthro_podcast_order( $query ) {

    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( $query->is_post_type_archive( 'podcast_episode' ) || $query->is_tax( 'podcast_season' ) ) {
        $query->set( 'meta_key', '_anthro_ep_number' );
        $query->set( 'orderby', [ 'meta_value_num' => 'DESC', 'date' => 'DESC' ] );
        $query->set( 'posts_per_page', 12 );
    }
}
add_action( 'pre_get_posts', 'anthro_podcast_order' );


/* =============================================
   19. PODCAST SEO  —  بيانات منظمة
============================================= */

/**
 * إخراج Schema.org PodcastEpisode لتحسين الظهور في نتائج البحث.
 */
function anthro_podcast_schema() {

    if ( ! is_singular( 'podcast_episode' ) ) {
        return;
    }

    $post_id   = get_the_ID();
    $audio_url = get_post_meta( $post_id, '_anthro_audio_url', true );

    if ( ! $audio_url ) {
        return;
    }

    $schema = [
        '@context'      => 'https://schema.org',
        '@type'         => 'PodcastEpisode',
        'url'           => get_permalink( $post_id ),
        'name'          => get_the_title( $post_id ),
        'datePublished' => get_the_date( 'c', $post_id ),
        'description'   => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
        'associatedMedia' => [
            '@type'      => 'MediaObject',
            'contentUrl' => $audio_url,
        ],
        'partOfSeries'  => [
            '@type' => 'PodcastSeries',
            'name'  => get_bloginfo( 'name' ),
            'url'   => get_post_type_archive_link( 'podcast_episode' ),
        ],
    ];

    $ep_number = get_post_meta( $post_id, '_anthro_ep_number', true );
    if ( $ep_number ) {
        $schema['episodeNumber'] = absint( $ep_number );
    }

    if ( has_post_thumbnail( $post_id ) ) {
        $schema['image'] = get_the_post_thumbnail_url( $post_id, 'full' );
    }

    echo '<script type="application/ld+json">'
       . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
       . '</script>' . "\n";
}
add_action( 'wp_head', 'anthro_podcast_schema' );


/* =============================================
   20. CUSTOMIZER  —  روابط منصات البودكاست
============================================= */

function anthro_podcast_customizer( $wp_customize ) {

    $wp_customize->add_section( 'anthro_podcast_section', [
        'title'    => __( 'روابط البودكاست', 'anthro' ),
        'priority' => 35,
    ] );

    $platforms = [
        'anthro_social_spotify' => __( 'رابط البودكاست على Spotify', 'anthro' ),
        'anthro_social_apple'   => __( 'رابط البودكاست على Apple Podcasts', 'anthro' ),
    ];

    foreach ( $platforms as $setting => $label ) {
        $wp_customize->add_setting( $setting, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ] );

        $wp_customize->add_control( $setting, [
            'label'   => $label,
            'section' => 'anthro_podcast_section',
            'type'    => 'url',
        ] );
    }
}
add_action( 'customize_register', 'anthro_podcast_customizer' );


/* =============================================
   21. ONE-CLICK AUTO DEMO DATA & SITE SETUP
============================================= */

function anthro_get_post_by_title( $title, $post_type = 'post' ) {
    $posts = get_posts( [
        'title'                  => $title,
        'post_type'              => $post_type,
        'post_status'            => 'any',
        'numberposts'            => 1,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ] );
    return ! empty( $posts ) ? $posts[0] : null;
}

function anthro_auto_setup_demo_content() {
    // Ensure Taxonomy & Category Admin APIs are loaded
    if ( ! function_exists( 'wp_create_category' ) ) {
        if ( file_exists( ABSPATH . 'wp-admin/includes/taxonomy.php' ) ) {
            require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        }
    }

    // 1. Create & Set Front Page
    $front_page = anthro_get_post_by_title( 'الرئيسية', 'page' );
    if ( ! $front_page ) {
        $front_page_id = wp_insert_post( [
            'post_title'   => 'الرئيسية',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ] );
    } else {
        $front_page_id = $front_page->ID;
    }

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id );

    // 2. Set Permalinks to /%postname%/
    global $wp_rewrite;
    if ( $wp_rewrite ) {
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
        $wp_rewrite->flush_rules();
    }

    // 3. Create Categories
    $cat_arch = function_exists( 'wp_create_category' ) ? wp_create_category( 'أنثروبولوجيا العمران' ) : 1;
    $cat_cult = function_exists( 'wp_create_category' ) ? wp_create_category( 'الثقافة المادية' ) : 1;
    $cat_hist = function_exists( 'wp_create_category' ) ? wp_create_category( 'التاريخ الشفهي' ) : 1;

    // 4. Create Featured Article 1
    $post_1 = anthro_get_post_by_title( 'روشن الحجاز: هندسة الضوء والخصوصية في المعمار التقليدي', 'post' );
    if ( ! $post_1 ) {
        $p1_id = wp_insert_post( [
            'post_title'   => 'روشن الحجاز: هندسة الضوء والخصوصية في المعمار التقليدي',
            'post_content' => 'يمثل الروشن الحجازي إحدى أبرز المفردات المعمارية التقليدية في غرب الجزيرة العربية، حيث يدمج بين الوظيفة البيئية لتهوية المنازل والجمالية البصرية، مع الحفاظ الكامل على الخصوصية الاجتماعية. تناقش هذه الدراسة الميدانية كيف تكيف الإنسان الحجازي مع حرارة الصيف عبر ابتكار واجهات خشبية تنفسية مفرغة تُعرف بالروشن.',
            'post_excerpt' => 'دراسة ميدانية لمفردات الرواشين الحجازية وعلاقتها بالبنية الاجتماعية والتكيف المناخي في جدة التاريخية.',
            'post_status'  => 'publish',
            'post_category'=> [ $cat_arch ],
        ] );

        if ( $p1_id && ! is_wp_error( $p1_id ) ) {
            update_post_meta( $p1_id, '_anthro_featured', '1' );
            update_post_meta( $p1_id, '_anthro_read_time', '8' );
            update_post_meta( $p1_id, '_anthro_opening_quote', 'الروشن ليس مجرد نسيج خشبي معقد، بل هو نظام تنفس اجتماعي ومعماري متكامل حظي به المنزل الحجازي.' );
        }
    }

    // 5. Create Article 2
    $post_2 = anthro_get_post_by_title( 'القهوة السعودية: أنثروبولوجيا الضيافة والرمزية الاجتماعية', 'post' );
    if ( ! $post_2 ) {
        $p2_id = wp_insert_post( [
            'post_title'   => 'القهوة السعودية: أنثروبولوجيا الضيافة والرمزية الاجتماعية',
            'post_content' => 'تعد القهوة في الثقافة السعودية رمزاً عريقاً للكرامة والضيافة، وترتبط بطقوس وممارسات اجتماعية دقيقة تبدأ من اختيار حبوب البن وتجهيز المحماس، وحتى صب الفنجال باليد اليمنى وتحديد مقداره. يستعرض هذا البحث الرموز الثقافية والتواصلية التي تحكم مجلس القهوة.',
            'post_excerpt' => 'قراءة أنثروبولوجية في دلالات القهوة وطقوس تقديمها في المجتمع السعودي عبر الأجيال.',
            'post_status'  => 'publish',
            'post_category'=> [ $cat_cult ],
        ] );

        if ( $p2_id && ! is_wp_error( $p2_id ) ) {
            update_post_meta( $p2_id, '_anthro_featured', '0' );
            update_post_meta( $p2_id, '_anthro_read_time', '6' );
        }
    }

    // 6. Create Article 3
    $post_3 = anthro_get_post_by_title( 'نقوش العلا واللحيانيون: توثيق المعتقدات والرموز القديمة', 'post' );
    if ( ! $post_3 ) {
        $p3_id = wp_insert_post( [
            'post_title'   => 'نقوش العلا واللحيانيون: توثيق المعتقدات والرموز القديمة',
            'post_content' => 'تكشف النقوش الصخرية في وادي العلا عن ثراء لغوي وديني للشعوب الاستيطانية القديمة كالديدانيين واللحيانيين. تناقش هذه الورقة قراءة أنثروبولوجية للنصوص المكتوبة والرموز الصخرية التي وثقت الحياة اليومية والتجارية في شمال غرب الجزيرة العربية.',
            'post_excerpt' => 'تحليل أنثروبولوجي للنصوص الصخرية والرموز العقائدية في شمال غرب المملكة.',
            'post_status'  => 'publish',
            'post_category'=> [ $cat_hist ],
        ] );

        if ( $p3_id && ! is_wp_error( $p3_id ) ) {
            update_post_meta( $p3_id, '_anthro_featured', '0' );
            update_post_meta( $p3_id, '_anthro_read_time', '10' );
        }
    }

    // 7. Create Podcast Episode 1
    $ep_1 = anthro_get_post_by_title( 'الحلقة 1: العمارة الطينية ونمط الحياة القديم في نجد', 'podcast_episode' );
    if ( ! $ep_1 ) {
        $ep1_id = wp_insert_post( [
            'post_title'   => 'الحلقة 1: العمارة الطينية ونمط الحياة القديم في نجد',
            'post_content' => 'في هذه الحلقة الأولى من بودكاست أنثرو، نستضيف الباحثة د. نورة المحمد للحوار حول تقنيات البناء بالطين، وتصميم قصر المربع والمجمعات السكنية النجدية، وكيف شكلت هذه المواد الطبيعية نمط العلاقات الاجتماعية والتكافل بين السكان.',
            'post_excerpt' => 'حوار حول العمارة الطينية وتقنيات البناء النجدية التقليدية وتأثير البيئة الصحراوية.',
            'post_status'  => 'publish',
            'post_type'    => 'podcast_episode',
        ] );

        if ( $ep1_id && ! is_wp_error( $ep1_id ) ) {
            update_post_meta( $ep1_id, '_anthro_ep_number', '1' );
            update_post_meta( $ep1_id, '_anthro_ep_duration', '52' );
            update_post_meta( $ep1_id, '_anthro_guest_name', 'د. نورة المحمد' );
            update_post_meta( $ep1_id, '_anthro_audio_url', 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3' );
            update_post_meta( $ep1_id, '_anthro_spotify_url', 'https://spotify.com' );
            update_post_meta( $ep1_id, '_anthro_apple_url', 'https://apple.com' );
        }
    }

    // 8. Update Current Admin Bio
    $user_id = get_current_user_id();
    if ( $user_id ) {
        update_user_meta( $user_id, 'description', 'أستاذ أنثروبولوجيا ومؤرخ مهتم بتوثيق التراث المادي والشفهي في الجزيرة العربية.' );
        update_user_meta( $user_id, 'twitter', 'anthro_sa' );
    }
}

// Automatic Run on Theme Activation
add_action( 'after_switch_theme', 'anthro_auto_setup_demo_content' );

// One-Click Admin Banner Notice
function anthro_demo_import_notice() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    
    if ( isset( $_GET['anthro_imported'] ) ) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>🌿 تم تثبيت وتفعيل كافة محتويات مشروع أنثرو التلقائية والصفحة الرئيسية بنجاح!</strong></p></div>';
        return;
    }

    $setup_url = wp_nonce_url( admin_url( 'admin.php?action=anthro_do_import_demo' ), 'anthro_import_action' );
    echo '<div class="notice notice-info" style="border-inline-start-color: #686848; padding: 12px 18px;">
        <h3 style="margin: 0 0 6px 0; color: #4a5d4e;">🌿 تثبيت محتوى مشروع أنثرو التلقائي (Anthropology Project Demo Content)</h3>
        <p style="margin: 0 0 10px 0;">اضغط على الزر أدناه لتلقائياً إعداد الصفحة الرئيسية، وتوليد المقالات المميزة، التصنيفات، والبودكاست ليظهر موقعك مثل الـ Prototype تماماً وبدون أي إدخال يدوي:</p>
        <a href="' . esc_url( $setup_url ) . '" class="button button-primary" style="background: #686848; border-color: #4a5d4e; font-weight: bold;">🚀 اضغط هنا لـ تفعيل ومحي محتوى المشروع التلقائي بضغطة زر</a>
    </div>';
}
add_action( 'admin_notices', 'anthro_demo_import_notice' );

// Handle One-Click Import Action
function anthro_handle_demo_import_action() {
    if ( isset( $_GET['action'] ) && $_GET['action'] === 'anthro_do_import_demo' && check_admin_referer( 'anthro_import_action' ) ) {
        anthro_auto_setup_demo_content();
        wp_redirect( admin_url( 'index.php?anthro_imported=1' ) );
        exit;
    }
}
add_action( 'admin_action_anthro_do_import_demo', 'anthro_handle_demo_import_action' );



