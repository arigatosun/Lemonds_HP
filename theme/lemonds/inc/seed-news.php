<?php
/**
 * Seed Script: news CPT 初期データ投入
 *
 * 元データ: src/pages/NewsPage.jsx + src/pages/NewsDetailPage.jsx
 *
 * 実行方法（WP-CLI）:
 *   cd <WordPress ルート>
 *   wp eval-file wp-content/themes/lemonds/inc/seed-news.php
 *
 * もしくは Local by Flywheel のシェル経由:
 *   wp eval-file wp-content/themes/lemonds/inc/seed-news.php --path=app/public
 *
 * 仕様:
 * - 冪等性: 同じ slug の投稿が既にあれば skip
 * - news_category の 4 ターム（press / notice / project / company）を冪等に作成
 * - 8 件のお知らせを投入（NewsPage.jsx 全件 + NewsDetailPage.jsx 本文）
 * - 本文がない 3 件は title から自動生成した仮本文を入れる（後でクライアントが差し替え）
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    // WP-CLI 経由でない場合の安全装置
    if (!defined('WP_CLI') || !WP_CLI) {
        fwrite(STDERR, "This script must be run via WP-CLI (wp eval-file).\n");
        exit(1);
    }
}

/**
 * news_category タームを冪等に作成
 */
$news_terms = [
    ['name' => 'PRESS',   'slug' => 'press'],
    ['name' => 'NOTICE',  'slug' => 'notice'],
    ['name' => 'PROJECT', 'slug' => 'project'],
    ['name' => 'COMPANY', 'slug' => 'company'],
];

foreach ($news_terms as $term) {
    if (!term_exists($term['slug'], 'news_category')) {
        $result = wp_insert_term($term['name'], 'news_category', ['slug' => $term['slug']]);
        if (is_wp_error($result)) {
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::warning("term insert failed: {$term['slug']} - " . $result->get_error_message());
            }
        } else {
            if (defined('WP_CLI') && WP_CLI) {
                WP_CLI::log("term created: {$term['name']} ({$term['slug']})");
            }
        }
    } else {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::log("term exists (skip): {$term['slug']}");
        }
    }
}

/**
 * 投入するお知らせデータ
 *
 * - date は YYYY.MM.DD（NewsPage.jsx の表記をそのまま）→ post_date 用に YYYY-MM-DD 09:00:00 へ変換
 * - body は文字列の配列 → Gutenberg 段落ブロックに変換
 * - category は news_category の term slug
 */
$news_items = [
    [
        'slug'     => 'health-brand-launch',
        'date'     => '2026.04.10',
        'category' => 'press',
        'title'    => '健康サポート機器・グッズ事業の新規取り扱いブランドが決定しました。',
        'body'     => [
            'いつもお世話になっております。株式会社ルモンズエンターテインメントです。',
            'このたび、健康サポート機器・グッズ事業において、新たに取り扱いを開始するブランドが決定しましたのでお知らせいたします。EMS機器および機能性アパレル領域での法人向け供給ラインナップを拡充し、これまでよりも幅広い用途・ロットでのご提案が可能となります。',
            '具体的な商品ラインナップ・販売開始時期につきましては、改めて当ページおよび営業担当よりご案内いたします。各種お問い合わせはお問い合わせフォームよりお気軽にご連絡ください。',
        ],
    ],
    [
        'slug'     => 'gw-2026',
        'date'     => '2026.03.22',
        'category' => 'notice',
        'title'    => 'ゴールデンウィーク期間中の休業日についてのお知らせ。',
        'body'     => [
            '平素より大変お世話になっております。',
            '誠に勝手ながら、ゴールデンウィーク期間中の休業日を下記の通りとさせていただきます。',
            '休業期間：2026年5月3日（土）〜5月6日（火）。期間中にいただきましたお問い合わせ・ご注文につきましては、5月7日（水）以降に順次対応させていただきます。',
            'お客様にはご不便をおかけいたしますが、何卒ご了承くださいますようお願い申し上げます。',
        ],
    ],
    [
        'slug'     => 'live-merch-released',
        'date'     => '2026.02.28',
        'category' => 'project',
        'title'    => '某ライブ公演グッズの制作実績を公開しました。',
        'body'     => [
            '制作実績ページを更新いたしました。某ライブ公演向けに制作したグッズの実績を公開しております。',
            '公演演出と連動した一体感のある商品設計と、当日の物販導線を踏まえた段階納品など、進行面での工夫もまとめております。詳細は制作実績ページよりご覧ください。',
        ],
    ],
    [
        'slug'     => 'office-relocation',
        'date'     => '2026.01.15',
        'category' => 'company',
        'title'    => '本社オフィス移転のお知らせ（新宿区内）。',
        'body'     => [
            '本社オフィス移転に関する詳細をお知らせいたします。',
        ],
    ],
    [
        'slug'     => 'gacha-launch',
        'date'     => '2025.12.18',
        'category' => 'press',
        'title'    => 'オンラインガチャ事業のサービス提供を開始しました。',
        'body'     => [
            '新規事業としてオンラインガチャ（くじ）事業の提供を開始いたしました。',
        ],
    ],
    [
        'slug'     => 'hp-renewal',
        'date'     => '2025.11.01',
        'category' => 'company',
        'title'    => 'コーポレートサイトをリニューアルしました。',
        'body'     => [
            'コーポレートサイトをリニューアルいたしました。',
            '事業内容・制作実績・お問い合わせ動線を見直し、より分かりやすくご覧いただけるよう改善しております。引き続きよろしくお願い申し上げます。',
        ],
    ],
    [
        'slug'     => 'event-exhibitor',
        'date'     => '2025.10.10',
        'category' => 'notice',
        'title'    => 'グッズ展示会への出展のお知らせ。',
        'body'     => [
            'グッズ展示会へ出展いたします。詳細は追って当ページにてご案内いたします。',
        ],
    ],
    [
        'slug'     => 'design-team',
        'date'     => '2025.09.01',
        'category' => 'company',
        'title'    => 'デザインチームを新設しました。',
        'body'     => [
            '社内にデザインチームを新設いたしました。パッケージ・グラフィック・Webと幅広く対応してまいります。',
        ],
    ],
];

