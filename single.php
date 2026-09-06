<?php
/**
 * Single Post Template - TeleGroupsor
 * Optimized for Technical SEO, Core Web Vitals, Schema Alignment, and Accessibility.
 *
 * @package Telegram_Group_Links
 */

get_header();
?>



<main class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 mt-2 sm:mt-4 mb-16 font-sans">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        $post_id          = get_the_ID();
        $full_title_attr  = the_title_attribute( array( 'echo' => false ) );
        $subscriber_count = get_post_meta( $post_id, 'subscriber_count', true );

        // Detect 18+ status
        $is_members = function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( $post_id );

        // Detect Featured status
        $main_subscription_terms = get_the_terms( $post_id, 'subscription' );
        $main_is_featured        = false;
        if ( ! empty( $main_subscription_terms ) && ! is_wp_error( $main_subscription_terms ) ) {
            foreach ( $main_subscription_terms as $main_sub_term ) {
                if ( $main_sub_term->name === 'Featured' ) {
                    $main_is_featured = true;
                    break;
                }
            }
        }

        // Theme dynamic styling
        $primary_accent = $is_members
            ? 'from-[#f43f5e] via-[#e11d48] to-[#be123c]'
            : ( $main_is_featured ? 'from-amber-500 to-amber-600' : 'from-[#229ed9] to-[#1a8abe]' );

        $badge_color = $is_members
            ? 'bg-rose-50 text-rose-600 border-rose-200'
            : ( $main_is_featured ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-sky-50 text-[#229ed9] border-sky-100' );

        $accent_text = $is_members
            ? 'text-rose-600'
            : ( $main_is_featured ? 'text-amber-600' : 'text-[#229ed9]' );

        $hero_border = $is_members
            ? 'border-rose-100/90'
            : 'border-slate-200/80';

        $pill_bg = $is_members
            ? 'bg-rose-50/50 border-rose-100/70'
            : 'bg-slate-50/80 border-slate-100';

        $tag_hover = $is_members
            ? 'hover:text-rose-600 hover:border-rose-300'
            : 'hover:text-[#229ed9] hover:border-[#229ed9]/50';

        // Dynamic Action Button Label Logic
        $action_type_terms = get_the_terms( $post_id, 'type' );
        $action_type_raw   = ( ! empty( $action_type_terms ) && ! is_wp_error( $action_type_terms ) ) ? $action_type_terms[0]->name : 'Group';

        $clean_type = trim( str_ireplace( 'telegram', '', $action_type_raw ) );
        if ( empty( $clean_type ) ) {
            $clean_type = 'Group';
        }

        if ( strcasecmp( $clean_type, 'bot' ) === 0 ) {
            $button_label = __( 'Open Bot', 'Telegram_Group_Links' );
        } else {
            $button_label = sprintf( __( 'Join %s', 'Telegram_Group_Links' ), ucfirst( strtolower( $clean_type ) ) );
        }

        // Author identification
        $admin_terms = get_the_terms( $post_id, 'admin-name' );
        $author_name = ( ! empty( $admin_terms ) && ! is_wp_error( $admin_terms ) ) ? $admin_terms[0]->name : get_the_author();
    ?>

        <!-- Breadcrumb Navigation -->
        <nav aria-label="<?php esc_attr_e( 'Breadcrumbs', 'Telegram_Group_Links' ); ?>" class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6 tracking-wide uppercase overflow-x-auto whitespace-nowrap">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-slate-800 transition">
                <?php esc_html_e( 'Home', 'Telegram_Group_Links' ); ?>
            </a>
            <span aria-hidden="true">/</span>
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="hover:text-slate-800 transition">' . esc_html( $categories[0]->name ) . '</a><span aria-hidden="true">/</span>';
            }
            ?>
            <span class="<?php echo esc_attr( $accent_text ); ?> font-extrabold line-clamp-1" aria-current="page"><?php the_title(); ?></span>
        </nav>

        <!-- Main Article Container -->
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <!-- Main Hero Card -->
            <header class="relative bg-white/95  border-2 <?php echo esc_attr( $hero_border ); ?> rounded-2xl p-6 sm:p-8 transition-all duration-300 overflow-hidden mb-8">
                <!-- Ambient Glow Decoration -->
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-gradient-to-br <?php echo esc_attr( $primary_accent ); ?> opacity-10 blur-3xl rounded-full pointer-events-none" aria-hidden="true"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-6 sm:gap-8">

                    <!-- Group Avatar & Action Button -->
                    <div class="flex flex-col items-center gap-4 flex-shrink-0 w-full sm:w-48">

                        <div class="tgt-avatar-wrap relative w-32 h-32 sm:w-40 sm:h-40 rounded-full p-0.5 group flex-shrink-0">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr <?php echo esc_attr( $primary_accent ); ?> opacity-90" aria-hidden="true"></div>
                            <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-white bg-white">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', array(
                                        'class'    => 'w-full h-full object-cover',
                                        'alt'      => $full_title_attr,
                                        'loading'  => 'eager',
                                        'decoding' => 'async',
                                    ) ); ?>
                                <?php else : ?>
                                    <div class="w-full h-full <?php echo $is_members ? 'bg-rose-50 text-rose-400' : 'bg-slate-100 text-slate-400'; ?> flex items-center justify-center font-bold text-base" aria-hidden="true">TG</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php
                        // Group Join Direct Link
                        $terms = get_the_terms( $post_id, 'group-link' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            foreach ( $terms as $term ) {
                                $raw_url = trim( $term->name );
                                if ( ! empty( $raw_url ) ) {
                                    $link_href = ( strpos( $raw_url, 'http' ) === 0 ) ? $raw_url : 'https://' . $raw_url;
                                    ?>
                                    <a href="<?php echo esc_url( $link_href ); ?>"
                                       target="_blank"
                                       rel="nofollow noopener noreferrer"
                                       class="w-full bg-gradient-to-r <?php echo esc_attr( $primary_accent ); ?> hover:opacity-95 text-white font-bold text-xs sm:text-sm py-2.5 px-5 rounded-xl active:scale-95 transition-all flex items-center justify-center gap-2 text-center shadow-md"
                                       aria-label="<?php echo esc_attr( $button_label . ' - ' . $full_title_attr ); ?>">
                                        <span><?php echo esc_html( $button_label ); ?></span>
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                        </svg>
                                    </a>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>

                    <!-- Group Meta Details -->
                    <div class="flex-1 w-full text-center md:text-left min-w-0">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5 mb-2">
                            <?php
                            $title_type_terms = get_the_terms( $post_id, 'type' );
                            $title_type_name  = ( ! empty( $title_type_terms ) && ! is_wp_error( $title_type_terms ) ) ? $title_type_terms[0]->name : 'Telegram Group';

                            $title_link_terms = get_the_terms( $post_id, 'group-link' );
                            $has_group_link   = ( ! empty( $title_link_terms ) && ! is_wp_error( $title_link_terms ) );
                            ?>

                            <!-- Semantic H1 Title Tag -->
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                                <?php the_title(); ?> <?php echo esc_html( $title_type_name ); ?><?php if ( $has_group_link ) : ?> <?php esc_html_e( 'Link', 'Telegram_Group_Links' ); ?><?php endif; ?>
                            </h1>

                            <?php if ( $is_members ) : ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase border <?php echo esc_attr( $badge_color ); ?>">
                                    <span aria-hidden="true">🔞</span> <?php esc_html_e( '18+ Adult', 'Telegram_Group_Links' ); ?>
                                </span>
                            <?php elseif ( $main_is_featured ) : ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase border <?php echo esc_attr( $badge_color ); ?>">
                                    <span aria-hidden="true">★</span> <?php esc_html_e( 'Featured', 'Telegram_Group_Links' ); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Date and Author Meta -->
                        <p class="text-xs text-slate-400 mb-5 font-medium">
                            <?php esc_html_e( 'Published on', 'Telegram_Group_Links' ); ?>
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="font-semibold text-slate-600">
                                <?php echo esc_html( get_the_date() ); ?>
                            </time>
                            <?php if ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) : ?>
                                · <?php esc_html_e( 'Updated on', 'Telegram_Group_Links' ); ?>
                                <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>" class="font-semibold text-slate-600">
                                    <?php echo esc_html( get_the_modified_date() ); ?>
                                </time>
                            <?php endif; ?>
                            · <?php esc_html_e( 'By', 'Telegram_Group_Links' ); ?>
                            <span class="<?php echo esc_attr( $accent_text ); ?> font-bold">
                                <?php echo esc_html( $author_name ); ?>
                            </span>
                        </p>

                        <!-- Information Pills Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mb-5 text-left">
                            <div class="<?php echo esc_attr( $pill_bg ); ?> rounded-xl p-2.5 sm:p-3 min-w-0 border">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5"><?php esc_html_e( 'Category', 'Telegram_Group_Links' ); ?></span>
                                <span class="text-xs font-semibold text-slate-800 line-clamp-1 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#0088cc]'; ?>">
                                    <?php echo get_the_category_list( ', ' ); ?>
                                </span>
                            </div>
                            <div class="<?php echo esc_attr( $pill_bg ); ?> rounded-xl p-2.5 sm:p-3 min-w-0 border">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5"><?php esc_html_e( 'Type', 'Telegram_Group_Links' ); ?></span>
                                <span class="text-xs font-semibold text-slate-800 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#0088cc]'; ?>">
                                    <?php
                                    $type_out = get_the_term_list( $post_id, 'type', '', ', ' );
                                    echo ! empty( $type_out ) && ! is_wp_error( $type_out ) ? $type_out : esc_html__( 'Telegram Group', 'Telegram_Group_Links' );
                                    ?>
                                </span>
                            </div>
                            <div class="<?php echo esc_attr( $pill_bg ); ?> rounded-xl p-2.5 sm:p-3 min-w-0 border">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5"><?php esc_html_e( 'Country', 'Telegram_Group_Links' ); ?></span>
                                <span class="text-xs font-semibold text-slate-800 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#0088cc]'; ?>">
                                    <?php
                                    $country_out = get_the_term_list( $post_id, 'country', '', ', ' );
                                    echo ! empty( $country_out ) && ! is_wp_error( $country_out ) ? $country_out : esc_html__( 'Global', 'Telegram_Group_Links' );
                                    ?>
                                </span>
                            </div>
                            <div class="<?php echo esc_attr( $pill_bg ); ?> rounded-xl p-2.5 sm:p-3 min-w-0 border">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5"><?php esc_html_e( 'Language', 'Telegram_Group_Links' ); ?></span>
                                <span class="text-xs font-semibold text-slate-800 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#0088cc]'; ?>">
                                    <?php
                                    $lang_out = get_the_term_list( $post_id, 'language', '', ', ' );
                                    echo ! empty( $lang_out ) && ! is_wp_error( $lang_out ) ? $lang_out : esc_html__( 'English', 'Telegram_Group_Links' );
                                    ?>
                                </span>
                            </div>
                            <div class="<?php echo esc_attr( $pill_bg ); ?> rounded-xl p-2.5 sm:p-3 col-span-2 sm:col-span-1 min-w-0 border">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5"><?php esc_html_e( 'Members', 'Telegram_Group_Links' ); ?></span>
                                <span class="text-xs font-semibold text-slate-800">
                                    <?php echo esc_html( $subscriber_count ? number_format_i18n( (int) $subscriber_count ) : __( 'Active Community', 'Telegram_Group_Links' ) ); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Associated Post Tags -->
                        <div class="flex flex-wrap justify-center md:justify-start gap-1">
                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) {
                                foreach ( $tags as $tag ) {
                                    echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="bg-slate-100 text-slate-600 text-[10px] font-medium px-2.5 py-0.5 rounded-md border border-slate-200/60 ' . esc_attr( $tag_hover ) . ' transition">#' . esc_html( $tag->name ) . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Content & Guidelines Stack -->
            <div class="flex flex-col gap-6 mb-8 w-full min-w-0">

                <!-- Description Card -->
                <section class="w-full bg-white/95  border-2 <?php echo esc_attr( $hero_border ); ?> rounded-2xl p-5 sm:p-6 min-w-0">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <span class="w-2 h-5 rounded-full bg-gradient-to-b <?php echo esc_attr( $primary_accent ); ?>" aria-hidden="true"></span>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight"><?php esc_html_e( 'Description', 'Telegram_Group_Links' ); ?></h2>
                    </div>
                    <div class="entry-content term-description text-sm sm:text-base text-slate-600 leading-relaxed font-normal space-y-3 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#0088cc]'; ?>">
                        <?php the_content(); ?>
                    </div>
                </section>

                <?php
                /**
                 * Dynamic "About This Telegram Community" content block.
                 *
                 * Purpose: give every individual listing substantially more unique,
                 * page-specific editorial text - built primarily from the post's own
                 * description (get_the_content()), with category/type/country/
                 * language/tags folded in only as light supporting context. None of
                 * the pill/tag facts already shown in the hero card above are
                 * restated here as standalone statements. Nothing is fabricated:
                 * every sentence either comes from the description itself or from
                 * taxonomy/meta data that actually exists on the post.
                 *
                 * Note on "expand and explain": this template has no AI/LLM call
                 * available (by design - no external API, no added HTTP request),
                 * so it cannot generate independent analysis of the description.
                 * What it does instead is restructure the post's own description
                 * into readable, framed paragraphs, using taxonomy data only as
                 * brief supporting asides rather than duplicate fact statements.
                 *
                 * Reuses variables already computed earlier in this template
                 * ($post_id, $categories, $tags, $title_type_name, $hero_border,
                 * $primary_accent) instead of running duplicate queries.
                 */

                $about_type_source = ! empty( $title_type_name ) ? $title_type_name : 'Group';
                $about_type_lower  = trim( str_ireplace( 'telegram', '', strtolower( $about_type_source ) ) );
                if ( empty( $about_type_lower ) ) {
                    $about_type_lower = 'group';
                }

                // Normalize to one of Group / Channel / Bot for heading + archive link logic.
                if ( false !== strpos( $about_type_lower, 'channel' ) ) {
                    $about_type_key = 'channel';
                } elseif ( false !== strpos( $about_type_lower, 'bot' ) ) {
                    $about_type_key = 'bot';
                } else {
                    $about_type_key = 'group';
                }

                $about_type_label = ucfirst( $about_type_key );

                // Archive URL is selected dynamically based on the actual `type` taxonomy.
                $about_archive_urls = array(
                    'channel' => 'https://telegroupsor.link/type/channel/',
                    'group'   => 'https://telegroupsor.link/type/group/',
                    'bot'     => 'https://telegroupsor.link/type/bot/',
                );
                $about_archive_url = $about_archive_urls[ $about_type_key ];

                // Bold, blue, linked "Telegram group/channel/bot" phrase used inline
                // inside the About body text, pointing at the same dynamic archive URL.
                $about_type_link = sprintf(
                    '<a href="%1$s" class="font-bold text-[#0088cc] hover:text-[#006699] transition-colors">%2$s</a>',
                    esc_url( $about_archive_url ),
                    esc_html( sprintf( __( 'Telegram %s', 'Telegram_Group_Links' ), $about_type_lower ) )
                );

                $about_title = get_the_title();

                $about_category_names = ( ! empty( $categories ) && ! is_wp_error( $categories ) ) ? wp_list_pluck( $categories, 'name' ) : array();

                $about_country_terms = get_the_terms( $post_id, 'country' );
                $about_country_names = ( ! empty( $about_country_terms ) && ! is_wp_error( $about_country_terms ) ) ? wp_list_pluck( $about_country_terms, 'name' ) : array();

                $about_language_terms = get_the_terms( $post_id, 'language' );
                $about_language_names = ( ! empty( $about_language_terms ) && ! is_wp_error( $about_language_terms ) ) ? wp_list_pluck( $about_language_terms, 'name' ) : array();

                $about_tag_names = ( ! empty( $tags ) && ! is_wp_error( $tags ) ) ? wp_list_pluck( $tags, 'name' ) : array();

                // Pull the raw description and break it into sentences so it can be
                // presented as flowing, framed paragraphs instead of one static block.
                $about_content_raw = wp_strip_all_tags( get_the_content() );
                $about_content_raw = trim( preg_replace( '/\s+/', ' ', $about_content_raw ) );

                $about_sentences = array();
                if ( '' !== $about_content_raw ) {
                    $about_sentences = preg_split( '/(?<=[.!?])\s+(?=[A-Z0-9"\'])/', $about_content_raw, -1, PREG_SPLIT_NO_EMPTY );
                    $about_sentences = array_values( array_filter( array_map( 'trim', $about_sentences ) ) );
                }
                $about_sentence_count = count( $about_sentences );

                $about_paragraphs = array();

                // Lead paragraph is built as escaped-safe HTML (not plain text) so the
                // "Telegram group/channel/bot" phrase can be rendered as the bold,
                // blue, linked $about_type_link markup built above. All dynamic text
                // around it (title, sentences) is individually esc_html()'d before
                // being placed into the string, so nothing unsafe is introduced.
                if ( $about_sentence_count > 0 ) {

                    // Lead paragraph: the type is folded in once, naturally, to introduce
                    // the description rather than stated as a standalone fact.
                    $about_lead_sentences = array_slice( $about_sentences, 0, min( 2, $about_sentence_count ) );
                    $about_lead_html      = sprintf(
                        __( 'As a %1$s, %2$s describes itself this way: %3$s', 'Telegram_Group_Links' ),
                        $about_type_link,
                        '<strong>' . esc_html( $about_title ) . '</strong>',
                        esc_html( implode( ' ', $about_lead_sentences ) )
                    );

                    // Continue with the rest of the description, in manageable chunks,
                    // so longer descriptions get more than one paragraph.
                    if ( $about_sentence_count > 2 ) {
                        $about_remaining = array_slice( $about_sentences, 2 );
                        while ( ! empty( $about_remaining ) ) {
                            $about_chunk      = array_splice( $about_remaining, 0, 3 );
                            $about_paragraphs[] = sprintf(
                                __( 'The description goes on to add: %s', 'Telegram_Group_Links' ),
                                esc_html( implode( ' ', $about_chunk ) )
                            );
                        }
                    }
                } else {
                    // No usable description text - say so plainly instead of inventing detail.
                    $about_lead_html = sprintf(
                        __( '%1$s is listed here as a %2$s. No further description has been provided for this listing beyond what is shown above.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $about_title ) . '</strong>',
                        $about_type_link
                    );
                }

                // Publishing context, from real post data only (author term / WP author,
                // and the actual publish/update dates already used elsewhere on this
                // page) - adds genuine, page-specific detail for search engines rather
                // than restating the same boilerplate on every listing.
                $about_published_date = get_the_date();
                $about_modified_date  = ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) ? get_the_modified_date() : '';

                if ( ! empty( $about_modified_date ) ) {
                    $about_paragraphs[] = sprintf(
                        __( 'This listing was published by %1$s on %2$s and last updated on %3$s.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $author_name ) . '</strong>',
                        '<strong>' . esc_html( $about_published_date ) . '</strong>',
                        '<strong>' . esc_html( $about_modified_date ) . '</strong>'
                    );
                } else {
                    $about_paragraphs[] = sprintf(
                        __( 'This listing was published by %1$s on %2$s.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $author_name ) . '</strong>',
                        '<strong>' . esc_html( $about_published_date ) . '</strong>'
                    );
                }

                // Practical "how to join" sentence, only shown when a real join link
                // exists on the post (reuses $has_group_link / $button_label already
                // computed above the hero card - no duplicate query, no invented URL).
                if ( $has_group_link ) {
                    $about_action_verb  = ( strcasecmp( $about_type_lower, 'bot' ) === 0 )
                        ? __( 'open', 'Telegram_Group_Links' )
                        : __( 'join', 'Telegram_Group_Links' );
                    $about_paragraphs[] = sprintf(
                        __( 'Ready to get involved? Use the "%1$s" button above to %2$s this Telegram %3$s directly.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $button_label ) . '</strong>',
                        esc_html( $about_action_verb ),
                        '<strong>' . esc_html( $about_type_lower ) . '</strong>'
                    );
                }

                // Supporting context, folded into a single closing sentence rather than
                // restated as separate facts - only included where it adds something
                // beyond what the pills and tags above already show. Category, country,
                // and language are each linked to their own real archive URL (built
                // from the actual term objects already fetched above - no invented
                // URLs), so a click takes the user to everything else in that term.
                $about_category_links = array();
                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                    foreach ( $categories as $about_cat_term ) {
                        $about_cat_term_url = get_category_link( $about_cat_term->term_id );
                        if ( $about_cat_term_url && ! is_wp_error( $about_cat_term_url ) ) {
                            $about_category_links[] = '<a href="' . esc_url( $about_cat_term_url ) . '" class="font-bold text-[#0088cc] hover:text-[#006699] transition-colors">' . esc_html( $about_cat_term->name ) . '</a>';
                        }
                    }
                }

                $about_country_links = array();
                if ( ! empty( $about_country_terms ) && ! is_wp_error( $about_country_terms ) ) {
                    foreach ( $about_country_terms as $about_country_term ) {
                        $about_country_term_url = get_term_link( $about_country_term );
                        if ( $about_country_term_url && ! is_wp_error( $about_country_term_url ) ) {
                            $about_country_links[] = '<a href="' . esc_url( $about_country_term_url ) . '" class="font-bold text-[#0088cc] hover:text-[#006699] transition-colors">' . esc_html( $about_country_term->name ) . '</a>';
                        }
                    }
                }

                $about_language_links = array();
                if ( ! empty( $about_language_terms ) && ! is_wp_error( $about_language_terms ) ) {
                    foreach ( $about_language_terms as $about_language_term ) {
                        $about_language_term_url = get_term_link( $about_language_term );
                        if ( $about_language_term_url && ! is_wp_error( $about_language_term_url ) ) {
                            $about_language_links[] = '<a href="' . esc_url( $about_language_term_url ) . '" class="font-bold text-[#0088cc] hover:text-[#006699] transition-colors">' . esc_html( $about_language_term->name ) . '</a>';
                        }
                    }
                }

                $about_context_bits = array();
                if ( ! empty( $about_category_links ) ) {
                    $about_context_bits[] = sprintf( __( 'its %s category', 'Telegram_Group_Links' ), implode( '/', $about_category_links ) );
                }
                if ( ! empty( $about_country_links ) ) {
                    $about_context_bits[] = sprintf( __( 'its %s association', 'Telegram_Group_Links' ), implode( '/', $about_country_links ) );
                }
                if ( ! empty( $about_language_links ) ) {
                    $about_context_bits[] = sprintf( __( 'content mainly in %s', 'Telegram_Group_Links' ), implode( '/', $about_language_links ) );
                }
                if ( ! empty( $about_tag_names ) ) {
                    $about_context_bits[] = sprintf( __( 'its %s tags', 'Telegram_Group_Links' ), '<strong>' . esc_html( implode( ', ', array_slice( $about_tag_names, 0, 3 ) ) ) . '</strong>' );
                }

                if ( ! empty( $about_context_bits ) ) {
                    $about_paragraphs[] = sprintf(
                        __( 'Reading the description above alongside %s can help you confirm this particular %s matches what you are looking for before you join.', 'Telegram_Group_Links' ),
                        implode( ', ', $about_context_bits ),
                        '<strong>' . esc_html( $about_type_lower ) . '</strong>'
                    );
                }

                // Closing sentence: uses only real values that exist on the post -
                // actual title, actual subscriber count (with existing fallback),
                // and actual language taxonomy (with existing fallback). Nothing invented.
                $about_closing_subscribers = $subscriber_count
                    ? number_format_i18n( (int) $subscriber_count )
                    : __( 'Active Community', 'Telegram_Group_Links' );

                $about_closing_language = ! empty( $about_language_names )
                    ? implode( '/', $about_language_names )
                    : __( 'English', 'Telegram_Group_Links' );

                if ( $subscriber_count ) {
                    $about_closing_sentence = sprintf(
                        /* translators: 1: post title, 2: subscriber count, 3: language */
                        __( 'This %1$s community has around %2$s subscribers and primarily shares content in %3$s, making it a useful destination for users interested in this type of Telegram community.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $about_title ) . '</strong>',
                        '<strong>' . esc_html( $about_closing_subscribers ) . '</strong>',
                        '<strong>' . esc_html( $about_closing_language ) . '</strong>'
                    );
                } else {
                    $about_closing_sentence = sprintf(
                        /* translators: 1: post title, 2: fallback community status, 3: language */
                        __( 'This %1$s community is an %2$s and primarily shares content in %3$s, making it a useful destination for users interested in this type of Telegram community.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $about_title ) . '</strong>',
                        '<strong>' . esc_html( $about_closing_subscribers ) . '</strong>',
                        '<strong>' . esc_html( $about_closing_language ) . '</strong>'
                    );
                }

                // Independent-listing disclaimer, added after everything else: uses
                // the real post title and the real type (group/channel/bot) only.
                // "REPORT" links to the site's real contact page, bold and blue to
                // match the other links in this section.
                $about_report_link = '<a href="' . esc_url( 'https://telegroupsor.link/contact-us/' ) . '" class="font-bold text-[#0088cc] hover:text-[#006699] transition-colors">' . esc_html__( 'REPORT', 'Telegram_Group_Links' ) . '</a>';

                $about_disclaimer_sentence = sprintf(
                    __( 'When accessing the %1$s Telegram %2$s, please be aware that you must agree to all group rules. It\'s also important to note that this is an independent %2$s, with no connection to our website, and the group administrator is solely responsible for it. If you find anything inappropriate, click %3$s to report this %2$s.', 'Telegram_Group_Links' ),
                    '<strong>' . esc_html( $about_title ) . '</strong>',
                    esc_html( $about_type_lower ),
                    $about_report_link
                );

                // Only render the section when there is something real to show.
                $about_has_content = ! empty( $about_lead_html );

                if ( $about_has_content ) :
                    ?>
                    <section class="w-full bg-white/95  border-2 <?php echo esc_attr( $hero_border ); ?> rounded-2xl p-5 sm:p-6 min-w-0">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                            <span class="w-2 h-5 rounded-full bg-gradient-to-b <?php echo esc_attr( $primary_accent ); ?>" aria-hidden="true"></span>
                            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">
                                <a href="<?php echo esc_url( $about_archive_url ); ?>" class="text-[#0088cc] font-extrabold hover:text-[#006699] transition-colors">
                                    <?php
                                    echo esc_html(
                                        sprintf(
                                            /* translators: %s: Telegram entity type, e.g. Group, Channel, Bot */
                                            __( 'About This Telegram %s', 'Telegram_Group_Links' ),
                                            $about_type_label
                                        )
                                    );
                                    ?>
                                </a>
                            </h2>
                        </div>
                        <div class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal space-y-3">
                            <?php if ( ! empty( $about_lead_html ) ) : ?>
                                <p><?php echo wp_kses_post( $about_lead_html ); ?></p>
                            <?php endif; ?>
                            <?php foreach ( $about_paragraphs as $about_paragraph ) : ?>
                                <?php if ( ! empty( $about_paragraph ) ) : ?>
                                    <p><?php echo wp_kses_post( $about_paragraph ); ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <p class="font-medium text-slate-700"><?php echo wp_kses_post( $about_closing_sentence ); ?></p>
                            <?php if ( ! empty( $about_disclaimer_sentence ) ) : ?>
                                <p class="text-xs text-slate-500 border-t border-slate-100 pt-3 mt-1"><?php echo wp_kses_post( $about_disclaimer_sentence ); ?></p>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Group Guidelines Card (styled to match "About This Telegram Group" card) -->
                <section class="w-full bg-white/95  border-2 <?php echo esc_attr( $hero_border ); ?> rounded-2xl p-5 sm:p-6 min-w-0">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <span class="w-2 h-5 rounded-full bg-gradient-to-b <?php echo esc_attr( $primary_accent ); ?>" aria-hidden="true"></span>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">
                            <?php esc_html_e( 'Group Guidelines', 'Telegram_Group_Links' ); ?>
                        </h2>
                    </div>
                    <div class="term-description text-sm sm:text-base text-slate-600 leading-relaxed font-normal space-y-3 [&_strong]:text-slate-800 [&_strong]:font-semibold [&_p]:mb-0 <?php echo $is_members ? '[&_a]:font-bold [&_a]:text-rose-600 [&_a]:hover:text-rose-700 [&_a]:transition-colors' : '[&_a]:font-bold [&_a]:text-[#0088cc] [&_a]:hover:text-[#006699] [&_a]:transition-colors'; ?>">
                        <div class="tgt-ad-wrapper">
                            <?php dynamic_sidebar( 'rules-widget' ); ?>
                        </div>
                    </div>
                </section>

                <!-- Share Card -->
                <section class="w-full bg-white/95  border-2 <?php echo esc_attr( $hero_border ); ?> rounded-2xl p-5 text-center min-w-0">
                    <h2 class="text-sm font-extrabold text-slate-900 mb-1"><?php esc_html_e( 'Share With Friends', 'Telegram_Group_Links' ); ?></h2>
                    <p class="text-[11px] text-slate-400 mb-4"><?php esc_html_e( 'Help others discover this community', 'Telegram_Group_Links' ); ?></p>
                    <div class="tgt-ad-wrapper flex justify-center items-center">
                        <?php dynamic_sidebar( 'share-widget' ); ?>
                    </div>
                </section>

            </div>

        </article>

    <?php endwhile; endif; ?>

</main>

<!-- Related Groups Section -->
<section id="related-groups" class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 mb-16" aria-labelledby="related-groups-title">
    <div class="flex items-center justify-between mb-6">
        <h2 id="related-groups-title" class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
            <?php echo ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) ) ? esc_html__( 'Related 18+ Groups', 'Telegram_Group_Links' ) : esc_html__( 'Related Groups', 'Telegram_Group_Links' ); ?>
        </h2>
        <div class="h-px <?php echo ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) ) ? 'bg-rose-200' : 'bg-slate-200'; ?> flex-1 ml-6" aria-hidden="true"></div>
    </div>

    <!-- Related Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <?php
        $curr_categories = get_the_category();
        $curr_cat_ids    = ! empty( $curr_categories ) ? wp_list_pluck( $curr_categories, 'term_id' ) : array();

        $curr_tags    = get_the_tags();
        $curr_tag_ids = ! empty( $curr_tags ) ? wp_list_pluck( $curr_tags, 'term_id' ) : array();

        $tax_query = array( 'relation' => 'OR' );

        if ( ! empty( $curr_cat_ids ) ) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $curr_cat_ids,
            );
        }

        if ( ! empty( $curr_tag_ids ) ) {
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $curr_tag_ids,
            );
        }

        $blogs_args = array(
            'post_type'           => 'post',
            'posts_per_page'      => 8,
            'post__not_in'        => array( get_the_ID() ),
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        );

        if ( count( $tax_query ) > 1 ) {
            $blogs_args['tax_query'] = $tax_query;
        }

        if ( function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( get_the_ID() ) ) {
            $blogs_args['tgt_members_query'] = 'only';
        }

        $blog_posts = new WP_Query( $blogs_args );

        if ( $blog_posts->have_posts() ) :
            while ( $blog_posts->have_posts() ) : $blog_posts->the_post();
                $rel_id            = get_the_ID();
                $rel_title_attr    = the_title_attribute( array( 'echo' => false ) );
                $subscriber_count  = get_post_meta( $rel_id, 'subscriber_count', true );
                $trimmed_title     = wp_trim_words( get_the_title(), 4 );

                $rel_is_members    = function_exists( 'tgt_is_members_only_post' ) && tgt_is_members_only_post( $rel_id );
                $rel_sub_terms     = get_the_terms( $rel_id, 'subscription' );
                $rel_is_featured   = false;
                if ( ! empty( $rel_sub_terms ) && ! is_wp_error( $rel_sub_terms ) ) {
                    foreach ( $rel_sub_terms as $rel_sub ) {
                        if ( $rel_sub->name === 'Featured' ) {
                            $rel_is_featured = true;
                            break;
                        }
                    }
                }

                $rel_card_border = $rel_is_members
                    ? 'border-rose-100/90 hover:border-rose-400/70'
                    : 'border-slate-200/80 hover:border-[#229ed9]/60';

                $rel_avatar_glow = $rel_is_members
                    ? 'bg-gradient-to-tr from-rose-500 to-pink-300'
                    : 'bg-gradient-to-tr from-[#229ed9] to-sky-300';

                $rel_title_hover = $rel_is_members
                    ? 'group-hover:text-rose-600'
                    : 'group-hover:text-[#229ed9]';

                $rel_sub_icon = $rel_is_members
                    ? 'text-rose-500'
                    : 'text-[#229ed9]';

                $rel_badge_bg = $rel_is_members
                    ? 'bg-rose-50/80 border-rose-100/80 text-rose-700'
                    : 'bg-sky-50 border-sky-100 text-[#229ed9]';

                $rel_btn_accent = $rel_is_members
                    ? 'bg-gradient-to-r from-[#f43f5e] via-[#e11d48] to-[#be123c] hover:from-[#e11d48] hover:to-[#9f1239]'
                    : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] hover:from-[#1c86ba] hover:to-[#16729e]';

                $type_terms = get_the_terms( $rel_id, 'type' );
                $type_list  = ( $type_terms && ! is_wp_error( $type_terms ) ) ? wp_list_pluck( $type_terms, 'name' ) : array();

                $categories     = get_the_category( $rel_id );
                $category_names = ! empty( $categories ) ? wp_list_pluck( $categories, 'name' ) : array();

                $rel_subscription_terms = get_the_terms( $rel_id, 'subscription' );
                $rel_subscription_name  = ( ! empty( $rel_subscription_terms ) && ! is_wp_error( $rel_subscription_terms ) ) ? $rel_subscription_terms[0]->name : '';
        ?>
                <!-- Related Article Card -->
                <article class="card relative bg-white/95  rounded-2xl hover:-translate-y-1.5 transition-all duration-300 border-2 <?php echo esc_attr( $rel_card_border ); ?> flex flex-col justify-between p-4 h-full min-w-0 group overflow-hidden sm:items-center sm:text-center">

                    <?php if ( ! empty( $rel_subscription_name ) ) : ?>
                        <span class="featured absolute top-2.5 right-2.5 sm:right-auto sm:left-2.5 bg-gradient-to-r <?php echo $rel_is_members ? 'from-rose-500 to-rose-700' : 'from-[#229ed9] to-[#0088cc]'; ?> text-white text-[9.5px] font-bold tracking-wider uppercase px-2.5 py-0.5 rounded-full z-10">
                            <?php echo esc_html( $rel_subscription_name ); ?>
                        </span>
                    <?php endif; ?>

                    <div class="w-full flex flex-row sm:flex-col items-center sm:items-center flex-1 min-w-0 gap-3.5 sm:gap-0">

                        <!-- Avatar -->
                        <div class="tgt-card-avatar relative w-16 h-16 sm:w-24 sm:h-24 rounded-full p-0.5 sm:mt-1 sm:mb-2.5 flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 rounded-full <?php echo esc_attr( $rel_avatar_glow ); ?> opacity-80 group-hover:opacity-100 transition-opacity" aria-hidden="true"></div>
                            <div class="relative w-full h-full rounded-full overflow-hidden border-2 border-white bg-white">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail', array(
                                        'class'    => 'w-full h-full object-cover',
                                        'alt'      => $rel_title_attr,
                                        'loading'  => 'lazy',
                                        'decoding' => 'async',
                                    ) ); ?>
                                <?php else : ?>
                                    <div class="w-full h-full <?php echo $rel_is_members ? 'bg-rose-50 text-rose-400' : 'bg-slate-100 text-slate-400'; ?> flex items-center justify-center font-bold text-xs" aria-hidden="true">TG</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Information Block -->
                        <div class="flex-1 min-w-0 text-left sm:text-center w-full">
                            <a href="<?php the_permalink(); ?>" class="block w-full mb-1">
                                <h3 class="text-sm sm:text-[15px] font-extrabold text-slate-800 line-clamp-1 leading-snug <?php echo esc_attr( $rel_title_hover ); ?> transition-colors">
                                    <?php echo esc_html( $trimmed_title ); ?>
                                </h3>
                            </a>

                            <div class="subscribers inline-flex items-center gap-1.5 bg-slate-50/90 border border-slate-100 px-2.5 py-0.5 rounded-full text-slate-500 text-[11px] mb-2 sm:mb-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 <?php echo esc_attr( $rel_sub_icon ); ?> flex-shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                                </svg>
                                <span><b class="text-slate-800 font-bold"><?php echo esc_html( $subscriber_count ? number_format_i18n( (int) $subscriber_count ) : '0' ); ?></b> <em class="not-italic text-slate-400"><?php esc_html_e( 'Subscribers', 'Telegram_Group_Links' ); ?></em></span>
                            </div>

                            <div class="tags flex flex-wrap items-center sm:justify-center gap-1 mb-2 sm:mb-2.5 max-h-12 overflow-hidden">
                                <?php foreach ( $type_list as $t_name ) : ?>
                                    <span class="bg-slate-100 text-slate-600 text-[10px] font-medium px-2 py-0.5 rounded-md border border-slate-200/60"><?php echo esc_html( $t_name ); ?></span>
                                <?php endforeach; ?>
                                <?php foreach ( $category_names as $cat_name ) : ?>
                                    <span class="<?php echo esc_attr( $rel_badge_bg ); ?> text-[10px] font-semibold px-2 py-0.5 rounded-md border"><?php echo esc_html( $cat_name ); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <p class="description text-slate-500 text-xs leading-relaxed line-clamp-2 break-words mb-2 sm:mb-3">
                                <?php echo esc_html( wp_strip_all_tags( wp_trim_words( get_the_content(), 11 ) ) ); ?>
                            </p>
                        </div>

                    </div>

                    <!-- Action Link -->
                    <a href="<?php the_permalink(); ?>"
                       class="w-full mt-2 sm:mt-auto pt-1 block"
                       aria-label="<?php echo esc_attr( sprintf( __( 'Join %s', 'Telegram_Group_Links' ), $rel_title_attr ) ); ?>">
                        <span class="w-full <?php echo esc_attr( $rel_btn_accent ); ?> active:scale-[0.98] text-white font-bold text-xs sm:text-sm rounded-xl py-2 flex items-center justify-center gap-1.5 transition-all shadow-md">
                            <?php esc_html_e( 'Join Now', 'Telegram_Group_Links' ); ?>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </a>

                </article>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>

