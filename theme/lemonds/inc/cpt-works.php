<?php
/**
 * Custom Post Type: works (制作実績)
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * works CPT を登録する。
 */
function lemonds_register_cpt_works() {
    $labels = [
        'name'                  => __('制作実績', 'lemonds'),
        'singular_name'         => __('制作実績', 'lemonds'),
        'menu_name'             => __('制作実績', 'lemonds'),
        'name_admin_bar'        => __('制作実績', 'lemonds'),
        'add_new'               => __('新規追加', 'lemonds'),
        'add_new_item'          => __('新しい制作実績を追加', 'lemonds'),
        'new_item'              => __('新しい制作実績', 'lemonds'),
        'edit_item'             => __('制作実績を編集', 'lemonds'),
        'view_item'             => __('制作実績を表示', 'lemonds'),
        'all_items'             => __('制作実績一覧', 'lemonds'),
        'search_items'          => __('制作実績を検索', 'lemonds'),
        'not_found'             => __('制作実績が見つかりません。', 'lemonds'),
        'not_found_in_trash'    => __('ゴミ箱に制作実績はありません。', 'lemonds'),
        'featured_image'        => __('アイキャッチ画像', 'lemonds'),
        'set_featured_image'    => __('アイキャッチ画像を設定', 'lemonds'),
        'remove_featured_image' => __('アイキャッチ画像を削除', 'lemonds'),
        'use_featured_image'    => __('アイキャッチ画像として使用', 'lemonds'),
        'archives'              => __('制作実績アーカイブ', 'lemonds'),
    ];

    $args = [
        'label'         => __('制作実績', 'lemonds'),
        'labels'        => $labels,
        'public'        => true,
        'show_in_rest'  => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'works', 'with_front' => false],
        'taxonomies'    => ['works_category'],
    ];

    register_post_type('works', $args);
}
add_action('init', 'lemonds_register_cpt_works', 0);

/**
 * works のカスタムフィールドを REST 経由でも扱えるよう登録する。
 *
 * meta_key は ACF 互換命名（先頭アンダースコアなし・小文字スネークケース）。
 */
function lemonds_register_post_meta_works() {
    $auth_callback = function () {
        return current_user_can('edit_posts');
    };

    register_post_meta('works', 'client_name', [
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => $auth_callback,
    ]);

    register_post_meta('works', 'client_anonymous', [
        'type'              => 'boolean',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'auth_callback'     => $auth_callback,
    ]);

    register_post_meta('works', 'is_featured', [
        'type'              => 'boolean',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'auth_callback'     => $auth_callback,
    ]);

    register_post_meta('works', 'sort_order', [
        'type'              => 'number',
        'single'            => true,
        'show_in_rest'      => true,
        'default'           => 0,
        // PHP 8 では intval が4引数で呼ばれると ArgumentCountError を起こすため absint を使用
        'sanitize_callback' => 'absint',
        'auth_callback'     => $auth_callback,
    ]);
}
add_action('init', 'lemonds_register_post_meta_works', 0);
