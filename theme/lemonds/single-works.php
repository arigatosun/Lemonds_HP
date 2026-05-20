<?php
/**
 * Template: 制作実績 詳細（/works/<slug>/）
 *
 * ヒーロー部（タイトル + クライアント + 日付）は本テンプレで組み、
 * 本文以下（lead / details / gallery）は Gutenberg 本文 `the_content()` で出力する。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 旧 URL 互換: `/works-detail.html?slug=xxx` 形式で来た場合に新 permalink へ 301。
 * 30 日間の保険として `single-works.php` 冒頭で受ける（page-migrator 規約）。
 */
if (!is_admin() && isset($_GET['slug']) && !is_singular('works')) {
    $lm_legacy_slug = sanitize_title(wp_unslash($_GET['slug']));
    if ($lm_legacy_slug !== '') {
        $lm_legacy_post = get_page_by_path($lm_legacy_slug, OBJECT, 'works');
        if ($lm_legacy_post instanceof WP_Post) {
            wp_safe_redirect(get_permalink($lm_legacy_post), 301);
            exit;
        }
    }
}

// 一覧で使う並び順（archive と同一ロジック）。前後ナビ用にスラッグ配列を作る。
$lemonds_works_all_query = new WP_Query([
    'post_type'      => 'works',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => [
        'meta_value_num' => 'ASC',
        'date'           => 'DESC',
    ],
    'meta_key'       => 'sort_order',
    'meta_query'     => [
        'relation' => 'OR',
        [
            'key'     => 'sort_order',
            'compare' => 'EXISTS',
        ],
        [
            'key'     => 'sort_order',
            'compare' => 'NOT EXISTS',
        ],
    ],
    'fields'         => 'ids',
]);
$lemonds_works_ids = $lemonds_works_all_query->posts;
wp_reset_postdata();

get_header();

while (have_posts()) :
    the_post();

    $lm_post_id   = get_the_ID();
    $lm_terms     = get_the_terms($lm_post_id, 'works_category');
    $lm_term_main = (!empty($lm_terms) && !is_wp_error($lm_terms)) ? $lm_terms[0] : null;
    $lm_term_name = $lm_term_main ? $lm_term_main->name : '';
    $lm_date      = mysql2date('Y.m.d', get_post()->post_date);

    // クライアント表記
    $lm_client_name = (string) get_post_meta($lm_post_id, 'client_name', true);
    $lm_client_anon = (bool) get_post_meta($lm_post_id, 'client_anonymous', true);
    $lm_client_label = $lm_client_anon ? '非公開' : ($lm_client_name !== '' ? $lm_client_name : '—');

    // 前後ナビ用のインデックス算出
    $lm_idx   = array_search($lm_post_id, $lemonds_works_ids, true);
    $lm_total = count($lemonds_works_ids);
    $lm_prev_id = null;
    $lm_next_id = null;
    if ($lm_idx !== false && $lm_total > 1) {
        $lm_prev_id = $lemonds_works_ids[($lm_idx - 1 + $lm_total) % $lm_total];
        $lm_next_id = $lemonds_works_ids[($lm_idx + 1) % $lm_total];
    }
    ?>

    <?php get_template_part('template-parts/sub-header'); ?>

    <?php
    get_template_part(
        'template-parts/breadcrumb',
        null,
        [
            'items' => [
                ['label' => 'トップ',     'url' => lemonds_url('home')],
                ['label' => '制作実績',   'url' => lemonds_url('works')],
                ['label' => get_the_title()],
            ],
        ]
    );
    ?>

    <main class="lm-page lm-page--works-detail lm-works-detail">

        <?php /* ヒーロー部（タイトル + クライアント + 日付） */ ?>
        <section class="lm-section" style="padding-top: 32px; padding-bottom: 64px;">
            <div class="lm-work-detail-head">
                <div class="meta">
                    <?php if ($lm_term_name !== '') : ?>
                        <span class="cat"><?php echo esc_html($lm_term_name); ?></span>
                    <?php endif; ?>
                    <span class="date"><?php echo esc_html($lm_date); ?></span>
                </div>
                <h1 class="t"><?php the_title(); ?></h1>
                <?php
                $lm_excerpt = get_the_excerpt();
                if ($lm_excerpt !== '') :
                ?>
                    <p class="lead"><?php echo esc_html($lm_excerpt); ?></p>
                <?php endif; ?>
                <div class="client">&mdash; CLIENT / <?php echo esc_html($lm_client_label); ?></div>
            </div>
        </section>

        <?php /* メイン画像（Featured Image） */ ?>
        <?php if (has_post_thumbnail()) : ?>
            <section class="lm-section" style="padding-top: 0; padding-bottom: 48px;">
                <div class="lm-work-detail-hero-image">
                    <?php the_post_thumbnail('full', ['class' => 'lm-work-detail-hero-image__img', 'alt' => esc_attr(get_the_title())]); ?>
                </div>
            </section>
        <?php endif; ?>

        <?php /* サイド情報 + 本文（Gutenberg） */ ?>
        <section class="lm-section" style="padding-top: 0;">
            <div class="lm-work-detail-grid">
                <aside class="aside">
                    <div class="k">&mdash; PROJECT</div>
                    <div class="v"><?php the_title(); ?></div>
                    <div class="k" style="margin-top: 32px;">&mdash; CLIENT</div>
                    <div class="v"><?php echo esc_html($lm_client_label); ?></div>
                    <div class="k" style="margin-top: 32px;">&mdash; DATE</div>
                    <div class="v"><?php echo esc_html($lm_date); ?></div>
                    <?php if ($lm_term_name !== '') : ?>
                        <div class="k" style="margin-top: 32px;">&mdash; CATEGORY</div>
                        <div class="v"><?php echo esc_html($lm_term_name); ?></div>
                    <?php endif; ?>
                </aside>

                <div class="lm-work-detail-body">
                    <?php
                    // 本文（Gutenberg ブロックをそのまま出す）
                    the_content();
                    ?>
                </div>
            </div>
        </section>

        <?php /* 前後ナビ */ ?>
        <section class="lm-section" style="padding-top: 80px;">
            <div class="lm-work-pager">
                <?php if ($lm_prev_id) : ?>
                    <a href="<?php echo esc_url(get_permalink($lm_prev_id)); ?>" class="prev">
                        <span class="k">&larr; PREV</span>
                        <span class="t"><?php echo esc_html(get_the_title($lm_prev_id)); ?></span>
                    </a>
                <?php else : ?>
                    <span class="prev is-disabled"></span>
                <?php endif; ?>

                <a href="<?php echo esc_url(lemonds_url('works')); ?>"
                   class="index lm-pill-outline lm-pill-outline--section-action lm-pill-outline--back-action">
                    <span class="circle">&larr;</span>
                    <span class="label">一覧へ戻る</span>
                </a>

                <?php if ($lm_next_id) : ?>
                    <a href="<?php echo esc_url(get_permalink($lm_next_id)); ?>" class="next">
                        <span class="k">NEXT &rarr;</span>
                        <span class="t"><?php echo esc_html(get_the_title($lm_next_id)); ?></span>
                    </a>
                <?php else : ?>
                    <span class="next is-disabled"></span>
                <?php endif; ?>
            </div>
        </section>

    </main>

<?php endwhile; ?>

<?php get_template_part('template-parts/contact-cta'); ?>

<?php get_footer();
