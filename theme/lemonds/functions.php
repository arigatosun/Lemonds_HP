<?php
/**
 * LEMONDS ENTERTAINMENT theme functions
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('LEMONDS_THEME_VERSION')) {
    define('LEMONDS_THEME_VERSION', '1.0.0');
}

/**
 * after_setup_theme: テーマサポートとナビメニュー登録
 */
function lemonds_setup() {
    // <title> タグの自動出力
    add_theme_support('title-tag');
    // アイキャッチ画像
    add_theme_support('post-thumbnails');
    // HTML5 対応
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    // カスタムロゴ
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // ナビメニュー
    register_nav_menus([
        'primary' => __('Primary Menu', 'lemonds'),
        'footer'  => __('Footer Menu', 'lemonds'),
    ]);
}
add_action('after_setup_theme', 'lemonds_setup');

/**
 * wp_enqueue_scripts: スタイル・スクリプトの読み込み
 */
function lemonds_enqueue_assets() {
    // Google Fonts (Noto Sans JP) preconnect → enqueue
    wp_enqueue_style(
        'lemonds-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap',
        [],
        null
    );

    // colors_and_type.css （ベースのカラー・タイポ）
    wp_enqueue_style(
        'lemonds-colors-and-type',
        get_template_directory_uri() . '/assets/css/colors_and_type.css',
        [],
        LEMONDS_THEME_VERSION
    );

    // site.css （colors_and_type.css に依存）
    wp_enqueue_style(
        'lemonds-site',
        get_template_directory_uri() . '/assets/css/site.css',
        ['lemonds-colors-and-type'],
        LEMONDS_THEME_VERSION
    );

    // テーマ本体 style.css（テーマヘッダのみだが慣習として）
    wp_enqueue_style(
        'lemonds-style',
        get_stylesheet_uri(),
        ['lemonds-site'],
        LEMONDS_THEME_VERSION
    );

    // 1920基準スケール用 CSS + JS（site.css の後で読み込み、html/body の overflow-x を上書き）
    wp_enqueue_style(
        'lemonds-page-scale',
        get_template_directory_uri() . '/assets/css/page-scale.css',
        ['lemonds-site'],
        LEMONDS_THEME_VERSION
    );
    wp_enqueue_script(
        'lemonds-page-scale',
        get_template_directory_uri() . '/assets/js/page-scale.js',
        [],
        LEMONDS_THEME_VERSION,
        true
    );

    // ハンバーガーメニュートグル（モバイル/タブレット用）
    wp_enqueue_script(
        'lemonds-header-menu',
        get_template_directory_uri() . '/assets/js/header-menu.js',
        [],
        LEMONDS_THEME_VERSION,
        true
    );

    // contact ページ専用 JS（form-builder が後で生成）
    if (is_page('contact')) {
        $contact_js = get_template_directory() . '/assets/js/contact.js';
        if (file_exists($contact_js)) {
            wp_enqueue_script(
                'lemonds-contact',
                get_template_directory_uri() . '/assets/js/contact.js',
                [],
                LEMONDS_THEME_VERSION,
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'lemonds_enqueue_assets');

/**
 * Google Fonts の preconnect を <head> に出力
 */
function lemonds_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = [
            'href'        => 'https://fonts.googleapis.com',
            'crossorigin',
        ];
        $urls[] = [
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }
    return $urls;
}
add_filter('wp_resource_hints', 'lemonds_resource_hints', 10, 2);

/**
 * 並列レーンで生成中のファイルを file_exists ガード付きで require_once
 */
$lemonds_includes = [
    'inc/template-tags.php',
    'inc/seo.php',
    'inc/cpt-works.php',
    'inc/cpt-news.php',
    'inc/taxonomy-works-category.php',
    'inc/taxonomy-news-category.php',
    'inc/meta-box-works.php',
    'inc/cf7-tweaks.php',
];
foreach ($lemonds_includes as $lemonds_inc) {
    $lemonds_inc_path = get_template_directory() . '/' . $lemonds_inc;
    if (file_exists($lemonds_inc_path)) {
        require_once $lemonds_inc_path;
    }
}
unset($lemonds_inc, $lemonds_inc_path, $lemonds_includes);

/**
 * カスタムロゴが未設定の場合のフォールバック判定
 */
function lemonds_has_custom_logo() {
    return function_exists('has_custom_logo') && has_custom_logo();
}
