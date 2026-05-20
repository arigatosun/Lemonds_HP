<?php
/**
 * SEO メタタグ出力（手書き実装、Yoast / RankMath 不使用）
 *
 * - <title>      : add_theme_support('title-tag') の WP デフォルトに任せる
 * - description  : ページ種別ごとに動的に切り替え
 * - robots       : 404 / パスワード保護で noindex,nofollow
 * - OGP          : og:type, og:title, og:description, og:url, og:image, og:site_name, og:locale
 * - Twitter Card : summary_large_image + title/description/image
 * - canonical    : 現在ページの正規 URL
 * - JSON-LD      : Organization スキーマ（data-company.php からビルド）
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマ既定の OGP 画像 URL
 *
 * @return string
 */
function lemonds_seo_default_og_image() {
    // 1. 設定済みのサイトアイコン（site_icon）を最優先
    if (function_exists('get_site_icon_url')) {
        $icon = get_site_icon_url(1200);
        if (!empty($icon)) {
            return $icon;
        }
    }

    // 2. トップヒーローのコラージュ画像（カスタムロゴよりサイズが OGP 向き）
    if (function_exists('lemonds_img')) {
        $hero = get_template_directory() . '/assets/img/top-hero-collage-desktop.png';
        if (file_exists($hero)) {
            return lemonds_img('top-hero-collage-desktop.png');
        }
        // 3. ブランドロゴへフォールバック
        return lemonds_img('lemonds-logo-brand.png');
    }

    return home_url('/');
}

/**
 * 現在ページの description テキストを返す
 *
 * @return string
 */
function lemonds_seo_build_description() {
    // サイト全体のデフォルト文（schema-registry §4 のキャッチを基にした 1 文）
    $default = 'LEMONDS ENTERTAINMENT — 想いを、価値あるカタチに。OEM/ODM、MD、健康サポート、物流、オンラインガチャ、デザイン事業を展開する株式会社ルモンズエンターテインメント。';

    // 1. 個別投稿 / 固定ページ
    if (is_singular()) {
        $post_obj = get_queried_object();
        if ($post_obj) {
            if (!empty($post_obj->post_excerpt)) {
                return wp_strip_all_tags($post_obj->post_excerpt);
            }
            if (!empty($post_obj->post_content)) {
                $content = wp_strip_all_tags(strip_shortcodes($post_obj->post_content));
                $content = preg_replace('/\s+/u', ' ', $content);
                if ($content !== '') {
                    return mb_substr($content, 0, 80, 'UTF-8') . (mb_strlen($content, 'UTF-8') > 80 ? '…' : '');
                }
            }
        }
    }

    // 2. CPT アーカイブ
    if (is_post_type_archive('works')) {
        return 'LEMONDS ENTERTAINMENT の制作実績一覧。アパレル/コスメ/ライブグッズ/パッケージ/プロモーションノベルティなど、これまでに手がけたプロジェクトをご紹介します。';
    }
    if (is_post_type_archive('news')) {
        return 'LEMONDS ENTERTAINMENT のお知らせ一覧。プレスリリース・お知らせ・プロジェクト情報・会社情報を発信しています。';
    }

    // 3. タクソノミーアーカイブ
    if (is_tax() || is_category() || is_tag()) {
        $term = get_queried_object();
        if ($term && !empty($term->description)) {
            return wp_strip_all_tags($term->description);
        }
        if ($term && !empty($term->name)) {
            return sprintf('%s カテゴリーの一覧 | LEMONDS ENTERTAINMENT', $term->name);
        }
    }

    // 4. 404
    if (is_404()) {
        return 'お探しのページは見つかりませんでした。LEMONDS ENTERTAINMENT トップへお戻りください。';
    }

    // 5. デフォルト（フロント / ホーム / 検索 等）
    return $default;
}

/**
 * 現在ページの canonical URL
 *
 * @return string
 */
function lemonds_seo_build_canonical() {
    if (is_front_page() || is_home()) {
        return home_url('/');
    }
    if (is_singular()) {
        $permalink = get_permalink();
        if ($permalink) {
            return $permalink;
        }
    }
    if (is_post_type_archive()) {
        $post_type = get_query_var('post_type');
        if (is_array($post_type)) {
            $post_type = reset($post_type);
        }
        $link = $post_type ? get_post_type_archive_link($post_type) : '';
        if ($link) {
            return $link;
        }
    }
    if (is_tax() || is_category() || is_tag()) {
        $term = get_queried_object();
        if ($term && !empty($term->term_id)) {
            $link = get_term_link($term);
            if (!is_wp_error($link)) {
                return $link;
            }
        }
    }

    // 安全側のフォールバック
    if (!empty($GLOBALS['wp']->request)) {
        return home_url('/' . ltrim($GLOBALS['wp']->request, '/') . '/');
    }
    return home_url('/');
}

