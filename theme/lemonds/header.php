<?php
/**
 * Header template
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

$lemonds_logo = function_exists('lemonds_logo_brand_url') ? lemonds_logo_brand_url() : '';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="profile" href="https://gmpg.org/xfn/11" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>

<header class="lm-header">
    <a href="<?php echo esc_url(lemonds_url('home')); ?>" class="lm-header__logo">
        <?php if ($lemonds_logo) : ?>
            <img src="<?php echo esc_url($lemonds_logo); ?>" alt="LEMONDS ENTERTAINMENT Co.,Ltd" />
        <?php else : ?>
            <span>LEMONDS</span>
        <?php endif; ?>
    </a>

    <nav class="lm-header__nav" aria-label="Primary navigation">
        <a href="<?php echo esc_url(lemonds_url('services')); ?>">事業内容</a>
        <a href="<?php echo esc_url(lemonds_url('works')); ?>">制作実績</a>
        <a href="<?php echo esc_url(lemonds_url('news')); ?>">お知らせ</a>
        <a href="<?php echo esc_url(lemonds_url('company')); ?>">会社概要</a>
    </nav>

    <div class="lm-header__actions">
        <a href="<?php echo esc_url(lemonds_url('contact_quote')); ?>" class="lm-btn lm-btn-secondary lm-header__button">見積もり依頼</a>
        <a href="<?php echo esc_url(lemonds_url('contact')); ?>" class="lm-btn lm-btn-primary lm-header__button">お問い合わせ</a>
    </div>

    <button type="button" class="lm-header__menu" aria-label="Menu" aria-expanded="false" data-lm-menu-toggle>
        <span></span>
        <span></span>
    </button>

    <div class="lm-header__drawer">
        <nav class="lm-header__drawer-nav" aria-label="Mobile navigation">
            <a href="<?php echo esc_url(lemonds_url('services')); ?>">事業内容</a>
            <a href="<?php echo esc_url(lemonds_url('works')); ?>">制作実績</a>
            <a href="<?php echo esc_url(lemonds_url('news')); ?>">お知らせ</a>
            <a href="<?php echo esc_url(lemonds_url('company')); ?>">会社概要</a>
        </nav>
        <div class="lm-header__drawer-actions">
            <a href="<?php echo esc_url(lemonds_url('contact_quote')); ?>" class="lm-btn lm-btn-secondary lm-header__button">見積もり依頼</a>
            <a href="<?php echo esc_url(lemonds_url('contact')); ?>" class="lm-btn lm-btn-primary lm-header__button">お問い合わせ</a>
        </div>
    </div>
</header>
