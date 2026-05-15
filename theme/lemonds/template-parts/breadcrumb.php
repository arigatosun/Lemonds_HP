<?php
/**
 * Breadcrumb template part
 *
 * 使い方:
 * get_template_part('template-parts/breadcrumb', null, [
 *     'items' => [
 *         ['label' => 'トップ', 'url' => home_url('/')],
 *         ['label' => '制作実績'],
 *     ],
 * ]);
 *
 * もしくは下位互換:
 * get_template_part('template-parts/breadcrumb', null, [
 *     'current' => '制作実績',
 * ]);
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

$args    = isset($args) && is_array($args) ? $args : [];
$items   = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$current = isset($args['current']) ? (string) $args['current'] : '';

// items が未指定で current だけ来た場合、デフォで [トップ → current] を組む
if (empty($items) && $current !== '') {
    $items = [
        ['label' => 'トップ', 'url' => lemonds_url('home')],
        ['label' => $current],
    ];
}

if (empty($items)) {
    return;
}

echo lemonds_render_breadcrumb($items);
