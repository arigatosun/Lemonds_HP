<?php
/**
 * 404 template
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="lm-main lm-main--404">
    <section class="lm-page-hero">
        <div class="inner">
            <div class="head">
                <h1 class="en">404</h1>
                <div class="ja">ページが見つかりません</div>
            </div>
            <p class="lead">
                お探しのページは存在しないか、移動・削除された可能性があります。<br />
                URL をご確認のうえ、再度アクセスしてください。
            </p>
        </div>
    </section>

    <section class="lm-section lm-section--404-actions">
        <div class="lm-inner" style="text-align:center; padding: 48px 0;">
            <a href="<?php echo esc_url(lemonds_url('home')); ?>" class="lm-btn lm-btn-primary">トップへ戻る</a>
            <a href="<?php echo esc_url(lemonds_url('contact')); ?>" class="lm-btn lm-btn-secondary">お問い合わせ</a>
        </div>
    </section>
</main>
<?php
get_template_part('template-parts/contact-cta');
get_footer();
