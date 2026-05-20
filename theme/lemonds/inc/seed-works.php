<?php
/**
 * Seed: works CPT
 *
 * `src/pages/WorksPage.jsx` の 9 件 + `src/pages/WorksDetailPage.jsx` の詳細 5 件分を
 * WordPress に投入する冪等スクリプト。
 *
 * 実行方法（プロジェクトルートで）:
 *
 *   wp eval-file wp-content/themes/lemonds/inc/seed-works.php
 *
 *   # 既存投稿を上書きしたい場合（強制再投入）:
 *   wp eval-file wp-content/themes/lemonds/inc/seed-works.php --force
 *
 * 動作:
 * - 同じ post_name（slug）の投稿が既にあれば skip（冪等）
 *   ただし `--force` を渡した場合は wp_update_post で再同期する。
 * - Featured Image はテーマの assets/img/ にコピー済みの photo-*.jpg を
 *   `media_sideload_image()` でメディアライブラリに取り込み、サムネとして設定する。
 * - 詳細（lead / details）は Gutenberg 段落 + テーブル相当のブロックで投入する。
 * - 9 件中、詳細未実装の 4 件は最低限の情報（タイトル + 画像 + クライアント）のみ投入。
 *
 * 依存:
 * - works CPT / works_category タクソノミー / meta-box-works が事前登録済（Wave 1 完了）。
 * - WP-CLI（`wp` コマンド）が利用可能な環境。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    // 直接アクセスは禁止（wp eval-file 経由のみ）
    exit;
}

// WP-CLI 経由のみ実行を許可（誤実行防止）
if (!defined('WP_CLI') || !WP_CLI) {
    echo "[seed-works] WP-CLI からのみ実行してください: wp eval-file wp-content/themes/lemonds/inc/seed-works.php\n";
    return;
}

// メディア取り込みに必要な WP コア関数を読み込み
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

// ----------------------------------------------------------------------
// CLI 引数
// ----------------------------------------------------------------------
$lm_seed_force = false;
if (isset($GLOBALS['argv']) && is_array($GLOBALS['argv'])) {
    foreach ($GLOBALS['argv'] as $arg) {
        if ($arg === '--force') {
            $lm_seed_force = true;
        }
    }
}

// ----------------------------------------------------------------------
// データ定義（src/pages/WorksPage.jsx + WorksDetailPage.jsx から抽出）
// ----------------------------------------------------------------------

/**
 * 各 work エントリのスキーマ:
 *   slug          : post_name（旧 ?slug=xxx と一致）
 *   title         : post_title
 *   excerpt       : post_excerpt（一覧の copy）
 *   client_name   : クライアント名
 *   anonymous     : true なら「非公開」表示
 *   category_slug : works_category タームの slug
 *   date          : 'YYYY.MM.DD' 形式（post_date に変換）
 *   image         : assets/img/ 配下のファイル名
 *   lead          : 詳細冒頭の lead 段落（未実装は空文字）
 *   details       : [[label, value], ...]（未実装は空配列）
 *   gallery       : 追加ギャラリー画像のファイル名配列（未実装は空配列）
 *   sort_order    : int（昇順表示）
 *   is_featured   : bool（トップ掲載候補）
 */
