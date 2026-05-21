<?php
/**
 * Template Name: 会社概要
 * Template: 固定ページ /company/ 用テンプレ
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

// 会社概要データを取得
$company_info = require get_theme_file_path('inc/data-company.php');

get_header();
?>

<?php get_template_part('template-parts/breadcrumb', null, ['current' => '会社概要']); ?>

<main class="lm-page lm-page--company">

    <!-- About us -->
    <section class="lm-section lm-company-about" data-screen-label="About">
        <div class="lm-section-head">
            <div>
                <h2>ABOUT US</h2>
                <div class="ja">私たちについて</div>
            </div>
            <p class="lead">信頼のOEMパートナーとして。<br/>企画から納品までを一貫してお引き受けします。</p>
        </div>

        <div class="lm-about-body">
            <div class="copy">
                <p>株式会社ルモンズエンターテインメントは、企業・ブランド向けのオリジナルグッズ製造を軸に、企画提案・仕様設計・製造進行までを一貫して手がけるグッズ製造事業を展開しています。</p>
                <p>アーティストのツアーグッズをはじめとした多様な商品制作で培った経験とノウハウを活かし、企画性・実用性・品質のバランスを重視した「使われること」を前提としたものづくりを行っています。</p>
                <p>企画提案から設計、量産、納品までをワンストップで対応し、小ロットから量産まで、用途や目的に応じた柔軟な生産体制を構築。国内外の工場ネットワークと独自の品質チェック体制により、安定した品質と納期管理を実現しています。</p>
            </div>
            <div class="lm-about-collage" aria-hidden="true">
                <div class="lm-about-collage__tile lm-about-collage__tile--edge" style="background-image:url('<?php echo esc_url(lemonds_img('about-product-samples.png')); ?>');"></div>
                <div class="lm-about-collage__tile lm-about-collage__tile--left" style="background-image:url('<?php echo esc_url(lemonds_img('photo-merch-flatlay.jpg')); ?>');"></div>
                <div class="lm-about-collage__tile lm-about-collage__tile--center" style="background-image:url('<?php echo esc_url(lemonds_img('photo-business-meeting.jpg')); ?>');"></div>
                <div class="lm-about-collage__tile lm-about-collage__tile--right" style="background-image:url('<?php echo esc_url(lemonds_img('about-fulfillment-work.png')); ?>');"></div>
                <div class="lm-about-collage__tile lm-about-collage__tile--large" style="background-image:url('<?php echo esc_url(lemonds_img('about-goods-planning.png')); ?>');"></div>
            </div>
        </div>
    </section>

    <!-- COMPANY -->
    <section class="lm-section" style="padding-top: 0;" data-screen-label="Company Info">
        <div class="lm-section-head">
            <div>
                <h2>COMPANY</h2>
                <div class="ja">会社概要</div>
            </div>
        </div>

        <dl class="lm-company-table">
            <?php foreach ($company_info['rows'] as $row): ?>
                <?php
                $status = isset($row['status']) ? $row['status'] : 'confirmed';
                $value  = isset($row['value']) ? (string) $row['value'] : '';
                // 値が未入力の項目はクライアント原稿待ちのため非表示にする
                // (data-company.php に値を入れれば自動で表示が復活する)
                if ($status === 'empty' || $value === '') {
                    continue;
                }
                ?>
                <div class="row">
                    <dt><?php echo esc_html($row['label']); ?></dt>
                    <dd>
                        <?php if ($status === 'unconfirmed'): ?>
                            <!-- TODO: クライアント確認（<?php echo esc_html($row['label']); ?>: 表記要確認） -->
                        <?php endif; ?>
                        <?php echo esc_html($value); ?>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </section>

    <!-- PHILOSOPHY -->
    <section class="lm-section lm-section--dark" data-screen-label="Philosophy">
        <div class="lm-section-head">
            <div>
                <h2>PHILOSOPHY</h2>
                <div class="ja">経営理念</div>
            </div>
            <p class="lead">想いを、価値あるカタチに。</p>
        </div>

        <div class="lm-philosophy">
            <div class="portrait" style="background-image:url('<?php echo esc_url(lemonds_img('photo-business-meeting.jpg')); ?>');"></div>
            <div class="msg">
                <div class="k">— MESSAGE FROM CEO</div>
                <!-- TODO: クライアント差し替え（代表メッセージ本文・仮文） -->
                <p>ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
                <p>ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
                <div class="sign">
                    <span class="role">代表取締役</span>
                    <span class="name">横山 駿</span>
                </div>
            </div>
        </div>
    </section>

    <!-- MAP -->
    <section class="lm-section" data-screen-label="Map">
        <div class="lm-section-head">
            <div>
                <h2>MAP</h2>
                <div class="ja">所在地</div>
            </div>
            <p class="lead">JR・東京メトロ各線「新宿三丁目」「新宿御苑前」よりアクセス。</p>
        </div>

        <div class="lm-map">
            <div class="map-area">
                <?php if (!empty($company_info['map_embed_src'])): ?>
                    <!-- TODO: クライアント差し替え可（公式の埋め込みコードを受領したら src を置き換える） -->
                    <iframe
                        src="<?php echo esc_url($company_info['map_embed_src']); ?>"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        title="株式会社ルモンズエンターテインメント 所在地マップ"></iframe>
                <?php else: ?>
                    <div class="placeholder">— GOOGLE MAP PLACEHOLDER —</div>
                <?php endif; ?>
            </div>
            <div class="addr">
                <div class="k">— ADDRESS</div>
                <p class="v">
                    <?php if (!empty($company_info['address_postal'])): ?>
                        <?php echo esc_html($company_info['address_postal']); ?><br/>
                    <?php endif; ?>
                    <?php echo esc_html($company_info['address_line']); ?>
                </p>
                <div class="k" style="margin-top:32px;">— TEL</div>
                <p class="v"><?php echo esc_html($company_info['tel']); ?></p>
            </div>
        </div>
    </section>

</main>

<?php get_template_part('template-parts/contact-cta'); ?>
<?php get_footer();
