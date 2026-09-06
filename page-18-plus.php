<?php
/**
 * Template Name: 18 Plus Page
 * Description: Real WordPress Page template for 18+ Adult Archive. Provides full Yoast SEO, schema, sitemap, and Open Graph support while maintaining exact design parity.
 *
 * @package Telegram_Group_Links
 */

get_header();

// Detect 18+ / Members context
$is_members = ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) )
    || is_page( '18-plus' )
    || is_page( 'members' )
    || get_query_var( 'tgt_members_archive' );

// Theme-driven CSS helper tokens
$theme_accent_btn   = 'bg-gradient-to-r from-[#f43f5e] via-[#e11d48] to-[#be123c] hover:from-[#e11d48] hover:to-[#9f1239] shadow-rose-500/20 group-hover:shadow-rose-500/35';
$theme_search_focus = 'hover:border-rose-300 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-500/15';
$theme_card_border  = 'border-rose-100/90 hover:border-rose-400/70 hover:shadow-rose-500/10';
$theme_title_hover  = 'group-hover:text-rose-600';
$theme_text_accent  = 'text-rose-600';
$theme_badge_bg     = 'bg-rose-50 border-rose-200/80 text-rose-700';

$users_icon_svg = '<svg class="w-3.5 h-3.5 opacity-60 inline-block align-middle flex-shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>';