$lm_works_data = [
    [
        'slug'          => 'live-merch-2026',
        'title'         => '某ライブ公演向けグッズ制作',
        'excerpt'       => '数千名規模の公演で配布する一体感をつくるグッズの企画〜量産。',
        'client_name'   => '某ライブ公演',
        'anonymous'     => true,
        'category_slug' => 'event-live',
        'date'          => '2026.04.10',
        'image'         => 'photo-concert-lights.jpg',
        'lead'          => '数千名規模の公演で配布する一体感をつくるグッズの企画〜量産。',
        'details'       => [
            ['依頼背景', 'ファンクラブ会員と一般動員が混在する公演で、客席に一体感を作りたいという背景。視覚演出と連動したグッズ制作を依頼いただきました。'],
            ['制作した商品', 'ペンライト連動Tシャツ／公演限定タオル／アクリルキーホルダー／ステッカーセット'],
            ['仕様・素材', 'Tシャツ：6.0oz / 綿100% / シルクスクリーン4色刷り。タオル：今治ジャガード。アクキー：2mm両面印刷。'],
            ['数量', '合計 3,200 点（4SKU）'],
            ['納期', 'デザイン確定から量産納品まで 5週間'],
            ['対応ポイント', 'リハーサル日程に合わせた段階納品。会場直送と物販導線を踏まえた個包装仕様で、当日の販売オペレーションを軽量化。'],
        ],
        'gallery'       => ['photo-merch-flatlay.jpg', 'photo-friends-selfie.jpg'],
        'sort_order'    => 10,
        'is_featured'   => true,
    ],
    [
        'slug'          => 'cosmetics-package',
        'title'         => '新コスメブランドのパッケージ設計',
        'excerpt'       => 'ブランド世界観を表現するパッケージとノベルティのデザイン・調達。',
        'client_name'   => '株式会社ABC',
        'anonymous'     => false,
        'category_slug' => 'cosmetics',
        'date'          => '2026.03.22',
        'image'         => 'photo-cosmetics-pink.jpg',
        'lead'          => 'ブランド世界観を表現するパッケージとノベルティのデザイン・調達。',
        'details'       => [
            ['依頼背景', '新規ブランド立ち上げに伴うパッケージ・販促物のトータル設計のご依頼。'],
            ['制作した商品', '化粧箱／インナートレー／販促ノベルティ（ポーチ・ステッカー）'],
            ['仕様・素材', '板紙特殊コート＋空押し／インナーは再生紙トレー'],
            ['数量', '1.2万点'],
            ['納期', '企画から初回納品まで 8週間'],
            ['対応ポイント', '量販店での視認性とSNS映えを両立する版面設計。色校3回・撮影立会いまでサポート。'],
        ],
        'gallery'       => ['photo-cosmetics-mono.jpg', 'photo-merch-flatlay.jpg'],
        'sort_order'    => 20,
        'is_featured'   => true,
    ],
    [
        'slug'          => 'package-renewal',
        'title'         => '既存パッケージのリニューアル',
        'excerpt'       => '量販店向けパッケージを刷新し、店頭での視認性を改善。',
        'client_name'   => '',
        'anonymous'     => true,
        'category_slug' => 'package',
        'date'          => '2026.02.15',
        'image'         => 'photo-cosmetics-mono.jpg',
        'lead'          => '量販店向けパッケージを刷新し、店頭での視認性を改善。',
        'details'       => [
            ['依頼背景', '既存パッケージが店頭で埋もれてしまうという課題への対応。'],
            ['制作した商品', '商品パッケージ／POP'],
            ['仕様・素材', 'コート紙＋部分マットOPP'],
            ['数量', '3万点'],
            ['納期', '6週間'],
            ['対応ポイント', '棚割りを踏まえた色面設計と、品番違いの差別化レイアウト。'],
        ],
        'gallery'       => ['photo-cosmetics-pink.jpg', 'photo-business-meeting.jpg'],
        'sort_order'    => 30,
        'is_featured'   => true,
    ],
    [
        'slug'          => 'apparel-md',
        'title'         => '販売用アパレル商品の量産対応',
        'excerpt'       => '販売を見据えたMD商品として、量産・在庫管理まで一貫対応。',
        'client_name'   => '株式会社XYZ',
        'anonymous'     => false,
        'category_slug' => 'apparel-md',
        'date'          => '2026.01.30',
        'image'         => 'photo-merch-flatlay.jpg',
        'lead'          => '販売を見据えたMD商品として、量産・在庫管理まで一貫対応。',
        'details'       => [
            ['依頼背景', 'EC販売用にMD設計から量産・物流までを一括で委ねたいというご依頼。'],
            ['制作した商品', 'Tシャツ／フーディ／キャップ'],
            ['仕様・素材', 'コットンUSA／フードはヘビーオンス'],
            ['数量', '合計 5,000 点'],
            ['納期', '12週間'],
            ['対応ポイント', '売れ筋予測に応じたサイズ別生産比率と段階入庫。'],
        ],
        'gallery'       => ['photo-business-meeting.jpg', 'photo-friends-selfie.jpg'],
        'sort_order'    => 40,
        'is_featured'   => true,
    ],
    [
        'slug'          => 'promo-novelty',
        'title'         => 'プロモーション施策ノベルティ',
        'excerpt'       => '販促キャンペーンで配布するノベルティの企画・小ロット製造。',
        'client_name'   => '',
        'anonymous'     => true,
        'category_slug' => 'promotion',
        'date'          => '2025.12.18',
        'image'         => 'photo-friends-selfie.jpg',
        'lead'          => '販促キャンペーンで配布するノベルティの企画・小ロット製造。',
        'details'       => [
            ['依頼背景', '短期キャンペーン用に小ロットノベルティをスピード制作する必要があった。'],
            ['制作した商品', 'エコバッグ／ステッカー／ピンバッジ'],
            ['仕様・素材', 'コットンキャンバス／オフセット印刷'],
            ['数量', '合計 800 点'],
            ['納期', '3週間'],
            ['対応ポイント', '短納期前提の素材選定と、現場直送の物流設計。'],
        ],
        'gallery'       => ['photo-merch-flatlay.jpg', 'photo-cosmetics-pink.jpg'],
        'sort_order'    => 50,
        'is_featured'   => true,
    ],

    // ---- 詳細未実装 4 件（最低限の情報のみ投入） ----
    [
        'slug'          => 'health-apparel',
        'title'         => '機能性アパレルの法人供給',
        'excerpt'       => 'EMS関連のサポートウエアを小ロットからOEM供給。',
        'client_name'   => '株式会社DEF',
        'anonymous'     => false,
        'category_slug' => 'apparel-md',
        'date'          => '2025.11.20',
        'image'         => 'photo-business-meeting.jpg',
        'lead'          => '',
        'details'       => [],
        'gallery'       => [],
        'sort_order'    => 60,
        'is_featured'   => false,
    ],
    [
        'slug'          => 'online-gacha',
        'title'         => 'オンラインガチャ施策の運用設計',
        'excerpt'       => 'グッズ製造から在庫・配送までを通したオンラインくじの設計。',
        'client_name'   => '',
        'anonymous'     => true,
        'category_slug' => 'promotion',
        'date'          => '2025.10.08',
        'image'         => 'photo-cosmetics-pink.jpg',
        'lead'          => '',
        'details'       => [],
        'gallery'       => [],
        'sort_order'    => 70,
        'is_featured'   => false,
    ],
    [
        'slug'          => 'logistics-event',
        'title'         => 'イベント出展時の搬入・物流支援',
        'excerpt'       => 'チャーター便を含む緊急配送と当日搬入のオペレーション。',
        'client_name'   => '',
        'anonymous'     => true,
        'category_slug' => 'event-live',
        'date'          => '2025.09.12',
        'image'         => 'photo-merch-flatlay.jpg',
        'lead'          => '',
        'details'       => [],
        'gallery'       => [],
        'sort_order'    => 80,
        'is_featured'   => false,
    ],
    [
        'slug'          => 'graphic-branding',
        'title'         => 'ブランドロゴ・パッケージのトータル設計',
        'excerpt'       => 'ロゴ刷新からパッケージ・販促ツールまでをワンストップで制作。',
        'client_name'   => '株式会社GHI',
        'anonymous'     => false,
        'category_slug' => 'package',
        'date'          => '2025.08.05',
        'image'         => 'photo-cosmetics-mono.jpg',
        'lead'          => '',
        'details'       => [],
        'gallery'       => [],
        'sort_order'    => 90,
        'is_featured'   => false,
    ],
];

