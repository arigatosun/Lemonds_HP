<?php
/**
 * Taxonomy: news_category
 *
 * news CPT 用カテゴリ（非階層）。初期タームを冪等に seed する。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * news_category タクソノミーを登録する。
 */
function lemonds_register_taxonomy_news_category() {
    $labels = [
        'name'                       => __('お知らせカテゴリー', 'lemonds'),
        'singular_name'              => __('お知らせカテゴリー', 'lemonds'),
        'menu_name'                  => __('お知らせカテゴリー', 'lemonds'),
        'all_items'                  => __('すべてのお知らせカテゴリー', 'lemonds'),
        'edit_item'                  => __('お知らせカテゴリーを編集', 'lemonds'),
        'view_item'                  => __('お知らせカテゴリーを表示', 'lemonds'),
        'update_item'                => __('お知らせカテゴリーを更新', 'lemonds'),
        'add_new_item'               => __('新しいお知らせカテゴリーを追加', 'lemonds'),
        'new_item_name'              => __('新しいお知らせカテゴリー名', 'lemonds'),
        'search_items'               => __('お知らせカテゴリーを検索', 'lemonds'),
        'popular_items'              => __('よく使われるお知らせカテゴリー', 'lemonds'),
        'separate_items_with_commas' => __('カンマ区切りで複数指定', 'lemonds'),
        'add_or_remove_items'        => __('お知らせカテゴリーを追加または削除', 'lemonds'),
        'choose_from_most_used'      => __('よく使われるお知らせカテゴリーから選択', 'lemonds'),
        'not_found'                  => __('お知らせカテゴリーが見つかりません。', 'lemonds'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'hierarchical'       => false,
        'show_in_rest'       => true,
        'show_admin_column'  => true,
        'show_tagcloud'      => false,
        'rewrite'            => ['slug' => 'news-category', 'with_front' => false],
    ];

    register_taxonomy('news_category', ['news'], $args);
}
add_action('init', 'lemonds_register_taxonomy_news_category', 0);

/**
 * news_category の初期タームを seed する（冪等）。
 */
function lemonds_seed_news_category_terms() {
    if (!taxonomy_exists('news_category')) {
        return;
    }

    $terms = [
        ['name' => 'PRESS',   'slug' => 'press'],
        ['name' => 'NOTICE',  'slug' => 'notice'],
        ['name' => 'PROJECT', 'slug' => 'project'],
        ['name' => 'COMPANY', 'slug' => 'company'],
    ];

    foreach ($terms as $term) {
        if (!term_exists($term['slug'], 'news_category')) {
            wp_insert_term($term['name'], 'news_category', ['slug' => $term['slug']]);
        }
    }
}
add_action('init', 'lemonds_seed_news_category_terms', 1);
