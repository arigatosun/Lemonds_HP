<?php
/**
 * Taxonomy: works_category
 *
 * works CPT 用カテゴリ（非階層）。初期タームを冪等に seed する。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * works_category タクソノミーを登録する。
 */
function lemonds_register_taxonomy_works_category() {
    $labels = [
        'name'                       => __('実績カテゴリー', 'lemonds'),
        'singular_name'              => __('実績カテゴリー', 'lemonds'),
        'menu_name'                  => __('実績カテゴリー', 'lemonds'),
        'all_items'                  => __('すべての実績カテゴリー', 'lemonds'),
        'edit_item'                  => __('実績カテゴリーを編集', 'lemonds'),
        'view_item'                  => __('実績カテゴリーを表示', 'lemonds'),
        'update_item'                => __('実績カテゴリーを更新', 'lemonds'),
        'add_new_item'               => __('新しい実績カテゴリーを追加', 'lemonds'),
        'new_item_name'              => __('新しい実績カテゴリー名', 'lemonds'),
        'search_items'               => __('実績カテゴリーを検索', 'lemonds'),
        'popular_items'              => __('よく使われる実績カテゴリー', 'lemonds'),
        'separate_items_with_commas' => __('カンマ区切りで複数指定', 'lemonds'),
        'add_or_remove_items'        => __('実績カテゴリーを追加または削除', 'lemonds'),
        'choose_from_most_used'      => __('よく使われる実績カテゴリーから選択', 'lemonds'),
        'not_found'                  => __('実績カテゴリーが見つかりません。', 'lemonds'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'hierarchical'       => false,
        'show_in_rest'       => true,
        'show_admin_column'  => true,
        'show_tagcloud'      => false,
        'rewrite'            => ['slug' => 'works-category', 'with_front' => false],
    ];

    register_taxonomy('works_category', ['works'], $args);
}
add_action('init', 'lemonds_register_taxonomy_works_category', 0);

/**
 * works_category の初期タームを seed する（冪等）。
 */
function lemonds_seed_works_category_terms() {
    if (!taxonomy_exists('works_category')) {
        return;
    }

    $terms = [
        ['name' => 'EVENT / LIVE', 'slug' => 'event-live'],
        ['name' => 'COSMETICS',    'slug' => 'cosmetics'],
        ['name' => 'GOODS',        'slug' => 'goods'],
        ['name' => 'APPAREL / MD', 'slug' => 'apparel-md'],
        ['name' => 'PROMOTION',    'slug' => 'promotion'],
        ['name' => 'PACKAGE',      'slug' => 'package'],
    ];

    foreach ($terms as $term) {
        if (!term_exists($term['slug'], 'works_category')) {
            wp_insert_term($term['name'], 'works_category', ['slug' => $term['slug']]);
        }
    }
}
add_action('init', 'lemonds_seed_works_category_terms', 1);