// ----------------------------------------------------------------------
// ヘルパ関数
// ----------------------------------------------------------------------

/**
 * 詳細データ（lead / details / gallery）から Gutenberg 用の post_content を生成。
 *
 * @param string $lead    冒頭リード文
 * @param array  $details [[label, value], ...]
 * @param array  $gallery 追加画像のファイル名配列（テーマ assets/img/ 配下）
 * @return string Gutenberg ブロック付き HTML
 */
function lemonds_seed_build_content($lead, array $details, array $gallery) {
    $blocks = '';

    if ($lead !== '') {
        $blocks .= "<!-- wp:paragraph -->\n";
        $blocks .= '<p class="lm-works-detail__lead">' . esc_html($lead) . "</p>\n";
        $blocks .= "<!-- /wp:paragraph -->\n\n";
    }

    if (!empty($details)) {
        $blocks .= "<!-- wp:heading {\"level\":2} -->\n";
        $blocks .= "<h2>プロジェクト詳細</h2>\n";
        $blocks .= "<!-- /wp:heading -->\n\n";

        foreach ($details as $row) {
            if (!is_array($row) || count($row) < 2) {
                continue;
            }
            list($label, $value) = $row;

            $blocks .= "<!-- wp:heading {\"level\":3} -->\n";
            $blocks .= '<h3>' . esc_html((string) $label) . "</h3>\n";
            $blocks .= "<!-- /wp:heading -->\n\n";

            $blocks .= "<!-- wp:paragraph -->\n";
            $blocks .= '<p>' . esc_html((string) $value) . "</p>\n";
            $blocks .= "<!-- /wp:paragraph -->\n\n";
        }
    }

    // ギャラリー（後で attachment_id に差し替えるため、ここではプレースホルダ画像 URL のみ）
    if (!empty($gallery)) {
        $blocks .= "<!-- wp:heading {\"level\":2} -->\n";
        $blocks .= "<h2>ギャラリー</h2>\n";
        $blocks .= "<!-- /wp:heading -->\n\n";

        foreach ($gallery as $filename) {
            $url = get_template_directory_uri() . '/assets/img/' . ltrim((string) $filename, '/');
            $blocks .= "<!-- wp:image {\"sizeSlug\":\"large\"} -->\n";
            $blocks .= '<figure class="wp-block-image size-large"><img src="' . esc_url($url) . '" alt=""/></figure>' . "\n";
            $blocks .= "<!-- /wp:image -->\n\n";
        }
    }

    return rtrim($blocks);
}

