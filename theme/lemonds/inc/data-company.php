<?php
/**
 * 会社概要データ
 *
 * schema-registry §4 を一次資料とした会社情報。
 * - status: 'confirmed' = ✅確定 / 'unconfirmed' = ⚠️要確認 / 'empty' = ❌空欄
 * - 'empty' / 'unconfirmed' の項目は page-company.php 側で TODO コメントを残す
 *
 * 将来 ACF Options Page に移行する場合も同スキーマを維持できるよう、
 * キーは ACF 互換命名（小文字スネークケース）にしている。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

return [
    // 表向きの会社情報テーブル（順序保持）
    'rows' => [
        [
            'key'    => 'company_name',
            'label'  => '会社名',
            'value'  => '株式会社ルモンズエンターテインメント',
            'status' => 'confirmed',
        ],
        [
            'key'    => 'company_name_en',
            'label'  => '英語表記',
            'value'  => 'LEMONDS ENTERTAINMENT CO.,LTD.',
            'status' => 'unconfirmed',
        ],
        [
            'key'    => 'address',
            'label'  => '所在地',
            'value'  => '〒160-0022 東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F',
            'status' => 'confirmed',
        ],
        [
            'key'    => 'tel',
            'label'  => 'TEL',
            'value'  => '03-5969-9075',
            'status' => 'confirmed',
        ],
        [
            'key'    => 'founded',
            'label'  => '設立',
            'value'  => '2017年11月7日',
            'status' => 'confirmed',
        ],
        [
            'key'    => 'capital',
            'label'  => '資本金',
            'value'  => '500万円',
            'status' => 'confirmed',
        ],
        [
            'key'    => 'fiscal_month',
            'label'  => '決算月',
            'value'  => '',
            'status' => 'empty',
        ],
        [
            'key'    => 'officers',
            'label'  => '役員',
            'value'  => '代表取締役 横山 駿',
            'status' => 'confirmed',
        ],
        [
            'key'    => 'revenue',
            'label'  => '売上高',
            'value'  => '',
            'status' => 'empty',
        ],
        [
            'key'    => 'employees',
            'label'  => '従業員数',
            'value'  => '',
            'status' => 'empty',
        ],
        [
            'key'    => 'affiliations',
            'label'  => '所属団体',
            'value'  => '',
            'status' => 'empty',
        ],
    ],

    // 連絡関連（MAP セクション等で再利用）
    'address_postal' => '〒160-0022',
    'address_line'   => '東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F',
    'tel'            => '03-5969-9075',

    // Google Maps 埋め込み（住所文字列ベースの汎用 URL）
    // 確定済み住所のため埋め込み可能。差し替える場合はクライアントから正規の埋め込みコードを受領する。
    'map_embed_src'  => 'https://www.google.com/maps?q=' . rawurlencode('東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F') . '&hl=ja&z=17&output=embed',
];
