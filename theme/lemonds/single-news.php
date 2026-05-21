<?php
/**
 * Single Template: お知らせ詳細 (news CPT)
 *
 * 元データ: news-detail.html + src/pages/NewsDetailPage.jsx
 *
 * URL: /news/<slug>/
 * - ヘッダ部（カテゴリ / 公開日 / タイトル）は単テンプレで組む
 * - 本文は the_content() で Gutenberg ブロックをそのまま出す
 * - 関連投稿: 同カテゴリ posts_per_page=3 + 自分除外、不足分は新しい順で補完
 * - 旧 URL 互換: ?slug=xxx で来た場合 permalink へ 301 リダイレクト
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 旧 URL（news-detail.html?slug=xxx）からのアクセスを permalink へリダイレクト
 *
 * - is_singular('news') かつ ?slug=xxx で現ページとマッチしないケースを救う
 * - 例: /?slug=xxx でも、is_singular(news) のアーカイブ画面でクエリパラメータがある場合
 *
 * 移行から 30 日間の保険的処理（schema-registry / Wave 4 連動）
 */
if (!headers_sent() && isset($_GET['slug'])) {
    $requested_slug = sanitize_title(wp_unslash($_GET['slug']));
    if ($requested_slug !== '') {
        // 現在の post slug と異なる、または index.php?slug= 経由なら正しい URL に飛ばす
        $current_slug = get_post_field('post_name', get_queried_object_id());
        if ($current_slug !== $requested_slug) {
            $target_post = get_page_by_path($requested_slug, OBJECT, 'news');
            if ($target_post instanceof WP_Post) {
                $target_url = get_permalink($target_post);
                if ($target_url) {
                    wp_safe_redirect($target_url, 301);
                    exit;
                }
            }
        }
    }
}

get_header();

