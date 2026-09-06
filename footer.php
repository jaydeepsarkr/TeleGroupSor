<?php
/**
 * The template for displaying the footer
 *
 * @package Telegram_Group_Links
 */

// Detect 18+ / Members context (same logic as header)
$is_members = ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) )
    || is_page( '18-plus' )
    || is_page( 'members' )
    || get_query_var( 'tgt_members_archive' );

// Dynamic Theme Styles
$footer_bg = $is_members
    ? 'bg-[#fff1f2]/90 border-rose-200/80 shadow-rose-500/5'
    : 'bg-[#edf7fc]/90 border-sky-200/80 shadow-sky-500/5';

$top_gradient = $is_members
    ? 'from-rose-500 via-red-500 to-rose-600'
    : 'from-[#229ed9] via-[#0088cc] to-sky-400';

$icon_bg = $is_members
    ? 'bg-rose-100/80 border-rose-200 text-rose-600 shadow-rose-500/10'
    : 'bg-white border-sky-200/90 text-[#0088cc] shadow-sky-500/10';

$brand_title_hover = $is_members
    ? 'group-hover:text-rose-600'
    : 'group-hover:text-[#0088cc]';

$subheading_text = $is_members
    ? 'text-rose-600'
    : 'text-[#0088cc]';

$social_bg = $is_members
    ? 'bg-rose-100/70 border-rose-200 text-rose-700 hover:bg-rose-600 hover:text-white hover:border-rose-600'
    : 'bg-white/90 border-sky-200/90 text-[#0088cc] hover:bg-[#229ed9] hover:text-white hover:border-[#229ed9]';

$border_color = $is_members
    ? 'border-rose-200/80'
    : 'border-sky-200/70';

$link_hover_class = $is_members
    ? '[&_a:hover]:text-rose-600'
    : '[&_a:hover]:text-[#0088cc]';

$about_link_class = $is_members
    ? '[&_a]:text-rose-600'
    : '[&_a]:text-[#0088cc]';
?>