// Retrieve Dynamic Title
$dynamic_h1_title = function_exists( 'get_yoast_seo_title' ) ? get_yoast_seo_title() : get_the_title();
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 mt-2 sm:mt-4 mb-16 font-sans">

    <!-- Hero Section -->
    <header class="text-center py-6 sm:py-10 relative">
        <span class="inline-flex items-center gap-2 bg-rose-950/90 text-rose-300 border border-rose-700/60 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-4 shadow-md">
            <span class="relative flex h-2.5 w-2.5" aria-hidden="true">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
            </span>
            <span aria-hidden="true">🔞</span> <?php esc_html_e( '18+ Adult Content', 'Telegram_Group_Links' ); ?>
        </span>
        <h1 id="page-title" class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">
            <?php echo esc_html( $dynamic_h1_title ); ?>
        </h1>
        <p class="mt-3 text-slate-500 text-sm sm:text-base max-w-xl mx-auto font-medium">
            <?php esc_html_e( 'A separate, hand-picked adult section — strictly intended for mature audiences (18+).', 'Telegram_Group_Links' ); ?>
        </p>
    </header>

    <section aria-labelledby="page-title">

        <!-- Search Bar (scoped to 18+ content) -->
        <form id="members-search-form" method="get" action="<?php echo esc_url( get_permalink() ); ?>" class="max-w-xl mx-auto mb-10 sm:mb-12" role="search">
            <div class="relative flex items-center bg-white/95 backdrop-blur-sm rounded-full border border-rose-200/80 shadow-md shadow-rose-200/50 <?php echo esc_attr( $theme_search_focus ); ?> p-1.5 transition-all duration-300">
                
                <!-- Search Icon -->
                <div class="pl-3.5 text-rose-400 flex-shrink-0" aria-hidden="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </div>

                <!-- Input Field -->
                <label for="members-search-input" class="sr-only"><?php esc_html_e( 'Search 18+ groups', 'Telegram_Group_Links' ); ?></label>
                <input
                    type="search"
                    placeholder="<?php esc_attr_e( 'Search 18+ groups...', 'Telegram_Group_Links' ); ?>"
                    name="search"
                    id="members-search-input"
                    value="<?php echo isset( $_GET['search'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['search'] ) ) ) : ''; ?>"
                    class="w-full bg-transparent border-none text-slate-800 placeholder-slate-400 text-sm px-3 py-1.5 focus:outline-none"
                    autocomplete="off"
                >

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="<?php echo esc_attr( $theme_accent_btn ); ?> active:scale-95 text-white text-sm font-bold px-5 sm:px-6 py-2 rounded-full cursor-pointer transition-all duration-200 shadow-md flex-shrink-0"
                    aria-label="<?php esc_attr_e( 'Submit search for 18+ groups', 'Telegram_Group_Links' ); ?>"
                >
                    <?php esc_html_e( 'Search', 'Telegram_Group_Links' ); ?>
                </button>
            </div>
        </form>

        <?php
        $members_search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
        
        $members_paged = 1;
        if ( get_query_var( 'paged' ) ) {
            $members_paged = max( 1, absint( get_query_var( 'paged' ) ) );
        } elseif ( get_query_var( 'page' ) ) {
            $members_paged = max( 1, absint( get_query_var( 'page' ) ) );
        } elseif ( isset( $_GET['mpageid'] ) ) {
            $members_paged = max( 1, absint( $_GET['mpageid'] ) );
        }

        $members_args = array(
            'post_type'           => array( 'post', 'blog' ),
            'posts_per_page'      => 20,
            'paged'               => $members_paged,
            's'                   => $members_search,
            'tgt_members_query'   => 'only',
            'ignore_sticky_posts' => true,
        );

        $members_query = new WP_Query( $members_args );
        ?>

        <!-- 18+ Content Grid -->
        <div class="card-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-10">
            <?php if ( $members_query->have_posts() ) : ?>
                <?php
                while ( $members_query->have_posts() ) :
                    $members_query->the_post();
                    $post_id          = get_the_ID();
                    $full_title_attr  = the_title_attribute( array( 'echo' => false ) );
                    $trimmed_title    = wp_trim_words( get_the_title(), 4 );
                    $subscriber_count = get_post_meta( $post_id, 'subscriber_count', true );

                    $type_terms = get_the_terms( $post_id, 'type' );
                    $type_list  = ( $type_terms && ! is_wp_error( $type_terms ) ) ? wp_list_pluck( $type_terms, 'name' ) : array();
                    $type_names = implode( ', ', $type_list );

                    $categories     = get_the_category( $post_id );
                    $category_names = ( $categories && ! is_wp_error( $categories ) ) ? wp_list_pluck( $categories, 'name' ) : array();
                    $category_list  = implode( ', ', $category_names );
                    ?>
                    <article class="card relative bg-white/95 backdrop-blur-sm border-2 <?php echo esc_attr( $theme_card_border ); ?> rounded-2xl p-4 sm:p-5 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">

                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 bg-rose-950 text-rose-300 border border-rose-800 text-[10px] font-black uppercase tracking-wide px-2 py-0.5 rounded-full shadow-xs z-10">
                            <span aria-hidden="true">🔞</span> <?php esc_html_e( '18+', 'Telegram_Group_Links' ); ?>
                        </span>

                        <div>
                            <div class="flex items-start gap-3.5">
                                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-xl overflow-hidden bg-rose-50/50 border border-rose-100/80 flex-shrink-0 shadow-xs">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'thumbnail', array(
                                            'class'    => 'w-full h-full object-cover group-hover:scale-105 transition duration-300',
                                            'alt'      => $full_title_attr,
                                            'loading'  => 'lazy',
                                            'decoding' => 'async',
                                        ) ); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs" aria-hidden="true">18+</div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-1 min-w-0 pr-8">
                                    <a href="<?php the_permalink(); ?>">
                                        <h2 class="font-extrabold text-base text-slate-900 line-clamp-2 leading-snug <?php echo esc_attr( $theme_title_hover ); ?> transition-colors">
                                            <?php echo esc_html( $trimmed_title ); ?>
                                        </h2>
                                    </a>

                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        <?php if ( ! empty( $type_names ) ) : ?>
                                            <span class="bg-slate-100 text-slate-600 text-[11px] font-semibold px-2 py-0.5 rounded-md border border-slate-200/60">
                                                <?php echo esc_html( $type_names ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $category_list ) ) : ?>
                                            <span class="<?php echo esc_attr( $theme_badge_bg ); ?> text-[11px] font-bold px-2 py-0.5 rounded-md border">
                                                <?php echo esc_html( $category_list ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-3.5 text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                <?php echo esc_html( wp_strip_all_tags( wp_trim_words( get_the_content(), 12, '...' ) ) ); ?>
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-rose-100 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <?php echo $users_icon_svg; ?>
                                <span><?php echo esc_html( $subscriber_count ? number_format_i18n( (int) $subscriber_count ) : '0' ); ?></span>
                            </div>

                            <a href="<?php the_permalink(); ?>"
                               class="<?php echo esc_attr( $theme_text_accent ); ?> text-xs font-extrabold inline-flex items-center gap-1 group-hover:gap-2 transition-all"
                               aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'Telegram_Group_Links' ), $full_title_attr ) ); ?>">
                                <?php esc_html_e( 'View', 'Telegram_Group_Links' ); ?> <span class="text-sm" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-span-full text-center py-16 bg-white/60 rounded-2xl border border-rose-100/60">
                    <p class="text-slate-500 text-sm sm:text-base font-medium">
                        <?php echo $members_search !== '' ? esc_html__( 'No 18+ groups matched your search.', 'Telegram_Group_Links' ) : esc_html__( 'No 18+ content has been published yet.', 'Telegram_Group_Links' ); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Numbered Pagination -->
        <?php
        $total_pages = (int) $members_query->max_num_pages;
        if ( $total_pages > 1 ) :
        ?>
            <nav class="mt-12 mb-12 flex justify-center items-center gap-2 font-sans" aria-label="<?php esc_attr_e( '18+ Archive Pagination', 'Telegram_Group_Links' ); ?>">
                <?php
                $pagination_search = array();
                if ( '' !== $members_search ) {
                    $pagination_search['search'] = $members_search;
                }

                $pagination_links = paginate_links( array(
                    'total'     => $total_pages,
                    'current'   => $members_paged,
                    'type'      => 'array',
                    'prev_text' => __( 'Prev', 'Telegram_Group_Links' ),
                    'next_text' => __( 'Next', 'Telegram_Group_Links' ),
                    'add_args'  => ! empty( $pagination_search ) ? array( 'search' => urlencode( $members_search ) ) : array(),
                ) );

                if ( $pagination_links ) {
                    foreach ( $pagination_links as $link ) {
                        $is_current   = ( strpos( $link, 'current' ) !== false );
                        $is_next_prev = ( strpos( $link, 'Next' ) !== false || strpos( $link, 'Prev' ) !== false );

                        if ( $is_current ) {
                            $classes = 'w-10 h-10 flex items-center justify-center text-sm font-bold rounded-2xl bg-gradient-to-r from-rose-500 to-rose-700 text-white shadow-md shadow-rose-500/20';
                        } else {
                            $width_class = $is_next_prev ? 'px-4 h-10' : 'w-10 h-10';
                            $classes     = "{$width_class} flex items-center justify-center text-sm font-semibold rounded-2xl bg-white text-slate-700 border border-rose-100 shadow-xs hover:border-rose-300 hover:text-rose-600 transition-all";
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

        <?php wp_reset_postdata(); ?>

        <!-- Gutenberg/Page Content Display Section -->
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                if ( get_the_content() ) : ?>
                    <div class="term-description mt-8 [&_a]:text-rose-600 hover:[&_a]:text-rose-700">
                        <?php the_content(); ?>
                    </div>
                <?php endif;
            endwhile;
            wp_reset_postdata();
        endif;
        ?>

    </section>

</main>

<?php get_footer(); ?>