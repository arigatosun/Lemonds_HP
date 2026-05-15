<?php
/**
 * Meta Box: works (実績メタ情報)
 *
 * 4 フィールド（client_name / client_anonymous / is_featured / sort_order）の
 * シンプルなメタボックスを works CPT 編集画面に追加する。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * メタボックスを登録する。
 */
function lemonds_add_meta_box_works() {
    add_meta_box(
        'works_meta',
        __('実績メタ情報', 'lemonds'),
        'lemonds_render_meta_box_works',
        'works',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'lemonds_add_meta_box_works');

/**
 * メタボックスの内容を描画する。
 *
 * @param WP_Post $post 編集中の投稿オブジェクト。
 */
function lemonds_render_meta_box_works($post) {
    wp_nonce_field('works_meta_save', 'works_meta_nonce');

    $client_name      = (string) get_post_meta($post->ID, 'client_name', true);
    $client_anonymous = (bool) get_post_meta($post->ID, 'client_anonymous', true);
    $is_featured      = (bool) get_post_meta($post->ID, 'is_featured', true);
    $sort_order_raw   = get_post_meta($post->ID, 'sort_order', true);
    $sort_order       = ($sort_order_raw === '') ? 0 : (int) $sort_order_raw;
    ?>
    <p>
        <label for="lemonds_client_name"><strong><?php esc_html_e('クライアント名', 'lemonds'); ?></strong></label><br>
        <input type="text" id="lemonds_client_name" name="lemonds_client_name"
            value="<?php echo esc_attr($client_name); ?>" class="widefat">
    </p>
    <p>
        <label>
            <input type="checkbox" id="lemonds_client_anonymous" name="lemonds_client_anonymous" value="1"
                <?php checked($client_anonymous, true); ?>>
            <?php esc_html_e('クライアント名を匿名表示にする（某◯◯様）', 'lemonds'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" id="lemonds_is_featured" name="lemonds_is_featured" value="1"
                <?php checked($is_featured, true); ?>>
            <?php esc_html_e('トップ掲載候補', 'lemonds'); ?>
        </label>
    </p>
    <p>
        <label for="lemonds_sort_order"><strong><?php esc_html_e('並び順', 'lemonds'); ?></strong></label><br>
        <input type="number" id="lemonds_sort_order" name="lemonds_sort_order"
            value="<?php echo esc_attr((string) $sort_order); ?>" class="widefat" step="1">
        <small><?php esc_html_e('小さい順に表示（既定: 0）', 'lemonds'); ?></small>
    </p>
    <?php
}

/**
 * 保存処理。
 *
 * @param int $post_id 投稿 ID。
 */
function lemonds_save_meta_box_works($post_id) {
    // nonce 検証。
    if (!isset($_POST['works_meta_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['works_meta_nonce'])), 'works_meta_save')) {
        return;
    }

    // 自動保存中はスキップ。
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // 権限チェック。
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // client_name（テキスト）
    $client_name = isset($_POST['lemonds_client_name'])
        ? sanitize_text_field(wp_unslash($_POST['lemonds_client_name']))
        : '';
    update_post_meta($post_id, 'client_name', $client_name);

    // client_anonymous（チェックボックス、未送信は false）
    $client_anonymous = !empty($_POST['lemonds_client_anonymous']);
    update_post_meta($post_id, 'client_anonymous', $client_anonymous);

    // is_featured（チェックボックス、未送信は false）
    $is_featured = !empty($_POST['lemonds_is_featured']);
    update_post_meta($post_id, 'is_featured', $is_featured);

    // sort_order（数値、空なら 0）
    $sort_order = isset($_POST['lemonds_sort_order']) && $_POST['lemonds_sort_order'] !== ''
        ? (int) $_POST['lemonds_sort_order']
        : 0;
    update_post_meta($post_id, 'sort_order', $sort_order);
}
add_action('save_post_works', 'lemonds_save_meta_box_works');