while (have_posts()) :
    the_post();

    $post_id     = get_the_ID();
    $title       = get_the_title();
    $date_full   = mysql2date('Y.m.d', get_post_field('post_date', $post_id));
    $date_y      = mysql2date('Y', get_post_field('post_date', $post_id));
    $date_m      = mysql2date('m', get_post_field('post_date', $post_id));
    $date_d      = mysql2date('d', get_post_field('post_date', $post_id));
    $cat_label   = lemonds_news_category_label($post_id);
    $cat_slug    = '';
    $news_terms  = get_the_terms($post_id, 'news_category');
    if (!empty($news_terms) && !is_wp_error($news_terms)) {
        $cat_slug = $news_terms[0]->slug;
    }
    ?>

    <?php
    get_template_part('template-parts/breadcrumb', null, [
        'items' => [
            ['label' => 'トップ', 'url' => lemonds_url('home')],
            ['label' => 'お知らせ', 'url' => lemonds_url('news')],
            ['label' => $title],
        ],
    ]);
    ?>

    <?php
    get_template_part('template-parts/page-hero', null, [
        'en'   => 'NEWS',
        'ja'   => 'お知らせ',
        'lead' => '新規取り扱い商品やプロジェクトリリース、休業日のご案内など、ルモンズエンターテインメントからのお知らせを掲載しています。',
    ]);
    ?>

    <article class="lm-section lm-article-wrap" style="padding-top: 0; padding-bottom: 96px;" data-screen-label="News Article">
        <div class="lm-article">
            <!-- エディトリアル日付 + カテゴリ -->
            <div class="lm-article-meta">
                <div class="lm-article-date">
                    <span class="full"><?php echo esc_html($date_full); ?></span>
                    <span class="y"><?php echo esc_html($date_y); ?></span>
                    <span class="sep">/</span>
                    <span class="md"><?php echo esc_html($date_m); ?><span class="sep small">/</span><?php echo esc_html($date_d); ?></span>
                </div>

                <?php if ($cat_label !== '') : ?>
                    <div class="lm-article-cat lm-cat--<?php echo esc_attr(strtolower($cat_slug ?: $cat_label)); ?>">
                        <?php echo esc_html($cat_label); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- タイトル -->
            <h1 class="lm-article-title"><?php echo esc_html($title); ?></h1>

            <!-- 本文（Gutenberg ブロックをそのまま出力） -->
            <div class="lm-article-body">
                <?php the_content(); ?>
            </div>

            <!-- 一覧へ戻る -->
            <div class="lm-article-foot">
                <a href="<?php echo esc_url(lemonds_url('news')); ?>" class="lm-pill-outline lm-pill-outline--section-action lm-pill-outline--back-action" style="text-decoration:none;">
                    <span class="circle">&larr;</span>
                    <span class="label">お知らせ一覧へ戻る</span>
                </a>
            </div>
        </div>
    </article>

    <?php
    /**
     * 関連お知らせ取得
     * - 同カテゴリ優先、自分除外で 3 件
     * - 不足分は同カテゴリ以外（自分除外）の新しい順で補完
     */
    $related_posts = [];

    if (!empty($cat_slug)) {
        $same_cat_query = new WP_Query([
            'post_type'      => 'news',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$post_id],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [
                [
                    'taxonomy' => 'news_category',
                    'field'    => 'slug',
                    'terms'    => $cat_slug,
                ],
            ],
            'no_found_rows'  => true,
        ]);
        if ($same_cat_query->have_posts()) {
            $related_posts = $same_cat_query->posts;
        }
        wp_reset_postdata();
    }

    // 不足分を新しい順で補完
    if (count($related_posts) < 3) {
        $exclude_ids = array_merge([$post_id], wp_list_pluck($related_posts, 'ID'));
        $fill_query = new WP_Query([
            'post_type'      => 'news',
            'post_status'    => 'publish',
            'posts_per_page' => 3 - count($related_posts),
            'post__not_in'   => $exclude_ids,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ]);
        if ($fill_query->have_posts()) {
            $related_posts = array_merge($related_posts, $fill_query->posts);
        }
        wp_reset_postdata();
    }
    ?>

    <!-- 関連お知らせ -->
    <?php if (!empty($related_posts)) : ?>
        <section class="lm-section" style="padding-top: 0; padding-bottom: 80px;" data-screen-label="Related News">
            <div class="lm-section-head">
                <div>
                    <h2>RELATED</h2>
                    <div class="ja">関連のお知らせ</div>
                </div>
            </div>
            <ul class="lm-news-list">
                <?php foreach ($related_posts as $related) : ?>
                    <?php
                    $rel_permalink = get_permalink($related);
                    $rel_date      = mysql2date('Y.m.d', $related->post_date);
                    $rel_cat       = lemonds_news_category_label($related->ID);
                    ?>
                    <li class="lm-news-row" onclick="location.href='<?php echo esc_url($rel_permalink); ?>'">
                        <a href="<?php echo esc_url($rel_permalink); ?>" class="lm-news-row__link" style="display:contents;">
                            <span class="date"><?php echo esc_html($rel_date); ?></span>
                            <span class="cat"><?php echo esc_html($rel_cat); ?></span>
                            <span class="title"><?php echo esc_html(get_the_title($related)); ?></span>
                            <span class="arrow">&rarr;</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php
    /**
     * PREV / NEXT （新しい順の隣接、自分以外で循環）
     * - get_adjacent_post を使うと年月日で循環できないので、ID リストで自前計算
     */
    $all_news = get_posts([
        'post_type'      => 'news',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);

    $prev_id = null;
    $next_id = null;
    if (!empty($all_news)) {
        $count   = count($all_news);
        $cur_idx = array_search($post_id, $all_news, true);
        if ($cur_idx !== false && $count > 1) {
            $prev_idx = ($cur_idx - 1 + $count) % $count;
            $next_idx = ($cur_idx + 1) % $count;
            $prev_id  = $all_news[$prev_idx];
            $next_id  = $all_news[$next_idx];
        }
    }
    ?>

    <!-- PREV / NEXT + 一覧へ -->
    <section class="lm-section" style="padding-top: 0; padding-bottom: 80px;">
        <?php if ($prev_id && $next_id) : ?>
            <div class="lm-news-pager">
                <a href="<?php echo esc_url(get_permalink($prev_id)); ?>" class="prev">
                    <span class="k">&larr; PREV</span>
                    <span class="t"><?php echo esc_html(get_the_title($prev_id)); ?></span>
                </a>
                <a href="<?php echo esc_url(get_permalink($next_id)); ?>" class="next">
                    <span class="k">NEXT &rarr;</span>
                    <span class="t"><?php echo esc_html(get_the_title($next_id)); ?></span>
                </a>
            </div>
        <?php endif; ?>
        <div class="lm-news-pager-index">
            <a href="<?php echo esc_url(lemonds_url('news')); ?>" class="index lm-pill-outline lm-pill-outline--section-action lm-pill-outline--back-action">
                <span class="circle">&larr;</span>
                <span class="label">お知らせ一覧へ</span>
            </a>
        </div>
    </section>

<?php endwhile; ?>

<?php get_template_part('template-parts/contact-cta'); ?>

<?php
get_footer();
