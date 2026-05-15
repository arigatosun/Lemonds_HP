<?php
/**
 * 最小限の SEO メタタグ出力
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * <head> 内に description / OGP / canonical を出力
 */
function lemonds_output_meta_tags() {
    $site_name   = get_bloginfo('name');
    $site_desc   = get_bloginfo('description');
    $default_img = lemonds_img('lemonds-logo-brand.png');

    $title       = wp_get_document_title();
    $description = $site_desc;
    $canonical   = home_url(add_query_arg([], $GLOBALS['wp']->request));
    $og_type     = 'website';
    $og_image    = $default_img;

    if (is_singular()) {
        $post_obj = get_queried_object();
        if ($post_obj && !empty($post_obj->post_excerpt)) {
            $description = wp_strip_all_tags($post_obj->post_excerpt);
        } elseif ($post_obj) {
            $description = wp_trim_words(wp_strip_all_tags($post_obj->post_content), 60, '…');
        }
        $canonical = get_permalink();
        $og_type   = 'article';
        if (has_post_thumbnail($post_obj)) {
            $thumb_url = get_the_post_thumbnail_url($post_obj, 'large');
            if ($thumb_url) {
                $og_image = $thumb_url;
            }
        }
    } elseif (is_post_type_archive() || is_tax() || is_category() || is_tag()) {
        $canonical = home_url(add_query_arg([], $GLOBALS['wp']->request));
    } elseif (is_front_page() || is_home()) {
        $canonical = home_url('/');
    }

    if (empty($description)) {
        $description = 'LEMONDS ENTERTAINMENT — 想いを、価値あるカタチに。OEM/ODM、MD、健康サポート、物流、オンラインガチャ、デザイン事業を展開。';
    }

    echo "\n<!-- LEMONDS SEO -->\n";
    echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo "<!-- /LEMONDS SEO -->\n";
}
add_action('wp_head', 'lemonds_output_meta_tags', 5);
