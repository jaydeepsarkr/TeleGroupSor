<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package Telegram_Group_Links
 */

get_header(); 

// Dynamic fallback image path
$error_image_url = get_template_directory_uri() . '/images/404-error.jpg';
?>

<main class="min-h-[80vh] flex items-center justify-center px-6 py-16  font-sans">
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Left Content Area -->
        <div class="text-center lg:text-left">
            <span class="inline-block px-4 py-1 rounded-full bg-sky-100 text-sky-600 font-medium text-sm mb-5">
                <?php esc_html_e( 'Error 404', 'Telegram_Group_Links' ); ?>
            </span>

            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-4 tracking-tight">
                <?php esc_html_e( 'Page Not Found', 'Telegram_Group_Links' ); ?>
            </h1>

            <p class="text-lg text-slate-600 leading-relaxed mb-8">
                <?php esc_html_e( "Sorry, the page you're looking for doesn't exist or may have been moved.", 'Telegram_Group_Links' ); ?>
            </p>

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mb-8 text-left">
                <h2 class="font-semibold text-slate-800 text-base mb-3">
                    <?php esc_html_e( 'Possible Reasons', 'Telegram_Group_Links' ); ?>
                </h2>

                <ul class="space-y-2 text-slate-600 text-sm">
                    <li>• <?php esc_html_e( 'The URL may be incorrect or mistyped.', 'Telegram_Group_Links' ); ?></li>
                    <li>• <?php esc_html_e( 'The channel or group has been removed.', 'Telegram_Group_Links' ); ?></li>
                    <li>• <?php esc_html_e( 'The link you followed is outdated.', 'Telegram_Group_Links' ); ?></li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                   class="px-7 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-semibold transition duration-300 text-center shadow-sm hover:shadow-md active:scale-95">
                    <span aria-hidden="true">🏠</span> <?php esc_html_e( 'Back to Home', 'Telegram_Group_Links' ); ?>
                </a>

                <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"
                   class="px-7 py-3 border border-slate-300 hover:border-sky-500 hover:text-sky-600 rounded-xl font-semibold transition duration-300 text-center text-slate-700 active:scale-95">
                    <?php esc_html_e( 'Contact Us', 'Telegram_Group_Links' ); ?>
                </a>
            </div>
        </div>

        <!-- Right Illustration Area -->
        <div class="flex justify-center">
            <img
                src="<?php echo esc_url( $error_image_url ); ?>"
                alt="<?php esc_attr_e( '404 Page Not Found Illustration', 'Telegram_Group_Links' ); ?>"
                loading="eager"
                decoding="async"
                width="450"
                height="350"
                class="w-full max-w-md rounded-3xl object-cover "
                onerror="this.onerror=null;this.src='https://telegroupsor.link/wp-content/uploads/2024/07/400-error-bad-request.jpg';"
            >
        </div>

    </div>
</main>

<?php get_footer(); ?>