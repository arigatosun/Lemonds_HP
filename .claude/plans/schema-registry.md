# データスキーマ一次資料

> 全サブエージェントが参照する一次資料。既存 JSX から抽出済み。
> 矛盾が起きたら **このファイルを正** とする。更新は PR ベースで行う。

---

> **ACF は使用しない方針**（[decisions.md §A](decisions.md) 参照）。  
> WordPress 標準の post_meta + Gutenberg 本文 + Featured Image + Taxonomy で構築。  
> 将来 ACF Pro を導入する場合に取り込めるよう、meta_key は ACF 互換命名（先頭アンダースコアなし・小文字スネークケース）にする。

## 1. Works（制作実績）

### CPT 設計

| 項目 | 値 |
|---|---|
| post_type | `works` |
| slug | `works` |
| supports | `title`, `editor`, `thumbnail`, `excerpt`, `revisions` |
| menu_position | 5 |
| menu_icon | `dashicons-portfolio` |
| has_archive | `true`（permalink: `/works/`）|
| rewrite | `['slug' => 'works', 'with_front' => false]` |
| show_in_rest | `true` |
| taxonomies | `works_category` |

### データの保管場所マッピング

| JSX キー | WP の保管場所 | 取得 API |
|---|---|---|
| `title` | post_title | `the_title()` |
| `slug` | post_name | `$post->post_name` |
| `date` | post_date | `mysql2date('Y.m.d', $post->post_date)` |
| `category` | Taxonomy `works_category` | `get_the_terms($post, 'works_category')` |
| `copy` | post_excerpt | `get_the_excerpt()` |
| `lead` | post_content の冒頭ブロック | `the_content()` で全部出す |
| `img` | Featured Image | `get_the_post_thumbnail_url()` |
| `gallery[]` | post_content 内の **ギャラリーブロック**（Gutenberg）| `the_content()` で出力 |
| `details[][]` | post_content 内の **見出し/段落/テーブルブロック** | `the_content()` で出力 |
| `client` | post_meta `client_name` | `get_post_meta($id, 'client_name', true)` |
| — | post_meta `client_anonymous` | bool |
| — | post_meta `is_featured` | bool（トップ掲載候補）|
| — | post_meta `sort_order` | int（並び順、小さい順）|

**詳細ページのレイアウト方針**:
- ヒーロー部（タイトル + クライアント名 + 日付）は単テンプレ側で組む
- 本文以下（lead / details / gallery）は Gutenberg ブロックで自由配置
- テーマ側で `.lm-works-detail` 配下のブロックを CSS で整形（h2/h3/p/figure/table 等を対象）

**現状件数**: 一覧 9 件、詳細 5 件実装済 → 残り 4 件は WP 投稿で空タイトル + 「下書き」状態にしておくか、画像のみ Featured Image で登録。

---

## 2. News（お知らせ）

### CPT 設計

| 項目 | 値 |
|---|---|
| post_type | `news` |
| slug | `news` |
| supports | `title`, `editor`, `thumbnail`, `excerpt`, `revisions` |
| menu_position | 6 |
| menu_icon | `dashicons-megaphone` |
| has_archive | `true` |
| show_in_rest | `true` |
| taxonomies | `news_category` |

### Taxonomy: `news_category`（hierarchical = false）

ターム: `PRESS`, `NOTICE`, `PROJECT`, `COMPANY`（slug 小文字）

### データの保管場所マッピング

| JSX キー | WP の保管場所 | 取得 API |
|---|---|---|
| `title` | post_title | `the_title()` |
| `slug` | post_name | `$post->post_name` |
| `date` | post_date | `mysql2date('Y.m.d', $post->post_date)` |
| `category` | Taxonomy `news_category` | `get_the_terms($post, 'news_category')` |
| `body[]` | post_content（Gutenberg 段落ブロック）| `the_content()` |

**関連投稿ロジック**: 同カテゴリで `posts_per_page=3` + 自分除外、不足分は新しい順で補完。**手動指定は当面なし**（必要なら後日 meta-box 追加）。  
**現状件数**: 8 件。トップは新しい順 5 件。

---

## 3. Services（事業内容）

固定ページ `/services` で実装。WP の固定ページ + テンプレート `page-services.php`。

### 8 事業データ（JSX 抽出済、PHP 配列で持つ or ACF Options）

```php
$services = [
  ['id' => 'oem',       'no' => '01', 'jp' => 'OEM・ODM事業',          'en' => 'OEM / ODM',     'status' => null, ...],
  ['id' => 'md',        'no' => '02', 'jp' => 'MD事業',                 'en' => 'MD',            'status' => null, ...],
  ['id' => 'health',    'no' => '03', 'jp' => '健康サポート機器・グッズ事業', 'en' => 'Health',  'status' => null, ...],
  ['id' => 'logistics', 'no' => '04', 'jp' => '倉庫・配送代行事業',     'en' => 'Logistics',     'status' => null, ...],
  ['id' => 'gacha',     'no' => '05', 'jp' => 'オンラインガチャ（くじ）事業', 'en' => 'Online Gacha', 'status' => null, ...],
  ['id' => 'design',    'no' => '06', 'jp' => 'パッケージ・グラフィックデザイン事業', 'en' => 'Design', 'status' => null, ...],
  ['id' => 'trade',     'no' => '07', 'jp' => '輸出入通関事業',         'en' => 'Trade',         'status' => '準備中', ...],
  ['id' => 'web',       'no' => '08', 'jp' => 'HP・ECサイト制作事業',    'en' => 'Web / EC',      'status' => '準備中', ...],
];
```

