<?php
/**
 * Header Template - TeleGroupsor
 * Fully optimized for Modern SEO, Core Web Vitals, Schema.org, and Accessibility.
 */

// Detect 18+ / Members context
$is_18_plus = (function_exists('tgt_is_members_only_post') && tgt_is_members_only_post(get_the_ID())) || is_page('18-plus') || is_page('members') || get_query_var('tgt_members_archive');

$site_name = get_bloginfo('name');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php if (!current_theme_supports('title-tag')) : ?>
        <title><?php echo esc_html(wp_get_document_title()); ?></title>
    <?php endif; ?>

    <!-- Mobile Theme Styling -->
    <meta name="theme-color" content="<?php echo $is_18_plus ? '#e11d48' : '#229ed9'; ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr($site_name); ?>">
    <meta name="rating" content="<?php echo $is_18_plus ? 'adult' : 'general'; ?>">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="format-detection" content="telephone=no">

    <!-- Webmaster Verification -->
    <meta name="google-site-verification" content="ymogktE3SuJz1ecDDrBUUlSMiT8HEDJTUHd4sgC8o2I" />

    <!-- Preconnect Resources for Speed -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://platform-api.sharethis.com" crossorigin>
    <link rel="preconnect" href="https://pagead2.googlesyndication.com" crossorigin>
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_template_directory_uri() . '/images/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(get_template_directory_uri() . '/images/favicon-16x16.png'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri() . '/images/apple-touch-icon.png'); ?>">

    <!-- Third-Party Async / Defer Scripts -->
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=668bd3d2a88bfa0019b937f0&product=inline-share-buttons&source=platform" async></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2854765960889779" crossorigin="anonymous"></script>

    <!-- Stylesheets -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/style.css'); ?>" />

    <?php wp_head(); ?>

    <style>
        :root {
            --tg-blue-light: <?php echo $is_18_plus ? '#fff1f2' : '#edf7fc'; ?>;
            --tg-accent: <?php echo $is_18_plus ? '#f43f5e' : '#229ed9'; ?>;
            --tg-accent-dark: <?php echo $is_18_plus ? '#be123c' : '#0088cc'; ?>;
        }

        /* CUSTOM THEMED SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--tg-blue-light);
        }
        ::-webkit-scrollbar-thumb {
            background: <?php echo $is_18_plus ? '#fda4af' : '#90cdfa'; ?>;
            border-radius: 9999px;
            border: 2px solid var(--tg-blue-light);
            transition: background-color 0.2s ease;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--tg-accent);
        }
        * {
            scrollbar-width: thin;
            scrollbar-color: <?php echo $is_18_plus ? '#fda4af #fff1f2' : '#90cdfa #edf7fc'; ?>;
        }


        body {
            padding-top: 4.5rem;
            -webkit-tap-highlight-color: transparent;
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background-color: var(--tg-blue-light);
            position: relative;
            min-height: 100vh;
        }

        /* Dynamic Vector Doodle Background */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-color: var(--tg-blue-light);
            background-image:
                <?php if ($is_18_plus) : ?>
                radial-gradient(ellipse at top, rgba(244, 63, 94, 0.14) 0%, rgba(255, 228, 230, 0.6) 45%, rgba(255, 241, 242, 0.95) 100%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160' fill='none' stroke='%23be123c' stroke-width='1.1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M25 20 L40 12 L35 32 Z' opacity='0.45'/%3E%3Cpath d='M105 25 C100 20 90 20 85 25 C80 30 80 40 85 45 L95 55 L105 45 C110 40 110 30 105 25 Z' opacity='0.4'/%3E%3Ccircle cx='25' cy='85' r='10' opacity='0.4'/%3E%3Cpath d='M25 75 L25 80 M20 85 L25 85' opacity='0.4'/%3E%3Cpath d='M90 95 Q105 80 120 95 T105 125 Z' opacity='0.35'/%3E%3Cpath d='M55 55 L70 50 L65 70 L50 65 Z' opacity='0.35'/%3E%3Ccircle cx='70' cy='115' r='5' opacity='0.35'/%3E%3Cpath d='M110 70 L125 65 L120 80 Z' opacity='0.35'/%3E%3Cpath d='M45 120 C40 115 50 105 55 110' opacity='0.4'/%3E%3C/svg%3E");
                <?php else : ?>
                radial-gradient(ellipse at top, rgba(34, 158, 217, 0.12) 0%, rgba(225, 242, 251, 0.5) 45%, rgba(237, 247, 252, 0.95) 100%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160' fill='none' stroke='%230088cc' stroke-width='1.1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M25 20 L40 12 L35 32 Z' opacity='0.45'/%3E%3Cpath d='M105 25 C100 20 90 20 85 25 C80 30 80 40 85 45 L95 55 L105 45 C110 40 110 30 105 25 Z' opacity='0.4'/%3E%3Ccircle cx='25' cy='85' r='10' opacity='0.4'/%3E%3Cpath d='M25 75 L25 80 M20 85 L25 85' opacity='0.4'/%3E%3Cpath d='M90 95 Q105 80 120 95 T105 125 Z' opacity='0.35'/%3E%3Cpath d='M55 55 L70 50 L65 70 L50 65 Z' opacity='0.35'/%3E%3Ccircle cx='70' cy='115' r='5' opacity='0.35'/%3E%3Cpath d='M110 70 L125 65 L120 80 Z' opacity='0.35'/%3E%3Cpath d='M45 120 C40 115 50 105 55 110' opacity='0.4'/%3E%3C/svg%3E");
                <?php endif; ?>
            background-size: auto, 160px 160px;
            background-repeat: no-repeat, repeat;
            opacity: 0.85;
        }

        /* HEADER ANIMATION & STYLES */
        #siteHeader {
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        background-color 0.3s ease,
                        border-color 0.3s ease,
                        box-shadow 0.3s ease;
            will-change: transform;
        }
        .header-glass {
            -webkit-backdrop-filter: blur(14px);
            backdrop-filter: blur(14px);
        }
        #siteHeader nav img,
        #siteHeader nav svg,
        #mobileMenu img,
        #mobileMenu svg {
            width: 1.1rem;
            height: 1.1rem;
            display: inline-block;
            vertical-align: middle;
        }
        #siteHeader nav ul li a {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.9rem;
            border-radius: 0.75rem;
            font-size: 0.88rem;
            font-weight: 700;
            color: <?php echo $is_18_plus ? '#4c0519' : '#334155'; ?>;
            transition: all .2s ease;
            touch-action: manipulation;
        }
        #siteHeader nav ul li a:hover,
        #siteHeader nav ul li.current-menu-item > a {
            background-color: <?php echo $is_18_plus ? '#ffe4e6' : 'rgba(34, 158, 217, 0.12)'; ?>;
            color: <?php echo $is_18_plus ? '#e11d48' : '#0284c7'; ?>;
        }
        #mobileMenu ul li a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: #334155;
            min-height: 40px;
            touch-action: manipulation;
            transition: all .15s ease;
        }
        #mobileMenu ul li a:hover {
            background-color: <?php echo $is_18_plus ? '#ffe4e6' : 'rgba(34, 158, 217, 0.1)'; ?>;
            color: <?php echo $is_18_plus ? '#e11d48' : '#0284c7'; ?>;
        }
        .tgt-members-btn-lg {
            padding: 11px 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #f43f5e, #be123c, #881337);
            color: #ffffff !important;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            white-space: nowrap;
            touch-action: manipulation;
            border: 1.5px solid rgba(255, 255, 255, 0.35);
        }
        .tgt-members-btn-lg:hover,
        .tgt-members-btn-lg:active {
            transform: translateY(-1.5px) scale(1.02);
            box-shadow: 0 4px 18px rgba(225, 29, 72, 0.45);
            filter: brightness(1.08);
            color: #ffffff;
        }

        /* AURORA ROTATING BORDER */
        @keyframes auroraSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .aurora-glow-container {
            position: relative;
            border-radius: 9999px;
            padding: 1.5px;
            overflow: hidden;
            background: transparent;
        }
        .aurora-glow-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg,
                transparent 0deg,
                <?php echo $is_18_plus ? '#f43f5e 80deg, #fb7185 160deg, transparent 220deg, #e11d48 290deg' : '#229ed9 80deg, #60a5fa 160deg, transparent 220deg, #0088cc 290deg'; ?>,
                transparent 360deg
            );
            animation: auroraSpin 4.5s linear infinite;
        }
        .island-inner {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 9999px;
            backdrop-filter: blur(16px);
        }
        @keyframes statBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .beacon-core {
            animation: statBlink 2s ease-in-out infinite;
        }
    </style>
