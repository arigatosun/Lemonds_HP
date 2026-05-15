<?php
/**
 * Custom Post Type: news (お知らせ)
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * news CPT を登録する。
 */
function lemonds_register_cpt_news() {
    $labels = [
        'name'                  => __('お知らせ', 'lemonds'),
        'singular_name'         => __('お知らせ', 'lemonds'),
        'menu_name'             => __('お知らせ', 'lemonds'),
        'name_admin_bar'        => __('お知らせ', 'lemonds'),
        'add_new'               => __('新規追加', 'lemonds'),
        'add_new_item'          => __('新しいお知らせを追加', 'lemonds'),
        'new_item'              => __('新しいお知らせ', 'lemonds'),
        'edit_item'             => __('お知らせを編集', 'lemonds'),
        'view_item'             => __('お知らせを表示', 'lemonds'),
        'all_items'             => __('お知らせ一覧', 'lemonds'),
        'search_items'          => __('お知らせを検索', 'lemonds'),
        'not_found'             => __('お知らせが見つかりません。', 'lemonds'),
        'not_found_in_trash'    => __('ゴミ箱にお知らせはありません。', 'lemonds'),
        'featured_image'        => __('アイキャッチ画像', 'lemonds'),
        'set_featured_image'    => __('アイキャッチ画像を設定', 'lemonds'),
        'remove_featured_image' => __('アイキャッチ画像を削除', 'lemonds'),
        'use_featured_image'    => __('アイキャッチ画像として使用', 'lemonds'),
        'archives'              => __('お知らせアーカイブ', 'lemonds'),
    ];

    $args = [
        'label'         => __('お知らせ', 'lemonds'),
        'labels'        => $labels,
        'public'        => true,
        'show_in_rest'  => true,
        'menu_position' => 6,
        'menu_icon'     => 'dashicons-megaphone',
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'news', 'with_front' => false],
        'taxonomies'    => ['news_category'],
    ];

    register_post_type('news', $args);
}
add_action('init', 'lemonds_register_cpt_news', 0);
