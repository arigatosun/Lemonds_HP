<?php
/**
 * Template Name: お問い合わせ
 *
 * 固定ページ `/contact/` 用テンプレート。
 * フォーム本体は Contact Form 7 の shortcode に委譲し、ここでは
 * 周囲の HTML 構造（ヒーロー・リード文・補足情報など）のみを管理する。
 *
 * 関連:
 * - schema-registry.md §5 Contact フォーム
 * - assets/js/contact.js（?type=quote 連動 / form-builder 所有 / Wave 3）
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>


<?php
get_template_part('template-parts/breadcrumb', null, [
    'items' => [
        ['label' => 'トップ', 'url' => lemonds_url('home')],
        ['label' => 'お問い合わせ'],
    ],
]);
?>

<?php
get_template_part('template-parts/page-hero', null, [
    'en'   => 'CONTACT',
    'ja'   => 'お問い合わせ',
    'lead' => 'グッズ制作、MD、配送、オンライン施策など、まずはお気軽にご相談ください。内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。',
]);
?>

<main class="lm-page lm-page--contact">
    <section class="lm-section" style="padding-top: 0;">
        <div class="lm-form">
            <?= do_shortcode('[contact-form-7 id="50e7785" title="お問い合わせ"]') ?>

            <p class="reply">
                内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。
            </p>

            <p class="lm-form-policy-note">
                送信前に
                <a href="<?= esc_url(lemonds_url('policy')) ?>">個人情報保護方針</a>
                をご確認ください。
            </p>
        </div>
    </section>
</main>

<?php
get_footer();
