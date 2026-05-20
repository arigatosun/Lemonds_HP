<?php
/**
 * Contact Form 7 用のテーマ側カスタマイズ
 *
 * - 送信成功時に `/contact/thanks/` へリダイレクトする JS を wp_footer に挿入
 *   （CF7 標準は AJAX 完了でメッセージを差し替えるのみで遷移しないため、
 *    `wpcf7mailsent` イベントを捕捉して location.href で遷移させる）
 * - contact ページ以外では何も出力しない（バンドル肥大化防止）
 *
 * 参照:
 * - schema-registry.md §5 Contact フォーム
 * - inc/cf7-mail-template.md（人間向け CF7 設定再現手順）
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 送信成功で /contact/thanks/ に遷移させる JS を wp_footer に出力
 */
function lemonds_cf7_thanks_redirect() {
    // contact 固定ページのみで出力
    if (!is_page('contact')) {
        return;
    }

    // thanks ページの URL を生成
    // page-contact-thanks.php が割り当てられた固定ページ（slug: contact/thanks 等）に解決する。
    // 解決できない場合は site_url() を起点にしたフォールバック URL を使う。
    $thanks_page = get_page_by_path('contact/thanks');
    if ($thanks_page) {
        $thanks_url = get_permalink($thanks_page);
    } else {
        $thanks_url = home_url('/contact/thanks/');
    }
    ?>
    <script>
    (function () {
        document.addEventListener('wpcf7mailsent', function () {
            window.location.href = <?= wp_json_encode($thanks_url) ?>;
        }, false);
    })();
    </script>
    <?php
}
add_action('wp_footer', 'lemonds_cf7_thanks_redirect');

/**
 * （任意）CF7 標準の autop / br を抑制したい場合は以下を有効化する。
 * 現状は CF7 デフォルトの整形に任せ、テーマ側 CSS で見た目を整える。
 *
 * add_filter('wpcf7_autop_or_not', '__return_false');
 */