/**
 * 'YYYY.MM.DD' → 'YYYY-MM-DD 10:00:00' に変換（post_date 用）。
 */
function lemonds_seed_normalize_date($yyyy_dot_mm_dot_dd) {
    $s = (string) $yyyy_dot_mm_dot_dd;
    $s = str_replace('.', '-', $s);
    // 不正な形式なら現在時刻
    $ts = strtotime($s);
    if (!$ts) {
        return current_time('mysql');
    }
    return date('Y-m-d 10:00:00', $ts);
}

/**
 * テーマ assets/img/ 配下の画像をメディアライブラリにサイドロードして attachment_id を返す。
 * 同名タイトル（_lemonds_seed_source = filename）の attachment が既にあれば再利用する。
 *
 * @param string $filename 例: photo-concert-lights.jpg
 * @param int    $post_id  紐づけ先の post_id
 * @return int|false attachment_id or false
 */
function lemonds_seed_sideload_image($filename, $post_id) {
    $filename = ltrim((string) $filename, '/');
    if ($filename === '') {
        return false;
    }

    // 同じ seed ソースで作った attachment が既にあれば再利用
    $existing = get_posts([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'meta_key'       => '_lemonds_seed_source',
        'meta_value'     => $filename,
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);
    if (!empty($existing)) {
        return (int) $existing[0];
    }

    $src_path = get_template_directory() . '/assets/img/' . $filename;
    if (!file_exists($src_path)) {
        WP_CLI::warning("画像が見つかりません: {$src_path}");
        return false;
    }

    // テンポラリにコピーしてから sideload（同じファイル名を維持）
    $tmp = wp_tempnam($filename);
    if (!$tmp) {
        return false;
    }
    copy($src_path, $tmp);

    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $attachment_id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        WP_CLI::warning("media_handle_sideload 失敗 ({$filename}): " . $attachment_id->get_error_message());
        return false;
    }

    update_post_meta($attachment_id, '_lemonds_seed_source', $filename);
    return (int) $attachment_id;
}