<footer class="mt-20 backdrop-blur-md border-t relative z-10 shadow-sm <?php echo esc_attr( $footer_bg ); ?>">

    <!-- Top Glow Line -->
    <div class="h-1 bg-gradient-to-r <?php echo esc_attr( $top_gradient ); ?> shadow-xs" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">

        <!-- Main Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            <!-- Left Brand & About Column -->
            <div class="lg:col-span-6 space-y-4">

                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-3 group" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> - Home">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl border <?php echo esc_attr( $icon_bg ); ?> flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300 overflow-hidden flex-shrink-0">
                        <?php
                        if ( has_custom_logo() ) {
                            the_custom_logo();
                        } else {
                        ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M21.05 2.31a1.2 1.2 0 0 0-1.24-.16L2.6 9.44a1.15 1.15 0 0 0 .07 2.15l4.44 1.45 1.7 5.46c.16.5.6.86 1.13.86.32 0 .62-.13.85-.36l2.5-2.5 4.55 3.36c.22.16.48.25.75.25a1.2 1.2 0 0 0 1.19-.98l2.5-14.7a1.2 1.2 0 0 0-.23-1.12Z"/>
                            </svg>
                        <?php } ?>
                    </div>

                    <div>
                        <span class="block text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight <?php echo esc_attr( $brand_title_hover ); ?> transition-colors">
                            <?php bloginfo( 'name' ); ?>
                        </span>
                        <p class="<?php echo esc_attr( $subheading_text ); ?> text-xs font-semibold">
                            <?php esc_html_e( 'Discover & Join Active Telegram Communities', 'Telegram_Group_Links' ); ?>
                        </p>
                    </div>
                </a>

                <!-- About Sidebar Content -->
                <div class=" text-xs sm:text-sm text-slate-600 leading-relaxed max-w-lg [&_h2]:text-base [&_h2]:font-bold [&_h2]:text-slate-800 [&_h2]:mb-2 [&_h3]:text-sm [&_h3]:font-bold [&_h3]:text-slate-800 [&_h3]:mb-1.5 <?php echo esc_attr( $about_link_class ); ?> [&_a]:font-semibold [&_a:hover]:underline">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>

                <!-- Social Media Badges -->
                <div class="flex items-center gap-2 pt-1" aria-label="<?php esc_attr_e( 'Social Media Links', 'Telegram_Group_Links' ); ?>">
                    <a href="https://t.me/DrExynos" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg border <?php echo esc_attr( $social_bg ); ?> flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-xs" aria-label="Telegram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                        </svg>
                    </a>

                    <a href="https://www.facebook.com/joydeep.sarkar.7587370" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg border <?php echo esc_attr( $social_bg ); ?> flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-xs" aria-label="Facebook">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>

                    <a href="https://www.instagram.com/exynos_____/" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg border <?php echo esc_attr( $social_bg ); ?> flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-xs" aria-label="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>

            </div>

            <!-- Right Links Columns -->
            <div class="lg:col-span-6 grid grid-cols-2 gap-6 sm:gap-10">

                <!-- Categories -->
                <div class=" text-sm space-y-2 [&_h2]:text-sm [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:tracking-wider [&_h2]:uppercase [&_h2]:border-b [&_h2]:pb-2 [&_h3]:text-sm [&_h3]:font-bold [&_h3]:text-slate-900 [&_h3]:tracking-wider [&_h3]:uppercase [&_h3]:border-b [&_h3]:pb-2 [&_h2]:<?php echo esc_attr( $border_color ); ?> [&_h3]:<?php echo esc_attr( $border_color ); ?> [&_ul]:space-y-2 [&_ul]:list-none [&_ul]:p-0 [&_ul]:m-0 [&_li]:list-none [&_li]:p-0 [&_a]:text-xs [&_a]:sm:text-[13px] [&_a]:font-medium [&_a]:text-slate-600 <?php echo esc_attr( $link_hover_class ); ?> [&_a]:transition-colors [&_a]:inline-block">
                    <?php dynamic_sidebar( 'footer-4' ); ?>
                </div>

                <!-- Pages / Quick Links -->
                <div class=" text-sm space-y-2 [&_h2]:text-sm [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:tracking-wider [&_h2]:uppercase [&_h2]:border-b [&_h2]:pb-2 [&_h3]:text-sm [&_h3]:font-bold [&_h3]:text-slate-900 [&_h3]:tracking-wider [&_h3]:uppercase [&_h3]:border-b [&_h3]:pb-2 [&_h2]:<?php echo esc_attr( $border_color ); ?> [&_h3]:<?php echo esc_attr( $border_color ); ?> [&_ul]:space-y-2 [&_ul]:list-none [&_ul]:p-0 [&_ul]:m-0 [&_li]:list-none [&_li]:p-0 [&_a]:text-xs [&_a]:sm:text-[13px] [&_a]:font-medium [&_a]:text-slate-600 <?php echo esc_attr( $link_hover_class ); ?> [&_a]:transition-colors [&_a]:inline-block">
                    <?php dynamic_sidebar( 'footer-5' ); ?>
                </div>

            </div>

        </div>

        <!-- Bottom Legal Disclaimer -->
        <div class="mt-8 pt-5 border-t <?php echo esc_attr( $border_color ); ?> flex justify-center text-center">
            <div class=" max-w-3xl text-[11px] text-slate-500 leading-relaxed [&_p]:m-0 [&_a]:text-slate-600 <?php echo esc_attr( $link_hover_class ); ?>">
                <?php dynamic_sidebar( 'footer-6' ); ?>
            </div>
        </div>

    </div>