/**
 * OGP 画像 URL（ページごと）
 *
 * @return string
 */
function lemonds_seo_build_og_image() {
    // 個別投稿に Featured Image があればそれを使う
    if (is_singular()) {
        $post_obj = get_queried_object();
        if ($post_obj && has_post_thumbnail($post_obj)) {
            $thumb_url = get_the_post_thumbnail_url($post_obj, 'large');
            if ($thumb_url) {
                return $thumb_url;
            }
        }
    }
    return lemonds_seo_default_og_image();
}

/**
 * OGP type
 *
 * @return string
 */
function lemonds_seo_build_og_type() {
    if (is_singular(['works', 'news', 'post'])) {
        return 'article';
    }
    return 'website';
}

/**
 * OGP / Twitter / canonical / description / robots を <head> に出力
 */
function lemonds_output_meta_tags() {
    $site_name   = get_bloginfo('name');
    $title       = wp_get_document_title();
    $description = lemonds_seo_build_description();
    $canonical   = lemonds_seo_build_canonical();
    $og_image    = lemonds_seo_build_og_image();
    $og_type     = lemonds_seo_build_og_type();
    $locale      = function_exists('get_locale') ? get_locale() : 'ja_JP';

    // robots: 404 / パスワード保護 / 検索結果 で noindex
    $robots_directives = [];
    if (is_404() || is_search()) {
        $robots_directives[] = 'noindex';
        $robots_directives[] = 'follow';
    } elseif (is_singular() && post_password_required()) {
        $robots_directives[] = 'noindex';
        $robots_directives[] = 'nofollow';
    }

    echo "\n<!-- LEMONDS SEO -->\n";

    if (!empty($robots_directives)) {
        echo '<meta name="robots" content="' . esc_attr(implode(',', $robots_directives)) . '" />' . "\n";
    }

    echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";

    // OGP
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr($locale) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";

    echo "<!-- /LEMONDS SEO -->\n";
}
add_action('wp_head', 'lemonds_output_meta_tags', 5);

/**
 * Organization 構造化データ（JSON-LD）を出力
 *
 * data-company.php から会社名 / 所在地 / 電話 / URL / logo を組み立てる。
 */
function lemonds_output_jsonld_organization() {
    // 404 / パスワード保護では出さない
    if (is_404()) {
        return;
    }
    if (is_singular() && post_password_required()) {
        return;
    }

    $company_file = get_template_directory() . '/inc/data-company.php';
    $company = file_exists($company_file) ? include $company_file : [];

    // 会社情報の取り出し（data-company.php の構造に合わせる）
    $company_name    = '株式会社ルモンズエンターテインメント';
    $company_name_en = 'LEMONDS ENTERTAINMENT CO.,LTD.';
    $address_postal  = isset($company['address_postal']) ? $company['address_postal'] : '〒160-0022';
    $address_line    = isset($company['address_line'])   ? $company['address_line']   : '東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F';
    $tel             = isset($company['tel'])            ? $company['tel']            : '03-5969-9075';

    if (!empty($company['rows']) && is_array($company['rows'])) {
        foreach ($company['rows'] as $row) {
            if (!isset($row['key'])) {
                continue;
            }
            if ($row['key'] === 'company_name' && !empty($row['value'])) {
                $company_name = $row['value'];
            } elseif ($row['key'] === 'company_name_en' && !empty($row['value'])) {
                $company_name_en = $row['value'];
            }
        }
    }

    // 住所の分解（〒160-0022 と 残り）
    $postal_code = '';
    if (preg_match('/〒?\s*(\d{3}-\d{4})/u', $address_postal . ' ' . $address_line, $m)) {
        $postal_code = $m[1];
    }
    $street_address = trim(preg_replace('/〒?\s*\d{3}-\d{4}\s*/u', '', $address_line));

    $logo_url = function_exists('lemonds_logo_brand_url')
        ? lemonds_logo_brand_url()
        : get_template_directory_uri() . '/assets/img/lemonds-logo-brand.png';

    $org = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        'name'        => $company_name,
        'alternateName' => $company_name_en,
        'url'         => home_url('/'),
        'logo'        => $logo_url,
        'image'       => lemonds_seo_default_og_image(),
        'telephone'   => $tel,
        'address'     => [
            '@type'           => 'PostalAddress',
            'postalCode'      => $postal_code,
            'streetAddress'   => $street_address,
            'addressLocality' => '新宿区',
            'addressRegion'   => '東京都',
            'addressCountry'  => 'JP',
        ],
    ];

    echo "\n<script type=\"application/ld+json\">\n";
    echo wp_json_encode($org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "\n</script>\n";
}
add_action('wp_head', 'lemonds_output_jsonld_organization', 6);