// ----------------------------------------------------------------------
// 投入処理
// ----------------------------------------------------------------------

$lm_stats = [
    'created' => 0,
    'updated' => 0,
    'skipped' => 0,
    'failed'  => 0,
];

foreach ($lm_works_data as $entry) {
    $slug = (string) $entry['slug'];
    if ($slug === '') {
        $lm_stats['failed']++;
        continue;
    }

    $existing_post = get_page_by_path($slug, OBJECT, 'works');

    if ($existing_post instanceof WP_Post && !$lm_seed_force) {
        WP_CLI::log("[skip] {$slug} は既に存在します (ID: {$existing_post->ID})");
        $lm_stats['skipped']++;
        continue;
    }

    $post_date    = lemonds_seed_normalize_date($entry['date']);
    $post_content = lemonds_seed_build_content(
        (string) $entry['lead'],
        is_array($entry['details']) ? $entry['details'] : [],
        is_array($entry['gallery']) ? $entry['gallery'] : []
    );

    $post_data = [
        'post_type'    => 'works',
        'post_status'  => 'publish',
        'post_title'   => (string) $entry['title'],
        'post_name'    => $slug,
        'post_excerpt' => (string) $entry['excerpt'],
        'post_content' => $post_content,
        'post_date'    => $post_date,
        'post_date_gmt' => get_gmt_from_date($post_date),
    ];

    if ($existing_post instanceof WP_Post) {
        // --force 経路: 既存を更新
        $post_data['ID'] = $existing_post->ID;
        $post_id = wp_update_post($post_data, true);
    } else {
        $post_id = wp_insert_post($post_data, true);
    }

    if (is_wp_error($post_id) || !$post_id) {
        $msg = is_wp_error($post_id) ? $post_id->get_error_message() : 'unknown error';
        WP_CLI::warning("[fail] {$slug}: {$msg}");
        $lm_stats['failed']++;
        continue;
    }

    // メタ情報
    update_post_meta($post_id, 'client_name', (string) $entry['client_name']);
    update_post_meta($post_id, 'client_anonymous', !empty($entry['anonymous']));
    update_post_meta($post_id, 'is_featured', !empty($entry['is_featured']));
    update_post_meta($post_id, 'sort_order', (int) $entry['sort_order']);

    // タクソノミー
    $cat_slug = (string) $entry['category_slug'];
    if ($cat_slug !== '') {
        $term = get_term_by('slug', $cat_slug, 'works_category');
        if ($term && !is_wp_error($term)) {
            wp_set_object_terms($post_id, [(int) $term->term_id], 'works_category', false);
        } else {
            WP_CLI::warning("[warn] works_category term '{$cat_slug}' が存在しません（{$slug}）。事前に taxonomy seed を確認してください。");
        }
    }

    // Featured Image
    if (!empty($entry['image'])) {
        $attachment_id = lemonds_seed_sideload_image((string) $entry['image'], $post_id);
        if ($attachment_id) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    if ($existing_post instanceof WP_Post) {
        WP_CLI::log("[update] {$slug} (ID: {$post_id})");
        $lm_stats['updated']++;
    } else {
        WP_CLI::log("[create] {$slug} (ID: {$post_id})");
        $lm_stats['created']++;
    }
}

WP_CLI::success(sprintf(
    'works seed 完了: created=%d, updated=%d, skipped=%d, failed=%d',
    $lm_stats['created'],
    $lm_stats['updated'],
    $lm_stats['skipped'],
    $lm_stats['failed']
));