</head>
<body <?php body_class(); ?>>

<header id="siteHeader"
    class="fixed top-0 left-0 right-0 z-50 header-glass border-b <?php echo $is_18_plus ? 'bg-rose-50/90 border-rose-200/80 shadow-sm' : 'bg-white/90 border-sky-100/90 shadow-sm shadow-sky-500/5'; ?>">

    <div class="w-full h-16 px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-4">

        <!-- Logo & Brand Header -->
        <div class="flex items-center gap-2.5 min-w-0 flex-shrink-0">
            <?php the_custom_logo(); ?>

            <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-title-header text-lg sm:text-xl font-extrabold tracking-tight bg-gradient-to-r <?php echo $is_18_plus ? 'from-rose-600 via-red-600 to-rose-900' : 'from-[#229ed9] via-[#0088cc] to-sky-700'; ?> bg-clip-text text-transparent truncate">
                <?php bloginfo('name'); ?>
            </a>
        </div>

        <!-- Center: Dynamic Floating Telegram Island -->
        <div class="hidden xl:flex items-center justify-center flex-1 mx-4 max-w-sm" aria-hidden="true">
            <div class="aurora-glow-container group cursor-pointer shadow-sm shadow-sky-500/10">
                <div class="island-inner flex items-center gap-3 px-4 py-1.5 transition-all group-hover:bg-white border border-sky-100/60">

                    <!-- Live Dot -->
                    <div class="flex items-center gap-1.5">
                        <span class="beacon-core w-2 h-2 rounded-full <?php echo $is_18_plus ? 'bg-rose-500 shadow-rose-400' : 'bg-[#229ed9] shadow-sky-400'; ?> shadow-sm"></span>
                        <span class="text-[10px] font-extrabold tracking-wider <?php echo $is_18_plus ? 'text-rose-700/80' : 'text-sky-700/80'; ?> uppercase">TELEGROUPSOR</span>
                    </div>

                    <span class="w-[1px] h-3.5 <?php echo $is_18_plus ? 'bg-rose-200' : 'bg-sky-200'; ?>"></span>

                    <!-- Live Dynamic Counter -->
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-extrabold text-slate-800 tracking-tight">12,480+</span>
                        <span class="text-xs font-semibold <?php echo $is_18_plus ? 'text-rose-600' : 'text-[#0088cc]'; ?>">Online</span>
                    </div>

                    <span class="text-xs <?php echo $is_18_plus ? 'text-rose-500' : 'text-[#229ed9]'; ?> transform transition-transform group-hover:rotate-45 duration-300">✈️</span>

                </div>
            </div>
        </div>

        <!-- Right Side: Navigation & Actions -->
        <div class="flex items-center gap-3.5 flex-shrink-0">

            <!-- Desktop Navigation -->
            <nav class="hidden lg:block" aria-label="<?php esc_attr_e( 'Primary Navigation', 'Telegram_Group_Links' ); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'flex items-center gap-1',
                    'walker'         => class_exists('WP_Custom_Navwalker') ? new WP_Custom_Navwalker() : '',
                    'fallback_cb'    => false,
                ));
                ?>
            </nav>

            <!-- 18+ Button -->
            <a href="<?php echo esc_url( home_url( '/18-plus/' ) ); ?>"
               id="membersOnlyBtn"
               class="tgt-members-btn-lg flex-shrink-0 hidden sm:inline-flex"
               aria-label="<?php esc_attr_e( '18+ Adult Telegram Groups', 'Telegram_Group_Links' ); ?>">
                <span class="text-base leading-none inline-block transform scale-110" aria-hidden="true">🔞</span>
                <span><?php esc_html_e( '18+ ', 'Telegram_Group_Links' ); ?></span>
            </a>

            <!-- Mobile Menu Toggle Button -->
            <button id="menuBtn"
                type="button"
                aria-label="<?php esc_attr_e( 'Open Navigation Menu', 'Telegram_Group_Links' ); ?>"
                aria-expanded="false"
                aria-controls="mobileMenu"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl border <?php echo $is_18_plus ? 'bg-rose-100/60 border-rose-200 text-rose-900' : 'bg-white border-sky-200/80 text-sky-800 hover:bg-sky-50'; ?> active:scale-95 transition flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </div>

    </div>

