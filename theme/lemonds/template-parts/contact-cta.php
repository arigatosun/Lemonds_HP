<?php
/**
 * Contact CTA template part（下層ページ共通の下部 CTA）
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="lm-section lm-section--dark lm-contact-cta" data-screen-label="CTA Contact">
    <div class="lm-contact-cta__panel">
        <div class="lm-contact-cta__photo">
            <div class="lm-contact-cta__badge">— IN PERSON</div>
            <div class="lm-contact-cta__caption">
                <div class="lm-contact-cta__caption-k">— STUDIO / TOKYO</div>
                <div class="lm-contact-cta__caption-title">
                    直接お会いしての<br />ご相談も承ります。
                </div>
            </div>
        </div>

        <div class="lm-contact-cta__body">
            <div class="lm-contact-cta__eyebrow">— SECTION 08</div>
            <div>
                <h2 class="lm-contact-cta__title">CONTACT.</h2>
                <div class="lm-contact-cta__ja">お問い合わせ</div>
            </div>
            <p class="lm-contact-cta__copy">
                <span class="lm-contact-cta__copy-unit">グッズ制作、MD、配送、</span>
                <span class="lm-contact-cta__copy-unit">オンライン施策まで、</span><br />
                <span class="lm-contact-cta__copy-unit">条件整理からお手伝いします。</span>
            </p>

            <div class="lm-contact-cta__actions">
                <a href="<?php echo esc_url(lemonds_url('contact')); ?>" class="lm-contact-btn lm-contact-btn--primary">
                    <span>お問い合わせする</span><span class="arrow">&rarr;</span>
                </a>
                <a href="<?php echo esc_url(lemonds_url('contact_quote')); ?>" class="lm-contact-btn lm-contact-btn--secondary">
                    <span>見積もりを相談する</span><span class="arrow">&rarr;</span>
                </a>
            </div>

            <div class="lm-contact-cta__info">
                <div>
                    <div class="lm-contact-cta__info-label">TEL</div>
                    <div class="lm-contact-cta__info-value">03-5969-9075</div>
                </div>
            </div>
        </div>
    </div>
</section>
