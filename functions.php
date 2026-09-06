<?php
/**
 * TeleGroupsor Theme Functions and Definitions
 *
 * @package Telegram_Group_Links
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ==================================================
// 1. THEME SETUP
// ==================================================

function Telegram_Group_Links_theme_setup() {
    add_theme_support( 'custom-logo' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'Telegram_Group_Links' ),
    ) );
}
add_action( 'after_setup_theme', 'Telegram_Group_Links_theme_setup' );


// ==================================================
// 2. ENQUEUE STYLES & SCRIPTS
// ==================================================

function load_css() {
    $theme_version = wp_get_theme()->get( 'Version' );

    // Use file modification times so updated CSS is never hidden by an old browser/CDN cache.
    $main_css_version    = file_exists( get_theme_file_path( '/css/main.css' ) ) ? filemtime( get_theme_file_path( '/css/main.css' ) ) : $theme_version;
    $header_css_version  = file_exists( get_theme_file_path( '/css/header.css' ) ) ? filemtime( get_theme_file_path( '/css/header.css' ) ) : $theme_version;
    $footer_css_version  = file_exists( get_theme_file_path( '/css/footer.css' ) ) ? filemtime( get_theme_file_path( '/css/footer.css' ) ) : $theme_version;
    $blog_css_version    = file_exists( get_theme_file_path( '/css/blog_page.css' ) ) ? filemtime( get_theme_file_path( '/css/blog_page.css' ) ) : $theme_version;
    $tailwind_css_path   = get_theme_file_path( '/tailwind.css' );
    $tailwind_css_version = file_exists( $tailwind_css_path ) ? filemtime( $tailwind_css_path ) : $theme_version;

    // Base/theme styles.
    wp_enqueue_style( 'main', get_theme_file_uri( '/css/main.css' ), array(), $main_css_version );
    wp_enqueue_style( 'header', get_theme_file_uri( '/css/header.css' ), array(), $header_css_version );
    wp_enqueue_style( 'footer', get_theme_file_uri( '/css/footer.css' ), array(), $footer_css_version );
    wp_enqueue_style( 'blog_page', get_theme_file_uri( '/css/blog_page.css' ), array(), $blog_css_version );

    // Compiled Tailwind CSS. Load after the theme CSS so Tailwind utilities win where intended.
    wp_enqueue_style(
        'tailwind-output',
        get_theme_file_uri( '/tailwind.css' ),
        array( 'main', 'header', 'footer', 'blog_page' ),
        $tailwind_css_version
    );
}
add_action( 'wp_enqueue_scripts', 'load_css' );


// ==================================================
// 3. WIDGET AREAS
// ==================================================

function html2wp_widgets_init() {
    $sidebars = array(
        array(
            'name'        => __( 'Primary Sidebar', 'Telegram_Group_Links' ),
            'id'          => 'main-sidebar',
            'description' => __( 'Main sidebar on right side', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Blog Section', 'Telegram_Group_Links' ),
            'id'          => 'Blog-Section',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Sticker Section', 'Telegram_Group_Links' ),
            'id'          => 'Sticker-Section',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Categories Section', 'Telegram_Group_Links' ),
            'id'          => 'Categories-Section',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Footer Widget 1', 'Telegram_Group_Links' ),
            'id'          => 'footer-1',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Footer Widget 2', 'Telegram_Group_Links' ),
            'id'          => 'footer-2',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Footer Widget 3', 'Telegram_Group_Links' ),
            'id'          => 'footer-3',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Footer Widget 4', 'Telegram_Group_Links' ),
            'id'          => 'footer-4',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Footer Widget 5', 'Telegram_Group_Links' ),
            'id'          => 'footer-5',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Footer Widget 6', 'Telegram_Group_Links' ),
            'id'          => 'footer-6',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Rules', 'Telegram_Group_Links' ),
            'id'          => 'rules-widget',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Rate', 'Telegram_Group_Links' ),
            'id'          => 'rate-widget',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Share', 'Telegram_Group_Links' ),
            'id'          => 'share-widget',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Sticker page description', 'Telegram_Group_Links' ),
            'id'          => 'Sticker-Section-description',
            'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'Telegram_Group_Links' ),
        ),
        array(
            'name'        => __( 'Form Widget', 'Telegram_Group_Links' ),
            'id'          => 'form-widget',
            'description' => __( 'Widgets added here will appear directly beneath the submission form.', 'Telegram_Group_Links' ),
        ),
    );

    foreach ( $sidebars as $sidebar ) {
        register_sidebar( array(
            'name'          => $sidebar['name'],
            'id'            => $sidebar['id'],
            'description'   => $sidebar['description'],
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ) );
    }
}
add_action( 'widgets_init', 'html2wp_widgets_init' );


// ==================================================
// 4. DYNAMIC DOCUMENT TITLE & YOAST HELPER
// ==================================================

if ( ! function_exists( 'get_yoast_seo_title' ) ) {
    function get_yoast_seo_title() {
        $queried_obj = get_queried_object();

        if ( function_exists( 'YoastSEO' ) ) {
            try {
                $yoast_meta = YoastSEO()->meta->for_current_page();
                if ( $yoast_meta && ! empty( $yoast_meta->title ) ) {
                    return $yoast_meta->title;
                }
            } catch ( Exception $e ) {
            } catch ( Throwable $t ) {
            }
        }

        if ( $queried_obj instanceof WP_Term ) {
            $tax_meta = get_option( 'wpseo_taxonomy_meta' );
            if ( ! empty( $tax_meta[ $queried_obj->taxonomy ][ $queried_obj->term_id ]['wpseo_title'] ) ) {
                $raw = $tax_meta[ $queried_obj->taxonomy ][ $queried_obj->term_id ]['wpseo_title'];
                return function_exists( 'wpseo_replace_vars' ) ? wpseo_replace_vars( $raw, $queried_obj ) : $raw;
            }
            return single_term_title( '', false );
        }

        if ( is_singular() ) {
            return get_the_title();
        }

        if ( function_exists( 'wp_get_document_title' ) ) {
            return wp_get_document_title();
        }

        return get_bloginfo( 'name' );
    }
}


// ==================================================
// 5. NAV MENU CUSTOM LOGO FIELD & WALKER
// ==================================================

function add_menu_item_logo_field( $item_id, $item, $depth, $args ) {
    $logo_url = get_post_meta( $item_id, '_menu_item_logo', true );
    ?>
    <p class="field-url description description-wide">
        <label for="edit-menu-item-logo-<?php echo esc_attr( $item_id ); ?>">
            <?php esc_html_e( 'Logo URL', 'Telegram_Group_Links' ); ?><br />
            <input type="text" id="edit-menu-item-logo-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-logo" name="menu-item-logo[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $logo_url ); ?>" />
        </label>
    </p>
    <?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'add_menu_item_logo_field', 10, 4 );

function save_menu_item_logo_field( $menu_id, $menu_item_db_id ) {
    if ( isset( $_POST['menu-item-logo'][ $menu_item_db_id ] ) ) {
        $logo_url = sanitize_text_field( wp_unslash( $_POST['menu-item-logo'][ $menu_item_db_id ] ) );
        update_post_meta( $menu_item_db_id, '_menu_item_logo', $logo_url );
    }
}
add_action( 'wp_update_nav_menu_item', 'save_menu_item_logo_field', 10, 2 );

class WP_Custom_Navwalker extends Walker_Nav_Menu {
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent  = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes     = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= '<li' . $class_names . '>';

        $atts           = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title    = apply_filters( 'the_title', $item->title, $item->ID );
        $logo_url = get_post_meta( $item->ID, '_menu_item_logo', true );

        $before = is_object( $args ) && isset( $args->before ) ? $args->before : '';
        $after  = is_object( $args ) && isset( $args->after ) ? $args->after : '';
        $l_bef  = is_object( $args ) && isset( $args->link_before ) ? $args->link_before : '';
        $l_aft  = is_object( $args ) && isset( $args->link_after ) ? $args->link_after : '';

        $item_output  = $before;
        $item_output .= '<a' . $attributes . '>';
        if ( $logo_url ) {
            $item_output .= '<div class="menu-item-logo">';
            $item_output .= '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $title ) . '" loading="lazy">';
            $item_output .= '</div>';
        }
        $item_output .= $l_bef . $title . $l_aft;
        $item_output .= '</a>';
        $item_output .= $after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}


// ==================================================
// 6. CATEGORY IMAGE META
// ==================================================

function add_category_image_field() {
    ?>
    <div class="form-field">
        <label for="category_image"><?php esc_html_e( 'Category Image', 'Telegram_Group_Links' ); ?></label>
        <input type="text" name="category_image" id="category_image" value="">
        <p class="description"><?php esc_html_e( 'Enter the URL of the image for this category.', 'Telegram_Group_Links' ); ?></p>
    </div>
    <?php
}
add_action( 'category_add_form_fields', 'add_category_image_field' );

function edit_category_image_field( $term ) {
    $image = get_term_meta( $term->term_id, 'category_image', true );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="category_image"><?php esc_html_e( 'Category Image', 'Telegram_Group_Links' ); ?></label></th>
        <td>
            <input type="text" name="category_image" id="category_image" value="<?php echo esc_attr( $image ); ?>">
            <p class="description"><?php esc_html_e( 'Enter the URL of the image for this category.', 'Telegram_Group_Links' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'edit_category_image_field' );

function save_category_image_meta( $term_id ) {
    if ( ! current_user_can( 'edit_term', $term_id ) ) {
        return;
    }
    if ( isset( $_POST['category_image'] ) ) {
        update_term_meta( $term_id, 'category_image', esc_url_raw( wp_unslash( $_POST['category_image'] ) ) );
    }
}
add_action( 'edited_category', 'save_category_image_meta' );
add_action( 'create_category', 'save_category_image_meta' );


// ==================================================
// 7. CUSTOM POST TYPES & TAXONOMIES
// ==================================================

function register_sticker_post_type() {
    $labels = array(
        'name'               => _x( 'Stickers', 'Post Type General Name', 'Telegram_Group_Links' ),
        'singular_name'      => _x( 'Sticker', 'Post Type Singular Name', 'Telegram_Group_Links' ),
        'menu_name'          => __( 'Stickers', 'Telegram_Group_Links' ),
        'name_admin_bar'     => __( 'Sticker', 'Telegram_Group_Links' ),
        'add_new'            => __( 'Add New', 'Telegram_Group_Links' ),
        'add_new_item'       => __( 'Add New Sticker', 'Telegram_Group_Links' ),
        'new_item'           => __( 'New Sticker', 'Telegram_Group_Links' ),
        'edit_item'          => __( 'Edit Sticker', 'Telegram_Group_Links' ),
        'view_item'          => __( 'View Sticker', 'Telegram_Group_Links' ),
        'all_items'          => __( 'All Stickers', 'Telegram_Group_Links' ),
        'search_items'       => __( 'Search Stickers', 'Telegram_Group_Links' ),
        'parent_item_colon'  => __( 'Parent Stickers:', 'Telegram_Group_Links' ),
        'not_found'          => __( 'No stickers found.', 'Telegram_Group_Links' ),
        'not_found_in_trash' => __( 'No stickers found in Trash.', 'Telegram_Group_Links' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'sticker' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon'          => 'dashicons-format-image',
        'show_in_rest'       => true,
    );

    register_post_type( 'sticker', $args );
}
add_action( 'init', 'register_sticker_post_type' );

function register_sticker_taxonomy() {
    $labels = array(
        'name'              => _x( 'Sticker Links', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Sticker Link', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Sticker Links', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Sticker Links', 'Telegram_Group_Links' ),
        'parent_item'       => __( 'Parent Sticker Link', 'Telegram_Group_Links' ),
        'parent_item_colon' => __( 'Parent Sticker Link:', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Sticker Link', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Sticker Link', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Sticker Link', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Sticker Link Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Sticker Links', 'Telegram_Group_Links' ),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'sticker-link' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'sticker_link', array( 'sticker' ), $args );
}
add_action( 'init', 'register_sticker_taxonomy' );

function create_custom_type_taxonomy() {
    // Type Taxonomy
    $type_labels = array(
        'name'              => _x( 'Types', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Type', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Types', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Types', 'Telegram_Group_Links' ),
        'parent_item'       => __( 'Parent Type', 'Telegram_Group_Links' ),
        'parent_item_colon' => __( 'Parent Type:', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Type', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Type', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Type', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Type Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Type', 'Telegram_Group_Links' ),
    );

    register_taxonomy( 'type', array( 'post' ), array(
        'hierarchical'      => true,
        'labels'            => $type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'type' ),
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_in_rest'      => true,
    ) );

    // Subscription Taxonomy
    $subscription_labels = array(
        'name'              => _x( 'Subscriptions', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Subscription', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Subscriptions', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Subscriptions', 'Telegram_Group_Links' ),
        'parent_item'       => __( 'Parent Subscription', 'Telegram_Group_Links' ),
        'parent_item_colon' => __( 'Parent Subscription:', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Subscription', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Subscription', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Subscription', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Subscription Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Subscription', 'Telegram_Group_Links' ),
    );

    register_taxonomy( 'subscription', array( 'post' ), array(
        'hierarchical'      => true,
        'labels'            => $subscription_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'subscription' ),
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_in_rest'      => true,
    ) );

    // Language Taxonomy
    $language_labels = array(
        'name'              => _x( 'Languages', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Language', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Languages', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Languages', 'Telegram_Group_Links' ),
        'parent_item'       => __( 'Parent Language', 'Telegram_Group_Links' ),
        'parent_item_colon' => __( 'Parent Language:', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Language', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Language', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Language', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Language Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Language', 'Telegram_Group_Links' ),
    );

    register_taxonomy( 'language', array( 'post' ), array(
        'hierarchical'      => true,
        'labels'            => $language_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'language' ),
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_in_rest'      => true,
    ) );

    // Country Taxonomy
    $country_labels = array(
        'name'              => _x( 'Countries', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Country', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Countries', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Countries', 'Telegram_Group_Links' ),
        'parent_item'       => __( 'Parent Country', 'Telegram_Group_Links' ),
        'parent_item_colon' => __( 'Parent Country:', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Country', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Country', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Country', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Country Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Country', 'Telegram_Group_Links' ),
    );

    register_taxonomy( 'country', array( 'post' ), array(
        'hierarchical'      => true,
        'labels'            => $country_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'country' ),
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_in_rest'      => true,
    ) );

    // Admin Name Taxonomy
    $admin_name_labels = array(
        'name'              => _x( 'Admin Names', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Admin Name', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Admin Names', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Admin Names', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Admin Name', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Admin Name', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Admin Name', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Admin Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Admin Name', 'Telegram_Group_Links' ),
    );

    register_taxonomy( 'admin-name', array( 'post' ), array(
        'hierarchical'      => false,
        'labels'            => $admin_name_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'admin-name' ),
        'show_in_rest'      => true,
    ) );

    // Group Link Taxonomy
    $group_link_labels = array(
        'name'              => _x( 'Group Links', 'taxonomy general name', 'Telegram_Group_Links' ),
        'singular_name'     => _x( 'Group Link', 'taxonomy singular name', 'Telegram_Group_Links' ),
        'search_items'      => __( 'Search Group Links', 'Telegram_Group_Links' ),
        'all_items'         => __( 'All Group Links', 'Telegram_Group_Links' ),
        'parent_item'       => __( 'Parent Group Link', 'Telegram_Group_Links' ),
        'parent_item_colon' => __( 'Parent Group Link:', 'Telegram_Group_Links' ),
        'edit_item'         => __( 'Edit Group Link', 'Telegram_Group_Links' ),
        'update_item'       => __( 'Update Group Link', 'Telegram_Group_Links' ),
        'add_new_item'      => __( 'Add New Group Link', 'Telegram_Group_Links' ),
        'new_item_name'     => __( 'New Group Link Name', 'Telegram_Group_Links' ),
        'menu_name'         => __( 'Group Link', 'Telegram_Group_Links' ),
    );

    register_taxonomy( 'group-link', array( 'post' ), array(
        'hierarchical'      => false,
        'labels'            => $group_link_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'group-link' ),
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'create_custom_type_taxonomy', 0 );


// ==================================================
// 8. SUBSCRIBER COUNT METABOX
// ==================================================

function add_subscriber_count_meta_box() {
    add_meta_box(
        'subscriber_count_meta_box',
        __( 'Subscriber Count', 'Telegram_Group_Links' ),
        'show_subscriber_count_meta_box',
        'post',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_subscriber_count_meta_box' );

function show_subscriber_count_meta_box( $post ) {
    wp_nonce_field( 'save_subscriber_count', 'subscriber_count_nonce' );
    $subscriber_count = get_post_meta( $post->ID, 'subscriber_count', true );
    ?>
    <label for="subscriber_count"><?php esc_html_e( 'Subscriber Count:', 'Telegram_Group_Links' ); ?></label>
    <input type="number" name="subscriber_count" id="subscriber_count" value="<?php echo esc_attr( $subscriber_count ); ?>" style="width:100%;margin-top:4px;" />
    <?php
}

function save_subscriber_count_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['subscriber_count_nonce'] ) || ! wp_verify_nonce( $_POST['subscriber_count_nonce'], 'save_subscriber_count' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['subscriber_count'] ) ) {
        update_post_meta( $post_id, 'subscriber_count', sanitize_text_field( wp_unslash( $_POST['subscriber_count'] ) ) );
    }
}
add_action( 'save_post', 'save_subscriber_count_meta_box_data' );


// ==================================================
// 9. BLOG POST TYPE & BLOCK EDITOR INTEGRATION
// ==================================================

function create_blog_custom_post_type() {
    $labels = array(
        'name'                  => _x( 'Blogs', 'Post Type General Name', 'Telegram_Group_Links' ),
        'singular_name'         => _x( 'Blog', 'Post Type Singular Name', 'Telegram_Group_Links' ),
        'menu_name'             => __( 'Blogs', 'Telegram_Group_Links' ),
        'name_admin_bar'        => __( 'Blog', 'Telegram_Group_Links' ),
        'archives'              => __( 'Blog Archives', 'Telegram_Group_Links' ),
        'attributes'            => __( 'Blog Attributes', 'Telegram_Group_Links' ),
        'parent_item_colon'     => __( 'Parent Blog:', 'Telegram_Group_Links' ),
        'all_items'             => __( 'All Blogs', 'Telegram_Group_Links' ),
        'add_new_item'          => __( 'Add New Blog', 'Telegram_Group_Links' ),
        'add_new'               => __( 'Add New', 'Telegram_Group_Links' ),
        'new_item'              => __( 'New Blog', 'Telegram_Group_Links' ),
        'edit_item'             => __( 'Edit Blog', 'Telegram_Group_Links' ),
        'update_item'           => __( 'Update Blog', 'Telegram_Group_Links' ),
        'view_item'             => __( 'View Blog', 'Telegram_Group_Links' ),
        'view_items'            => __( 'View Blogs', 'Telegram_Group_Links' ),
        'search_items'          => __( 'Search Blog', 'Telegram_Group_Links' ),
        'not_found'             => __( 'Not found', 'Telegram_Group_Links' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'Telegram_Group_Links' ),
        'featured_image'        => __( 'Featured Image', 'Telegram_Group_Links' ),
        'set_featured_image'    => __( 'Set featured image', 'Telegram_Group_Links' ),
        'remove_featured_image' => __( 'Remove featured image', 'Telegram_Group_Links' ),
        'use_featured_image'    => __( 'Use as featured image', 'Telegram_Group_Links' ),
        'insert_into_item'      => __( 'Insert into blog', 'Telegram_Group_Links' ),
        'uploaded_to_this_item' => __( 'Uploaded to this blog', 'Telegram_Group_Links' ),
        'items_list'            => __( 'Blogs list', 'Telegram_Group_Links' ),
        'items_list_navigation' => __( 'Blogs list navigation', 'Telegram_Group_Links' ),
        'filter_items_list'     => __( 'Filter blogs list', 'Telegram_Group_Links' ),
    );

    $args = array(
        'label'               => __( 'Blog', 'Telegram_Group_Links' ),
        'description'         => __( 'Custom Post Type for Blogs', 'Telegram_Group_Links' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'          => array( 'category', 'post_tag' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
    );

    register_post_type( 'blog', $args );
}
add_action( 'init', 'create_blog_custom_post_type', 0 );

function tgt_toggle_block_editor_per_post_type( $use_block_editor, $post_type ) {
    if ( 'blog' === $post_type ) {
        return true;
    }
    if ( 'post' === $post_type ) {
        return false;
    }
    return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'tgt_toggle_block_editor_per_post_type', 100, 2 );

function tgt_override_classic_editor_plugin( $is_classic, $post_type ) {
    if ( 'blog' === $post_type ) {
        return false;
    }
    if ( 'post' === $post_type ) {
        return true;
    }
    return $is_classic;
}
add_filter( 'classic_editor_for_post_type', 'tgt_override_classic_editor_plugin', 100, 2 );


// ==================================================
// 10. TELEGRAM INVITE CHECKER (SECURE REST ROUTE)
// ==================================================

add_action( 'rest_api_init', function () {
    register_rest_route( 'tg-tool/v1', '/check', array(
        'methods'             => 'POST',
        'callback'            => 'tgt_scrape_telegram',
        'permission_callback' => '__return_true',
    ) );
} );

function tgt_scrape_telegram( WP_REST_Request $request ) {
    $params = $request->get_json_params();
    $link   = isset( $params['link'] ) ? esc_url_raw( trim( $params['link'] ) ) : '';

    if ( empty( $link ) ) {
        return new WP_Error( 'missing_url', __( 'Please provide a valid Telegram link.', 'Telegram_Group_Links' ), array( 'status' => 400 ) );
    }

    $parsed = wp_parse_url( $link );
    $host   = isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';

    if ( ! in_array( $host, array( 't.me', 'telegram.me' ), true ) ) {
        return new WP_Error( 'invalid_url', __( 'Please enter a valid t.me or telegram.me link.', 'Telegram_Group_Links' ), array( 'status' => 400 ) );
    }

    $response = wp_remote_get( $link, array(
        'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        'timeout'     => 15,
        'redirection' => 5,
        'sslverify'   => false,
    ) );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'api_error', __( 'Connection failed. Could not reach Telegram.', 'Telegram_Group_Links' ), array( 'status' => 500 ) );
    }

    $html = wp_remote_retrieve_body( $response );
    if ( empty( $html ) ) {
        return new WP_Error( 'not_found', __( 'Channel not found or link expired.', 'Telegram_Group_Links' ), array( 'status' => 404 ) );
    }

    preg_match( '/<meta property="og:title" content="(.*?)"/i', $html, $title_match );
    $title = isset( $title_match[1] ) ? str_replace( ' – Telegram', '', $title_match[1] ) : '';

    preg_match( '/<meta property="og:description" content="(.*?)"/i', $html, $desc_match );
    $desc = isset( $desc_match[1] ) ? $desc_match[1] : '';

    preg_match( '/<meta property="og:image" content="(.*?)"/i', $html, $img_match );
    $image = isset( $img_match[1] ) ? esc_url_raw( $img_match[1] ) : '';

    preg_match( '/<div class="tgme_page_extra">\s*(.*?)\s*<\/div>/is', $html, $count_match );
    $count_text = isset( $count_match[1] ) ? wp_strip_all_tags( $count_match[1] ) : '0';

    $data = array(
        'name'        => ! empty( $title ) ? html_entity_decode( sanitize_text_field( $title ), ENT_QUOTES, 'UTF-8' ) : 'Telegram Channel',
        'description' => html_entity_decode( sanitize_text_field( $desc ), ENT_QUOTES, 'UTF-8' ),
        'image'       => $image,
        'count'       => sanitize_text_field( $count_text ),
        'link'        => $link,
    );

    return new WP_REST_Response( $data, 200 );
}

function tgt_render_tool() {
    ob_start();
    ?>
    <style>
        .tgt-wrapper { max-width: 600px; margin: 30px auto; font-family: 'Segoe UI', sans-serif; background: #fff; border: 1px solid #dfe6ed; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .tgt-header { text-align: center; margin-bottom: 25px; }
        .tgt-logo { width: 60px; height: 60px; margin-bottom: 15px; }
        .tgt-input-group { display: flex; gap: 10px; position: relative; }
        .tgt-input { flex: 1; padding: 12px 15px; border: 2px solid #e3e5e8; border-radius: 8px; font-size: 16px; outline: none; transition: 0.2s; }
        .tgt-input:focus { border-color: #24A1DE; }
        .tgt-btn { background: #24A1DE; color: #fff; border: none; padding: 0 25px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 16px; min-width: 100px; }
        .tgt-btn:hover { background: #1b8bbf; }
        .tgt-btn:disabled { opacity: 0.7; cursor: wait; }
        #tgt-result { display: none; margin-top: 30px; border: 1px solid #e3e5e8; border-radius: 12px; overflow: hidden; animation: slideUp 0.4s ease; }
        .tgt-banner-bg { background: linear-gradient(135deg, #24A1DE 0%, #6abceb 100%); height: 100px; position: relative; }
        .tgt-avatar { width: 90px; height: 90px; border-radius: 50%; border: 4px solid #fff; position: absolute; bottom: -45px; left: 50%; transform: translateX(-50%); background: #fff; object-fit: cover; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tgt-info { padding: 55px 20px 20px; text-align: center; }
        .tgt-title { font-size: 22px; font-weight: 800; margin: 0; color: #333; }
        .tgt-stats { color: #24A1DE; font-weight: 600; margin: 5px 0 15px; font-size: 14px; }
        .tgt-desc { color: #666; font-size: 14px; line-height: 1.5; background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .tgt-actions { display: flex; gap: 10px; justify-content: center; }
        .tgt-action-btn { text-decoration: none; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 600; transition: 0.2s; display: inline-block; }
        .btn-join { background: #24A1DE; color: white; }
        .btn-join:hover { background: #1b8bbf; color: white; }
        .btn-dl { border: 1px solid #ddd; color: #555; background: #fff; }
        .btn-dl:hover { background: #f5f5f5; color: #333; border-color: #ccc; }
        #tgt-error { display: none; margin-top: 15px; padding: 12px; background: #ffeaea; color: #d63031; border-radius: 6px; font-size: 14px; text-align: center; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="tgt-wrapper">
        <div class="tgt-header">
            <svg class="tgt-logo" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg">
                <circle cx="120" cy="120" r="120" fill="#24A1DE"/>
                <path d="M54 120c0-36.3 29.7-66 66-66 36.3 0 66 29.7 66 66 0 36.3-29.7 66-66 66-36.3 0-66-29.7-66-66zm113.5-35.8l-15.8 83.5c-1.2 5.5-4.5 6.9-9.1 4.3l-25.2-18.6-12.2 11.7c-1.3 1.3-2.5 2.5-5.1 2.5l1.8-25.6 46.6-42.1c2-1.8-0.4-2.8-3.1-1l-57.6 36.3-24.8-7.8c-5.4-1.7-5.5-5.4 1.1-8l97-37.4c4.5-1.9 8.4 1.1 6.4 12.2z" fill="#FFF"/>
            </svg>
            <h2 style="margin:0;"><?php esc_html_e( 'Telegram Link Checker', 'Telegram_Group_Links' ); ?></h2>
            <p style="color:#777; font-size:14px; margin-top:5px;"><?php esc_html_e( 'Check any public channel or group info.', 'Telegram_Group_Links' ); ?></p>
        </div>

        <div class="tgt-input-group">
            <input type="text" id="tgt-input" class="tgt-input" placeholder="t.me/yourchannel" />
            <button id="tgt-btn" class="tgt-btn"><?php esc_html_e( 'Check', 'Telegram_Group_Links' ); ?></button>
        </div>
        <div id="tgt-error"></div>

        <div id="tgt-result">
            <div class="tgt-banner-bg">
                <img id="tgt-img" class="tgt-avatar" src="" alt="<?php esc_attr_e( 'Telegram Icon', 'Telegram_Group_Links' ); ?>" />
            </div>
            <div class="tgt-info">
                <h3 id="tgt-name" class="tgt-title"></h3>
                <div id="tgt-count" class="tgt-stats"></div>
                <div id="tgt-desc" class="tgt-desc"></div>

                <div class="tgt-actions">
                    <a id="tgt-link" href="#" target="_blank" rel="noopener noreferrer" class="tgt-action-btn btn-join"><?php esc_html_e( 'Open in Telegram', 'Telegram_Group_Links' ); ?></a>
                    <a id="tgt-dl" href="#" target="_blank" rel="noopener noreferrer" download class="tgt-action-btn btn-dl"><?php esc_html_e( 'Download Icon', 'Telegram_Group_Links' ); ?></a>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('tgt-btn');
        if (!btn) return;

        btn.addEventListener('click', async () => {
            const input = document.getElementById('tgt-input').value.trim();
            const err = document.getElementById('tgt-error');
            const res = document.getElementById('tgt-result');

            if(!input) return;

            if(!input.includes('t.me/') && !input.includes('telegram.me/')) {
                err.innerText = "<?php echo esc_js( __( 'Please enter a valid t.me or telegram.me link', 'Telegram_Group_Links' ) ); ?>";
                err.style.display = 'block';
                return;
            }

            btn.disabled = true;
            btn.innerText = "<?php echo esc_js( __( 'Checking...', 'Telegram_Group_Links' ) ); ?>";
            err.style.display = 'none';
            res.style.display = 'none';

            try {
                const req = await fetch('<?php echo esc_url( rest_url( 'tg-tool/v1/check' ) ); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ link: input })
                });
                const data = await req.json();

                if(!req.ok) throw new Error(data.message || "<?php echo esc_js( __( 'Could not fetch data.', 'Telegram_Group_Links' ) ); ?>");

                document.getElementById('tgt-name').innerText = data.name;
                document.getElementById('tgt-count').innerText = data.count;
                document.getElementById('tgt-desc').innerText = data.description || "<?php echo esc_js( __( 'No description available.', 'Telegram_Group_Links' ) ); ?>";

                const img = data.image || "https://telegram.org/img/t_logo.png";
                document.getElementById('tgt-img').src = img;

                document.getElementById('tgt-link').href = data.link;
                document.getElementById('tgt-dl').href = img;

                res.style.display = 'block';
            } catch (e) {
                err.innerText = e.message;
                err.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.innerText = "<?php echo esc_js( __( 'Check', 'Telegram_Group_Links' ) ); ?>";
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'telegram_invite_tool', 'tgt_render_tool' );


// ==================================================
// 11. 18+ ADULT CONTENT SYSTEM
// ==================================================

if ( ! defined( 'TGT_MEMBERS_META_KEY' ) ) {
    define( 'TGT_MEMBERS_META_KEY', '_tgt_members_only' );
    define( 'TGT_FLAG_META_KEY', '_tgt_flagged_for_review' );
    define( 'TGT_FLAG_KEYWORDS_META_KEY', '_tgt_flagged_keywords' );
    define( 'TGT_FLAG_TIME_META_KEY', '_tgt_flagged_time' );
}

function tgt_members_supported_post_types() {
    return array( 'post', 'blog' );
}

function tgt_is_members_only_post( $post_id ) {
    return get_post_meta( $post_id, TGT_MEMBERS_META_KEY, true ) === '1';
}

// Meta Box
function tgt_add_members_meta_box() {
    foreach ( tgt_members_supported_post_types() as $post_type ) {
        add_meta_box(
            'tgt_members_only_box',
            __( '18+ Adult Content Access', 'Telegram_Group_Links' ),
            'tgt_render_members_meta_box',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'tgt_add_members_meta_box' );

function tgt_render_members_meta_box( $post ) {
    wp_nonce_field( 'tgt_save_members_meta', 'tgt_members_meta_nonce' );

    $is_members_only  = get_post_meta( $post->ID, TGT_MEMBERS_META_KEY, true );
    $is_flagged       = get_post_meta( $post->ID, TGT_FLAG_META_KEY, true );
    $flagged_keywords = get_post_meta( $post->ID, TGT_FLAG_KEYWORDS_META_KEY, true );
    ?>
    <p>
        <label style="display:flex;align-items:center;gap:8px;font-weight:600;">
            <input type="checkbox" name="tgt_members_only" value="1" <?php checked( $is_members_only, '1' ); ?> />
            <?php esc_html_e( '✓ 18+ Adult Content', 'Telegram_Group_Links' ); ?>
        </label>
        <span style="display:block;margin-top:6px;color:#666;font-size:12px;line-height:1.4;">
            <?php esc_html_e( 'When checked, this post is marked as 18+ Adult Content. It will be hidden from the public homepage, standard archives, search, RSS feeds, widgets, and only displayed on the 18+ Adult page.', 'Telegram_Group_Links' ); ?>
        </span>
    </p>
    <?php if ( '1' === $is_flagged && '1' !== $is_members_only ) : ?>
        <div style="margin-top:12px;padding:10px;background:#fff8e5;border:1px solid #f0c33c;border-radius:4px;">
            <strong style="color:#8a6500;">⚠ <?php esc_html_e( 'Flagged for Review', 'Telegram_Group_Links' ); ?></strong>
            <p style="margin:6px 0 0;font-size:12px;color:#555;">
                <?php esc_html_e( 'Matched keyword(s):', 'Telegram_Group_Links' ); ?>
                <em><?php echo esc_html( $flagged_keywords ); ?></em>
            </p>
            <p style="margin:6px 0 0;font-size:12px;">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=tgt-members-settings&tab=flagged' ) ); ?>">
                    <?php esc_html_e( 'Review in 18+ Adult Content Settings →', 'Telegram_Group_Links' ); ?>
                </a>
            </p>
        </div>
    <?php endif; ?>
    <?php
}

// Save Meta & Scan Keywords
function tgt_save_members_meta_and_scan( $post_id, $post, $update ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
        return;
    }
    if ( ! in_array( $post->post_type, tgt_members_supported_post_types(), true ) ) {
        return;
    }
    if ( in_array( $post->post_status, array( 'auto-draft', 'trash' ), true ) ) {
        return;
    }

    if ( isset( $_POST['tgt_members_meta_nonce'] ) && wp_verify_nonce( $_POST['tgt_members_meta_nonce'], 'tgt_save_members_meta' ) ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        $is_members_only = isset( $_POST['tgt_members_only'] ) ? '1' : '';
        update_post_meta( $post_id, TGT_MEMBERS_META_KEY, $is_members_only );

        if ( '1' === $is_members_only ) {
            delete_post_meta( $post_id, TGT_FLAG_META_KEY );
            delete_post_meta( $post_id, TGT_FLAG_KEYWORDS_META_KEY );
            delete_post_meta( $post_id, TGT_FLAG_TIME_META_KEY );
        }
    }

    tgt_scan_post_for_keywords( $post_id, $post );
}
add_action( 'save_post', 'tgt_save_members_meta_and_scan', 20, 3 );

function tgt_get_keyword_settings() {
    static $settings = null;
    if ( null !== $settings ) {
        return $settings;
    }

    $raw_keywords = get_option( 'tgt_keyword_list', '' );
    $keywords     = array_filter( array_map( 'trim', preg_split( '/[\r\n,]+/', (string) $raw_keywords ) ) );

    $settings = array(
        'enabled'     => '1' === get_option( 'tgt_keyword_scan_enabled', '0' ),
        'keywords'    => array_values( array_unique( $keywords ) ),
        'sensitivity' => get_option( 'tgt_keyword_sensitivity', 'whole_word' ),
    );

    return $settings;
}

function tgt_scan_post_for_keywords( $post_id, $post ) {
    $settings = tgt_get_keyword_settings();

    if ( ! $settings['enabled'] || empty( $settings['keywords'] ) ) {
        return;
    }

    if ( tgt_is_members_only_post( $post_id ) ) {
        delete_post_meta( $post_id, TGT_FLAG_META_KEY );
        delete_post_meta( $post_id, TGT_FLAG_KEYWORDS_META_KEY );
        delete_post_meta( $post_id, TGT_FLAG_TIME_META_KEY );
        return;
    }

    $haystack = $post->post_title . ' ' . wp_strip_all_tags( $post->post_content );
    $matches  = array();

    foreach ( $settings['keywords'] as $keyword ) {
        if ( '' === $keyword ) {
            continue;
        }

        if ( 'partial' === $settings['sensitivity'] ) {
            if ( false !== stripos( $haystack, $keyword ) ) {
                $matches[] = $keyword;
            }
        } else {
            $pattern = '/(?<![\p{L}\p{N}_])' . preg_quote( $keyword, '/' ) . '(?![\p{L}\p{N}_])/iu';
            if ( preg_match( $pattern, $haystack ) ) {
                $matches[] = $keyword;
            }
        }
    }

    if ( ! empty( $matches ) ) {
        update_post_meta( $post_id, TGT_FLAG_META_KEY, '1' );
        update_post_meta( $post_id, TGT_FLAG_KEYWORDS_META_KEY, implode( ', ', array_unique( $matches ) ) );
        update_post_meta( $post_id, TGT_FLAG_TIME_META_KEY, current_time( 'mysql' ) );
    }
}

// Query Filter for Adult Content
function tgt_filter_members_only_from_queries( $query ) {
    if ( is_admin() || ! ( $query instanceof WP_Query ) ) {
        return;
    }

    if ( $query->is_singular() ) {
        return;
    }

    $mode = $query->get( 'tgt_members_query' );
    if ( 'skip' === $mode ) {
        return;
    }

    $post_type  = $query->get( 'post_type' );
    $post_type  = empty( $post_type ) ? 'post' : $post_type;
    $post_types = (array) $post_type;

    if ( ! array_intersect( $post_types, array_merge( tgt_members_supported_post_types(), array( 'any' ) ) ) ) {
        return;
    }

    $meta_query = $query->get( 'meta_query' );
    if ( ! is_array( $meta_query ) ) {
        $meta_query = array();
    }

    if ( 'only' === $mode ) {
        $meta_query[] = array(
            'key'     => TGT_MEMBERS_META_KEY,
            'value'   => '1',
            'compare' => '=',
        );
    } else {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key'     => TGT_MEMBERS_META_KEY,
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key'     => TGT_MEMBERS_META_KEY,
                'value'   => '1',
                'compare' => '!=',
            ),
        );
    }

    $query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'tgt_filter_members_only_from_queries' );

// Body Class & Enqueue
function tgt_members_body_class( $classes ) {
    if ( is_singular() ) {
        $post_id = get_queried_object_id();
        if ( tgt_is_members_only_post( $post_id ) ) {
            $classes[] = 'adult-18plus-post';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'tgt_members_body_class' );

function tgt_enqueue_members_styles() {
    if ( is_singular() ) {
        $post_id = get_queried_object_id();
        if ( tgt_is_members_only_post( $post_id ) ) {
            wp_enqueue_style(
                'tgt-members-only',
                get_template_directory_uri() . '/css/members-only.css',
                array(),
                wp_get_theme()->get( 'Version' )
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'tgt_enqueue_members_styles' );

// Permalinks & Rewrites
function tgt_custom_18plus_post_link( $permalink, $post, $leavename ) {
    if ( is_object( $post ) && tgt_is_members_only_post( $post->ID ) ) {
        $home_url = home_url( '/' );
        if ( strpos( $permalink, $home_url ) === 0 ) {
            $relative_path = substr( $permalink, strlen( $home_url ) );
            if ( strpos( $relative_path, '18-plus/' ) !== 0 ) {
                $permalink = $home_url . '18-plus/' . $relative_path;
            }
        }
    }
    return $permalink;
}
add_filter( 'post_link', 'tgt_custom_18plus_post_link', 10, 3 );

function tgt_add_18plus_single_rewrite_rules() {
    add_rewrite_rule(
        '^18-plus/blog/([^/]+)/?$',
        'index.php?post_type=blog&name=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^18-plus/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );
}
add_action( 'init', 'tgt_add_18plus_single_rewrite_rules' );

function tgt_maybe_flush_members_rewrite() {
    $version_flag = 'v7_18plus_fix';
    if ( get_option( 'tgt_members_rewrite_flushed' ) !== $version_flag ) {
        flush_rewrite_rules( false );
        update_option( 'tgt_members_rewrite_flushed', $version_flag );
    }
}
add_action( 'init', 'tgt_maybe_flush_members_rewrite', 99 );

// Admin Settings
function tgt_add_members_settings_page() {
    add_menu_page(
        __( '18+ Adult Content', 'Telegram_Group_Links' ),
        __( '18+ Adult Content', 'Telegram_Group_Links' ),
        'manage_options',
        'tgt-members-settings',
        'tgt_render_members_settings_page',
        'dashicons-lock',
        30
    );
}
add_action( 'admin_menu', 'tgt_add_members_settings_page' );

function tgt_count_flagged_posts() {
    $query = new WP_Query( array(
        'post_type'         => tgt_members_supported_post_types(),
        'post_status'       => 'any',
        'posts_per_page'    => 1,
        'fields'            => 'ids',
        'no_found_rows'     => false,
        'meta_query'        => array(
            'relation' => 'AND',
            array(
                'key'   => TGT_FLAG_META_KEY,
                'value' => '1',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => TGT_MEMBERS_META_KEY,
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => TGT_MEMBERS_META_KEY,
                    'value'   => '1',
                    'compare' => '!=',
                ),
            ),
        ),
        'tgt_members_query' => 'skip',
    ) );
    return (int) $query->found_posts;
}

function tgt_render_members_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $tab           = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
    $flagged_count = tgt_count_flagged_posts();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( '18+ Adult Content Settings', 'Telegram_Group_Links' ); ?></h1>

        <?php if ( isset( $_GET['reviewed'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Post reviewed successfully.', 'Telegram_Group_Links' ); ?></p></div>
        <?php endif; ?>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=tgt-members-settings&tab=settings' ) ); ?>" class="nav-tab <?php echo 'settings' === $tab ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Settings', 'Telegram_Group_Links' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=tgt-members-settings&tab=flagged' ) ); ?>" class="nav-tab <?php echo 'flagged' === $tab ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Flagged Posts', 'Telegram_Group_Links' ); ?>
                <?php if ( $flagged_count > 0 ) : ?>
                    <span class="update-plugins count-<?php echo (int) $flagged_count; ?>" style="margin-left:4px;">
                        <span class="update-count"><?php echo (int) $flagged_count; ?></span>
                    </span>
                <?php endif; ?>
            </a>
        </h2>

        <?php
        if ( 'flagged' === $tab ) {
            tgt_render_flagged_posts_tab();
        } else {
            tgt_render_general_settings_tab();
        }
        ?>
    </div>
    <?php
}

function tgt_render_general_settings_tab() {
    if ( isset( $_POST['tgt_members_settings_nonce'] ) && wp_verify_nonce( $_POST['tgt_members_settings_nonce'], 'tgt_save_members_settings' ) ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized', 'Telegram_Group_Links' ) );
        }
        update_option( 'tgt_keyword_scan_enabled', isset( $_POST['tgt_keyword_scan_enabled'] ) ? '1' : '0' );
        update_option( 'tgt_keyword_list', isset( $_POST['tgt_keyword_list'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tgt_keyword_list'] ) ) : '' );
        $sensitivity = ( isset( $_POST['tgt_keyword_sensitivity'] ) && 'partial' === $_POST['tgt_keyword_sensitivity'] ) ? 'partial' : 'whole_word';
        update_option( 'tgt_keyword_sensitivity', $sensitivity );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'Telegram_Group_Links' ) . '</p></div>';
    }

    $enabled     = get_option( 'tgt_keyword_scan_enabled', '0' );
    $keywords    = get_option( 'tgt_keyword_list', '' );
    $sensitivity = get_option( 'tgt_keyword_sensitivity', 'whole_word' );
    ?>
    <form method="post" style="max-width:760px;margin-top:24px;">
        <?php wp_nonce_field( 'tgt_save_members_settings', 'tgt_members_settings_nonce' ); ?>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><?php esc_html_e( 'Enable Keyword Scanning', 'Telegram_Group_Links' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="tgt_keyword_scan_enabled" value="1" <?php checked( $enabled, '1' ); ?> />
                        <?php esc_html_e( 'Scan post title & content on save and flag matches for review', 'Telegram_Group_Links' ); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="tgt_keyword_list"><?php esc_html_e( 'Keyword List', 'Telegram_Group_Links' ); ?></label></th>
                <td>
                    <textarea name="tgt_keyword_list" id="tgt_keyword_list" rows="8" class="large-text code" placeholder="<?php esc_attr_e( 'one keyword per line (commas also work)', 'Telegram_Group_Links' ); ?>"><?php echo esc_textarea( $keywords ); ?></textarea>
                    <p class="description">
                        <?php esc_html_e( 'Posts whose title or content match any of these keywords are flagged for review. Flagged posts are never automatically hidden or marked 18+ — an administrator must approve or reject the suggestion.', 'Telegram_Group_Links' ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e( 'Detection Sensitivity', 'Telegram_Group_Links' ); ?></th>
                <td>
                    <label style="display:block;margin-bottom:6px;">
                        <input type="radio" name="tgt_keyword_sensitivity" value="whole_word" <?php checked( $sensitivity, 'whole_word' ); ?> />
                        <?php esc_html_e( 'Strict — match whole words only', 'Telegram_Group_Links' ); ?>
                    </label>
                    <label style="display:block;">
                        <input type="radio" name="tgt_keyword_sensitivity" value="partial" <?php checked( $sensitivity, 'partial' ); ?> />
                        <?php esc_html_e( 'Loose — match keyword as a substring anywhere', 'Telegram_Group_Links' ); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php submit_button( __( 'Save Settings', 'Telegram_Group_Links' ) ); ?>
    </form>
    <?php
}

function tgt_render_flagged_posts_tab() {
    $flagged_query = new WP_Query( array(
        'post_type'         => tgt_members_supported_post_types(),
        'post_status'       => 'any',
        'posts_per_page'    => 50,
        'orderby'           => 'date',
        'order'             => 'DESC',
        'meta_query'        => array(
            'relation' => 'AND',
            array(
                'key'   => TGT_FLAG_META_KEY,
                'value' => '1',
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => TGT_MEMBERS_META_KEY,
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => TGT_MEMBERS_META_KEY,
                    'value'   => '1',
                    'compare' => '!=',
                ),
            ),
        ),
        'tgt_members_query' => 'skip',
    ) );
    ?>
    <div style="margin-top:24px;">
        <?php if ( ! $flagged_query->have_posts() ) : ?>
            <p><?php esc_html_e( 'No posts are currently flagged for review.', 'Telegram_Group_Links' ); ?> 🎉</p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Title', 'Telegram_Group_Links' ); ?></th>
                        <th><?php esc_html_e( 'Type', 'Telegram_Group_Links' ); ?></th>
                        <th><?php esc_html_e( 'Matched Keyword(s)', 'Telegram_Group_Links' ); ?></th>
                        <th><?php esc_html_e( 'Flagged On', 'Telegram_Group_Links' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'Telegram_Group_Links' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ( $flagged_query->have_posts() ) :
                        $flagged_query->the_post();
                        $post_id          = get_the_ID();
                        $matched_keywords = get_post_meta( $post_id, TGT_FLAG_KEYWORDS_META_KEY, true );
                        $flagged_time     = get_post_meta( $post_id, TGT_FLAG_TIME_META_KEY, true );

                        $approve_url = wp_nonce_url(
                            admin_url( 'admin-post.php?action=tgt_review_flagged_post&post_id=' . $post_id . '&flag_action=approve' ),
                            'tgt_review_flag_' . $post_id
                        );
                        $reject_url = wp_nonce_url(
                            admin_url( 'admin-post.php?action=tgt_review_flagged_post&post_id=' . $post_id . '&flag_action=reject' ),
                            'tgt_review_flag_' . $post_id
                        );
                        ?>
                        <tr>
                            <td><a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>"><strong><?php the_title(); ?></strong></a></td>
                            <td><?php echo esc_html( get_post_type() ); ?></td>
                            <td><?php echo esc_html( $matched_keywords ); ?></td>
                            <td><?php echo esc_html( $flagged_time ); ?></td>
                            <td>
                                <a href="<?php echo esc_url( $approve_url ); ?>" class="button button-primary" onclick="return confirm('<?php echo esc_js( __( 'Mark this post as 18+ Adult Content?', 'Telegram_Group_Links' ) ); ?>');">
                                    <?php esc_html_e( 'Approve → 18+', 'Telegram_Group_Links' ); ?>
                                </a>
                                <a href="<?php echo esc_url( $reject_url ); ?>" class="button" onclick="return confirm('<?php echo esc_js( __( 'Dismiss this flag and keep the post public?', 'Telegram_Group_Links' ) ); ?>');">
                                    <?php esc_html_e( 'Reject', 'Telegram_Group_Links' ); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
    wp_reset_postdata();
}

function tgt_handle_flag_review_action() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Unauthorized', 'Telegram_Group_Links' ) );
    }

    $post_id = isset( $_GET['post_id'] ) ? intval( $_GET['post_id'] ) : 0;
    check_admin_referer( 'tgt_review_flag_' . $post_id );

    $flag_action = isset( $_GET['flag_action'] ) ? sanitize_key( $_GET['flag_action'] ) : '';

    if ( $post_id > 0 ) {
        if ( 'approve' === $flag_action ) {
            update_post_meta( $post_id, TGT_MEMBERS_META_KEY, '1' );
        }
        delete_post_meta( $post_id, TGT_FLAG_META_KEY );
        delete_post_meta( $post_id, TGT_FLAG_KEYWORDS_META_KEY );
        delete_post_meta( $post_id, TGT_FLAG_TIME_META_KEY );
    }

    wp_safe_redirect( admin_url( 'admin.php?page=tgt-members-settings&tab=flagged&reviewed=1' ) );
    exit;
}
add_action( 'admin_post_tgt_review_flagged_post', 'tgt_handle_flag_review_action' );

// Admin Column Display
function tgt_add_members_admin_column( $columns ) {
    $columns['tgt_members_status'] = __( '18+ Status', 'Telegram_Group_Links' );
    return $columns;
}
add_filter( 'manage_post_posts_columns', 'tgt_add_members_admin_column' );
add_filter( 'manage_blog_posts_columns', 'tgt_add_members_admin_column' );

function tgt_render_members_admin_column( $column, $post_id ) {
    if ( 'tgt_members_status' !== $column ) {
        return;
    }
    if ( tgt_is_members_only_post( $post_id ) ) {
        echo '<span style="color:#c0392b;font-weight:600;">🔞 ' . esc_html__( '18+', 'Telegram_Group_Links' ) . '</span>';
    } elseif ( '1' === get_post_meta( $post_id, TGT_FLAG_META_KEY, true ) ) {
        echo '<span style="color:#8a6500;font-weight:600;">⚠ ' . esc_html__( 'Flagged', 'Telegram_Group_Links' ) . '</span>';
    } else {
        echo '<span style="color:#999;">' . esc_html__( 'Public', 'Telegram_Group_Links' ) . '</span>';
    }
}
add_action( 'manage_post_posts_custom_column', 'tgt_render_members_admin_column', 10, 2 );
add_action( 'manage_blog_posts_custom_column', 'tgt_render_members_admin_column', 10, 2 );


// ==================================================
// 12. OFFICIAL AJAX SUBMISSION HANDLER
// ==================================================

add_action( 'wp_ajax_tgt_ajax_publish_listing', 'tgt_handle_ajax_publish_submission_core' );
add_action( 'wp_ajax_nopriv_tgt_ajax_publish_listing', 'tgt_handle_ajax_publish_submission_core' );

function tgt_handle_ajax_publish_submission_core() {
    check_ajax_referer( 'handle_custom_form', 'custom_form_nonce' );

    $group_link = isset( $_POST['group_link'] ) ? esc_url_raw( wp_unslash( $_POST['group_link'] ) ) : '';
    if ( empty( $group_link ) || ( strpos( $group_link, 't.me/' ) === false && strpos( $group_link, 'telegram.me/' ) === false ) ) {
        wp_send_json_error( array(
            'code'    => 'invalid_url',
            'message' => __( 'Please enter a valid Telegram link (e.g., https://t.me/example).', 'Telegram_Group_Links' ),
        ) );
    }

    $post_title   = isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : '';
    $post_content = isset( $_POST['post_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['post_content'] ) ) : '';
    $tags_raw     = isset( $_POST['tags'] ) ? sanitize_text_field( wp_unslash( $_POST['tags'] ) ) : '';
    $category_id  = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $country_val  = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
    $lang_val     = isset( $_POST['language'] ) ? sanitize_text_field( wp_unslash( $_POST['language'] ) ) : '';
    $type_val     = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'Channel';
    $admin_handle = isset( $_POST['admin'] ) ? sanitize_text_field( wp_unslash( $_POST['admin'] ) ) : '';
    $subscribers  = isset( $_POST['subscribers'] ) ? sanitize_text_field( wp_unslash( $_POST['subscribers'] ) ) : '0';

    if ( empty( $post_title ) ) {
        wp_send_json_error( array( 'code' => 'missing_title', 'message' => __( 'Please enter a name or title.', 'Telegram_Group_Links' ) ) );
    }

    if ( empty( $post_content ) ) {
        wp_send_json_error( array( 'code' => 'missing_description', 'message' => __( 'Please add a short description.', 'Telegram_Group_Links' ) ) );
    }

    $is_18_plus_req = ( isset( $_POST['is_18_plus'] ) && '1' === $_POST['is_18_plus'] )
        || ( function_exists( 'tgt_contains_adult_content' ) && tgt_contains_adult_content( $post_title . ' ' . $post_content . ' ' . $tags_raw . ' ' . $group_link ) );

    if ( ! $is_18_plus_req && empty( $category_id ) ) {
        wp_send_json_error( array( 'code' => 'missing_category', 'message' => __( 'Please choose a category.', 'Telegram_Group_Links' ) ) );
    }

    if ( empty( $country_val ) ) {
        wp_send_json_error( array( 'code' => 'missing_country', 'message' => __( 'Please choose a country/region.', 'Telegram_Group_Links' ) ) );
    }

    if ( empty( $lang_val ) ) {
        wp_send_json_error( array( 'code' => 'missing_language', 'message' => __( 'Please select a language.', 'Telegram_Group_Links' ) ) );
    }

    if ( empty( $admin_handle ) ) {
        wp_send_json_error( array( 'code' => 'missing_admin', 'message' => __( 'Please enter the admin username.', 'Telegram_Group_Links' ) ) );
    }

    if ( '' === $subscribers || intval( $subscribers ) < 0 ) {
        wp_send_json_error( array( 'code' => 'invalid_subscribers', 'message' => __( 'Please enter a valid number of members.', 'Telegram_Group_Links' ) ) );
    }

    // Check duplicate
    if ( ! empty( $post_title ) && get_page_by_title( trim( $post_title ), OBJECT, 'post' ) ) {
        wp_send_json_error( array(
            'code'    => 'duplicate',
            'message' => __( 'This Telegram group or channel is already listed on our website!', 'Telegram_Group_Links' ),
        ) );
    }

    $post_id = wp_insert_post( array(
        'post_title'   => $post_title,
        'post_content' => $post_content,
        'post_status'  => 'publish',
        'post_type'    => 'post',
    ) );

    if ( ! $post_id || is_wp_error( $post_id ) ) {
        wp_send_json_error( array(
            'code'    => 'server_error',
            'message' => __( 'Could not publish group. Please try again.', 'Telegram_Group_Links' ),
        ) );
    }

    if ( ! empty( $category_id ) ) {
        wp_set_post_categories( $post_id, array( $category_id ) );
    }
    if ( ! empty( $type_val ) ) {
        wp_set_object_terms( $post_id, $type_val, 'type' );
    }
    if ( ! empty( $lang_val ) ) {
        wp_set_object_terms( $post_id, $lang_val, 'language' );
    }
    if ( ! empty( $country_val ) ) {
        wp_set_object_terms( $post_id, $country_val, 'country' );
    }
    if ( ! empty( $tags_raw ) ) {
        $tags = array_map( 'sanitize_text_field', explode( ',', $tags_raw ) );
        wp_set_post_tags( $post_id, $tags );
    }

    update_post_meta( $post_id, 'subscriber_count', intval( $subscribers ) );

    if ( ! empty( $admin_handle ) ) {
        wp_set_object_terms( $post_id, $admin_handle, 'admin-name' );
    }
    if ( ! empty( $group_link ) ) {
        wp_set_object_terms( $post_id, $group_link, 'group-link' );
    }

    // Process Avatar Thumbnail
    $image_url = isset( $_POST['image_url'] ) ? esc_url_raw( wp_unslash( $_POST['image_url'] ) ) : '';
    if ( ! empty( $image_url ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $image_response = wp_remote_get( $image_url, array( 'timeout' => 20, 'sslverify' => false ) );
        if ( ! is_wp_error( $image_response ) && 200 === wp_remote_retrieve_response_code( $image_response ) ) {
            $image_body = wp_remote_retrieve_body( $image_response );
            $upload_dir = wp_upload_dir();
            $filename   = sanitize_title( $post_title ) . '-' . $post_id . '.jpg';
            $file_path  = $upload_dir['path'] . '/' . $filename;

            if ( file_put_contents( $file_path, $image_body ) ) {
                $attachment = array(
                    'post_mime_type' => 'image/jpeg',
                    'post_title'     => sanitize_text_field( $post_title ),
                    'post_status'    => 'inherit',
                );
                $attach_id = wp_insert_attachment( $attachment, $file_path, $post_id );
                if ( $attach_id && ! is_wp_error( $attach_id ) ) {
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
                    wp_update_attachment_metadata( $attach_id, $attach_data );
                    set_post_thumbnail( $post_id, $attach_id );
                }
            }
        }
    }

    if ( $is_18_plus_req ) {
        update_post_meta( $post_id, '_tgt_members_only', '1' );
        delete_post_meta( $post_id, '_tgt_flagged_for_review' );
        delete_post_meta( $post_id, '_tgt_flagged_keywords' );
        delete_post_meta( $post_id, '_tgt_flagged_time' );
    } else {
        update_post_meta( $post_id, '_tgt_members_only', '0' );
    }

    wp_send_json_success( array(
        'message' => __( 'Your group has been successfully added!', 'Telegram_Group_Links' ),
        'post_id' => $post_id,
    ) );
}
