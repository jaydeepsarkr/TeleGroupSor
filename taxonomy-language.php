<?php
/**
 * Template Name: Language Taxonomy Archive
 * Description: This template displays posts categorized by the 'language' taxonomy.
 *
 * @package Telegram_Group_Links
 */

get_header();

// =========================================================================
// 1. TAXONOMY CONTEXT & OBJECT DETECTION
// =========================================================================
$queried_obj = get_queried_object();
$term_id     = 0;
$term_slug   = '';
$term_desc   = '';

if ( $queried_obj instanceof WP_Term ) {
    $term_id   = $queried_obj->term_id;
    $term_slug = $queried_obj->slug;
    $term_desc = term_description( $term_id, 'language' );
} elseif ( is_tax() || is_category() || is_tag() ) {
    $term_desc = term_description();
}

// Retrieve Dynamic Custom Yoast Title with Safe Fallbacks
$dynamic_h1_title = function_exists( 'get_yoast_seo_title' ) ? get_yoast_seo_title() : single_term_title( '', false );

// Detect 18+ / Members context (consistent with header/footer logic)
$is_members = ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) )
    || is_page( '18-plus' )
    || is_page( 'members' )
    || get_query_var( 'tgt_members_archive' );

// Theme-driven CSS helper tokens
$theme_accent_btn = $is_members
    ? 'bg-gradient-to-r from-[#f43f5e] via-[#e11d48] to-[#be123c] hover:from-[#e11d48] hover:to-[#9f1239] shadow-rose-500/20 group-hover:shadow-rose-500/35'
    : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] hover:from-[#1c86ba] hover:to-[#16729e] shadow-sky-500/20 group-hover:shadow-sky-500/35';

// Card "Join Now" button accent - matches the Related Groups card button
// styling on single.php exactly (no extra colored shadow classes there).
$card_btn_accent = $is_members
    ? 'bg-gradient-to-r from-[#f43f5e] via-[#e11d48] to-[#be123c] hover:from-[#e11d48] hover:to-[#9f1239]'
    : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] hover:from-[#1c86ba] hover:to-[#16729e]';

$theme_search_focus = $is_members
    ? 'hover:border-rose-300 focus-within:border-rose-500 focus-within:ring-rose-500/15'
    : 'hover:border-[#229ed9]/50 focus-within:border-[#229ed9] focus-within:ring-[#229ed9]/15';

// Card border/hover treatment - matches the Related Groups card on single.php
// (border-only hover state, no separate box-shadow utility on the card itself).
$theme_card_border = $is_members
    ? 'border-rose-100/90 hover:border-rose-400/70'
    : 'border-slate-200/80 hover:border-[#229ed9]/60';

$theme_avatar_glow = $is_members
    ? 'bg-gradient-to-tr from-rose-500 to-pink-300'
    : 'bg-gradient-to-tr from-[#229ed9] to-sky-300';

$theme_title_hover = $is_members
    ? 'group-hover:text-rose-600'
    : 'group-hover:text-[#229ed9]';

$theme_sub_icon = $is_members
    ? 'text-rose-500'
    : 'text-[#229ed9]';

$theme_badge_bg = $is_members
    ? 'bg-rose-50/80 border-rose-100/80 text-rose-700'
    : 'bg-sky-50 border-sky-100 text-[#229ed9]';

$users_icon_svg = '<svg class="w-3.5 h-3.5 ' . esc_attr( $theme_sub_icon ) . ' flex-shrink-0 inline-block align-middle" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>';
?>



<main class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 mt-2 sm:mt-4 mb-16 font-sans">

    <!-- Primary Dynamic H1 Title Header -->
    <header class="text-center mt-2 sm:mt-4 mb-6 sm:mb-8">
        <h1 id="yoast_seo_title" class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-snug">
            <?php echo esc_html( $dynamic_h1_title ); ?>
        </h1>
    </header>

    <section aria-labelledby="yoast_seo_title">

        <!-- In-Archive Search Form -->
        <?php
        $premium_searchData = isset( $_GET['premium_search'] ) ? sanitize_text_field( wp_unslash( $_GET['premium_search'] ) ) : '';
        $current_term_link  = ( $term_id > 0 ) ? get_term_link( $term_id, 'language' ) : '';
        $form_action_url    = ( ! is_wp_error( $current_term_link ) && ! empty( $current_term_link ) ) ? $current_term_link : home_url( add_query_arg( null, null ) );
        ?>
        <form id="search-form-second" method="get" action="<?php echo esc_url( $form_action_url ); ?>" class="max-w-xl mx-auto mb-8 sm:mb-10" role="search">
            <div class="relative flex items-center bg-white/95 backdrop-blur-sm rounded-full border border-gray-200/80 shadow-md shadow-gray-200/50 <?php echo esc_attr( $theme_search_focus ); ?> p-1.5 transition-all duration-300">

                <div class="pl-3.5 text-gray-400 flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </div>

                <label for="language-search-input" class="sr-only"><?php esc_html_e( 'Search in this language', 'Telegram_Group_Links' ); ?></label>
                <input
                    type="search"
                    id="language-search-input"
                    placeholder="<?php esc_attr_e( 'Search channels, groups, or topics...', 'Telegram_Group_Links' ); ?>"
                    name="premium_search"
                    value="<?php echo esc_attr( $premium_searchData ); ?>"
                    class="w-full bg-transparent border-none text-gray-800 placeholder-gray-400 text-sm px-3 py-1.5 focus:outline-none"
                    autocomplete="off"
                >

                <button
                    type="submit"
                    class="<?php echo esc_attr( $theme_accent_btn ); ?> active:scale-95 text-white text-sm font-semibold px-6 py-2 rounded-full cursor-pointer transition-all duration-200 shadow-md flex-shrink-0"
                    aria-label="<?php esc_attr_e( 'Search', 'Telegram_Group_Links' ); ?>"
                >
                    <?php esc_html_e( 'Search', 'Telegram_Group_Links' ); ?>
                </button>
            </div>
        </form>

        <!-- Query Execution -->
        <?php
        $paged_current = 1;
        if ( get_query_var( 'paged' ) ) {
            $paged_current = max( 1, absint( get_query_var( 'paged' ) ) );
        } elseif ( isset( $_GET['ppageid'] ) ) {
            $paged_current = max( 1, absint( $_GET['ppageid'] ) );
        }

        $non_premium_posts_args = array(
            'post_type'           => 'post',
            'posts_per_page'      => 20,
            'paged'               => $paged_current,
            's'                   => $premium_searchData,
            'ignore_sticky_posts' => true,
        );

        if ( ! empty( $term_slug ) ) {
            $non_premium_posts_args['tax_query'] = array(
                array(
                    'taxonomy' => 'language',
                    'field'    => 'slug',
                    'terms'    => $term_slug,
                ),
            );
        }

        if ( $is_members ) {
            $non_premium_posts_args['tgt_members_query'] = 'only';
        }

        $non_premium_posts = new WP_Query( $non_premium_posts_args );
        ?>

        <!-- Cards Grid Container -->
        <div class="card-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">

            <?php
            if ( $non_premium_posts->have_posts() ) :
                while ( $non_premium_posts->have_posts() ) :
                    $non_premium_posts->the_post();
                    $post_id          = get_the_ID();
                    $full_title_attr  = the_title_attribute( array( 'echo' => false ) );
                    $trimmed_title    = wp_trim_words( get_the_title(), 4 );
                    $subscriber_count = get_post_meta( $post_id, 'subscriber_count', true );

                    $language_terms = get_the_terms( $post_id, 'language' );
                    $language_list  = ( $language_terms && ! is_wp_error( $language_terms ) ) ? wp_list_pluck( $language_terms, 'name' ) : array();

                    $categories     = get_the_category( $post_id );
                    $category_names = ( $categories && ! is_wp_error( $categories ) ) ? wp_list_pluck( $categories, 'name' ) : array();

                    $subscription_terms = get_the_terms( $post_id, 'subscription' );
                    $subscription_name  = ( $subscription_terms && ! is_wp_error( $subscription_terms ) ) ? $subscription_terms[0]->name : '';
            ?>
                    <!-- Single Adaptive Card (styling matched to single.php Related Groups card) -->
                    <article class="card relative bg-white/95 backdrop-blur-sm rounded-2xl hover:-translate-y-1.5 transition-all duration-300 border-2 <?php echo esc_attr( $theme_card_border ); ?> flex flex-col justify-between p-4 h-full min-w-0 group overflow-hidden sm:items-center sm:text-center">

                        <?php if ( ! empty( $subscription_name ) ) : ?>
                            <span class="featured absolute top-2.5 right-2.5 sm:right-auto sm:left-2.5 bg-gradient-to-r <?php echo $is_members ? 'from-rose-500 to-rose-700' : 'from-[#229ed9] to-[#0088cc]'; ?> text-white text-[9.5px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full z-10">
                                <?php echo esc_html( $subscription_name ); ?>
                            </span>
                        <?php endif; ?>

                        <!-- Card Content -->
                        <div class="w-full flex flex-row sm:flex-col items-center sm:items-center flex-1 min-w-0 gap-3.5 sm:gap-0">

                            <!-- Avatar Thumbnail -->
                            <div class="tgt-card-avatar relative w-16 h-16 sm:w-24 sm:h-24 rounded-full p-0.5 sm:mt-1 sm:mb-2.5 flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 rounded-full <?php echo esc_attr( $theme_avatar_glow ); ?> opacity-80 group-hover:opacity-100 transition-opacity" aria-hidden="true"></div>
                                <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-white bg-white">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'thumbnail', array(
                                            'class'    => 'w-full h-full object-cover',
                                            'alt'      => $full_title_attr,
                                            'loading'  => 'lazy',
                                            'decoding' => 'async',
                                        ) ); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full <?php echo $is_members ? 'bg-rose-50 text-rose-400' : 'bg-slate-100 text-slate-400'; ?> flex items-center justify-center font-bold text-xs" aria-hidden="true">TG</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Information Block -->
                            <div class="flex-1 min-w-0 text-left sm:text-center w-full">
                                <a href="<?php the_permalink(); ?>" class="block w-full mb-1">
                                    <h2 class="text-sm sm:text-[15px] font-extrabold text-slate-800 line-clamp-1 leading-snug <?php echo esc_attr( $theme_title_hover ); ?> transition-colors">
                                        <?php echo esc_html( $trimmed_title ); ?>
                                    </h2>
                                </a>

                                <div class="subscribers inline-flex items-center gap-1.5 bg-slate-50/90 border border-slate-100 px-2.5 py-0.5 rounded-full text-slate-500 text-[11px] mb-2 sm:mb-2.5">
                                    <?php echo $users_icon_svg; ?>
                                    <span>
                                        <b class="text-slate-800 font-bold"><?php echo esc_html( $subscriber_count ? number_format_i18n( (int) $subscriber_count ) : '0' ); ?></b>
                                        <em class="not-italic text-slate-400"><?php esc_html_e( 'Subscribers', 'Telegram_Group_Links' ); ?></em>
                                    </span>
                                </div>

                                <div class="tags flex flex-wrap items-center sm:justify-center gap-1 mb-2 sm:mb-2.5 max-h-12 overflow-hidden">
                                    <?php foreach ( $language_list as $lang_name ) : ?>
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-medium px-2 py-0.5 rounded-md border border-slate-200/60"><?php echo esc_html( $lang_name ); ?></span>
                                    <?php endforeach; ?>
                                    <?php foreach ( $category_names as $cat_term_name ) : ?>
                                        <span class="<?php echo esc_attr( $theme_badge_bg ); ?> text-[10px] font-semibold px-2 py-0.5 rounded-md border"><?php echo esc_html( $cat_term_name ); ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <p class="description text-slate-500 text-xs leading-relaxed line-clamp-2 break-words mb-2 sm:mb-3">
                                    <?php echo esc_html( wp_strip_all_tags( wp_trim_words( get_the_excerpt(), 11, '...' ) ) ); ?>
                                </p>
                            </div>

                        </div>

                        <!-- Action Link -->
                        <a href="<?php the_permalink(); ?>"
                           class="w-full mt-2 sm:mt-auto pt-1 block"
                           aria-label="<?php echo esc_attr( sprintf( __( 'Join %s', 'Telegram_Group_Links' ), $full_title_attr ) ); ?>">
                            <span class="w-full <?php echo esc_attr( $card_btn_accent ); ?> active:scale-[0.98] text-white font-bold text-xs sm:text-sm rounded-xl py-2 flex items-center justify-center gap-1.5 transition-all shadow-md">
                                <?php esc_html_e( 'Join Now', 'Telegram_Group_Links' ); ?>
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </span>
                        </a>

                    </article>
            <?php
                endwhile;
            else :
                echo '<p class="col-span-full text-center text-gray-500 py-12">' . esc_html__( 'No posts found in this section.', 'Telegram_Group_Links' ) . '</p>';
            endif;
            ?>

        </div>

        <!-- Clean Pagination Bar -->
        <?php
        $total_pages = (int) $non_premium_posts->max_num_pages;

        if ( $total_pages > 1 ) :
        ?>
            <nav class="pagination flex justify-center items-center gap-1.5 mt-10 font-sans" aria-label="<?php esc_attr_e( 'Pagination', 'Telegram_Group_Links' ); ?>">
                <?php
                $pagination_args = array(
                    'total'     => $total_pages,
                    'current'   => $paged_current,
                    'type'      => 'array',
                    'prev_text' => __( 'Prev', 'Telegram_Group_Links' ),
                    'next_text' => __( 'Next', 'Telegram_Group_Links' ),
                );

                if ( ! empty( $premium_searchData ) ) {
                    $pagination_args['add_args'] = array( 'premium_search' => urlencode( $premium_searchData ) );
                }

                $pagination_links = paginate_links( $pagination_args );

                if ( $pagination_links ) {
                    foreach ( $pagination_links as $link ) {
                        $is_current   = ( strpos( $link, 'current' ) !== false );
                        $is_next_prev = ( strpos( $link, 'Next' ) !== false || strpos( $link, 'Prev' ) !== false );

                        if ( $is_current ) {
                            $current_bg = $is_members
                                ? 'bg-gradient-to-r from-rose-500 to-rose-700 shadow-rose-500/20'
                                : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] shadow-sky-500/20';
                            $classes = "w-9 h-9 flex items-center justify-center text-xs font-bold rounded-xl {$current_bg} text-white shadow-md";
                        } else {
                            $width_class    = $is_next_prev ? 'px-3.5 h-9' : 'w-9 h-9';
                            $inactive_hover = $is_members
                                ? 'hover:border-rose-300 hover:text-rose-600'
                                : 'hover:border-[#229ed9]/50 hover:text-[#229ed9]';
                            $classes        = "{$width_class} flex items-center justify-center text-xs font-semibold rounded-xl bg-white text-slate-700 border border-slate-200 shadow-xs {$inactive_hover} transition-all";
                        }

                        if ( preg_match( '/class=["\'][^"\']*["\']/', $link ) ) {
                            $output_link = preg_replace( '/class=["\'][^"\']*["\']/', 'class="' . $classes . '"', $link );
                        } else {
                            $output_link = preg_replace( '/<(a|span)/', '<$1 class="' . $classes . '"', $link );
                        }

                        echo $output_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                }
                ?>
            </nav>
        <?php
        endif;
        wp_reset_postdata();
        ?>

        <!-- Taxonomy Description Footer (Main Content Area) -->
        <?php if ( ! empty( $term_desc ) ) : ?>
            <div class="term-description mt-8 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">
                <?php echo wp_kses_post( $term_desc ); ?>
            </div>
        <?php endif; ?>

    </section>

</main>

<?php get_footer(); ?>