<?php
/**
 * テンプレートで使うヘルパ関数群
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 旧 .html URL → 新 permalink への変換マップを一元管理
 *
 * @param string $key URL キー
 * @return string URL
 */
function lemonds_url($key) {
    $map = [
        'home'           => home_url('/'),
        'services'       => home_url('/services/'),
        'works'          => home_url('/works/'),
        'news'           => home_url('/news/'),
        'company'        => home_url('/company/'),
        'contact'        => home_url('/contact/'),
        'contact_quote'  => home_url('/contact/?type=quote'),
        'contact_thanks' => home_url('/contact/thanks/'),
        'policy'         => home_url('/policy/'),
    ];
    return isset($map[$key]) ? $map[$key] : home_url('/');
}

/**
 * works_category の最初の term name
 *
 * @param int $post_id
 * @return string
 */
function lemonds_works_category_label($post_id) {
    $terms = get_the_terms($post_id, 'works_category');
    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }
    return $terms[0]->name;
}

/**
 * news_category の最初の term name
 *
 * @param int $post_id
 * @return string
 */
function lemonds_news_category_label($post_id) {
    $terms = get_the_terms($post_id, 'news_category');
    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }
    return $terms[0]->name;
}

/**
 * ロゴ画像 URL（シンボル）
 */
function lemonds_logo_url() {
    return get_template_directory_uri() . '/assets/img/lemonds-logo.svg';
}

/**
 * ワードマーク URL
 */
function lemonds_logo_wordmark_url() {
    return get_template_directory_uri() . '/assets/img/lemonds-logo-wordmark.svg';
}

/**
 * ブランドロゴ（カラー版 PNG）URL
 */
function lemonds_logo_brand_url() {
    return get_template_directory_uri() . '/assets/img/lemonds-logo-brand.png';
}

/**
 * テーマ assets/img/ の画像 URL を返す
 *
 * @param string $filename
 * @return string
 */
function lemonds_img($filename) {
    return get_template_directory_uri() . '/assets/img/' . ltrim($filename, '/');
}

/**
 * パンくず HTML をレンダリングする
 *
 * @param array $items [['label' => 'トップ', 'url' => '/'], ['label' => '制作実績']]
 *                     最後の要素はカレント扱いで url 不要
 * @return string
 */
function lemonds_render_breadcrumb($items) {
    if (empty($items) || !is_array($items)) {
        return '';
    }

    $count = count($items);
    $out = '<nav class="lm-breadcrumb" aria-label="breadcrumb">';

    foreach ($items as $i => $item) {
        $is_last = ($i === $count - 1);
        $label = isset($item['label']) ? $item['label'] : '';

        if ($is_last) {
            $out .= '<span class="cur">' . esc_html($label) . '</span>';
        } else {
            $url = isset($item['url']) ? $item['url'] : '#';
            $out .= '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
            $out .= '<span class="sep">・</span>';
        }
    }

    $out .= '</nav>';
    return $out;
}

/**
 * パンくず HTML を出力する
 *
 * @param array $items
 */
function lemonds_the_breadcrumb($items) {
    echo lemonds_render_breadcrumb($items);
}
