<?php
/**
 * Template Name: Sticker
 * Description: Page template for displaying Telegram sticker packs.
 *
 * @package Telegram_Group_Links
 */

get_header();

// Detect 18+ / Members context (consistent with header/footer logic)
$is_members = ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) )
    || is_page( '18-plus' )
    || is_page( 'members' )
    || get_query_var( 'tgt_members_archive' );

// Theme-driven CSS helper tokens
$theme_accent_btn = $is_members
    ? 'bg-gradient-to-r from-[#f43f5e] via-[#e11d48] to-[#be123c] hover:from-[#e11d48] hover:to-[#9f1239]'
    : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] hover:from-[#1c86ba] hover:to-[#16729e]';

$theme_search_focus = $is_members
    ? 'hover:border-rose-300 focus-within:border-rose-500 focus-within:ring-rose-500/15'
    : 'hover:border-[#229ed9]/50 focus-within:border-[#229ed9] focus-within:ring-[#229ed9]/15';

$theme_card_border = $is_members
    ? 'border-rose-100/90 hover:border-rose-400/70'
    : 'border-slate-200/80 hover:border-[#229ed9]/60';

$theme_image_bg = $is_members
    ? 'bg-rose-50/60'
    : 'bg-slate-50/80';

$theme_title_hover = $is_members
    ? 'group-hover:text-rose-600'
    : 'group-hover:text-[#229ed9]';

$theme_link_color = $is_members
    ? 'text-rose-600 hover:text-rose-800'
    : 'text-[#229ed9] hover:text-[#0088cc]';

$premium_search = isset( $_GET['premium_search'] ) ? sanitize_text_field( wp_unslash( $_GET['premium_search'] ) ) : '';

$paged = 1;
if ( get_query_var( 'paged' ) ) {
    $paged = max( 1, absint( get_query_var( 'paged' ) ) );
} elseif ( get_query_var( 'page' ) ) {
    $paged = max( 1, absint( get_query_var( 'page' ) ) );
} elseif ( isset( $_GET['ppageid'] ) ) {
    $paged = max( 1, absint( $_GET['ppageid'] ) );
}

$args = array(
    'post_type'           => 'sticker',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'orderby'             => 'date',
    'order'               => 'DESC',
    's'                   => $premium_search,
    'ignore_sticky_posts' => true,
);

if ( $is_members ) {
    $args['tgt_members_query'] = 'only';
}

$query = new WP_Query( $args );

// Retrieve Dynamic Title
$dynamic_h1_title = function_exists( 'get_yoast_seo_title' ) ? get_yoast_seo_title() : get_the_title();
?>



<main class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 mt-2 sm:mt-4 mb-16 font-sans">

    <!-- Primary H1 Header -->
    <header class="text-center mb-6 sm:mb-8">
        <h1 id="sticker-page-title" class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 mb-2 sm:mb-3 tracking-tight">
            <?php echo esc_html( $dynamic_h1_title ); ?>
        </h1>
        <p class="text-slate-500 text-sm sm:text-base">
            <?php esc_html_e( 'Browse the best Telegram sticker packs.', 'Telegram_Group_Links' ); ?>
        </p>
    </header>

    <section aria-labelledby="sticker-page-title">

        <!-- Enhanced Search Form -->
        <form method="get" action="<?php echo esc_url( get_permalink() ); ?>" class="max-w-xl mx-auto mb-10 sm:mb-12" role="search">
            <div class="relative flex items-center bg-white/95 backdrop-blur-sm rounded-full border border-gray-200/80 <?php echo esc_attr( $theme_search_focus ); ?> p-1.5 transition-all duration-300 shadow-md shadow-gray-200/50">

                <!-- Search Icon -->
                <div class="pl-3.5 text-gray-400 flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </div>

                <!-- Input Field -->
                <label for="sticker-search-input" class="sr-only"><?php esc_html_e( 'Search stickers', 'Telegram_Group_Links' ); ?></label>
                <input
                    type="search"
                    id="sticker-search-input"
                    name="premium_search"
                    value="<?php echo esc_attr( $premium_search ); ?>"
                    placeholder="<?php esc_attr_e( 'Search stickers...', 'Telegram_Group_Links' ); ?>"
                    class="w-full bg-transparent border-none text-gray-800 placeholder-gray-400 text-sm px-3 py-1.5 focus:outline-none"
                    autocomplete="off"
                >

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="<?php echo esc_attr( $theme_accent_btn ); ?> active:scale-95 text-white text-sm font-semibold px-6 py-2 rounded-full cursor-pointer transition-all duration-200 flex-shrink-0 shadow-md"
                    aria-label="<?php esc_attr_e( 'Search Stickers', 'Telegram_Group_Links' ); ?>"
                >
                    <?php esc_html_e( 'Search', 'Telegram_Group_Links' ); ?>
                </button>
            </div>
        </form>

        <?php if ( $query->have_posts() ) : ?>
            <!-- Sticker Grid Container -->
            <div class="sticker-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <?php
            while ( $query->have_posts() ) :
                $query->the_post();
                $post_id         = get_the_ID();
                $full_title_attr = the_title_attribute( array( 'echo' => false ) );
                $image           = get_the_post_thumbnail_url( $post_id, 'medium' );

                // Resolve Telegram Sticker Link
                $sticker_terms     = get_the_terms( $post_id, 'sticker_link' );
                $sticker_link_name = ( $sticker_terms && ! is_wp_error( $sticker_terms ) ) ? $sticker_terms[0]->name : get_the_permalink();
            ?>
                <article class="sticker-card relative bg-white/95 backdrop-blur-sm rounded-2xl border-2 <?php echo esc_attr( $theme_card_border ); ?> hover:-translate-y-1.5 transition-all duration-300 overflow-hidden h-full min-w-0 flex flex-col justify-between group shadow-sm hover:shadow-xl">

                    <div class="w-full flex flex-row sm:flex-col items-center sm:items-stretch flex-1 min-w-0">
                        <!-- Sticker Preview Wrapper -->
                        <div class="w-24 h-24 sm:w-full sm:h-52 <?php echo esc_attr( $theme_image_bg ); ?> flex items-center justify-center p-3 sm:p-4 flex-shrink-0 border-r sm:border-r-0 sm:border-b border-slate-100">
                            <?php if ( $image ) : ?>
                                <img
                                    src="<?php echo esc_url( $image ); ?>"
                                    alt="<?php echo esc_attr( $full_title_attr ); ?>"
                                    loading="lazy"
                                    decoding="async"
                                    class="w-16 h-16 sm:w-32 sm:h-32 object-contain group-hover:scale-105 transition-transform duration-300">
                            <?php else : ?>
                                <div class="w-14 h-14 sm:w-24 sm:h-24 rounded-2xl <?php echo $is_members ? 'bg-rose-100 text-rose-500' : 'bg-sky-100 text-sky-500'; ?> flex items-center justify-center font-extrabold text-xs sm:text-sm" aria-hidden="true">
                                    <?php esc_html_e( 'STICKER', 'Telegram_Group_Links' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content Area -->
                        <div class="p-4 sm:p-5 flex flex-col flex-1 min-w-0 justify-between">
                            <div>
                                <h2 class="text-sm sm:text-lg font-bold text-slate-800 mb-1 sm:mb-2 line-clamp-1 <?php echo esc_attr( $theme_title_hover ); ?> transition-colors">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-3 sm:mb-5 line-clamp-2">
                                    <?php echo esc_html( wp_strip_all_tags( wp_trim_words( get_the_content(), 10, '...' ) ) ); ?>
                                </p>
                            </div>

                            <a
                                href="<?php echo esc_url( $sticker_link_name ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?php echo esc_attr( sprintf( __( 'Add %s Sticker Pack', 'Telegram_Group_Links' ), $full_title_attr ) ); ?>"
                                class="inline-flex items-center gap-1.5 <?php echo esc_attr( $theme_link_color ); ?> font-bold text-xs sm:text-sm mt-auto transition-colors group-hover:translate-x-0.5 transform duration-200">
                                <span><?php esc_html_e( 'Add Sticker', 'Telegram_Group_Links' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </article>
            <?php endwhile; ?>
            </div>

            <!-- Numbered Pagination Bar -->
            <?php
            $total_pages = (int) $query->max_num_pages;
            if ( $total_pages > 1 ) :
            ?>
                <nav class="pagination flex justify-center items-center gap-1.5 mt-10 sm:mt-12 font-sans" aria-label="<?php esc_attr_e( 'Stickers Pagination', 'Telegram_Group_Links' ); ?>">
                    <?php
                    $pagination_search = array();
                    if ( '' !== $premium_search ) {
                        $pagination_search['premium_search'] = $premium_search;
                    }

                    $pagination_links = paginate_links( array(
                        'total'     => $total_pages,
                        'current'   => $paged,
                        'type'      => 'array',
                        'prev_text' => __( 'Prev', 'Telegram_Group_Links' ),
                        'next_text' => __( 'Next', 'Telegram_Group_Links' ),
                        'add_args'  => ! empty( $pagination_search ) ? array( 'premium_search' => urlencode( $premium_search ) ) : array(),
                    ) );

                    if ( $pagination_links ) {
                        foreach ( $pagination_links as $link ) {
                            $is_current   = ( strpos( $link, 'current' ) !== false );
                            $is_next_prev = ( strpos( $link, 'Next' ) !== false || strpos( $link, 'Prev' ) !== false );

                            if ( $is_current ) {
                                $current_bg = $is_members
                                    ? 'bg-gradient-to-r from-rose-500 to-rose-700'
                                    : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe]';
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
            <?php endif; ?>

        <?php else : ?>
            <div class="text-center py-20 bg-white/60 rounded-2xl border border-slate-200/60 mt-6">
                <h2 class="text-xl font-bold text-slate-800 mb-2"><?php esc_html_e( 'No stickers found', 'Telegram_Group_Links' ); ?></h2>
                <p class="text-slate-500 text-sm"><?php esc_html_e( 'Try searching with a different keyword.', 'Telegram_Group_Links' ); ?></p>
            </div>
        <?php endif; wp_reset_postdata(); ?>

        <!-- Widget Area for Descriptions -->
        <div class="term-description mt-10 tgt-ad-wrapper <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">
            <?php dynamic_sidebar( 'Sticker page description' ); ?>
        </div>

    </section>

</main>

<?php get_footer(); ?>