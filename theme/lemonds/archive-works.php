<?php
/**
 * Template: 制作実績アーカイブ（/works/）
 *
 * works CPT の一覧。`sort_order` 昇順 → 不足は新しい順で取得し、
 * works_category タクソノミーのフィルタタブ（クライアントサイド）を表示する。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

// 一覧データを取得（sort_order 昇順 → 同値内は新しい順）
$lemonds_works_query = new WP_Query([
    'post_type'      => 'works',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => [
        'meta_value_num' => 'ASC',
        'date'           => 'DESC',
    ],
    'meta_key'       => 'sort_order',
    // sort_order が未設定（meta_key 未登録）の投稿も拾えるよう RELATION で OR 風に
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
]);

// カテゴリ一覧（タブ用）— 実際に投稿に紐づいているタームのみ
$lemonds_works_terms = get_terms([
    'taxonomy'   => 'works_category',
    'hide_empty' => true,
    'orderby'    => 'name',
]);
if (is_wp_error($lemonds_works_terms)) {
    $lemonds_works_terms = [];
}

$lemonds_works_total = $lemonds_works_query->found_posts;

get_header();
?>

<?php
get_template_part(
    'template-parts/breadcrumb',
    null,
    [
        'items' => [
            ['label' => 'トップ', 'url' => lemonds_url('home')],
            ['label' => '制作実績'],
        ],
    ]
);
?>

<main class="lm-page lm-page--works">

    <?php
    get_template_part(
        'template-parts/page-hero',
        null,
        [
            'en'   => 'WORKS',
            'ja'   => '制作実績',
            'lead' => 'イベント、エンタメ、企業販促、健康関連商品など、幅広い領域の制作に対応しています。匿名案件を含む実績の一部をご紹介します。',
        ]
    );
    ?>

    <?php /* フィルタタブ */ ?>
    <section class="lm-section" style="padding-top: 0; padding-bottom: 56px;">
        <div class="lm-works-filter" data-lm-works-filter>
            <button type="button" class="chip is-on" data-filter="ALL">すべて</button>
            <?php foreach ($lemonds_works_terms as $term) : ?>
                <button type="button" class="chip" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
            <?php endforeach; ?>
            <span class="count" data-lm-works-count><?php echo (int) $lemonds_works_total; ?> 件</span>
        </div>
    </section>

    <?php /* 一覧 */ ?>
    <section class="lm-section" style="padding-top: 0;">
        <div class="lm-works-grid">
            <?php if ($lemonds_works_query->have_posts()) : ?>
                <?php while ($lemonds_works_query->have_posts()) : $lemonds_works_query->the_post(); ?>
                    <?php
                    $lm_post_id   = get_the_ID();
                    $lm_terms     = get_the_terms($lm_post_id, 'works_category');
                    $lm_term_main = (!empty($lm_terms) && !is_wp_error($lm_terms)) ? $lm_terms[0] : null;
                    $lm_term_slug = $lm_term_main ? $lm_term_main->slug : '';
                    $lm_term_name = $lm_term_main ? $lm_term_main->name : '';
                    $lm_date      = mysql2date('Y.m.d', get_post()->post_date);
                    $lm_thumb_url = get_the_post_thumbnail_url($lm_post_id, 'large');
                    if (!$lm_thumb_url) {
                        // フォールバック: 何も無ければプレースホルダ画像
                        $lm_thumb_url = lemonds_img('photo-merch-flatlay.jpg');
                    }
                    $lm_excerpt = get_the_excerpt();

                    // クライアント名（匿名フラグなら「非公開」を表示）
                    $lm_client_name = (string) get_post_meta($lm_post_id, 'client_name', true);
                    $lm_client_anon = (bool) get_post_meta($lm_post_id, 'client_anonymous', true);
                    $lm_client_label = $lm_client_anon ? '非公開' : ($lm_client_name !== '' ? $lm_client_name : '');
                    ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>"
                       class="lm-work-card"
                       data-category="<?php echo esc_attr($lm_term_slug); ?>">
                        <div class="thumb" style="background-image: url('<?php echo esc_url($lm_thumb_url); ?>');">
                            <span class="arrow">&rarr;</span>
                        </div>
                        <div class="meta">
                            <span class="cat"><?php echo esc_html($lm_term_name); ?></span>
                            <span class="date"><?php echo esc_html($lm_date); ?></span>
                        </div>
                        <h3 class="t"><?php the_title(); ?></h3>
                        <?php if ($lm_excerpt !== '') : ?>
                            <p class="c"><?php echo esc_html($lm_excerpt); ?></p>
                        <?php endif; ?>
                        <?php if ($lm_client_label !== '') : ?>
                            <div class="client">&mdash; <?php echo esc_html($lm_client_label); ?></div>
                        <?php endif; ?>
                    </a>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="lm-works-empty">現在、表示できる制作実績はありません。</p>
            <?php endif; ?>
        </div>
    </section>

    <?php /* フィルタ用のシンプルなインライン JS（jQuery 非依存） */ ?>
    <script>
    (function () {
        var root = document.querySelector('[data-lm-works-filter]');
        if (!root) { return; }
        var chips = root.querySelectorAll('.chip');
        var counter = root.querySelector('[data-lm-works-count]');
        var cards = document.querySelectorAll('.lm-works-grid .lm-work-card');

        function apply(filter) {
            var visible = 0;
            cards.forEach(function (card) {
                var cat = card.getAttribute('data-category') || '';
                var show = (filter === 'ALL') || (cat === filter);
                card.style.display = show ? '' : 'none';
                if (show) { visible++; }
            });
            if (counter) {
                counter.textContent = visible + ' 件';
            }
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                chips.forEach(function (c) { c.classList.remove('is-on'); });
                chip.classList.add('is-on');
                apply(chip.getAttribute('data-filter') || 'ALL');
            });
        });
    })();
    </script>

</main>

<?php get_template_part('template-parts/contact-cta'); ?>

<?php get_footer();