</header>

<!-- Mobile Drawer Overlay -->
<div id="menuOverlay" class="hidden fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden transition-opacity" aria-hidden="true"></div>

<!-- Mobile Drawer Dropdown Menu -->
<div id="mobileMenu"
     role="dialog"
     aria-modal="true"
     aria-label="<?php esc_attr_e( 'Mobile Menu', 'Telegram_Group_Links' ); ?>"
     class="hidden fixed top-16 right-3 z-50 h-auto max-h-[calc(100vh-5rem)] w-64 max-w-[85%] bg-white rounded-2xl border border-sky-100 lg:hidden flex-col overflow-hidden shadow-xl">

    <div class="flex items-center justify-between h-12 px-4 border-b border-sky-100 <?php echo $is_18_plus ? 'bg-rose-50/50' : 'bg-sky-50/50'; ?>">
        <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full <?php echo $is_18_plus ? 'bg-rose-500' : 'bg-[#229ed9]'; ?>" aria-hidden="true"></span>
            <?php esc_html_e( 'Menu Navigation', 'Telegram_Group_Links' ); ?>
        </span>
        <button id="closeMenu" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'Telegram_Group_Links' ); ?>"
                class="p-1 rounded-md border <?php echo $is_18_plus ? 'border-rose-200/60 hover:bg-rose-50' : 'border-sky-200/60 hover:bg-sky-50'; ?> bg-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- 18+ Direct Button in Mobile Drawer -->
    <div class="p-2.5 pb-1">
        <a href="<?php echo esc_url( home_url( '/18-plus/' ) ); ?>" class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100/70 font-bold text-xs tracking-wide transition active:scale-[0.98]">
            <span class="text-sm leading-none" aria-hidden="true">🔞</span>
            <span><?php esc_html_e( '18+ ADULT GROUPS', 'Telegram_Group_Links' ); ?></span>
        </a>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 overflow-y-auto" aria-label="<?php esc_attr_e( 'Mobile Menu Links', 'Telegram_Group_Links' ); ?>">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'flex flex-col p-2 space-y-0.5',
            'walker'         => class_exists('WP_Custom_Navwalker') ? new WP_Custom_Navwalker() : '',
            'fallback_cb'    => false,
        ));
        ?>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('menuBtn');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOverlay = document.getElementById('menuOverlay');

    function openMenu() {
        if (!mobileMenu || !menuOverlay) return;
        mobileMenu.classList.remove('hidden');
        mobileMenu.classList.add('flex');
        menuOverlay.classList.remove('hidden');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
    }

    function hideMenu() {
        if (!mobileMenu || !menuOverlay) return;
        mobileMenu.classList.add('hidden');
        mobileMenu.classList.remove('flex');
        menuOverlay.classList.add('hidden');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
    }

    if (menuBtn) menuBtn.addEventListener('click', openMenu);
    if (closeMenu) closeMenu.addEventListener('click', hideMenu);
    if (menuOverlay) menuOverlay.addEventListener('click', hideMenu);
});
</script>
