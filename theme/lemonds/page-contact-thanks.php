<?php
/**
 * Template Name: お問い合わせ送信完了
 *
 * 固定ページ `/contact/thanks/` 用テンプレート。
 * CF7 の送信成功時に on_sent_ok 相当のリダイレクトで遷移してくる想定。
 *
 * スラッグが `thanks`（親: contact）の場合は `page-thanks.php` に
 * マッチするため、`page-contact-thanks.php` は Template Name 経由でも
 * 選択できるようにしてある（運用ガイドにも記載）。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php get_template_part('template-parts/sub-header'); ?>

<?php
get_template_part('template-parts/breadcrumb', null, [
    'items' => [
        ['label' => 'トップ', 'url' => lemonds_url('home')],
        ['label' => 'お問い合わせ', 'url' => lemonds_url('contact')],
        ['label' => '送信完了'],
    ],
]);
?>

<main class="lm-page lm-page--contact-thanks">
    <section class="lm-section" data-screen-label="Thanks">
        <div class="lm-thanks">
            <div class="k">&mdash; THANK YOU</div>
            <h1>お問い合わせありがとうございました。</h1>
            <p>
                送信が完了いたしました。<br />
                内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。<br />
                お急ぎの場合は、お電話（03-5969-9075）よりお問い合わせください。
            </p>
            <div class="ctas">
                <a
                    href="<?= esc_url(lemonds_url('home')) ?>"
                    class="lm-pill-outline lm-pill-outline--section-action lm-pill-outline--back-action lm-thanks-button lm-thanks-button--home"
                    style="text-decoration: none;"
                >
                    <span class="circle">&larr;</span>
                    <span class="label">トップへ戻る</span>
                </a>
                <a
                    href="<?= esc_url(lemonds_url('services')) ?>"
                    class="lm-pill-outline lm-pill-outline--section-action lm-thanks-button lm-thanks-button--services"
                    style="text-decoration: none;"
                >
                    <span class="label">事業内容を見る</span>
                    <span class="circle">&rarr;</span>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_template_part('template-parts/contact-cta'); ?>
<?php get_footer();