<?php
/**
 * Directory Guide section - genuine, evergreen, type-aware value content
 * placed after Related Groups purely for search-engine and reader value.
 * Reuses variables already built in the About block above ($about_title,
 * $about_type_lower, $about_type_label, $about_category_links,
 * $about_country_links, $about_language_links) rather than re-querying,
 * since those were snapshotted before the Related Groups loop reassigned
 * $categories/$subscriber_count for each related post. Nothing here is
 * post-specific data that could be fabricated - it's general guidance
 * plus real links to this post's own category/country/language archives.
 */
if ( ! empty( $post_id ) ) :
    ?>
    <section id="directory-guide" class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 mb-16" aria-labelledby="directory-guide-title">
        <h2 id="directory-guide-title" class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mb-4">
            <?php
            echo esc_html(
                sprintf(
                    /* translators: %s: Telegram entity type, e.g. Group, Channel, Bot */
                    __( 'Finding and Joining Telegram %ss on TeleGroupsor', 'Telegram_Group_Links' ),
                    $about_type_label
                )
            );
            ?>
        </h2>

        <div class="text-base sm:text-lg text-slate-600 leading-relaxed space-y-4">
            <?php
            $about_submit_link = '<a href="' . esc_url( 'https://telegroupsor.link/submit-link/' ) . '" class="font-bold text-[#0088cc] hover:text-[#006699] transition-colors">' . esc_html__( 'submitted', 'Telegram_Group_Links' ) . '</a>';
            ?>
            <p>
                <?php
                echo wp_kses_post(
                    sprintf(
                        /* translators: 1: post title, 2: linked word "submitted" */
                        __( 'TeleGroupsor is a directory built to help people discover active Telegram groups, channels, and bots across a wide range of interests, without having to search Telegram itself or rely on outdated invite links shared in forums. Every listing on this site, including %1$s, is %2$s with its own description, category, and language so visitors can quickly judge whether it fits what they are looking for before tapping join.', 'Telegram_Group_Links' ),
                        '<strong>' . esc_html( $about_title ) . '</strong>',
                        $about_submit_link
                    )
                );
                ?>
            </p>

            <?php
            $guide_links = array();
            if ( ! empty( $about_category_links ) ) {
                $guide_links[] = implode( '/', $about_category_links );
            }
            if ( ! empty( $about_country_links ) ) {
                $guide_links[] = implode( '/', $about_country_links );
            }
            if ( ! empty( $about_language_links ) ) {
                $guide_links[] = implode( '/', $about_language_links );
            }
            ?>
            <?php if ( ! empty( $guide_links ) ) : ?>
                <p>
                    <?php
                    echo wp_kses_post(
                        sprintf(
                            __( 'If %1$s is not quite what you are after, you can browse more listings in %2$s to compare similar communities before deciding which one to join.', 'Telegram_Group_Links' ),
                            '<strong>' . esc_html( $about_title ) . '</strong>',
                            implode( ', ', $guide_links )
                        )
                    );
                    ?>
                </p>
            <?php endif; ?>

            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight mt-2">
                <?php esc_html_e( 'Tips for Joining Telegram Groups and Channels Safely', 'Telegram_Group_Links' ); ?>
            </h3>
            <?php
            $safety_tips = array(
                __( 'Read the group or channel description and pinned rules before participating, since each community sets its own guidelines.', 'Telegram_Group_Links' ),
                __( 'Avoid sharing personal information such as your phone number, address, or financial details in public groups.', 'Telegram_Group_Links' ),
                __( 'Remember that every listing is run independently by its own admin and is not affiliated with or verified by this website.', 'Telegram_Group_Links' ),
                __( "If a group, channel, or bot violates Telegram's terms or posts inappropriate content, use the report link on its listing page.", 'Telegram_Group_Links' ),
                __( 'You can leave any group or channel at any time directly from the Telegram app if it no longer suits your interests.', 'Telegram_Group_Links' ),
            );
            ?>
            <div role="list" class="flex flex-col gap-2.5">
                <?php foreach ( $safety_tips as $safety_tip ) : ?>
                    <div role="listitem" class="flex flex-row flex-nowrap items-start gap-3">
                        <span style="margin-top:9px;" class="block w-2 h-2 rounded-full bg-[#229ed9] flex-shrink-0" aria-hidden="true"></span>
                        <span class="flex-1"><?php echo esc_html( $safety_tip ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>