</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ===========================
        Mobile Menu Drawer
    =========================== */
    const menuBtn = document.getElementById("menuBtn");
    const closeMenu = document.getElementById("closeMenu");
    const mobileMenu = document.getElementById("mobileMenu");
    const menuOverlay = document.getElementById("menuOverlay");

    function isMenuOpen() {
        return mobileMenu && !mobileMenu.classList.contains("translate-x-full");
    }

    function openMobileMenu() {
        if (mobileMenu) mobileMenu.classList.remove("translate-x-full");
        if (menuOverlay) menuOverlay.classList.remove("hidden");
        if (menuBtn) menuBtn.setAttribute("aria-expanded", "true");
        document.body.classList.add("overflow-hidden");
    }

    function closeMobileMenu() {
        if (mobileMenu) mobileMenu.classList.add("translate-x-full");
        if (menuOverlay) menuOverlay.classList.add("hidden");
        if (menuBtn) menuBtn.setAttribute("aria-expanded", "false");
        document.body.classList.remove("overflow-hidden");
    }

    if (menuBtn) {
        menuBtn.addEventListener("click", function () {
            isMenuOpen() ? closeMobileMenu() : openMobileMenu();
        });
    }

    if (closeMenu) {
        closeMenu.addEventListener("click", closeMobileMenu);
    }

    if (menuOverlay) {
        menuOverlay.addEventListener("click", closeMobileMenu);
    }

    document.querySelectorAll("#mobileMenu a").forEach(link => {
        link.addEventListener("click", closeMobileMenu);
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeMobileMenu();
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth >= 1024) closeMobileMenu();
    });

    /* ===========================
        Hide Header on Scroll
    =========================== */
    const header = document.getElementById("siteHeader");

    if (header) {
        let lastScrollTop = 0;
        let ticking = false;
        const scrollThreshold = 80;

        function updateHeader() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (!isMenuOpen()) {
                if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                    header.classList.add("-translate-y-full");
                } else {
                    header.classList.remove("-translate-y-full");
                }
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            ticking = false;
        }

        window.addEventListener("scroll", function () {
            if (!ticking) {
                window.requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }, { passive: true });
    }

    /* ===========================
        Hero Typing Animation
    =========================== */
    const typingText = document.getElementById("typing-text");

    if (typingText) {
        const textArray = ["Groups.", "Channels.", "Bots."];
        const typingSpeed = 120;
        const backspaceSpeed = 80;
        const pauseBetweenTexts = 1200;

        let currentTextIndex = 0;
        let currentCharIndex = 0;
        let isTyping = true;
        let isWelcomeTextComplete = false;

        function typeWelcomeText() {
            const welcomeText = "Explore Telegram: ";

            if (currentCharIndex < welcomeText.length) {
                typingText.textContent = welcomeText.slice(0, currentCharIndex + 1);
                currentCharIndex++;
                setTimeout(typeWelcomeText, typingSpeed);
            } else {
                isWelcomeTextComplete = true;
                currentCharIndex = 0;
                setTimeout(startTypingLoop, pauseBetweenTexts);
            }
        }

        function startTypingLoop() {
            if (isWelcomeTextComplete) {
                type();
            }
        }

        function type() {
            const currentText = textArray[currentTextIndex];

            if (isTyping) {
                if (currentCharIndex < currentText.length) {
                    typingText.textContent = "Explore Telegram: " + currentText.slice(0, currentCharIndex + 1);
                    currentCharIndex++;
                    setTimeout(type, typingSpeed);
                } else {
                    isTyping = false;
                    setTimeout(type, pauseBetweenTexts);
                }
            } else {
                if (currentCharIndex > 0) {
                    typingText.textContent = "Explore Telegram: " + currentText.slice(0, currentCharIndex - 1);
                    currentCharIndex--;
                    setTimeout(type, backspaceSpeed);
                } else {
                    isTyping = true;
                    currentTextIndex = (currentTextIndex + 1) % textArray.length;
                    setTimeout(type, pauseBetweenTexts);
                }
            }
        }

        typeWelcomeText();
    }

});
</script>

<?php wp_footer(); ?>
</body>
</html>