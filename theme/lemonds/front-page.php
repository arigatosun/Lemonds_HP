<?php
/**
 * Front Page Template（トップページ）
 *
 * 移植元:
 *   - index.html
 *   - src/home/Header.jsx（→ header.php に統合済みのためここでは呼ばない）
 *   - src/home/Hero.jsx
 *   - src/home/ServiceGrid.jsx
 *   - src/home/Production.jsx
 *   - src/home/Works.jsx        ※ Wave 4 で WP_Query 化済（is_featured 優先 5 件）
 *   - src/home/News.jsx         ※ Wave 4 で WP_Query 化済（新しい順 5 件）
 *   - src/home/Company.jsx
 *   - src/home/Contact.jsx
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────
// Hero: PICK UP スライダー用データ
// ─────────────────────────────────────────────
$hero_pickup_items = [
    ['img' => 'photo-concert-lights.jpg', 'title' => '大型ライブ公演向けグッズ制作',   'date' => '2026.04.10'],
    ['img' => 'photo-cosmetics-pink.jpg', 'title' => '新プロダクトの取り扱い開始',     'date' => '2026.04.10'],
    ['img' => 'photo-cosmetics-mono.jpg', 'title' => '既存パッケージのリニューアル',   'date' => '2026.04.10'],
];
$hero_cur = $hero_pickup_items[0];

// ─────────────────────────────────────────────
// ServiceGrid: SERVICE セクション 8 事業
// ─────────────────────────────────────────────
$services = [
    [
        'title' => 'OEM・ODM事業',
        'img'   => 'photo-business-meeting.jpg',
        'copy'  => 'オリジナルグッズ製造サービス。企業・ブランド向けのオリジナルグッズやノベルティを中心に、商品企画から製造、納品までを一貫して支援するOEM事業を展開しています。',
    ],
    [
        'title' => 'MD事業',
        'img'   => 'photo-merch-flatlay.jpg',
        'copy'  => '販売を見据えた商品企画・量産対応。販売・流通を前提としたMD（マーチャンダイジング）事業として、企画性と実用性を両立した商品開発を行っています。',
    ],
    [
        'title' => '健康サポート機器・グッズ事業',
        'img'   => 'service-health-support-ems-a.jpg',
        'copy'  => 'EMS健康機器や各種アパレル商品（機能性Tシャツ・サポートウエア）を中心に、法人様向けに仕入れ・供給いたします。小ロットからの対応はもちろん、OEM・受注生産にも柔軟に対応しております。',
    ],
    [
        'title' => '倉庫・配送代行事業',
        'img'   => 'service-logistics-fulfillment-a.jpg',
        'copy'  => '販売形態に合わせた物流サポート。自社通販、個人EC、同人関連販売など、小規模事業者様の多様な販売形態に対応した倉庫保管および配送代行サービスを提供しています。',
    ],
    [
        'title' => 'オンラインガチャ（くじ）事業',
        'img'   => 'service-online-gacha-a.jpg',
        'copy'  => '販売体験を広げるオンライン施策。オンライン上で楽しめるガチャ（くじ）形式の販売施策を通じて、商品販売の新たな形を提案しています。',
    ],
    [
        'title' => 'パッケージ・グラフィックデザイン事業',
        'img'   => 'service-package-design-a.jpg',
        'copy'  => 'パッケージ、グッズデザインを中心としたブランディングおよび各種グラフィックデザインを手がけております。',
    ],
    [
        'title' => '輸出入通関事業（準備中）',
        'img'   => 'service-import-export-a.jpg',
        'copy'  => '海外生産や輸入を見据えた通関対応について、現在サービス体制を準備しています。今後、輸出入までを一貫して管理できる体制を構築予定です。',
    ],
    [
        'title' => 'HP・ECサイト制作事業（準備中）',
        'img'   => 'service-web-ec-a.jpg',
        'copy'  => 'オリジナルグッズやMD販売に適した。HP・ECサイト制作サービスを準備しています。商品設計から販売導線までを一貫して考えた販売基盤の構築を支援予定です。',
    ],
];

// ─────────────────────────────────────────────
// Production: PRODUCTION SYSTEM 4 ステップ
// ─────────────────────────────────────────────
$production_steps = [
    ['n' => '01', 'title' => '企画・仕様整理', 'img' => 'top-production-planning.jpg',      'copy' => '目的、数量、予算、納期、仕様を整理。グッズの方向性を一緒に固めます。'],
    ['n' => '02', 'title' => '製造・生産管理', 'img' => 'top-production-manufacturing.jpg', 'copy' => '国内外の製造先と連携し、サンプル確認から量産までの進行を一貫して管理します。'],
    ['n' => '03', 'title' => '検品・品質確認', 'img' => 'top-production-quality.jpg',       'copy' => '仕上がり、数量、不良、梱包状態を確認。出荷前のチェック工程を社内で完結します。'],
    ['n' => '04', 'title' => '梱包・配送・納品', 'img' => 'top-production-delivery.jpg',    'copy' => '倉庫保管、発送、イベント会場納品まで対応。販売現場まで途切れないオペレーションです。'],
];

// ─────────────────────────────────────────────
// Works プレビュー（schema-registry §7）
// Phase A: is_featured=1 を sort_order 昇順で最大 5 件取得
// Phase B: 不足分を「is_featured 不問の新しい順」で補完（Phase A と重複除外）
// ─────────────────────────────────────────────
$works_preview_posts = [];
$works_preview_limit = 5;

// Phase A: トップ掲載候補（is_featured=1）を sort_order 昇順で取得
$works_phase_a_query = new WP_Query([
    'post_type'      => 'works',
    'post_status'    => 'publish',
    'posts_per_page' => $works_preview_limit,
    'meta_query'     => [
        [
            'key'     => 'is_featured',
            'value'   => '1',
            'compare' => '=',
        ],
    ],
    'meta_key'       => 'sort_order',
    'orderby'        => [
        'meta_value_num' => 'ASC',
        'date'           => 'DESC',
    ],
    'no_found_rows'  => true,
]);
if ($works_phase_a_query->have_posts()) {
    $works_preview_posts = $works_phase_a_query->posts;
}
wp_reset_postdata();

// Phase B: 不足分を新しい順で補完（Phase A の ID は除外）
$works_phase_b_needed = $works_preview_limit - count($works_preview_posts);
if ($works_phase_b_needed > 0) {
    $works_phase_a_ids = wp_list_pluck($works_preview_posts, 'ID');
    $works_phase_b_query = new WP_Query([
        'post_type'      => 'works',
        'post_status'    => 'publish',
        'posts_per_page' => $works_phase_b_needed,
        'post__not_in'   => !empty($works_phase_a_ids) ? $works_phase_a_ids : [0],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);
    if ($works_phase_b_query->have_posts()) {
        $works_preview_posts = array_merge($works_preview_posts, $works_phase_b_query->posts);
    }
    wp_reset_postdata();
}

// ─────────────────────────────────────────────
// News プレビュー（schema-registry §7）
// 新しい順 5 件
// ─────────────────────────────────────────────
$news_preview_limit = 5;
$news_preview_query = new WP_Query([
    'post_type'      => 'news',
    'post_status'    => 'publish',
    'posts_per_page' => $news_preview_limit,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
]);
$news_preview_posts = $news_preview_query->have_posts() ? $news_preview_query->posts : [];
wp_reset_postdata();

get_header();
?>

<main class="lm-page lm-page--home">

    <!-- ============================================================
         HERO（移植元: src/home/Hero.jsx）
         ※ PICK UP の左右矢印は静的では切り替え不可。
            現状は最初のアイテムを表示。インタラクションが必要なら
            後段で軽量 JS を追加（responsive-fixer 連携）。
         ============================================================ -->
    <section class="lm-hero">
        <div class="lm-hero__stage">
            <div class="lm-hero__media">
                <div class="lm-hero__media-image" aria-hidden="true"></div>
                <div class="lm-hero__wash" aria-hidden="true"></div>
            </div>

            <div class="lm-hero__headline">
                <h1>
                    <span>想いを、価値ある</span><br class="lm-hero__sp-break" />
                    <span>カタチに。</span>
                </h1>
                <div class="lm-hero__en">Turning your ideas into meaningful value.</div>
                <p>
                    企業のブランド価値を高めるオリジナルグッズを、<br />
                    企画から製造まで一貫してサポートします。
                </p>
            </div>

            <div class="lm-hero__pickup">
                <div class="lm-hero__pickup-inner">
                    <div class="lm-hero__pickup-head">
                        <div>
                            <h2>PICK UP</h2>
                            <div class="lm-hero__pickup-ja">ピックアップ</div>
                        </div>
                        <div class="lm-hero__pickup-arrows">
                            <button type="button" class="lm-arrow-btn" data-lm-pickup-prev aria-label="前へ">&larr;</button>
                            <button type="button" class="lm-arrow-btn" data-lm-pickup-next aria-label="次へ">&rarr;</button>
                        </div>
                    </div>
                    <div class="lm-hero__pickup-image" style="background-image:url(<?php echo esc_url(lemonds_img($hero_cur['img'])); ?>)"></div>
                    <h3><?php echo esc_html($hero_cur['title']); ?></h3>
                    <div class="lm-hero__pickup-date"><?php echo esc_html($hero_cur['date']); ?></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SERVICE（移植元: src/home/ServiceGrid.jsx）
         ============================================================ -->
    <section class="lm-home-service">
        <div class="lm-home-service__inner">
            <!-- Left: SERVICE intro -->
            <div class="lm-home-service__intro">
                <h2 class="lm-home-service__title">SERVICE</h2>
                <div class="lm-home-service__ja">事業内容</div>
                <p class="lm-home-service__copy">
                    アパレル、ノベルティ、健康機器、EC施策まで。幅広く対応する総合グッズカンパニーとして、お客様のニーズにお応えします。
                </p>
                <div class="lm-home-service__action">
                    <a href="<?php echo esc_url(lemonds_url('services')); ?>" class="lm-pill-outline lm-pill-outline--section-action">
                        <span>詳細を見る</span><span class="circle">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Right: services grid -->
            <div class="lm-home-service__grid">
                <?php foreach ($services as $s) : ?>
                    <div class="lm-svc-card-b">
                        <div class="thumb" style="background-image:url(<?php echo esc_url(lemonds_img($s['img'])); ?>)"></div>
                        <div class="title"><?php echo esc_html($s['title']); ?></div>
                        <div class="copy"><?php echo esc_html($s['copy']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PRODUCTION SYSTEM（移植元: src/home/Production.jsx）
         ============================================================ -->
    <section class="lm-section" data-screen-label="05 Production">
        <div class="lm-section-head">
            <div>
                <h2>PRODUCTION SYSTEM</h2>
                <div class="ja">ものづくりを支える体制</div>
            </div>
            <p class="lead">
                企画・仕様整理から製造、検品、梱包、納品まで。<br />
                案件ごとの条件に合わせて、品質と納期を両立できる体制を整えています。
            </p>
        </div>

        <!-- 連結線つきステップトラック -->
        <div class="lm-track">
            <div class="lm-track-line"></div>
            <?php foreach ($production_steps as $s) : ?>
                <div class="lm-track-item">
                    <div class="lm-track-photo" style="background-image:url(<?php echo esc_url(lemonds_img($s['img'])); ?>)"></div>
                    <div class="lm-track-marker">
                        <span class="lm-track-dot"></span>
                        <span class="lm-track-n">STEP <?php echo esc_html($s['n']); ?></span>
                        <span class="lm-track-line-after" aria-hidden="true"></span>
                    </div>
                    <h3 class="lm-track-title"><?php echo esc_html($s['title']); ?></h3>
                    <p class="lm-track-copy"><?php echo esc_html($s['copy']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================================================
         WORKS（移植元: src/home/Works.jsx）
         WP_Query: is_featured=1 優先 5 件 + 不足分は新しい順で補完
         投稿が 0 件の場合はセクション自体を非表示にする。
         ============================================================ -->
    <?php if (!empty($works_preview_posts)) : ?>
    <section class="lm-section" data-screen-label="06 Works">
        <div class="lm-section-head">
            <div>
                <h2>WORKS</h2>
                <div class="ja">制作実績</div>
            </div>
            <p class="lead">
                イベント、エンタメ、企業販促、健康関連商品など、幅広い領域の制作に対応しています。<br />
                匿名案件を含む実績の一部をご紹介します。
            </p>
        </div>

        <?php
        // 先頭 3 件を 3 カラム、残り（最大 2 件）を 2 カラムで配置
        $works_primary   = array_slice($works_preview_posts, 0, 3);
        $works_secondary = array_slice($works_preview_posts, 3);
        ?>

        <div class="lm-grid lm-grid--3">
            <?php foreach ($works_primary as $w_post) : ?>
                <?php
                $w_id        = $w_post->ID;
                $w_permalink = get_permalink($w_post);
                $w_title     = get_the_title($w_post);
                $w_excerpt   = get_the_excerpt($w_post);
                $w_date      = mysql2date('Y.m.d', $w_post->post_date);
                $w_category  = lemonds_works_category_label($w_id);
                $w_thumb_url = get_the_post_thumbnail_url($w_id, 'large');
                if (!$w_thumb_url) {
                    $w_thumb_url = lemonds_img('photo-merch-flatlay.jpg');
                }
                ?>
                <a class="lm-news-card" href="<?php echo esc_url($w_permalink); ?>">
                    <div class="lm-news-card__image" style="background-image:url(<?php echo esc_url($w_thumb_url); ?>)"></div>
                    <div class="label">
                        <span class="eyebrow"><?php echo esc_html($w_category); ?></span>
                        <span class="title"><?php echo esc_html($w_title); ?></span>
                        <?php if ($w_excerpt !== '') : ?>
                            <span class="copy"><?php echo esc_html($w_excerpt); ?></span>
                        <?php endif; ?>
                        <span class="date"><?php echo esc_html($w_date); ?></span>
                    </div>
                    <span class="arrow">&rarr;</span>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($works_secondary)) : ?>
        <div class="lm-grid lm-grid--2 lm-grid--works-secondary">
            <?php foreach ($works_secondary as $w_post) : ?>
                <?php
                $w_id        = $w_post->ID;
                $w_permalink = get_permalink($w_post);
                $w_title     = get_the_title($w_post);
                $w_excerpt   = get_the_excerpt($w_post);
                $w_date      = mysql2date('Y.m.d', $w_post->post_date);
                $w_category  = lemonds_works_category_label($w_id);
                $w_thumb_url = get_the_post_thumbnail_url($w_id, 'large');
                if (!$w_thumb_url) {
                    $w_thumb_url = lemonds_img('photo-merch-flatlay.jpg');
                }
                ?>
                <a class="lm-news-card" href="<?php echo esc_url($w_permalink); ?>">
                    <div class="lm-news-card__image" style="background-image:url(<?php echo esc_url($w_thumb_url); ?>)"></div>
                    <div class="label">
                        <span class="eyebrow"><?php echo esc_html($w_category); ?></span>
                        <span class="title"><?php echo esc_html($w_title); ?></span>
                        <?php if ($w_excerpt !== '') : ?>
                            <span class="copy"><?php echo esc_html($w_excerpt); ?></span>
                        <?php endif; ?>
                        <span class="date"><?php echo esc_html($w_date); ?></span>
                    </div>
                    <span class="arrow">&rarr;</span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="lm-section-action lm-section-action--center">
            <a href="<?php echo esc_url(lemonds_url('works')); ?>" class="lm-pill-outline lm-pill-outline--section-action">
                <span>制作実績を見る</span><span class="circle">&rarr;</span>
            </a>
        </div>
    </section>
    <?php endif; // works_preview_posts ?>

    <!-- ============================================================
         NEWS（移植元: src/home/News.jsx）
         WP_Query: post_type=news, posts_per_page=5, date DESC
         投稿が 0 件の場合はセクション自体を非表示にする。
         ============================================================ -->
    <?php if (!empty($news_preview_posts)) : ?>
    <section class="lm-section" data-screen-label="07 News">
        <div class="lm-section-head">
            <div>
                <h2>NEWS</h2>
                <div class="ja">お知らせ</div>
            </div>
            <p class="lead">
                新規取り扱い商品やプロジェクトリリース、休業日のご案内など、<br />
                ルモンズエンターテインメントからのお知らせを掲載しています。
            </p>
        </div>

        <ul class="lm-news-list">
            <?php foreach ($news_preview_posts as $n_post) : ?>
                <?php
                $n_id        = $n_post->ID;
                $n_permalink = get_permalink($n_post);
                $n_date      = mysql2date('Y.m.d', $n_post->post_date);
                $n_category  = lemonds_news_category_label($n_id);
                $n_title     = get_the_title($n_post);
                ?>
                <li class="lm-news-row">
                    <a class="lm-news-row__link" href="<?php echo esc_url($n_permalink); ?>">
                        <span class="date"><?php echo esc_html($n_date); ?></span>
                        <span class="cat"><?php echo esc_html($n_category); ?></span>
                        <span class="title"><?php echo esc_html($n_title); ?></span>
                        <span class="arrow">&rarr;</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="lm-section-action lm-section-action--center">
            <a href="<?php echo esc_url(lemonds_url('news')); ?>" class="lm-pill-outline lm-pill-outline--section-action">
                <span>お知らせ一覧を見る</span><span class="circle">&rarr;</span>
            </a>
        </div>
    </section>
    <?php endif; // news_preview_posts ?>

    <!-- ============================================================
         COMPANY（移植元: src/home/Company.jsx）
         ※ 所在地・設立は schema-registry §4 確定値を使用。
         ============================================================ -->
    <section class="lm-section" data-screen-label="08 Company">
        <div class="lm-section-head">
            <div>
                <h2>COMPANY</h2>
                <div class="ja">私たちについて</div>
            </div>
            <p class="lead">
                グッズ制作・MD・物流・オンライン施策を通じて、企業や事業者のものづくりを支えています。<br />
                現場ごとの条件に合わせ、企画から納品までを一気通貫で。
            </p>
        </div>

        <!-- photo-led mosaic -->
        <div class="lm-co-mosaic">
            <div class="m-large" style="background-image:url(<?php echo esc_url(lemonds_img('top-company-office.jpg')); ?>)">
                <div class="cap">
                    <span class="k">&mdash; OFFICE / TOKYO</span>
                    <span class="t">想いを、価値あるカタチに。</span>
                </div>
            </div>
            <div class="m-tall" style="background-image:url(<?php echo esc_url(lemonds_img('top-company-materials.jpg')); ?>)"></div>
            <div class="m-mid"  style="background-image:url(<?php echo esc_url(lemonds_img('top-company-logistics.jpg')); ?>)"></div>
            <div class="m-mid"  style="background-image:url(<?php echo esc_url(lemonds_img('top-company-event.jpg')); ?>)"></div>
            <div class="m-wide" style="background-image:url(<?php echo esc_url(lemonds_img('top-company-flow.jpg')); ?>)"></div>
        </div>

        <!-- small overview strip -->
        <div class="lm-co-strip">
            <div class="meta">
                <div class="k">&mdash; ABOUT US</div>
                <h3>株式会社ルモンズエンターテインメント</h3>
                <p>
                    企画・仕様整理から、製造・検品・配送・販売現場での納品まで。<br />
                    ジャンルを問わず、現場の条件に合わせた提案と進行を心がけています。
                </p>
                <a href="<?php echo esc_url(lemonds_url('company')); ?>" class="lm-pill-outline lm-pill-outline--section-action">
                    <span>会社概要を見る</span><span class="circle">&rarr;</span>
                </a>
            </div>
            <dl class="facts">
                <div><dt>所在地</dt><dd>東京都新宿区新宿6丁目24番20号</dd></div>
                <div><dt>設立</dt><dd>2017年11月7日</dd></div>
                <div><dt>事業</dt><dd>商品企画 / 仕様プランニング / 制作進行 / 販売管理</dd></div>
            </dl>
        </div>
    </section>

    <!-- ============================================================
         CONTACT（移植元: src/home/Contact.jsx）
         ※ ホームの Contact セクションは独自レイアウト（lm-home-contact）。
            下層共通の contact-cta テンプレパーツは使わない。
         ============================================================ -->
    <section class="lm-section lm-home-contact" data-screen-label="08 Contact">
        <div class="lm-section-head">
            <div>
                <h2>CONTACT</h2>
                <div class="ja">お問い合わせ</div>
            </div>
            <p class="lead">
                グッズ制作、MD、配送、オンライン施策など、まずはお気軽にご相談ください。<br />
                初回ご相談は無料、専任担当が条件整理からお手伝いします。
            </p>
        </div>

        <div class="lm-home-contact__panel">
            <!-- photo -->
            <div class="lm-home-contact__photo">
                <div class="lm-home-contact__badge">&mdash; IN PERSON</div>
                <div class="lm-home-contact__caption">
                    <div class="lm-home-contact__caption-k">&mdash; STUDIO / TOKYO</div>
                    <div class="lm-home-contact__caption-title">
                        直接お会いしての<br />ご相談も承ります。
                    </div>
                </div>
            </div>

            <!-- body -->
            <div class="lm-home-contact__body">
                <div class="lm-home-contact__eyebrow">
                    &mdash; SECTION 06
                </div>
                <div>
                    <h2 class="lm-home-contact__title">CONTACT.</h2>
                    <div class="lm-home-contact__ja">お問い合わせ</div>
                </div>
                <p class="lm-home-contact__copy">
                    グッズ制作、MD、配送、オンライン施策。<br />
                    条件整理からお手伝いします。
                </p>

                <div class="lm-home-contact__actions">
                    <a href="<?php echo esc_url(lemonds_url('contact')); ?>" class="lm-contact-btn lm-contact-btn--dark">
                        <span>お問い合わせする</span>
                        <span class="arrow">&rarr;</span>
                    </a>
                    <a href="<?php echo esc_url(lemonds_url('contact_quote')); ?>" class="lm-contact-btn lm-contact-btn--light-outline">
                        <span>見積もりを相談する</span>
                        <span class="arrow">&rarr;</span>
                    </a>
                </div>

                <div class="lm-home-contact__info">
                    <div>
                        <div class="lm-home-contact__info-label">EMAIL</div>
                        <!-- TODO: クライアント差し替え（schema-registry §4 で email は未登録、JSX の info@lemonds.page を暫定転記） -->
                        <div class="lm-home-contact__info-value">info@lemonds.page</div>
                    </div>
                    <div>
                        <div class="lm-home-contact__info-label">TEL</div>
                        <div class="lm-home-contact__info-value">03-5969-9075</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
