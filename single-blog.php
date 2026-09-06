<?php 
/**
 * Template for Single Blog Posts (single-blog.php)
 *
 * @package Telegram_Group_Links
 */

get_header(); 

// Detect 18+ / Members context (consistent with header/footer logic)
$is_members =
    ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) )
    || is_page( '18-plus' )
    || is_page( 'members' )
    || get_query_var( 'tgt_members_archive' );

// Theme-driven CSS helper tokens
$theme_card_border = $is_members
    ? 'border-rose-100/90 shadow-rose-500/5'
    : 'border-slate-200/80 shadow-sky-500/5';

$theme_category_badge = $is_members
    ? 'bg-rose-50 border-rose-200/80 text-rose-700 hover:bg-rose-100'
    : 'bg-sky-50 border-sky-100 text-[#0088cc] hover:bg-sky-100';

$theme_border_divider = $is_members
    ? 'border-rose-100'
    : 'border-slate-200';

$theme_tag_item = $is_members
    ? 'bg-slate-100/80 text-slate-600 border-slate-200/60 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200'
    : 'bg-slate-100/80 text-slate-600 border-slate-200/60 hover:bg-sky-50 hover:text-[#0088cc] hover:border-sky-200';

$theme_link_content = $is_members
    ? '[&_a]:text-rose-600 hover:[&_a]:text-rose-700'
    : '[&_a]:text-[#0088cc] hover:[&_a]:text-[#229ed9]';
?>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 mt-2 sm:mt-4 pb-12 font-sans">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
        $author_id = get_the_author_meta( 'ID' );
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white/95  rounded-2xl border-2 ' . esc_attr( $theme_card_border ) . ' overflow-hidden ' ); ?>>

        <!-- Content -->
        <div class="p-6 md:p-10">

            <!-- Categories -->
            <div class="flex flex-wrap gap-2 mb-5">
                <?php
                $categories = get_the_category();
                if ( $categories ) :
                    foreach ( $categories as $category ) :
                ?>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="px-3 py-1 text-xs font-bold rounded-full border <?php echo esc_attr( $theme_category_badge ); ?> transition-colors">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>

            <!-- Semantic H1 Title -->
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-5 tracking-tight">
                <?php the_title(); ?>
            </h1>

            <!-- Meta Details -->
            <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-xs sm:text-sm text-slate-500 border-b <?php echo esc_attr( $theme_border_divider ); ?> pb-6 mb-8 font-medium">

                <!-- Published Date -->
                <span class="flex items-center gap-1.5">
                    <span aria-hidden="true">📅</span>
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
                    </time>
                </span>

                <!-- Modified Date (If Updated) -->
                <?php if ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) : ?>
                    <span class="flex items-center gap-1.5">
                        <span aria-hidden="true">🔄</span>
                        <span><?php esc_html_e( 'Updated:', 'Telegram_Group_Links' ); ?></span>
                        <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_modified_date( 'F j, Y' ) ); ?>
                        </time>
                    </span>
                <?php endif; ?>

                <!-- Author Link -->
                <span class="flex items-center gap-1.5">
                    <span aria-hidden="true">👤</span>
                    <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="hover:text-slate-800 transition-colors">
                        <?php the_author(); ?>
                    </a>
                </span>

                <!-- Reading Time -->
                <?php if ( shortcode_exists( 'rt_reading_time' ) ) : ?>
                    <span class="flex items-center gap-1.5">
                        <span aria-hidden="true">🕒</span>
                        <?php echo do_shortcode( '[rt_reading_time]' ); ?>
                    </span>
                <?php endif; ?>

            </div>

            <!-- Post Content Body -->
            <div class="post-content term-description text-slate-600 leading-relaxed text-sm sm:text-base space-y-4 <?php echo esc_attr( $theme_link_content ); ?> [&_h1]:text-2xl [&_h1]:font-extrabold [&_h1]:text-slate-900 [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h3]:text-lg [&_h3]:font-bold [&_h3]:text-slate-900 [&_a]:font-semibold [&_a]:underline">
                <?php the_content(); ?>
            </div>

            <!-- Tags -->
            <?php
            $tags = get_the_tags();
            if ( $tags ) :
            ?>
                <div class="mt-10 pt-8 border-t <?php echo esc_attr( $theme_border_divider ); ?>">

                    <h3 class="font-bold text-slate-900 text-sm sm:text-base mb-4 uppercase tracking-wider">
                        <?php esc_html_e( 'Tags', 'Telegram_Group_Links' ); ?>
                    </h3>

                    <div class="flex flex-wrap gap-2">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                               class="px-3 py-1 rounded-md text-xs font-semibold border transition-all duration-200 <?php echo esc_attr( $theme_tag_item ); ?>">
                                #<?php echo esc_html( $tag->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endif; ?>

        </div>

    </article>

    <?php endwhile; else : ?>

        <div class="text-center py-20 bg-white/60 rounded-2xl border border-slate-200/60 mt-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">
                <?php esc_html_e( 'No Posts Found', 'Telegram_Group_Links' ); ?>
            </h2>
            <p class="text-slate-500 text-sm">
                <?php esc_html_e( "We couldn't find the post you're looking for.", 'Telegram_Group_Links' ); ?>
            </p>
        </div>

    <?php endif; ?>

</main>

<?php get_footer(); ?>