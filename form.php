<?php
/**
 * Template Name: Custom Form
 * Description: Interactive frontend form for submitting Telegram channels, groups, and bots.
 *
 * @package Telegram_Group_Links
 */

// Handle AJAX Publishing Endpoint directly
if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'tgt_ajax_publish_listing' ) {
    tgt_handle_ajax_publish_submission();
    exit;
}

function tgt_handle_ajax_publish_submission() {
    // Clear any previous output buffer cleanly
    while ( ob_get_level() > 0 ) {
        ob_end_clean();
    }

    if ( ! headers_sent() ) {
        header( 'Content-Type: application/json; charset=UTF-8' );
    }

    $nonce = $_POST['custom_form_nonce'] ?? '';
    if ( ! wp_verify_nonce( $nonce, 'handle_custom_form' ) ) {
        echo wp_json_encode( array(
            'success' => false,
            'code'    => 'security_error',
            'message' => 'Session expired. Please refresh the page and try again.',
        ) );
        exit;
    }

    $group_link = esc_url_raw( $_POST['group_link'] ?? '' );
    if ( empty( $group_link ) || ( strpos( $group_link, 't.me/' ) === false && strpos( $group_link, 'telegram.me/' ) === false ) ) {
        echo wp_json_encode( array(
            'success' => false,
            'code'    => 'invalid_url',
            'message' => 'Please enter a valid Telegram link (e.g., https://t.me/example).',
        ) );
        exit;
    }

    $post_title   = sanitize_text_field( $_POST['post_title'] ?? '' );
    $post_content = sanitize_textarea_field( $_POST['post_content'] ?? '' );
    $tags_raw     = sanitize_text_field( $_POST['tags'] ?? '' );
    $category_id  = intval( $_POST['category'] ?? 0 );
    $country_val  = sanitize_text_field( $_POST['country'] ?? '' );
    $lang_val     = sanitize_text_field( $_POST['language'] ?? '' );
    $type_val     = sanitize_text_field( $_POST['type'] ?? 'Channel' );
    $admin_handle = sanitize_text_field( $_POST['admin'] ?? '' );
    $subscribers  = $_POST['subscribers'] ?? '0';

    if ( empty( $post_title ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_title', 'message' => 'Please enter a name or title.' ) );
        exit;
    }

    if ( empty( $post_content ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_description', 'message' => 'Please add a short description.' ) );
        exit;
    }

    if ( empty( $tags_raw ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_tags', 'message' => 'Please add at least one tag/keyword.' ) );
        exit;
    }

    $is_18_plus_req = ( isset( $_POST['is_18_plus'] ) && $_POST['is_18_plus'] === '1' ) || tgt_contains_adult_content( $post_title . ' ' . $post_content . ' ' . $tags_raw . ' ' . $group_link );

    if ( ! $is_18_plus_req && empty( $category_id ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_category', 'message' => 'Please choose a category.' ) );
        exit;
    }

    if ( empty( $country_val ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_country', 'message' => 'Please choose a country/region.' ) );
        exit;
    }

    if ( empty( $lang_val ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_language', 'message' => 'Please select a language.' ) );
        exit;
    }

    if ( empty( $admin_handle ) ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'missing_admin', 'message' => 'Please enter the admin username.' ) );
        exit;
    }

    if ( $subscribers === '' || intval( $subscribers ) < 0 ) {
        echo wp_json_encode( array( 'success' => false, 'code' => 'invalid_subscribers', 'message' => 'Please enter a valid number of members.' ) );
        exit;
    }

    if ( tgt_is_duplicate_submission( $group_link, $post_title ) ) {
        echo wp_json_encode( array(
            'success' => false,
            'code'    => 'duplicate',
            'message' => 'This Telegram link is already listed on our website!',
        ) );
        exit;
    }

    $post_data = array(
        'post_title'   => $post_title,
        'post_content' => $post_content,
        'post_status'  => 'publish',
        'post_type'    => 'post',
    );

    $post_id = wp_insert_post( $post_data );

    if ( ! $post_id || is_wp_error( $post_id ) ) {
        echo wp_json_encode( array(
            'success' => false,
            'code'    => 'server_error',
            'message' => 'Something went wrong while publishing. Please try again.',
        ) );
        exit;
    }

    if ( ! empty( $category_id ) ) {
        wp_set_post_categories( $post_id, array( $category_id ) );
    }

    if ( ! empty( $type_val ) ) {
        wp_set_object_terms( $post_id, $type_val, 'type' );
    }

    if ( ! empty( $lang_val ) ) {
        wp_set_object_terms( $post_id, $lang_val, 'language' );
    }

    if ( ! empty( $country_val ) ) {
        wp_set_object_terms( $post_id, $country_val, 'country' );
    }

    if ( ! empty( $tags_raw ) ) {
        $tags = array_map( 'sanitize_text_field', explode( ',', $tags_raw ) );
        wp_set_post_tags( $post_id, $tags );
    }

    update_post_meta( $post_id, 'subscriber_count', intval( $subscribers ) );

    // Yoast SEO Variables & Title Structuring
    $type_label  = ! empty( $type_val ) ? ucfirst( strtolower( $type_val ) ) : 'Channel';
    $yoast_title = $post_title . ' Telegram ' . $type_label . ' Link %%sep%% %%sitename%%';

    update_post_meta( $post_id, '_yoast_wpseo_title', sanitize_text_field( $yoast_title ) );
    update_post_meta( $post_id, '_yoast_wpseo_metadesc', sanitize_text_field( $yoast_title . ' ' . $post_content ) );

    if ( ! empty( $admin_handle ) ) {
        wp_set_object_terms( $post_id, $admin_handle, 'admin-name' );
    }
    if ( ! empty( $group_link ) ) {
        wp_set_object_terms( $post_id, $group_link, 'group-link' );
    }

    $image_url = esc_url_raw( $_POST['image_url'] ?? '' );
    if ( ! empty( $image_url ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $image_result = tgt_attach_remote_image_to_post( $image_url, $post_id, $post_title );

        if ( is_wp_error( $image_result ) ) {
            error_log( '[TGT IMAGE ERROR]: ' . $image_result->get_error_message() );
        }
    }

    update_post_meta( $post_id, '_tgt_members_only', $is_18_plus_req ? '1' : '0' );

    echo wp_json_encode( array(
        'success' => true,
        'message' => 'Your group has been successfully added!',
        'post_id' => $post_id,
    ) );
    exit;
}

ob_start();

get_header();

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

function tgt_get_all_world_countries() {
    return array(
       "Worldwide / Global", "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria",
        "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan",
        "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia",
        "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Congo (Democratic Republic)",
        "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador",
        "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France",
        "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau",
        "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland",
        "Israel", "Italy", "Ivory Coast", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait",
        "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg",
        "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico",
        "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru",
        "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman",
        "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal",
        "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe",
        "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia",
        "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria",
        "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey",
        "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu",
        "Vatican City", "Venezuela", "Vietnam",  "Yemen", "Zambia", "Zimbabwe"
    );
}

function tgt_get_all_world_languages() {
    return array(
        "Abkhazian", "Afar", "Afrikaans", "Akan", "Albanian", "Amharic", "Arabic", "Aragonese", "Armenian", "Assamese", "Avaric", "Avestan", "Aymara", "Azerbaijani",
        "Bambara", "Bashkir", "Basque", "Belarusian", "Bengali", "Bihari languages", "Bislama", "Bosnian", "Breton", "Bulgarian", "Burmese",
        "Catalan", "Chamorro", "Chechen", "Chichewa", "Chinese", "Chuvash", "Cornish", "Corsican", "Cree", "Croatian", "Czech",
        "Danish", "Divehi", "Dutch", "Dzongkha",
        "English", "Esperanto", "Estonian", "Ewe",
        "Faroese", "Fijian", "Finnish", "French", "Fula",
        "Galician", "Ganda", "Georgian", "German", "Greek", "Guarani", "Gujarati", "Haitian", "Hausa", "Hebrew", "Herero", "Hindi", "Hiri Motu", "Hungarian",
        "Icelandic", "Ido", "Igbo", "Indonesian", "Interlingua", "Interlingue", "Inuktitut", "Inupiaq", "Irish", "Italian",
        "Japanese", "Javanese",
        "Kalaallisut", "Kannada", "Kanuri", "Kashmiri", "Kazakh", "Khmer", "Kikuyu", "Kinyarwanda", "Kirghiz", "Komi", "Kongo", "Korean", "Kuanyama", "Kurdish",
        "Lao", "Latin", "Latvian", "Letzeburgesch", "Limburgish", "Lingala", "Lithuanian", "Luba-Katanga", "Luganda",
        "Macedonian", "Malagasy", "Malay", "Malayalam", "Maltese", "Manx", "Maori", "Marathi", "Marshallese", "Moldovan", "Mongolian",
        "Nauru", "Navajo", "Ndonga", "Nepali", "Northern Sami", "Norwegian", "Norwegian Bokmal", "Norwegian Nynorsk",
        "Occitan", "Ojibwa", "Oriya", "Oromo", "Ossetian",
        "Pali", "Pashto", "Persian", "Polish", "Portuguese", "Punjabi",
        "Quechua",
        "Romanian", "Romansh", "Rundi", "Russian",
        "Samoan", "Sango", "Sardinian", "Scottish Gaelic", "Serbian", "Shona", "Sichuan Yi", "Sindhi", "Sinhala", "Slovak", "Slovenian", "Somali", "Sotho, Southern", "Spanish", "Sundanese", "Swahili", "Swati", "Swedish",
        "Tagalog", "Tahitian", "Tajik", "Tamil", "Tatar", "Telugu", "Thai", "Tibetan", "Tigrinya", "Tonga", "Tsonga", "Tswana", "Turkish", "Turkmen", "Twi",
        "Uighur", "Ukrainian", "Urdu", "Uzbek",
        "Venda", "Vietnamese", "Volapuk",
        "Walloon", "Welsh", "Western Frisian",
        "Xhosa",
        "Yiddish", "Yoruba",
        "Zhuang", "Zulu", "Other"
    );
}

function tgt_normalize_telegram_url( $url ) {
    if ( empty( $url ) ) return '';
    $clean = trim( $url );
    $clean = preg_replace( '#^https?://#i', '', $clean );
    $clean = preg_replace( '#^(t\.me|telegram\.me)/#i', '', $clean );
    $clean = ltrim( $clean, '@/' );
    $clean = rtrim( $clean, '/' );
    if ( ( $pos = strpos( $clean, '?' ) ) !== false ) $clean = substr( $clean, 0, $pos );
    if ( ( $pos = strpos( $clean, '#' ) ) !== false ) $clean = substr( $clean, 0, $pos );
    return strtolower( trim( $clean ) );
}

function tgt_contains_adult_content( $text ) {
    if ( empty( $text ) ) return false;
    $adult_pattern = '/\b(18\+|18plus|adult|nsfw|sex|sexy|porn|porno|pornstar|strip|stripper|hentai|erotic|xxx|boobs|nude|nudes|boobies|ass|bitch|dick|cock|pussy|vagina|fucker|fuck|fucking|whore|slut|escort|escorts|hookup|dating|dating18|hotgirls|desihot|milf|camgirl|lesbian|gay|callgirl|incest|fetish|leaks|onlyfans|leak|mms|webseries|uncut|desisex|bhabhi|boob|panties|bra|seductive|lust|sensual|orgasm|hardcore|softcore)\b/i';
    return preg_match( $adult_pattern, $text ) === 1;
}

function tgt_attach_remote_image_to_post( $image_url, $post_id, $post_title = '' ) {
    if ( empty( $image_url ) ) {
        return new WP_Error( 'image_empty', 'Image URL is empty.' );
    }

    $image_url = esc_url_raw( html_entity_decode( $image_url ) );
    $temp_source_path = null;
    $final_path        = null;

    try {
        $response = wp_remote_get( $image_url, array(
            'timeout'     => 30,
            'redirection' => 5,
            'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'image_download_failed', 'Failed to download Telegram profile picture.' );
        }

        $response_code = (int) wp_remote_retrieve_response_code( $response );
        if ( 200 !== $response_code ) {
            return new WP_Error( 'image_download_failed', 'Failed to download Telegram profile picture.' );
        }

        $image_data = wp_remote_retrieve_body( $response );
        if ( empty( $image_data ) ) {
            return new WP_Error( 'image_empty_data', 'Image file was empty.' );
        }

        $upload_dir = wp_upload_dir();
        if ( ! empty( $upload_dir['error'] ) ) {
            return new WP_Error( 'upload_dir_error', 'Upload directory is not writable.' );
        }

        $title_slug = sanitize_title( $post_title );
        $title_slug = preg_replace( '/[^a-z0-9-]/i', '-', $title_slug );
        $title_slug = trim( strtolower( preg_replace( '/-+/', '-', $title_slug ) ), '-' );
        if ( empty( $title_slug ) ) {
            $title_slug = 'telegram-image';
        }

        $desired_filename = $title_slug . '-' . $post_id . '.webp';
        $unique_filename   = wp_unique_filename( $upload_dir['path'], $desired_filename );
        $final_path        = $upload_dir['path'] . '/' . $unique_filename;

        $temp_source_path = wp_tempnam( 'tgt-src.tmp' );
        if ( empty( $temp_source_path ) || @file_put_contents( $temp_source_path, $image_data ) === false ) {
            return new WP_Error( 'image_temp_failed', 'Could not process source image.' );
        }

        $editor = wp_get_image_editor( $temp_source_path );
        $converted = false;

        if ( ! is_wp_error( $editor ) ) {
            if ( method_exists( $editor, 'set_quality' ) ) {
                $editor->set_quality( 92 );
            }
            $saved = $editor->save( $final_path, 'image/webp' );
            if ( ! is_wp_error( $saved ) && ! empty( $saved['path'] ) && file_exists( $saved['path'] ) ) {
                $final_path = $saved['path'];
                $converted = true;
            }
        }

        if ( ! $converted && function_exists( 'imagecreatefromstring' ) && function_exists( 'imagewebp' ) ) {
            $src_gd = @imagecreatefromstring( $image_data );
            if ( false !== $src_gd ) {
                imagepalettetotruecolor( $src_gd );
                imagealphablending( $src_gd, false );
                imagesavealpha( $src_gd, true );
                if ( @imagewebp( $src_gd, $final_path, 92 ) ) {
                    $converted = true;
                }
                imagedestroy( $src_gd );
            }
        }

        if ( ! $converted ) {
            return new WP_Error( 'webp_conversion_failed', 'Could not convert image format.' );
        }

        $metadata_title = ! empty( $post_title ) ? $post_title : sanitize_file_name( basename( $final_path ) );
        $attachment = array(
            'post_mime_type' => 'image/webp',
            'post_title'     => $metadata_title,
            'post_status'    => 'inherit',
        );

        $attachment_id = wp_insert_attachment( $attachment, $final_path, $post_id );
        if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
            if ( file_exists( $final_path ) ) @unlink( $final_path );
            return new WP_Error( 'attachment_failed', 'Could not save to media library.' );
        }

        update_post_meta( $attachment_id, '_wp_attachment_image_alt', $metadata_title );
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $final_path );
        if ( ! is_wp_error( $attachment_data ) && ! empty( $attachment_data ) ) {
            wp_update_attachment_metadata( $attachment_id, $attachment_data );
        }

        set_post_thumbnail( $post_id, $attachment_id );
        return $attachment_id;

    } finally {
        if ( ! empty( $temp_source_path ) && file_exists( $temp_source_path ) ) {
            @unlink( $temp_source_path );
        }
    }
}

function tgt_is_duplicate_submission( $group_link, $post_title ) {
    if ( ! empty( $post_title ) ) {
        $existing_post = get_page_by_title( trim( $post_title ), OBJECT, 'post' );
        if ( $existing_post && 'trash' !== $existing_post->post_status ) {
            return true;
        }
    }

    if ( ! empty( $group_link ) ) {
        if ( term_exists( $group_link, 'group-link' ) ) {
            return true;
        }

        $normalized_input = tgt_normalize_telegram_url( $group_link );
        if ( ! empty( $normalized_input ) ) {
            $all_terms = get_terms( array(
                'taxonomy'   => 'group-link',
                'hide_empty' => false,
            ) );

            if ( ! is_wp_error( $all_terms ) && ! empty( $all_terms ) ) {
                foreach ( $all_terms as $term ) {
                    if ( tgt_normalize_telegram_url( $term->name ) === $normalized_input || tgt_normalize_telegram_url( $term->slug ) === $normalized_input ) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}
?>

<style>
    .tgt-theme-input {
        background-color: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 1rem;
        transition: all 0.2s ease;
    }
    .tgt-theme-input:hover {
        border-color: #90cdfa;
    }
    .tgt-theme-input:focus {
        border-color: #229ed9;
        box-shadow: 0 0 0 4px rgba(34, 158, 217, 0.15);
        outline: none;
    }
    .tgt-theme-locked {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        color: #64748b !important;
        cursor: not-allowed;
    }
    .tgt-expand {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 450ms cubic-bezier(0.16, 1, 0.3, 1), opacity 350ms ease;
        opacity: 0;
    }
    .tgt-expand.is-open {
        grid-template-rows: 1fr;
        opacity: 1;
    }
    .tgt-expand > div {
        overflow: hidden;
        min-height: 0;
    }
    .tgt-spinner {
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-top-color: #ffffff;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: tgt-spin 0.6s linear infinite;
    }
    @keyframes tgt-spin {
        to { transform: rotate(360deg); }
    }
    @keyframes tgt-toast-slide {
        from { transform: translateY(16px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-toast-in {
        animation: tgt-toast-slide 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    /* Custom Dropdown Search Styles */
    .tgt-dropdown-wrapper {
        position: relative;
    }
    .tgt-dropdown-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 50;
        background: #ffffff;
        border: 1.5px solid #229ed9;
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(34, 158, 217, 0.15);
        margin-top: 4px;
        display: none;
        max-height: 240px;
        overflow-y: auto;
    }
    .tgt-dropdown-list.active {
        display: block;
    }
    .tgt-dropdown-item {
        padding: 10px 16px;
        font-size: 0.875rem;
        cursor: pointer;
        color: #1e293b;
        transition: background 0.15s ease;
    }
    .tgt-dropdown-item:hover, .tgt-dropdown-item.selected {
        background-color: #edf7fc;
        color: #0088cc;
        font-weight: 600;
    }
</style>

<!-- Floating Toast Container -->
<div id="tgt-toast-container" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[999999] flex flex-col gap-2.5 max-w-[92vw] sm:max-w-md w-full pointer-events-none"></div>

<div class="py-8 sm:py-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center relative z-10 font-sans">

    <!-- Main Shell -->
    <div class="w-full max-w-3xl bg-white/95  rounded-xl border-2 border-sky-100 shadow-sky-500/10 p-6 sm:p-10 transition-all">

        <!-- Header Section -->
        <div class="border-b border-sky-100/90 pb-7 mb-8 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center sm:justify-between gap-3 mb-4">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#edf7fc] border border-sky-200/60 text-[#0088cc] text-xs uppercase tracking-wider font-extrabold shadow-sm shadow-sky-500/5">
                    <span class="w-2 h-2 bg-[#229ed9] rounded-full animate-ping"></span>
                    Instant Submission
                </span>
                <span class="text-xs text-slate-400 font-semibold">Free & Fast Listing</span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Submit Your Telegram Group
            </h1>
            <p class="mt-2 text-sm sm:text-base text-slate-500 font-medium">
                Paste your public group or channel link below. We will automatically fill in the details so you can publish it in seconds.
            </p>
        </div>

        <!-- STEP 1: Link Ingestion -->
        <div class="space-y-3">
            <div class="flex items-center justify-between px-1">
                <label for="tgt-link-input" class="text-xs font-extrabold text-slate-700 uppercase tracking-wide">
                    Step 1: Paste Telegram Link <span class="text-rose-500">*</span>
                </label>
                <span class="text-[11px] font-bold text-sky-600">t.me/yourgroup</span>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 bg-slate-50/70 p-1.5 rounded-2xl sm:rounded-full border border-sky-100 focus-within:border-[#229ed9] focus-within:ring-4 focus-within:ring-[#229ed9]/15 transition-all">
                <div class="flex items-center flex-1 px-3 py-2 sm:py-0">
                    <svg class="w-5 h-5 text-sky-500/70 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <input type="text" id="tgt-link-input" placeholder="https://t.me/example"
                        class="w-full bg-transparent text-sm text-slate-800 placeholder-slate-400 font-medium focus:outline-none py-1">
                </div>
                <button type="button" id="tgt-fetch-btn"
                    class="w-full sm:w-auto px-7 py-3 rounded-xl sm:rounded-full bg-[#229ed9] hover:bg-[#0088cc] text-white text-xs uppercase tracking-wider font-extrabold flex items-center justify-center gap-2 shadow-md shadow-sky-500/20 active:scale-[0.98] transition-all disabled:opacity-50">
                    <span id="tgt-fetch-spinner" class="tgt-spinner hidden"></span>
                    <span id="tgt-fetch-label">Fetch Details</span>
                </button>
            </div>

            <!-- Error Notice -->
            <div id="tgt-fetch-error" class="hidden p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold flex items-center justify-between gap-3 animate-toast-in">
                <span id="tgt-error-text" class="flex-1"></span>
                <button type="button" onclick="document.getElementById('tgt-fetch-error').classList.add('hidden')" class="text-rose-600 hover:text-rose-900 font-bold uppercase text-[10px]">
                    Dismiss
                </button>
            </div>
        </div>

        <!-- STEP 2: Configuration & Details (Expandable) -->
        <div id="tgt-expand" class="tgt-expand">
            <div>
                <form method="post" id="tgt-full-form" class="space-y-6 pt-6 mt-6 border-t border-sky-100">
                    <?php wp_nonce_field( 'handle_custom_form', 'custom_form_nonce' ); ?>

                    <input type="hidden" name="type" id="hidden_type" value="Channel">
                    <input type="hidden" name="is_18_plus" id="hidden_is_18_plus" value="0">

                    <!-- Entity Data Bar -->
                    <div class="rounded-2xl border border-sky-100 p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-[#edf7fc]/50 shadow-sm">
                        <img id="tgt-final-image" src="" alt="Avatar" class="w-14 h-14 rounded-2xl border border-sky-200 object-cover bg-white flex-shrink-0 shadow-sm">
                        <div class="flex-1 min-w-0 space-y-0.5">
                            <span class="text-[10px] text-sky-800 uppercase tracking-widest font-extrabold">Your Link</span>
                            <input type="text" name="group_link" id="tgt-final-link" readonly class="w-full bg-transparent text-xs sm:text-sm font-bold text-slate-800 focus:outline-none cursor-default truncate">
                            <input type="hidden" name="image_url" id="tgt-final-image-url">
                        </div>
                        <span class="text-[11px] font-extrabold px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full border border-emerald-300 uppercase shadow-xs">Connected</span>
                    </div>

                    <!-- 18+ Content Flag Switcher -->
                    <div class="rounded-2xl border border-rose-100 p-4 flex items-center justify-between gap-4 bg-rose-50/40 transition-colors" id="tgt-18plus-container">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold text-rose-950 uppercase">18+ Adult Content</span>
                                <span id="tgt-auto-locked-badge" class="hidden text-[10px] font-extrabold uppercase bg-rose-600 text-white px-2 py-0.5 rounded-full shadow-xs">
                                    Detected
                                </span>
                            </div>
                            <p class="text-xs text-rose-800/80 font-medium mt-0.5">Turn this on if your group contains adult, dating, or mature content.</p>
                        </div>
                        <input type="checkbox" id="tgt-18plus-toggle" class="w-5 h-5 accent-rose-600 cursor-pointer rounded-md">
                    </div>

                    <!-- Input Grid -->
                    <div class="space-y-4">
                        <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wide block pb-1 border-b border-sky-100">
                            Step 2: Review Group Details
                        </span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Title -->
                            <div class="sm:col-span-2 space-y-1.5">
                                <label for="post_title" class="text-xs font-bold text-slate-700 uppercase">Group / Channel Name</label>
                                <input type="text" id="post_title" name="post_title" required readonly
                                    class="tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm font-bold">
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="post_content" class="text-xs font-bold text-slate-700 uppercase">Description</label>
                                    <span id="tgt-desc-unlock-badge" class="hidden text-[10px] font-extrabold text-sky-600 uppercase bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200">Editable</span>
                                </div>
                                <textarea id="post_content" name="post_content" rows="3" required readonly
                                    class="tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm resize-none"></textarea>
                            </div>

                            <!-- Category -->
                            <div id="tgt-category-wrap" class="space-y-1.5">
                                <label for="category" class="text-xs font-bold text-slate-700 uppercase">Category <span class="text-rose-500">*</span></label>
                                <select id="category" name="category" required
                                    class="tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-medium">
                                    <option value="">Choose Category</option>
                                    <?php
                                    $categories = get_categories( array( 'hide_empty' => 0 ) );
                                    foreach ( $categories as $category ) {
                                        echo '<option value="' . esc_attr( $category->term_id ) . '">' . esc_html( $category->name ) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Type -->
                            <div class="space-y-1.5">
                                <label for="type" class="text-xs font-bold text-slate-700 uppercase">Type</label>
                                <select id="type" required disabled
                                    class="tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm font-semibold">
                                    <option value="Channel">Channel</option>
                                    <option value="Group">Group</option>
                                    <option value="Bot">Bot</option>
                                </select>
                            </div>

                            <!-- Country (Searchable Dropdown) -->
                            <div class="space-y-1.5 tgt-dropdown-wrapper">
                                <label for="country_search" class="text-xs font-bold text-slate-700 uppercase">Target Country / Region <span class="text-rose-500">*</span></label>
                                <input type="text" id="country_search" placeholder="Type to search country..." autocomplete="off"
                                    class="tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-medium">
                                <input type="hidden" id="country" name="country" required>
                                <div id="country_dropdown_list" class="tgt-dropdown-list">
                                    <?php
                                    $all_countries = tgt_get_all_world_countries();
                                    foreach ( $all_countries as $country_name ) {
                                        echo '<div class="tgt-dropdown-item" data-value="' . esc_attr( $country_name ) . '">' . esc_html( $country_name ) . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Language (Searchable Dropdown) -->
                            <div class="space-y-1.5 tgt-dropdown-wrapper">
                                <label for="language_search" class="text-xs font-bold text-slate-700 uppercase">Language <span class="text-rose-500">*</span></label>
                                <input type="text" id="language_search" placeholder="Type to search language..." autocomplete="off"
                                    class="tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-medium">
                                <input type="hidden" id="language" name="language" required>
                                <div id="language_dropdown_list" class="tgt-dropdown-list">
                                    <?php
                                    $all_languages = tgt_get_all_world_languages();
                                    foreach ( $all_languages as $lang_name ) {
                                        echo '<div class="tgt-dropdown-item" data-value="' . esc_attr( $lang_name ) . '">' . esc_html( $lang_name ) . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Admin Handle -->
                            <div class="space-y-1.5">
                                <label for="admin" class="text-xs font-bold text-slate-700 uppercase">Owner / Admin Username <span class="text-rose-500">*</span></label>
                                <input type="text" id="admin" name="admin" placeholder="@username" required
                                    class="tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-semibold">
                            </div>

                            <!-- Subscribers -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="subscribers" class="text-xs font-bold text-slate-700 uppercase">Members / Subscribers <span class="text-rose-500">*</span></label>
                                    <span id="tgt-subscribers-lock-badge" class="hidden text-[10px] font-extrabold uppercase"></span>
                                </div>
                                <input type="number" id="subscribers" name="subscribers" required readonly
                                    class="tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm font-bold">
                            </div>

                            <!-- Tags (Required) -->
                            <div class="sm:col-span-2 space-y-1.5">
                                <label for="tags" class="text-xs font-bold text-slate-700 uppercase">Tags / Keywords <span class="text-rose-500">*</span></label>
                                <input type="text" id="tags" name="tags" placeholder="Tech, Crypto, Discussion, News (comma separated)" required
                                    class="tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Final Action Button -->
                    <div class="pt-4">
                        <button type="submit" id="tgt-publish-btn"
                            class="w-full py-4 rounded-2xl bg-[#229ed9] hover:bg-[#0088cc] text-white text-sm font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-sky-500/25 active:scale-[0.99] transition-all cursor-pointer">
                            <span id="tgt-publish-spinner" class="tgt-spinner hidden"></span>
                            <span id="tgt-publish-text">Publish Group Now</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Form Widget Area -->
    <?php if ( is_active_sidebar( 'form-widget' ) ) : ?>
        <div id="tgt-form-widget-area" class="w-full max-w-3xl mt-8">
            <?php dynamic_sidebar( 'form-widget' ); ?>
        </div>
    <?php endif; ?>

</div>

<!-- Modal Output -->
<div id="thankYouPopup" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[99999] items-center justify-center p-4">
    <div class="bg-white rounded-3xl border border-sky-100 shadow-2xl p-8 sm:p-10 max-w-md w-full text-center animate-toast-in">
        <div class="w-16 h-16 bg-[#edf7fc] text-[#0088cc] border border-sky-200 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            🎉
        </div>
        <span class="inline-block text-xs font-extrabold px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full uppercase mb-3">
            Published Successfully!
        </span>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Your Link Is Live</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">Your Telegram group has been added and is now visible to everyone exploring our directory.</p>
        <div class="flex flex-col gap-2.5">
            <button type="button" id="tgt-add-another-btn" class="w-full py-3.5 rounded-xl bg-[#229ed9] hover:bg-[#0088cc] text-white text-xs font-extrabold uppercase tracking-wider shadow-md shadow-sky-500/20 transition">
                Submit Another Link
            </button>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="w-full py-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-extrabold uppercase tracking-wider transition inline-block">
                Back to Home
            </a>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
function showToast(message, type = 'info', duration = 4000) {
    const container = document.getElementById('tgt-toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `pointer-events-auto flex items-center justify-between gap-3 p-4 rounded-2xl border font-sans text-xs font-bold shadow-lg animate-toast-in`;

    if (type === 'success') {
        toast.classList.add('bg-white', 'text-slate-800', 'border-emerald-200');
    } else if (type === 'error') {
        toast.classList.add('bg-rose-600', 'text-white', 'border-rose-700');
    } else if (type === 'warning') {
        toast.classList.add('bg-amber-400', 'text-amber-950', 'border-amber-500');
    } else {
        toast.classList.add('bg-slate-900', 'text-white', 'border-slate-800');
    }

    toast.innerHTML = `
        <span class="flex-1 leading-snug">${message}</span>
        <button type="button" class="text-[10px] uppercase font-extrabold underline ml-2 opacity-80 hover:opacity-100" onclick="this.parentElement.remove()">
            Dismiss
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 250);
    }, duration);
}

document.addEventListener('DOMContentLoaded', function () {
    const linkInput     = document.getElementById('tgt-link-input');
    const fetchBtn      = document.getElementById('tgt-fetch-btn');
    const fetchSpinner  = document.getElementById('tgt-fetch-spinner');
    const fetchLabel    = document.getElementById('tgt-fetch-label');
    const errorBox      = document.getElementById('tgt-fetch-error');
    const errorText     = document.getElementById('tgt-error-text');
    const expandWrap    = document.getElementById('tgt-expand');

    const finalLink      = document.getElementById('tgt-final-link');
    const finalImage     = document.getElementById('tgt-final-image');
    const finalImageUrl  = document.getElementById('tgt-final-image-url');
    const titleField     = document.getElementById('post_title');
    const descField      = document.getElementById('post_content');
    const descBadge      = document.getElementById('tgt-desc-unlock-badge');
    const tagsField      = document.getElementById('tags');
    const subsField      = document.getElementById('subscribers');
    const subsBadge      = document.getElementById('tgt-subscribers-lock-badge');
    const typeSelect     = document.getElementById('type');
    
    // Country Search Elements
    const countrySearch  = document.getElementById('country_search');
    const countryHidden  = document.getElementById('country');
    const countryList    = document.getElementById('country_dropdown_list');
    const countryItems   = countryList.querySelectorAll('.tgt-dropdown-item');

    // Language Search Elements
    const langSearch     = document.getElementById('language_search');
    const langHidden     = document.getElementById('language');
    const langList       = document.getElementById('language_dropdown_list');
    const langItems      = langList.querySelectorAll('.tgt-dropdown-item');

    const adminField     = document.getElementById('admin');
    const hiddenType     = document.getElementById('hidden_type');

    const categoryWrap   = document.getElementById('tgt-category-wrap');
    const categorySelect = document.getElementById('category');

    const toggle18Plus = document.getElementById('tgt-18plus-toggle');
    const hidden18Plus = document.getElementById('hidden_is_18_plus');
    const lockedBadge  = document.getElementById('tgt-auto-locked-badge');

    const fullForm       = document.getElementById('tgt-full-form');
    const publishBtn     = document.getElementById('tgt-publish-btn');
    const publishSpinner = document.getElementById('tgt-publish-spinner');
    const publishText    = document.getElementById('tgt-publish-text');

    const thankYouPopup = document.getElementById('thankYouPopup');
    const addAnotherBtn = document.getElementById('tgt-add-another-btn');

    const REST_ENDPOINT = '<?php echo esc_js( get_rest_url( null, 'tg-tool/v1/check' ) ); ?>';
    const PAGE_ENDPOINT = '<?php echo esc_js( get_permalink() ); ?>';

    const ADULT_REGEX = /\b(18\+|18plus|adult|nsfw|sex|sexy|porn|porno|pornstar|strip|stripper|hentai|erotic|xxx|boobs|nude|nudes|boobies|ass|bitch|dick|cock|pussy|vagina|fucker|fuck|fucking|whore|slut|escort|escorts|hookup|dating|dating18|hotgirls|desihot|milf|camgirl|lesbian|gay|callgirl|incest|fetish|leaks|onlyfans|leak|mms|webseries|uncut|desisex|bhabhi|boob|panties|bra|seductive|lust|sensual|orgasm|hardcore|softcore)\b/i;

    // Country Search & Dropdown Functionality
    countrySearch.addEventListener('focus', function() {
        countryList.classList.add('active');
    });

    countrySearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        countryList.classList.add('active');
        countryItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    countryItems.forEach(item => {
        item.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            countrySearch.value = this.textContent;
            countryHidden.value = val;
            countryList.classList.remove('active');
            countryItems.forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // Language Search & Dropdown Functionality
    langSearch.addEventListener('focus', function() {
        langList.classList.add('active');
    });

    langSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        langList.classList.add('active');
        langItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    langItems.forEach(item => {
        item.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            langSearch.value = this.textContent;
            langHidden.value = val;
            langList.classList.remove('active');
            langItems.forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#country_search') && !e.target.closest('#country_dropdown_list')) {
            countryList.classList.remove('active');
        }
        if (!e.target.closest('#language_search') && !e.target.closest('#language_dropdown_list')) {
            langList.classList.remove('active');
        }
    });

    function updateUiFor18PlusState(is18Plus) {
        if (is18Plus) {
            categoryWrap.classList.add('hidden');
            categorySelect.required = false;
            categorySelect.value = "";
        } else {
            categoryWrap.classList.remove('hidden');
            categorySelect.required = true;
        }
    }

    function checkContentForAdultStuff() {
        const fullContent = (titleField.value + ' ' + descField.value + ' ' + (tagsField.value || '') + ' ' + finalLink.value).toLowerCase();
        const isDetected  = ADULT_REGEX.test(fullContent);

        if (isDetected) {
            toggle18Plus.checked = true;
            toggle18Plus.disabled = true;
            hidden18Plus.value = '1';
            lockedBadge.classList.remove('hidden');
            updateUiFor18PlusState(true);
        } else {
            toggle18Plus.disabled = false;
            lockedBadge.classList.add('hidden');
            const isManualOn = toggle18Plus.checked;
            hidden18Plus.value = isManualOn ? '1' : '0';
            updateUiFor18PlusState(isManualOn);
        }
    }

    toggle18Plus.addEventListener('change', function() {
        hidden18Plus.value = this.checked ? '1' : '0';
        updateUiFor18PlusState(this.checked);
    });

    tagsField.addEventListener('input', checkContentForAdultStuff);
    descField.addEventListener('input', checkContentForAdultStuff);

    function showError(message) {
        errorText.textContent = `${message}`;
        errorBox.classList.remove('hidden');
        showToast(message, 'error');
    }

    function hideMessages() {
        errorBox.classList.add('hidden');
    }

    function setFetchLoading(isLoading) {
        fetchBtn.disabled = isLoading;
        fetchSpinner.classList.toggle('hidden', !isLoading);
        fetchLabel.textContent = isLoading ? 'Loading...' : 'Fetch Details';
    }

    function setPublishLoading(isLoading) {
        publishBtn.disabled = isLoading;
        publishSpinner.classList.toggle('hidden', !isLoading);
        publishText.textContent = isLoading ? 'Publishing Group...' : 'Publish Group Now';
    }

    function parseSubscriberNumber(text) {
        if (!text) return 0;
        const digits = text.toString().replace(/[^0-9]/g, '');
        return digits ? parseInt(digits, 10) : 0;
    }

    function autoSelectDropdownOption(selectElem, targetName, hiddenElem) {
        if (!targetName) return;
        const search = targetName.trim().toLowerCase();
        for (let i = 0; i < selectElem.options.length; i++) {
            const optText = selectElem.options[i].text.trim().toLowerCase();
            const optVal  = selectElem.options[i].value.trim().toLowerCase();
            if (optText === search || optVal === search || optText.includes(search)) {
                selectElem.selectedIndex = i;
                if (hiddenElem) hiddenElem.value = selectElem.options[i].value;
                break;
            }
        }
    }

    function detectTypeFromData(data) {
        const rawString = JSON.stringify(data).toLowerCase();
        if (rawString.includes('subscriber') || rawString.includes('subscribers')) return 'Channel';
        if (rawString.includes('member') || rawString.includes('members')) return 'Group';
        if (rawString.includes('bot')) return 'Bot';
        return 'Channel';
    }

    function detectLanguageFromText(text) {
        if (!text) return 'English';
        const cleanText = text.replace(/https?:\/\/\S+/g, '').replace(/[0-9\W_]+/g, ' ').trim();
        if (!cleanText) return 'English';

        if (/[\u0600-\u06FF]/.test(cleanText)) return 'Arabic';
        if (/[\u0900-\u097F]/.test(cleanText)) return 'Hindi';
        if (/[\u0980-\u09FF]/.test(cleanText)) return 'Bengali';
        if (/[\u0400-\u04FF]/.test(cleanText)) return 'Russian';
        if (/[\u4E00-\u9FFF]/.test(cleanText)) return 'Chinese';
        if (/[\u3040-\u309F\u30A0-\u30FF]/.test(cleanText)) return 'Japanese';
        if (/[\uAC00-\uD7AF]/.test(cleanText)) return 'Korean';
        if (/[\u0E00-\u0E7F]/.test(cleanText)) return 'Thai';

        return 'English';
    }

    function extractTelegramHandle(data, sourceUrl = '') {
        const handleRegex = /@[A-Za-z0-9_]{4,32}/;

        if (data.admin_handle && handleRegex.test(data.admin_handle)) {
            return data.admin_handle.match(handleRegex)[0];
        }
        if (data.username) {
            const formatted = data.username.startsWith('@') ? data.username : '@' + data.username;
            if (handleRegex.test(formatted)) return formatted;
        }

        const urlToParse = data.link || sourceUrl;
        if (urlToParse) {
            const urlMatch = urlToParse.match(/(?:t\.me|telegram\.me)\/([A-Za-z0-9_]{4,32})/i);
            if (urlMatch && urlMatch[1] && !['joinchat', 'addstickers', 'proxy'].includes(urlMatch[1].toLowerCase())) {
                return '@' + urlMatch[1];
            }
        }

        if (data.name && handleRegex.test(data.name)) {
            return data.name.match(handleRegex)[0];
        }
        if (data.description && handleRegex.test(data.description)) {
            return data.description.match(handleRegex)[0];
        }

        return '';
    }

    async function fetchTelegramDetails() {
        const link = linkInput.value.trim();
        hideMessages();

        if (!link) {
            showError('Please enter a Telegram link.');
            return;
        }
        if (!link.includes('t.me/') && !link.includes('telegram.me/')) {
            showError('Please provide a valid t.me or telegram.me link.');
            return;
        }

        setFetchLoading(true);

        try {
            const response = await fetch(REST_ENDPOINT, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ link })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Could not find details for this link. Please check the URL.');
            }

            finalLink.value = data.link || link;
            finalImage.src = data.image || '';
            finalImageUrl.value = data.image || '';
            titleField.value = data.name || '';

            const foundAdminHandle = extractTelegramHandle(data, link);
            if (foundAdminHandle) {
                adminField.value = foundAdminHandle;
                adminField.readOnly = true;
                adminField.className = "tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm font-semibold";
            } else {
                adminField.value = '';
                adminField.readOnly = false;
                adminField.placeholder = '@username';
                adminField.className = "tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-semibold";
            }

            if (data.description && data.description.trim() !== '') {
                descField.value = data.description;
                descField.readOnly = true;
                descField.className = "tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm resize-none";
                descBadge.classList.add('hidden');
            } else {
                descField.value = '';
                descField.readOnly = false;
                descField.placeholder = 'Add a short description about this group...';
                descField.className = "tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 resize-none";
                descBadge.classList.remove('hidden');
            }

            checkContentForAdultStuff();

            const parsedCount = parseSubscriberNumber(data.count);
            if (parsedCount > 0) {
                subsField.value = parsedCount;
                subsField.readOnly = true;
                subsField.className = "tgt-theme-input tgt-theme-locked w-full px-4 py-2.5 text-xs sm:text-sm font-bold";
                if (subsBadge) {
                    subsBadge.innerHTML = 'Auto-detected';
                    subsBadge.className = "text-[10px] font-extrabold uppercase text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200";
                    subsBadge.classList.remove('hidden');
                }
            } else {
                subsField.value = '0';
                subsField.readOnly = false;
                subsField.placeholder = 'e.g. 1000';
                subsField.className = "tgt-theme-input w-full px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-bold";
                if (subsBadge) {
                    subsBadge.innerHTML = 'Enter manually';
                    subsBadge.className = "text-[10px] font-extrabold uppercase text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200";
                    subsBadge.classList.remove('hidden');
                }
            }

            const detectedType = detectTypeFromData(data);
            if (detectedType) {
                autoSelectDropdownOption(typeSelect, detectedType, hiddenType);
            } else {
                hiddenType.value = typeSelect.value;
            }

            const detectedLang = detectLanguageFromText(data.description || data.name);
            if (detectedLang) {
                for (let item of langItems) {
                    if (item.getAttribute('data-value').toLowerCase() === detectedLang.toLowerCase()) {
                        langSearch.value = item.textContent;
                        langHidden.value = item.getAttribute('data-value');
                        break;
                    }
                }
            }

            expandWrap.classList.add('is-open');
            showToast('Group details found!', 'success');

            setTimeout(() => {
                expandWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);

        } catch (err) {
            showError(err.message || 'Could not load link details.');
            expandWrap.classList.remove('is-open');
        } finally {
            setFetchLoading(false);
        }
    }

    fetchBtn.addEventListener('click', fetchTelegramDetails);
    linkInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            fetchTelegramDetails();
        }
    });

    function resetListingForm() {
        linkInput.value = '';
        finalLink.value = '';
        finalImage.src = '';
        finalImageUrl.value = '';
        titleField.value = '';
        descField.value = '';
        tagsField.value = '';
        subsField.value = '';
        adminField.value = '';

        categorySelect.value = '';
        typeSelect.value = 'Channel';
        countrySearch.value = '';
        countryHidden.value = '';
        langSearch.value = '';
        langHidden.value = '';
        hiddenType.value = 'Channel';

        toggle18Plus.checked = false;
        toggle18Plus.disabled = false;
        hidden18Plus.value = '0';
        lockedBadge.classList.add('hidden');
        if (subsBadge) subsBadge.classList.add('hidden');
        if (descBadge) descBadge.classList.add('hidden');

        descField.readOnly = true;
        subsField.readOnly = true;
        adminField.readOnly = false;

        hideMessages();
        updateUiFor18PlusState(false);
        expandWrap.classList.remove('is-open');

        thankYouPopup.classList.add('hidden');
        thankYouPopup.classList.remove('flex');

        window.scrollTo({ top: linkInput.offsetTop - 80, behavior: 'smooth' });
        setTimeout(() => linkInput.focus(), 200);
    }

    fullForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        hideMessages();

        if (!titleField.value.trim()) {
            showError("Please enter a group title.");
            return;
        }
        if (!descField.value.trim()) {
            showError("Please add a description.");
            return;
        }
        if (!tagsField.value.trim()) {
            showError("Please enter tags or keywords.");
            return;
        }
        if (!toggle18Plus.checked && !categorySelect.value) {
            showError("Please choose a category.");
            return;
        }
        if (!countryHidden.value) {
            showError("Please select a target country/region.");
            return;
        }
        if (!langHidden.value) {
            showError("Please choose the primary language.");
            return;
        }
        if (!adminField.value.trim()) {
            showError("Please enter the admin username.");
            return;
        }
        if (subsField.value === '' || parseInt(subsField.value, 10) < 0) {
            showError("Please enter a valid member count.");
            return;
        }

        setPublishLoading(true);

        const formData = new FormData(fullForm);
        formData.append('action', 'tgt_ajax_publish_listing');
        formData.set('type', hiddenType.value || typeSelect.value || 'Channel');
        formData.set('country', countryHidden.value);
        formData.set('language', langHidden.value);

        try {
            const response = await fetch(PAGE_ENDPOINT, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const text = await response.text();
            let data;

            try {
                const firstBrace = text.indexOf('{');
                const lastBrace  = text.lastIndexOf('}');
                if (firstBrace !== -1 && lastBrace !== -1) {
                    const cleanJson = text.substring(firstBrace, lastBrace + 1);
                    data = JSON.parse(cleanJson);
                } else {
                    data = JSON.parse(text);
                }
            } catch (parseError) {
                if (text.includes('success') || text.includes('post_id') || text.trim() === '') {
                    data = { success: true };
                } else {
                    console.error("Non-JSON Server Output:", text);
                    showError("Server returned an invalid response. Please try again.");
                    return;
                }
            }

            if (!data.success) {
                if (data.code === 'duplicate') {
                    showError("This link is already listed on our website.");
                    showToast("Already added!", "warning", 5000);
                } else {
                    showError(data.message || "Could not publish your listing.");
                }
                return;
            }

            thankYouPopup.classList.remove('hidden');
            thankYouPopup.classList.add('flex');
            showToast('Your group has been published!', 'success', 5000);

        } catch (err) {
            console.error("Fetch Execution Error:", err);
            showError("Network connection error. Please try again.");
        } finally {
            setPublishLoading(false);
        }
    });

    addAnotherBtn.addEventListener('click', resetListingForm);
});
</script>
<?php
ob_end_flush();