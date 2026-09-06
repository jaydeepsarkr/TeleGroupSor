<?php
/**
 * The template for displaying all static pages (page.php)
 *
 * @package Telegram_Group_Links
 */

get_header(); 
?>

<main class=" min-h-screen py-12 font-sans">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
            $full_title_attr = the_title_attribute( array( 'echo' => false ) );
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white rounded-xl shadow border-2 border-slate-200 overflow-hidden' ); ?>>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="w-full h-64 md:h-96 overflow-hidden bg-slate-100">
                    <?php the_post_thumbnail( 'large', array(
                        'class'    => 'w-full h-full object-cover',
                        'alt'      => $full_title_attr,
                        'loading'  => 'eager',
                        'decoding' => 'async',
                    ) ); ?>
                </div>
            <?php endif; ?>

            <div class="p-8 md:p-12">

                <div class="mb-8">
                    <span class="inline-block px-4 py-1 bg-sky-100 text-sky-700 rounded-full text-sm font-medium">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
                        </time>
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-8 leading-tight tracking-tight">
                    <?php the_title(); ?>
                </h1>

                <div class="term-description mt-8 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">

                    <?php the_content(); ?>

                </div>

            </div>

        </article>

        <?php endwhile; else : ?>

            <div class="text-center py-20 bg-white rounded-xl border border-slate-200 shadow">
                <h2 class="text-2xl font-bold text-slate-800 mb-2">
                    <?php esc_html_e( 'Page Not Found', 'Telegram_Group_Links' ); ?>
                </h2>
                <p class="text-slate-500 text-sm">
                    <?php esc_html_e( "The page you are looking for doesn't exist.", 'Telegram_Group_Links' ); ?>
                </p>
            </div>

        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>