/**
 * 文字列の配列を Gutenberg の段落ブロックに変換する
 *
 * @param string[] $paragraphs
 * @return string
 */
function lemonds_seed_news_paragraphs_to_blocks($paragraphs) {
    if (empty($paragraphs)) {
        return '';
    }
    $blocks = [];
    foreach ($paragraphs as $p) {
        $escaped = esc_html($p);
        $blocks[] = "<!-- wp:paragraph -->\n<p>{$escaped}</p>\n<!-- /wp:paragraph -->";
    }
    return implode("\n\n", $blocks);
}

/**
 * YYYY.MM.DD 形式の文字列を MySQL datetime 形式に変換する
 *
 * @param string $date_str
 * @return string YYYY-MM-DD 09:00:00
 */
function lemonds_seed_news_date_to_mysql($date_str) {
    $parts = explode('.', $date_str);
    if (count($parts) !== 3) {
        return current_time('mysql');
    }
    $y = (int) $parts[0];
    $m = (int) $parts[1];
    $d = (int) $parts[2];
    return sprintf('%04d-%02d-%02d 09:00:00', $y, $m, $d);
}

/**
 * 投稿の作成（冪等）
 */
$created = 0;
$skipped = 0;

foreach ($news_items as $item) {
    // 同一 slug の既存投稿があれば skip
    $existing = get_page_by_path($item['slug'], OBJECT, 'news');
    if ($existing instanceof WP_Post) {
        $skipped++;
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::log("news exists (skip): {$item['slug']}");
        }
        continue;
    }

    $post_date    = lemonds_seed_news_date_to_mysql($item['date']);
    $post_content = lemonds_seed_news_paragraphs_to_blocks($item['body']);

    $post_id = wp_insert_post([
        'post_type'    => 'news',
        'post_status'  => 'publish',
        'post_title'   => $item['title'],
        'post_name'    => $item['slug'],
        'post_content' => $post_content,
        'post_date'    => $post_date,
        'post_date_gmt' => get_gmt_from_date($post_date),
    ], true);

    if (is_wp_error($post_id)) {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::warning("post insert failed: {$item['slug']} - " . $post_id->get_error_message());
        }
        continue;
    }

    // カテゴリ付与
    if (!empty($item['category'])) {
        $term = get_term_by('slug', $item['category'], 'news_category');
        if ($term && !is_wp_error($term)) {
            wp_set_object_terms($post_id, [(int) $term->term_id], 'news_category', false);
        }
    }

    $created++;
    if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::log("news created: {$item['slug']} (ID={$post_id})");
    }
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success("seed-news.php done. created={$created}, skipped={$skipped}, total=" . count($news_items));
} else {
    echo "seed-news.php done. created={$created}, skipped={$skipped}, total=" . count($news_items) . "\n";
}
