<?php
/**
 * Page Hero template part
 *
 * 使い方:
 * get_template_part('template-parts/page-hero', null, [
 *     'en'   => 'WORKS',
 *     'ja'   => '制作実績',
 *     'lead' => '......',
 * ]);
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = isset($args) && is_array($args) ? $args : [];
$en   = isset($args['en']) ? (string) $args['en'] : '';
$ja   = isset($args['ja']) ? (string) $args['ja'] : '';
$lead = isset($args['lead']) ? (string) $args['lead'] : '';
?>
<section class="lm-page-hero">
    <div class="inner">
        <div class="head">
            <?php if ($en !== '') : ?>
                <h1 class="en"><?php echo esc_html($en); ?></h1>
            <?php endif; ?>
            <?php if ($ja !== '') : ?>
                <div class="ja"><?php echo esc_html($ja); ?></div>
            <?php endif; ?>
        </div>
        <?php if ($lead !== '') : ?>
            <p class="lead"><?php echo nl2br(esc_html($lead)); ?></p>
        <?php endif; ?>
    </div>
</section>
