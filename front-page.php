<?php
/**
 * The Front Page Template - TeleGroupsor
 * Optimized for Technical SEO, Crawl Budget, Semantic Structure, and Core Web Vitals.
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
    ? 'bg-gradient-to-r from-[#f43f5e] via-[#e11d48] to-[#be123c] hover:from-[#e11d48] hover:to-[#9f1239] shadow-rose-500/20 group-hover:shadow-rose-500/35'
    : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] hover:from-[#1c86ba] hover:to-[#16729e] shadow-sky-500/20 group-hover:shadow-sky-500/35';

$theme_search_focus = $is_members
    ? 'hover:border-rose-300 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-500/15'
    : 'hover:border-[#229ed9]/50 focus-within:border-[#229ed9] focus-within:ring-2 focus-within:ring-[#229ed9]/15';

$theme_card_border = $is_members
    ? 'border-rose-100/90 hover:border-rose-400/70 hover:shadow-rose-500/10'
    : 'border-slate-200/80 hover:border-[#229ed9]/60 hover:shadow-sky-500/10';

$theme_title_hover = $is_members
    ? 'group-hover:text-rose-600'
    : 'group-hover:text-[#229ed9]';

$theme_text_accent = $is_members
    ? 'text-rose-600'
    : 'text-[#229ed9]';

$theme_badge_bg = $is_members
    ? 'bg-rose-50 border-rose-200/80 text-rose-700'
    : 'bg-sky-50 border-sky-100 text-[#0088cc]';

// Fallback icon SVG for subscriber/member indicators
$users_icon_svg = '<svg class="w-3.5 h-3.5 opacity-60 inline-block align-middle flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 mt-2 sm:mt-4 mb-16 font-sans">

  <!-- Primary Semantic H1 Header with Visual Styling Preserved -->
  <header id="typing-container" class="text-center py-6 sm:py-10">
    <h1 id="welcome" class="block text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
      <?php echo esc_html( sprintf( __( 'Welcome To %s', 'Telegram_Group_Links' ), get_bloginfo( 'name' ) ) ); ?>
    </h1>
    <span id="typing-text" class="block mt-2 <?php echo esc_attr( $theme_text_accent ); ?> font-semibold text-base sm:text-lg" aria-live="polite"></span>
  </header>

  <!-- Unified Search Form -->
  <form id="search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="max-w-xl mx-auto mb-8 sm:mb-10" role="search">
    <div class="relative flex items-center bg-white/95 backdrop-blur-sm rounded-full border border-gray-200/80 shadow-md shadow-gray-200/50 <?php echo esc_attr( $theme_search_focus ); ?> p-1.5 transition-all duration-300">
      
      <!-- Search Icon -->
      <div class="pl-3.5 text-gray-400 flex-shrink-0" aria-hidden="true">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
        </svg>
      </div>

      <!-- Input Field -->
      <label for="search-input" class="sr-only"><?php esc_html_e( 'Search groups and stickers', 'Telegram_Group_Links' ); ?></label>
      <input
        type="search"
        placeholder="<?php esc_attr_e( 'Search groups, stickers...', 'Telegram_Group_Links' ); ?>"
        name="search"
        id="search-input"
        value="<?php echo isset( $_GET['search'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['search'] ) ) ) : ''; ?>"
        class="w-full bg-transparent border-none text-gray-800 placeholder-gray-400 text-sm px-3 py-1.5 focus:outline-none"
        autocomplete="off"
      >

      <!-- Submit Button -->
      <button
        type="submit"
        class="<?php echo esc_attr( $theme_accent_btn ); ?> active:scale-95 text-white text-sm font-semibold px-5 sm:px-6 py-2 rounded-full cursor-pointer transition-all duration-200 shadow-md flex-shrink-0"
        aria-label="<?php esc_attr_e( 'Submit Search', 'Telegram_Group_Links' ); ?>"
      >
        <?php esc_html_e( 'Search', 'Telegram_Group_Links' ); ?>
      </button>
    </div>
  </form>

  <!-- ========================================================================= -->
  <!-- START: CONTINUOUS CATEGORY MARQUEE WITH STICKY 18+ BUTTON                 -->
  <!-- ========================================================================= -->
  <?php
  $marquee_categories = get_categories( array(
      'orderby'    => 'count',
      'order'      => 'DESC',
      'hide_empty' => true,
      'number'     => 40,
  ) );

  if ( ! empty( $marquee_categories ) && ! is_wp_error( $marquee_categories ) ) :
  ?>
  <div class="relative w-full overflow-hidden mb-10 sm:mb-14 group/marquee focus-within:pause select-none flex items-center" aria-label="<?php esc_attr_e( 'Popular Categories Marquee', 'Telegram_Group_Links' ); ?>">
    
    <!-- Left Gradient Shadow for Edge Fading -->
    <div class="pointer-events-none absolute left-0 top-0 z-10 h-full w-10 sm:w-16 bg-gradient-to-r from-gray-50/90 to-transparent" aria-hidden="true"></div>

    <!-- Scrolling Container -->
    <div class="animate-marquee flex items-center gap-2.5 sm:gap-3 py-2 pr-32 sm:pr-40">
      <?php
      for ( $repeat = 0; $repeat < 2; $repeat++ ) :
        foreach ( $marquee_categories as $cat ) :
          $cat_link  = get_category_link( $cat->term_id );
          $cat_name  = $cat->name;
          $cat_count = $cat->count;
      ?>
        <a href="<?php echo esc_url( $cat_link ); ?>"
           class="inline-flex items-center gap-2 bg-white/95 border <?php echo $is_members ? 'border-rose-100 hover:border-rose-400' : 'border-gray-200/90 hover:border-[#229ED9]'; ?> rounded-full px-3 py-1.5 sm:px-4 sm:py-2 shadow-xs hover:shadow-md hover:scale-[1.02] focus:outline-none transition-all duration-200 shrink-0 group"
           tabindex="0"
           aria-label="<?php echo esc_attr( sprintf( __( '%s category with %d groups', 'Telegram_Group_Links' ), $cat_name, $cat_count ) ); ?>">
          
          <!-- Category Folder Icon -->
          <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 <?php echo esc_attr( $theme_text_accent ); ?> group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
          </svg>

          <!-- Category Name -->
          <span class="text-xs sm:text-sm font-semibold text-gray-700 <?php echo esc_attr( $theme_title_hover ); ?> transition-colors whitespace-nowrap">
            <?php echo esc_html( $cat_name ); ?>
          </span>

          <!-- Count Badge -->
          <span class="<?php echo esc_attr( $theme_badge_bg ); ?> text-[10px] sm:text-xs font-bold px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded-full border group-hover:bg-slate-900 group-hover:text-white transition-colors">
            <?php echo esc_html( number_format_i18n( $cat_count ) ); ?>
          </span>
        </a>
      <?php
        endforeach;
      endfor;
      ?>
    </div>

    <!-- Right Gradient Fade + Sticky 18+ Button Container -->
    <div class="absolute right-0 top-0 bottom-0 z-20 flex items-center pl-6 sm:pl-10 pr-0.5 bg-gradient-to-l from-gray-50 via-gray-50/90 to-transparent">
      <a href="<?php echo esc_url( home_url( '/18-plus/' ) ); ?>"
         class="inline-flex items-center gap-1.5 sm:gap-2 bg-gradient-to-r from-rose-600 via-red-600 to-rose-700 hover:from-rose-700 hover:to-red-800 text-white text-xs sm:text-sm font-extrabold px-3 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-md shadow-rose-600/30 hover:scale-105 transition-all duration-200 border border-rose-400/30 shrink-0">
        <span class="relative flex h-2 w-2" aria-hidden="true">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
        </span>
        <span class="text-sm" aria-hidden="true">🔞</span>
        <span><?php esc_html_e( '18+ ADULT', 'Telegram_Group_Links' ); ?></span>
      </a>
    </div>

  </div>
  <?php endif; ?>

  <?php
  $searchData = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';

  $search_query_args = array();
  if ( '' !== $searchData ) {
      $search_query_args['search'] = $searchData;
  }

  $featured_limit = apply_filters( 'telegroupsor_featured_posts_limit', 20 );
  $featured_args  = array(
      'post_type'           => 'post',
      'posts_per_page'      => $featured_limit,
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
      's'                   => $searchData,
      'tax_query'           => array(
          array(
              'taxonomy' => 'subscription',
              'field'    => 'name',
              'terms'    => 'Featured',
          ),
      ),
  );

  $featured_query = new WP_Query( $featured_args );
  $featured_ids   = ( $featured_query->have_posts() ) ? wp_list_pluck( $featured_query->posts, 'ID' ) : array();
  ?>

  <!-- Featured Section -->
  <?php if ( $featured_query->have_posts() ) : ?>
  <section class="featured-section mb-10 sm:mb-14" aria-label="<?php esc_attr_e( 'Featured Telegram Communities', 'Telegram_Group_Links' ); ?>">
    <div class="featured-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
      <?php
      while ( $featured_query->have_posts() ) :
          $featured_query->the_post();
          $post_id          = get_the_ID();
          $subscriber_count = get_post_meta( $post_id, 'subscriber_count', true );
          $trimmed_title    = wp_trim_words( get_the_title(), 4 );
          $full_title_attr  = the_title_attribute( array( 'echo' => false ) );

          $type_terms = get_the_terms( $post_id, 'type' );
          $type_list  = ( $type_terms && ! is_wp_error( $type_terms ) ) ? wp_list_pluck( $type_terms, 'name' ) : array();
          $type_names = implode( ', ', $type_list );
      ?>
      <div class="featured-card relative bg-amber-50/50 backdrop-blur-sm border-2 border-amber-300/80 rounded-2xl pl-7 pr-3.5 py-3.5 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:border-amber-400 group">

        <!-- Vertical Ribbon Indicator -->
        <div class="absolute left-0 top-0 h-full w-5 flex items-center justify-center border-r border-amber-300/80 bg-amber-100/40 rounded-l-2xl" aria-hidden="true">
          <span class="text-amber-700 text-[8px] font-extrabold tracking-[0.18em] uppercase [writing-mode:vertical-rl] rotate-180">
            <?php esc_html_e( 'Featured', 'Telegram_Group_Links' ); ?>
          </span>
        </div>

        <!-- Link -->
        <a href="<?php the_permalink(); ?>"
           aria-label="<?php echo esc_attr( sprintf( __( 'Open %s details', 'Telegram_Group_Links' ), $full_title_attr ) ); ?>"
           class="absolute top-3 right-3 text-gray-400 hover:text-amber-600 transition-colors p-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>

        <div class="flex items-center gap-3">
          <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full overflow-hidden bg-white border border-amber-200 flex-shrink-0 shadow-sm">
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'thumbnail', array(
                  'class'   => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                  'alt'     => $full_title_attr,
                  'loading' => 'lazy',
                  'decoding'=> 'async',
              ) ); ?>
            <?php else : ?>
              <div class="w-full h-full bg-amber-100 flex items-center justify-center text-amber-500 font-bold text-xs" aria-hidden="true">TG</div>
            <?php endif; ?>
          </div>

          <div class="flex-1 min-w-0 pr-4">
            <a href="<?php the_permalink(); ?>">
              <h3 class="font-bold text-sm sm:text-[15px] text-gray-900 leading-snug line-clamp-2 group-hover:text-amber-700 transition-colors">
                <?php echo esc_html( $trimmed_title ); ?>
              </h3>
            </a>

            <div class="flex items-center gap-1.5 mt-1 text-xs text-gray-500 font-medium">
              <?php echo $users_icon_svg; ?>
              <span>
                <?php echo esc_html( $subscriber_count ? number_format_i18n( (int) $subscriber_count ) : '0' ); ?>
                <?php if ( ! empty( $type_names ) ) : ?>
                  <span class="mx-1 text-gray-300" aria-hidden="true">|</span>
                  <span class="text-amber-800/80"><?php echo esc_html( $type_names ); ?></span>
                <?php endif; ?>
              </span>
            </div>
          </div>
        </div>
      </div>
      <?php
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Standard Cards Grid Section -->
  <section aria-label="<?php esc_attr_e( 'Telegram Groups and Channels Listing', 'Telegram_Group_Links' ); ?>">
    <div class="card-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
      <?php
      $paged_non_premium = isset( $_GET['ppageid'] ) ? max( 1, absint( $_GET['ppageid'] ) ) : 1;

      $blogs_args = array(
        'post_type'           => 'post',
        'posts_per_page'      => 20,
        'paged'               => $paged_non_premium,
        's'                   => $searchData,
        'ignore_sticky_posts' => true,
      );

      if ( ! empty( $featured_ids ) ) {
          $blogs_args['post__not_in'] = $featured_ids;
      }

      $blog_posts = new WP_Query( $blogs_args );
      if ( $blog_posts->have_posts() ) {
        while ( $blog_posts->have_posts() ) {
          $blog_posts->the_post();
          $post_id         = get_the_ID();
          $full_title_attr = the_title_attribute( array( 'echo' => false ) );

          $type_terms = get_the_terms( $post_id, 'type' );
          $type_list  = ( $type_terms && ! is_wp_error( $type_terms ) ) ? wp_list_pluck( $type_terms, 'name' ) : array();
          $type_names = implode( ', ', $type_list );

          $categories     = get_the_category( $post_id );
          $category_names = ( $categories && ! is_wp_error( $categories ) ) ? wp_list_pluck( $categories, 'name' ) : array();
          $category_list  = implode( ', ', $category_names );

          $subscription_terms = get_the_terms( $post_id, 'subscription' );
          $subscription_name  = ( ! empty( $subscription_terms ) && ! is_wp_error( $subscription_terms ) ) ? $subscription_terms[0]->name : '';

          $subscriber_count = get_post_meta( $post_id, 'subscriber_count', true );
          $trimmed_title    = wp_trim_words( get_the_title(), 4 );
          ?>
          <article class="card bg-white/95 backdrop-blur-sm border-2 <?php echo esc_attr( $theme_card_border ); ?> rounded-2xl p-4 sm:p-5 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div>
              <?php if ( ! empty( $subscription_name ) ) : ?>
                <span class="inline-block <?php echo esc_attr( $theme_badge_bg ); ?> text-[11px] font-bold tracking-wide uppercase px-2.5 py-0.5 rounded-md mb-3 border">
                  <?php echo esc_html( $subscription_name ); ?>
                </span>
              <?php endif; ?>

              <div class="flex items-start gap-3.5">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-xl overflow-hidden <?php echo $is_members ? 'bg-rose-50/50 border-rose-100' : 'bg-gray-50 border-gray-100'; ?> border flex-shrink-0">
                  <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'thumbnail', array(
                        'class'    => 'w-full h-full object-cover group-hover:scale-105 transition duration-300',
                        'alt'      => $full_title_attr,
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                    ) ); ?>
                  <?php else : ?>
                    <div class="w-full h-full <?php echo $is_members ? 'bg-rose-50 text-rose-400' : 'bg-gray-100 text-gray-400'; ?> flex items-center justify-center font-bold text-xs" aria-hidden="true">TG</div>
                  <?php endif; ?>
                </div>

                <div class="flex-1 min-w-0">
                  <a href="<?php the_permalink(); ?>">
                    <h3 class="font-bold text-base text-gray-900 line-clamp-2 leading-snug <?php echo esc_attr( $theme_title_hover ); ?> transition-colors">
                      <?php echo esc_html( $trimmed_title ); ?>
                    </h3>
                  </a>

                  <div class="flex flex-wrap gap-1.5 mt-2">
                    <?php if ( ! empty( $type_names ) ) : ?>
                      <span class="bg-gray-100 text-gray-600 text-[11px] font-medium px-2 py-0.5 rounded-md">
                        <?php echo esc_html( $type_names ); ?>
                      </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $category_list ) ) : ?>
                      <span class="<?php echo esc_attr( $theme_badge_bg ); ?> text-[11px] font-semibold px-2 py-0.5 rounded-md border">
                        <?php echo esc_html( $category_list ); ?>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <p class="mt-3.5 text-xs sm:text-sm text-gray-500 line-clamp-2 leading-relaxed">
                <?php echo esc_html( wp_strip_all_tags( wp_trim_words( get_the_excerpt(), 14, '...' ) ) ); ?>
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
              <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                <?php echo $users_icon_svg; ?>
                <span><?php echo esc_html( $subscriber_count ? number_format_i18n( (int) $subscriber_count ) : '0' ); ?></span>
              </div>

              <a href="<?php the_permalink(); ?>"
                 class="<?php echo esc_attr( $theme_text_accent ); ?> text-xs font-bold inline-flex items-center gap-1 group-hover:gap-2 transition-all"
                 aria-label="<?php echo esc_attr( sprintf( __( 'Join %s', 'Telegram_Group_Links' ), $full_title_attr ) ); ?>">
                <?php esc_html_e( 'Join', 'Telegram_Group_Links' ); ?> <span class="text-sm" aria-hidden="true">→</span>
              </a>
            </div>
          </article>
        <?php
        }
      } elseif ( empty( $featured_ids ) ) {
        echo '<p class="col-span-full text-center text-gray-500 py-8">' . esc_html__( 'No posts found matching your criteria.', 'Telegram_Group_Links' ) . '</p>';
      }
      ?>
    </div>

    <!-- Standard Posts Numbered Pagination -->
    <?php
    $posts_total_pages = (int) $blog_posts->max_num_pages;

    if ( $posts_total_pages > 1 ) :
    ?>
    <nav class="posts-pagination flex items-center justify-center flex-wrap gap-2 mb-12 sm:mb-16 font-sans" aria-label="<?php esc_attr_e( 'Telegram Groups Pagination', 'Telegram_Group_Links' ); ?>">
      <?php if ( $paged_non_premium > 1 ) : 
          $prev_link_params = array_merge( array( 'ppageid' => $paged_non_premium - 1 ), $search_query_args );
      ?>
          <a href="<?php echo esc_url( add_query_arg( $prev_link_params, home_url( '/' ) ) ); ?>"
             class="px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium shadow-xs hover:border-slate-300 transition-all"
             aria-label="<?php esc_attr_e( 'Previous Page', 'Telegram_Group_Links' ); ?>">
              <?php esc_html_e( 'Previous', 'Telegram_Group_Links' ); ?>
          </a>
      <?php endif; ?>

      <?php
      $range = 2;
      for ( $i = 1; $i <= $posts_total_pages; $i++ ) :
          if ( $i == 1 || $i == $posts_total_pages || ( $i >= $paged_non_premium - $range && $i <= $paged_non_premium + $range ) ) :
              $page_link_params = array_merge( array( 'ppageid' => $i ), $search_query_args );
      ?>
          <a href="<?php echo esc_url( add_query_arg( $page_link_params, home_url( '/' ) ) ); ?>"
             aria-label="<?php echo esc_attr( sprintf( __( 'Page %d', 'Telegram_Group_Links' ), $i ) ); ?>"
             <?php if ( $i === $paged_non_premium ) echo 'aria-current="page"'; ?>
             class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl text-xs sm:text-sm font-medium transition-all
                    <?php echo ( $i == $paged_non_premium )
                        ? ( $is_members ? 'bg-gradient-to-r from-rose-500 to-rose-700 text-white shadow-sm font-bold' : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] text-white shadow-sm font-bold' )
                        : 'bg-white border border-gray-200 text-gray-700 hover:border-slate-300'; ?>">
              <?php echo esc_html( number_format_i18n( $i ) ); ?>
          </a>
      <?php
          elseif ( $i == $paged_non_premium - $range - 1 || $i == $paged_non_premium + $range + 1 ) :
      ?>
          <span class="px-1 text-gray-400 text-xs" aria-hidden="true">…</span>
      <?php
          endif;
      endfor;
      ?>

      <?php if ( $paged_non_premium < $posts_total_pages ) : 
          $next_link_params = array_merge( array( 'ppageid' => $paged_non_premium + 1 ), $search_query_args );
      ?>
          <a href="<?php echo esc_url( add_query_arg( $next_link_params, home_url( '/' ) ) ); ?>"
             class="px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium shadow-xs hover:border-slate-300 transition-all"
             aria-label="<?php esc_attr_e( 'Next Page', 'Telegram_Group_Links' ); ?>">
              <?php esc_html_e( 'Next', 'Telegram_Group_Links' ); ?>
          </a>
      <?php endif; ?>
    </nav>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  </section>

  <!-- Categories Widget Area -->
  <div class="term-description mb-10 sm:mb-14 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">
    <?php dynamic_sidebar( 'Categories Section' ); ?>
  </div>

  <!-- Popular Categories Section -->
  <section aria-labelledby="popular-categories">
    <h2 id="popular-categories" class="text-lg sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 tracking-tight">
      <?php esc_html_e( 'Popular Categories', 'Telegram_Group_Links' ); ?>
    </h2>
    <div class="container grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5 mb-12 sm:mb-16">
      <?php
      $categories = get_categories( array( 'number' => 12, 'orderby' => 'count', 'order' => 'DESC' ) );

      if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
          foreach ( $categories as $category ) {
              $cat_id        = $category->term_id;
              $image_url     = get_term_meta( $cat_id, 'category_image', true );
              $category_link = get_category_link( $cat_id );

              if ( ! $image_url ) {
                  $image_url = get_template_directory_uri() . '/images/category-placeholder.png';
              }
      ?>
        <a href="<?php echo esc_url( $category_link ); ?>" class="group" aria-label="<?php echo esc_attr( sprintf( __( 'Explore %s Telegram groups', 'Telegram_Group_Links' ), $category->name ) ); ?>">
          <div class="flex items-center gap-3 bg-white/95 backdrop-blur-sm border-2 <?php echo esc_attr( $theme_card_border ); ?> rounded-2xl p-3 sm:p-4 hover:shadow-md transition-all duration-300">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
              <img src="<?php echo esc_url( $image_url ); ?>"
                   alt="<?php echo esc_attr( $category->name ); ?>"
                   loading="lazy"
                   decoding="async"
                   width="56"
                   height="56"
                   class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            </div>

            <div class="flex-1 min-w-0">
              <h3 class="font-bold text-xs sm:text-sm text-gray-900 <?php echo esc_attr( $theme_title_hover ); ?> transition-colors truncate">
                <?php echo esc_html( $category->name ); ?>
              </h3>
              <p class="text-[11px] sm:text-xs text-gray-400 font-medium">
                <?php echo esc_html( sprintf( _n( '%s Group', '%s Groups', $category->count, 'Telegram_Group_Links' ), number_format_i18n( $category->count ) ) ); ?>
              </p>
            </div>

            <div class="<?php echo esc_attr( $theme_text_accent ); ?> text-base font-bold group-hover:translate-x-1 transition-transform hidden sm:block" aria-hidden="true">
              →
            </div>
          </div>
        </a>
      <?php
          }
      } else {
          echo '<p class="col-span-full text-center text-gray-500 py-4">' . esc_html__( 'No categories found.', 'Telegram_Group_Links' ) . '</p>';
      }
      ?>
    </div>
  </section>

  <!-- Sticker Widget Area -->
  <div class="term-description mb-10 sm:mb-14 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">
    <?php dynamic_sidebar( 'Sticker Section' ); ?>
  </div>

  <!-- Sticker Section -->
  <section aria-labelledby="sticker-section">
    <h2 id="sticker-section" class="text-lg sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 tracking-tight">
      <?php esc_html_e( 'Sticker Sets', 'Telegram_Group_Links' ); ?>
    </h2>
    <div class="stickers-gallery grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6 mb-8">
      <?php
      $sticker_args = array(
        'post_type'      => 'sticker',
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
      );

      $stickers_query = new WP_Query( $sticker_args );
      if ( $stickers_query->have_posts() ) {
        while ( $stickers_query->have_posts() ) {
          $stickers_query->the_post();
          $sticker_title       = get_the_title();
          $sticker_description = wp_trim_words( get_the_content(), 4, '...' );
          $sticker_url         = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium' ) : '';

          $sticker_links     = get_the_terms( get_the_ID(), 'sticker_link' );
          $sticker_link_name = ( $sticker_links && ! is_wp_error( $sticker_links ) ) ? $sticker_links[0]->name : '#';
          ?>
          <div class="sticker bg-white/95 backdrop-blur-sm rounded-2xl border-2 <?php echo esc_attr( $theme_card_border ); ?> shadow-xs hover:shadow-md transition-all duration-300 overflow-hidden group flex flex-col justify-between">
            <div class="relative <?php echo $is_members ? 'bg-rose-50/50' : 'bg-gray-50/70'; ?> p-4 sm:p-6 flex items-center justify-center min-h-[110px]">
              <?php if ( ! empty( $sticker_url ) ) : ?>
                <img src="<?php echo esc_url( $sticker_url ); ?>"
                     alt="<?php echo esc_attr( $sticker_title ); ?>"
                     loading="lazy"
                     decoding="async"
                     width="96"
                     height="96"
                     class="w-20 h-20 sm:w-24 sm:h-24 object-contain group-hover:scale-105 transition-transform duration-300">
              <?php else : ?>
                <span class="text-3xl" aria-hidden="true">🎨</span>
              <?php endif; ?>
            </div>

            <div class="p-3.5 sm:p-5 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="text-sm sm:text-base font-bold text-gray-900 line-clamp-1 <?php echo esc_attr( $theme_title_hover ); ?> transition-colors">
                  <?php echo esc_html( $sticker_title ); ?>
                </h3>

                <p class="mt-1 text-xs text-gray-500 line-clamp-2">
                  <?php echo esc_html( wp_strip_all_tags( $sticker_description ) ); ?>
                </p>
              </div>

              <a href="<?php echo esc_url( $sticker_link_name ); ?>"
                 target="_blank"
                 rel="noopener noreferrer"
                 class="mt-4 inline-flex items-center text-xs sm:text-sm font-bold <?php echo esc_attr( $theme_text_accent ); ?> gap-1 group-hover:gap-2 transition-all">
                <?php esc_html_e( 'Add Sticker', 'Telegram_Group_Links' ); ?> <span aria-hidden="true">→</span>
              </a>
            </div>
          </div>
      <?php
        }
      } else {
        echo '<p class="col-span-full text-center text-gray-500 py-4">' . esc_html__( 'No stickers found.', 'Telegram_Group_Links' ) . '</p>';
      }
      wp_reset_postdata();
      ?>
    </div>

    <div class="text-center mb-12 sm:mb-16">
      <?php 
      $stickers_archive = get_post_type_archive_link( 'sticker' );
      $stickers_link    = $stickers_archive ? $stickers_archive : home_url( '/stickers/' );
      ?>
      <a href="<?php echo esc_url( $stickers_link ); ?>"
         class="inline-flex items-center gap-2 px-6 py-2.5 sm:px-8 sm:py-3 rounded-full bg-white/95 border border-gray-200 text-xs sm:text-sm text-gray-800 <?php echo $is_members ? 'hover:border-rose-400 hover:text-rose-600' : 'hover:border-[#229ED9] hover:text-[#229ED9]'; ?> font-semibold transition-all shadow-xs hover:shadow-md">
        <?php esc_html_e( 'Explore All Stickers', 'Telegram_Group_Links' ); ?> <span aria-hidden="true">→</span>
      </a>
    </div>
  </section>

  <!-- Blog Widget Area -->
  <div class="term-description mb-10 sm:mb-14 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">
    <?php dynamic_sidebar( 'Blog Section' ); ?>
  </div>

  <!-- Blog Section -->
  <section aria-label="<?php esc_attr_e( 'Latest Articles and Guides', 'Telegram_Group_Links' ); ?>">
    <div class="blog-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
      <?php
      $paged_blog = isset( $_GET['bpageid'] ) ? max( 1, absint( $_GET['bpageid'] ) ) : 1;

      $args = array(
          'post_type'           => 'blog',
          'posts_per_page'      => 8,
          'paged'               => $paged_blog,
          'ignore_sticky_posts' => true,
      );

      $query = new WP_Query( $args );

      if ( $query->have_posts() ) :
          while ( $query->have_posts() ) :
              $query->the_post();
              $blog_id         = get_the_ID();
              $full_title_attr = the_title_attribute( array( 'echo' => false ) );
      ?>
        <article class="blog bg-white/95 backdrop-blur-sm rounded-2xl border-2 <?php echo esc_attr( $theme_card_border ); ?> shadow-xs hover:shadow-lg transition-all duration-300 overflow-hidden group flex flex-col justify-between">
          <div>
            <div class="overflow-hidden bg-gray-100 h-36 sm:h-40">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array(
                    'class'    => 'w-full h-full object-cover group-hover:scale-105 transition duration-300',
                    'alt'      => $full_title_attr,
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                ) ); ?>
              <?php else : ?>
                <div class="w-full h-full flex items-center justify-center text-gray-400 font-medium text-xs" aria-hidden="true">
                  <?php esc_html_e( 'No preview', 'Telegram_Group_Links' ); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="p-4 sm:p-5">
              <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="text-[11px] <?php echo esc_attr( $theme_text_accent ); ?> font-bold uppercase tracking-wider">
                <?php echo esc_html( get_the_date( 'M d, Y' ) ); ?>
              </time>

              <h3 class="mt-1.5 text-sm sm:text-base font-bold text-gray-900 line-clamp-2 leading-snug <?php echo esc_attr( $theme_title_hover ); ?> transition-colors">
                <a href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
              </h3>

              <p class="mt-2 text-xs sm:text-sm text-gray-500 line-clamp-2 leading-relaxed">
                <?php echo esc_html( wp_strip_all_tags( wp_trim_words( get_the_excerpt(), 14, '...' ) ) ); ?>
              </p>
            </div>
          </div>

          <div class="px-4 pb-4 sm:px-5 sm:pb-5">
            <a href="<?php the_permalink(); ?>"
               class="inline-flex items-center text-xs sm:text-sm <?php echo esc_attr( $theme_text_accent ); ?> font-bold gap-1 group-hover:gap-2 transition-all"
               aria-label="<?php echo esc_attr( sprintf( __( 'Read article: %s', 'Telegram_Group_Links' ), $full_title_attr ) ); ?>">
              <?php esc_html_e( 'Read Article', 'Telegram_Group_Links' ); ?> <span aria-hidden="true">→</span>
            </a>
          </div>
        </article>
      <?php
          endwhile;
      else :
          echo '<p class="col-span-full text-center text-gray-500 py-4">' . esc_html__( 'No blog posts found.', 'Telegram_Group_Links' ) . '</p>';
      endif;
      ?>
    </div>

    <!-- Blog Pagination -->
    <?php
    $blog_total_pages = (int) $query->max_num_pages;

    if ( $blog_total_pages > 1 ) :
    ?>
    <nav class="blog-pagination flex items-center justify-center flex-wrap gap-2 mb-12 sm:mb-16" aria-label="<?php esc_attr_e( 'Blog Pagination', 'Telegram_Group_Links' ); ?>">
      <?php if ( $paged_blog > 1 ) : 
          $prev_b_params = array_merge( array( 'bpageid' => $paged_blog - 1 ), $search_query_args );
      ?>
          <a href="<?php echo esc_url( add_query_arg( $prev_b_params, home_url( '/' ) ) . '#popular-categories' ); ?>"
             class="px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium shadow-xs hover:border-slate-300 transition-all"
             aria-label="<?php esc_attr_e( 'Previous Articles', 'Telegram_Group_Links' ); ?>">
              <?php esc_html_e( 'Previous', 'Telegram_Group_Links' ); ?>
          </a>
      <?php endif; ?>

      <?php
      $range = 2;
      for ( $i = 1; $i <= $blog_total_pages; $i++ ) :
          if ( $i == 1 || $i == $blog_total_pages || ( $i >= $paged_blog - $range && $i <= $paged_blog + $range ) ) :
              $page_b_params = array_merge( array( 'bpageid' => $i ), $search_query_args );
      ?>
          <a href="<?php echo esc_url( add_query_arg( $page_b_params, home_url( '/' ) ) . '#popular-categories' ); ?>"
             aria-label="<?php echo esc_attr( sprintf( __( 'Blog Page %d', 'Telegram_Group_Links' ), $i ) ); ?>"
             <?php if ( $i === $paged_blog ) echo 'aria-current="page"'; ?>
             class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl text-xs sm:text-sm font-medium transition-all
                    <?php echo ( $i == $paged_blog )
                        ? ( $is_members ? 'bg-gradient-to-r from-rose-500 to-rose-700 text-white shadow-sm font-bold' : 'bg-gradient-to-r from-[#229ed9] to-[#1a8abe] text-white shadow-sm font-bold' )
                        : 'bg-white border border-gray-200 text-gray-700 hover:border-slate-300'; ?>">
              <?php echo esc_html( number_format_i18n( $i ) ); ?>
          </a>
      <?php
          elseif ( $i == $paged_blog - $range - 1 || $i == $paged_blog + $range + 1 ) :
      ?>
          <span class="px-1 text-gray-400 text-xs" aria-hidden="true">…</span>
      <?php
          endif;
      endfor;
      ?>

      <?php if ( $paged_blog < $blog_total_pages ) : 
          $next_b_params = array_merge( array( 'bpageid' => $paged_blog + 1 ), $search_query_args );
      ?>
          <a href="<?php echo esc_url( add_query_arg( $next_b_params, home_url( '/' ) ) . '#popular-categories' ); ?>"
             class="px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium shadow-xs hover:border-slate-300 transition-all"
             aria-label="<?php esc_attr_e( 'Next Articles', 'Telegram_Group_Links' ); ?>">
              <?php esc_html_e( 'Next', 'Telegram_Group_Links' ); ?>
          </a>
      <?php endif; ?>
    </nav>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  </section>

  <!-- Footer Sidebar Area -->
  <div class="term-description mb-10 sm:mb-14 <?php echo $is_members ? '[&_a]:text-rose-600' : '[&_a]:text-[#229ed9]'; ?>">
    <?php dynamic_sidebar( 'Primary Sidebar' ); ?>
  </div>

</main>

<?php get_footer(); ?>