各要素のキー: `id`, `no`, `jp`, `en`, `tagline`, `img`, `body`, `scope[]`, `status`。  
**status: '準備中'** はグレーアウト + リンクなし表示。

**初期実装方針**: テーマ内 `inc/data-services.php` に配列保持。将来 ACF Options へ移行する余地を残す（その場合も同じスキーマ）。

---

## 4. Company（会社概要）

固定ページ `/company`。会社概要テーブルは ACF Options（`company_info`）または `inc/data-company.php` 配列。

| 項目 | 現状値 | ステータス |
|---|---|---|
| 会社名 | 株式会社ルモンズエンターテインメント | ✅確定 |
| 英語表記 | LEMONDS ENTERTAINMENT CO.,LTD. | ⚠️要確認 |
| 所在地 | 〒160-0022 東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F | ✅確定 |
| TEL | 03-5969-9075 | ✅確定 |
| 設立 | 2017年11月7日 | ✅確定 |
| 資本金 | 500万円 | ✅確定 |
| 決算月 | — | ❌空欄 |
| 役員 | 代表取締役 横山 駿 | ✅確定 |
| 売上高 | — | ❌空欄 |
| 従業員数 | — | ❌空欄（仮文に金額表記混入、要修正） |
| 所属団体 | — | ❌空欄 |

経営理念本文 + 代表メッセージ: 仮文。`横山 駿` 名義は確定。  
MAP: Google Maps 埋め込みプレースホルダー（住所確定済のため iframe 生成可）。

---

## 5. Contact フォーム（CF7）

### フォーム項目

| name | type | required | placeholder |
|---|---|---|---|
| `inquiry_type[]` | checkbox 複数 | ✅ | — |
| `company` | text | ✅ | ABC株式会社 |
| `name` | text | ✅ | 田中 太郎 |
| `email` | email | ✅ | xxxxxxxxxx@example.com |
| `tel` | tel | — | 000-0000-0000 |
| `message` | textarea | ✅ | 具体的なお問い合わせ内容をご記入ください |
| `attachment` | file (multiple, 10MB) | — | PDF/画像/Office |
| `privacy_consent` | acceptance | ✅ | 個人情報保護方針に同意 |

### 問い合わせ種別の選択肢

`企画・仕様の相談 / 見積もり依頼 / サンプルについて / 量産について / 検品・納品条件の相談 / その他`

### URL パラメータ連動

`/contact?type=quote` → 「見積もり依頼」をデフォルトチェック（assets/js/contact.js で実装）。

### 送信フロー

`送信` → CF7 → 管理者通知 + 自動返信 → `/contact/thanks/` へリダイレクト。

---

## 6. 内部リンク変換マップ（旧 → 新）

| 旧 URL（.html） | 新 URL（permalink）|
|---|---|
| `index.html` | `/` |
| `services.html` | `/services/` |
| `services.html#oem` 等 | `/services/#oem` |
| `works.html` | `/works/` |
| `works-detail.html?slug=xxx` | `/works/xxx/` |
| `news.html` | `/news/` |
| `news-detail.html?slug=xxx` | `/news/xxx/` |
| `company.html` | `/company/` |
| `contact.html` | `/contact/` |
| `contact.html?type=quote` | `/contact/?type=quote` |
| `contact-thanks.html` | `/contact/thanks/` |
| `policy.html` | `/policy/` |

**実装**: テーマ内 `inc/template-tags.php` に `lemonds_url($key)` ヘルパを定義し、PHP テンプレ側は全てこの関数経由で URL を出す（将来の変更に強い）。

---

## 7. トップページのプレビュー連動

| セクション | データソース | 件数 | 表示フィールド |
|---|---|---|---|
| `home/Works` | works CPT（`is_featured=true` 優先、不足分は新しい順） | 5 件 | thumbnail, title, category, excerpt_copy, work_date |
| `home/News` | news CPT（新しい順） | 5 件 | published_at, category, title |

---

## 8. 画像アセット

`assets/` 配下の画像は以下に分離:

- **固定ページ用**（Hero / Services / Production / Company 等）→ テーマの `assets/img/` へ移動、`get_template_directory_uri()` 経由で参照
- **Works / News 用**（投稿に紐づくもの）→ WordPress メディアライブラリで管理、Featured Image + ACF gallery で運用

既存ファイル名はそのまま維持して移行コストを下げる。
