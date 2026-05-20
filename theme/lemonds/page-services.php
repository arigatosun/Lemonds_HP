<?php
/**
 * Template Name: 事業内容
 *
 * Services ページ（/services/）テンプレート。
 * 8 事業データは inc/data-services.php から require して取得する。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

// 8 事業データを読み込み（functions.php は編集禁止のためテンプレ内で require）
$services = require get_theme_file_path('inc/data-services.php');
if (!is_array($services)) {
    $services = [];
}
$services_total = count($services);

get_header();
?>

<?php get_template_part('template-parts/sub-header'); ?>

<?php
get_template_part(
    'template-parts/breadcrumb',
    null,
    [
        'items' => [
            ['label' => 'トップ', 'url' => lemonds_url('home')],
            ['label' => '事業内容'],
        ],
    ]
);
?>

<main class="lm-page lm-page--services">

    <?php
    get_template_part(
        'template-parts/page-hero',
        null,
        [
            'en'   => 'SERVICE',
            'ja'   => '事業内容',
            'lead' => 'アパレル、ノベルティ、健康機器、EC施策まで。幅広く対応する総合グッズカンパニーとして、企画から納品まで一気通貫でお客様のニーズにお応えします。',
        ]
    );
    ?>

    <?php if (!empty($services)) : ?>

        <?php /* 事業カテゴリ一覧（アンカー目次） */ ?>
        <section class="lm-section" style="padding-top:0;" data-screen-label="Services Index">
            <ul class="lm-svc-index">
                <?php foreach ($services as $s) : ?>
                    <li>
                        <a href="#<?php echo esc_attr($s['id']); ?>">
                            <span class="no"><?php echo esc_html($s['no']); ?></span>
                            <span class="jp">
                                <?php echo esc_html($s['jp']); ?>
                                <?php if (!empty($s['status'])) : ?>
                                    <em class="status">（<?php echo esc_html($s['status']); ?>）</em>
                                <?php endif; ?>
                            </span>
                            <span class="arrow">&darr;</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <?php /* 各事業の詳細セクション */ ?>
        <section class="lm-section" style="padding-top:0;">
            <div class="lm-svc-list">
                <?php foreach ($services as $i => $s) :
                    $is_pending  = !empty($s['status']);
                    $is_flip     = ($i % 2 === 1);
                    $classes     = ['lm-svc-detail'];
                    if ($is_pending) {
                        $classes[] = 'is-pending';
                    }
                    if ($is_flip) {
                        $classes[] = 'is-flip';
                    }
                    $img_url = lemonds_img($s['img']);
                ?>
                    <article
                        id="<?php echo esc_attr($s['id']); ?>"
                        class="<?php echo esc_attr(implode(' ', $classes)); ?>"
                    >
                        <div class="media">
                            <div class="photo" style="background-image:url('<?php echo esc_url($img_url); ?>');"></div>
                            <?php if ($is_pending) : ?>
                                <span class="badge"><?php echo esc_html($s['status']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="body">
                            <div class="no">
                                <?php echo esc_html($s['no']); ?> / <?php echo esc_html(str_pad((string) $services_total, 2, '0', STR_PAD_LEFT)); ?>
                            </div>
                            <h2 class="jp"><?php echo esc_html($s['jp']); ?></h2>
                            <div class="en"><?php echo esc_html($s['en']); ?></div>
                            <div class="tagline"><?php echo esc_html($s['tagline']); ?></div>
                            <p class="body-copy"><?php echo esc_html($s['body']); ?></p>
                            <div class="scope">
                                <div class="k">対応範囲</div>
                                <ul>
                                    <?php foreach ((array) $s['scope'] as $scope_item) : ?>
                                        <li><?php echo esc_html($scope_item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php if (!$is_pending) : ?>
                                <a
                                    href="<?php echo esc_url(lemonds_url('contact')); ?>"
                                    class="lm-pill-outline lm-pill-outline--section-action lm-pill-outline--service-consult"
                                >
                                    <span>この事業について相談する</span><span class="circle">&rarr;</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

    <?php endif; ?>

</main>

<?php get_template_part('template-parts/contact-cta'); ?>

<?php
get_footer();
