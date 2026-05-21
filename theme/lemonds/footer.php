<?php
/**
 * Footer template
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<footer class="lm-footer">
    <div class="lm-footer__top">
        <div aria-hidden="true" class="lm-footer__brand">
            LEMONDS<span class="lm-footer__brand-dot">.</span>
        </div>
        <div class="lm-footer__tagline">
            想いを、価値あるカタチに。
        </div>
    </div>

    <div class="lm-footer__grid">
        <div>
            <div class="lm-footer__title">— ADDRESS</div>
            <p class="lm-footer__text">
                株式会社ルモンズエンターテインメント<br />
                〒160-0022<br />
                東京都新宿区新宿6丁目24-20<br />
                KDX新宿6丁目ビル8F
            </p>
            <div class="lm-footer__title lm-footer__title--spaced">— CONTACT</div>
            <p class="lm-footer__text">
                TEL  03-5969-9075
            </p>
        </div>

        <div>
            <div class="lm-footer__title">— SERVICE</div>
            <ul class="lm-footer__list">
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('services')); ?>#oem" class="lm-footer__link">OEM・ODM 事業</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('services')); ?>#md" class="lm-footer__link">MD 事業</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('services')); ?>#health" class="lm-footer__link">健康サポート機器・グッズ</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('services')); ?>#logistics" class="lm-footer__link">倉庫・配送代行</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('services')); ?>#gacha" class="lm-footer__link">オンラインガチャ</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('services')); ?>#design" class="lm-footer__link">パッケージ・グラフィックデザイン</a></li>
            </ul>
        </div>

        <div>
            <div class="lm-footer__title">— COMPANY</div>
            <ul class="lm-footer__list">
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('company')); ?>" class="lm-footer__link">会社概要</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('works')); ?>" class="lm-footer__link">制作実績</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('news')); ?>" class="lm-footer__link">お知らせ</a></li>
            </ul>
        </div>

        <div>
            <div class="lm-footer__title">— CONTACT</div>
            <ul class="lm-footer__list">
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('contact')); ?>" class="lm-footer__link">お問い合わせフォーム</a></li>
                <li class="lm-footer__item"><a href="<?php echo esc_url(lemonds_url('contact_quote')); ?>" class="lm-footer__link">見積もり依頼</a></li>
            </ul>
        </div>
    </div>

    <div class="lm-footer__bottom">
        <span>&copy; 2026 LEMONDS ENTERTAINMENT CO.,LTD.</span>
        <span class="lm-footer__legal">
            <a href="<?php echo esc_url(lemonds_url('policy')); ?>">PRIVACY POLICY</a>
        </span>
    </div>
</footer>

  </div><!-- /#root -->
</div><!-- /#stage -->

<?php wp_footer(); ?>
</body>
</html>
