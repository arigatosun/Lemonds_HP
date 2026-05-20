<?php
/**
 * Archive Template: お知らせ (news CPT 一覧)
 *
 * 元データ: news.html + src/pages/NewsPage.jsx
 *
 * URL: /news/
 * - WP_Query: post_type=news, posts_per_page=12, 新しい順
 * - news_category タクソノミーフィルタ（カテゴリタブ: ALL / PRESS / NOTICE / PROJECT / COMPANY）
 * - 公開日 / カテゴリ / タイトル を表示
 * - permalink は get_permalink($post) 経由
 * - ページネーションは paginate_links() を使用
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// 現在のカテゴリフィルタ（GET パラメータ ?cat=press などで切替）
$current_cat_slug = isset($_GET['cat']) ? sanitize_key($_GET['cat']) : 'all';

// クエリの組み立て
$paged = max(1, (int) get_query_var('paged'));
$query_args = [
    'post_type'      => 'news',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($current_cat_slug !== 'all' && term_exists($current_cat_slug, 'news_category')) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'news_category',
            'field'    => 'slug',
            'terms'    => $current_cat_slug,
        ],
    ];
}

$news_query = new WP_Query($query_args);

// 件数表示用に「現在のフィルタにマッチする総数」を取得
$total_count = (int) $news_query->found_posts;

// フィルタタブ用のカテゴリ一覧
$news_categories = get_terms([
    'taxonomy'   => 'news_category',
    'hide_empty' => false,
    'orderby'    => 'term_id',
    'order'      => 'ASC',
]);
?>

<?php get_template_part('template-parts/sub-header'); ?>

<?php
get_template_part('template-parts/breadcrumb', null, [
    'items' => [
        ['label' => 'トップ', 'url' => lemonds_url('home')],
        ['label' => 'お知らせ'],
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

<main class="lm-page lm-page--news">

    <!-- カテゴリフィルタ -->
    <section class="lm-section lm-news-filter-section">
        <div class="lm-works-filter">
            <a href="<?php echo esc_url(lemonds_url('news')); ?>" class="chip<?php echo ($current_cat_slug === 'all') ? ' is-on' : ''; ?>">すべて</a>
            <?php if (!empty($news_categories) && !is_wp_error($news_categories)) : ?>
                <?php foreach ($news_categories as $cat) : ?>
                    <a href="<?php echo esc_url(add_query_arg('cat', $cat->slug, lemonds_url('news'))); ?>" class="chip<?php echo ($current_cat_slug === $cat->slug) ? ' is-on' : ''; ?>">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="count"><?php echo (int) $total_count; ?> 件</span>
        </div>
    </section>

    <!-- 一覧 + ページネーション -->
    <section class="lm-section lm-news-list-section">
        <?php if ($news_query->have_posts()) : ?>
            <ul class="lm-news-list">
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                    <?php
                    $post_id     = get_the_ID();
                    $permalink   = get_permalink($post_id);
                    $date_label  = mysql2date('Y.m.d', get_post_field('post_date', $post_id));
                    $cat_label   = lemonds_news_category_label($post_id);
                    $title_label = get_the_title($post_id);
                    ?>
                    <li class="lm-news-row" onclick="location.href='<?php echo esc_url($permalink); ?>'">
                        <a href="<?php echo esc_url($permalink); ?>" class="lm-news-row__link" style="display:contents;">
                            <span class="date"><?php echo esc_html($date_label); ?></span>
                            <span class="cat"><?php echo esc_html($cat_label); ?></span>
                            <span class="title"><?php echo esc_html($title_label); ?></span>
                            <span class="arrow">&rarr;</span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>

            <?php
            // ページネーション
            $big = 999999999;
            $pagination_links = paginate_links([
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => (int) $news_query->max_num_pages,
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'type'      => 'array',
                'add_args'  => ($current_cat_slug !== 'all') ? ['cat' => $current_cat_slug] : false,
            ]);

            if (!empty($pagination_links) && is_array($pagination_links)) :
                ?>
                <div class="lm-pagination">
                    <?php foreach ($pagination_links as $link) : ?>
                        <?php
                        // current は <span class="page-numbers current"> として返ってくる
                        // それ以外は <a class="page-numbers"> 形式
                        // class="page" を追加して既存スタイルを継承させる
                        $link_html = $link;
                        if (strpos($link_html, 'current') !== false) {
                            $link_html = str_replace('page-numbers', 'page-numbers page is-on', $link_html);
                        } else {
                            $link_html = str_replace('page-numbers', 'page-numbers page', $link_html);
                        }
                        // 安全: paginate_links は信頼できる HTML を返す
                        echo $link_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="lm-news-empty" style="text-align:center; padding: 80px 0; color:#666;">
                該当するお知らせはありません。
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </section>

</main>

<?php get_template_part('template-parts/contact-cta'); ?>

<?php
get_footer();
