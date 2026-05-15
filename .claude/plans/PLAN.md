# 3日納品 Wave 計画

> Classic WP テーマ移行を 3 営業日で完了させるための並列実行計画。

参照: [schema-registry.md](schema-registry.md) / [decisions.md](decisions.md)

---

## 全体構造

```
Day 1: Wave 1（基盤） → Wave 2（静的ページ移植）
Day 2: Wave 3（CMS 実装） → Wave 4（連結・フォーム・SEO）
Day 3: Wave 5（QA） → Wave 6（納品準備）
```

各 Wave 内は並列実行可能、Wave 間は依存関係あり。

---

## Day 1

### Wave 1 — 基盤構築（並列 4 レーン）

| レーン | 担当エージェント | 成果物 | 所要 |
|---|---|---|---|
| L1-A | `wp-theme-architect` | `theme/lemonds/` 骨格（style.css ヘッダ、functions.php、index.php、header.php、footer.php、404.php、screenshot.png）| 1.5h |
| L1-B | `wp-theme-architect` | テンプレートパーツ（template-parts/breadcrumb.php, contact-cta.php, page-hero.php, sub-header.php）| 1.5h |
| L1-C | `cpt-builder` | `inc/cpt-works.php`, `inc/cpt-news.php`, `inc/taxonomy-*.php`, `inc/meta-box-works.php` | 1.5h |
| L1-D | ユーザー作業 | Local でサイト作成 / 必須プラグインインストール / テーマ activate | 0.5h |

**Wave 1 完了条件（DOD）**:
- Local の管理画面で `lemonds` テーマが activate 済
- WP 管理画面に「Works」「News」メニューが表示される
- works 編集画面で「クライアント名 / 公開可否 / トップ掲載 / 並び順」のメタボックスが表示される
- works_category / news_category タクソノミーが seed 済み
- フロントエンドが空のヘッダー/フッターで描画される

### Wave 2 — 静的ページ移植（並列 5 レーン）

依存: Wave 1 完了

| レーン | 担当 | ソース | 成果物 |
|---|---|---|---|
| L2-A | `page-migrator` | `index.html` + `src/home/*` | `front-page.php` |
| L2-B | `page-migrator` | `services.html` + `ServicesPage.jsx` | `page-services.php` + `inc/data-services.php` |
| L2-C | `page-migrator` | `company.html` + `CompanyPage.jsx` | `page-company.php` + `inc/data-company.php` |
| L2-D | `page-migrator` | `contact.html` + `ContactPage.jsx` + `contact-thanks.html` | `page-contact.php` + `page-contact-thanks.php`（フォーム本体は Wave 3）|
| L2-E | `page-migrator` | `policy.html` + `PolicyPage.jsx` | `page-policy.php` |

**並列時の競合回避**:
- 共通の `header.php` / `footer.php` / `template-parts/*` は Wave 1 で固定済（Wave 2 では編集禁止）
- 各レーンは自分の page-*.php のみ編集
- `assets/css/site.css` は **読み取り専用**（Wave 1 でコピー、Wave 5 で修正）

**Wave 2 完了条件**:
- 全 6 ページが Local の固定ページとして表示される
- 内部リンクは `lemonds_url()` ヘルパ経由
- `assets/css/site.css` を読み込んで現状の見た目が再現される
- フォーム送信は仮動作（Wave 3 で本実装）

---

## Day 2

### Wave 3 — CMS 実装（並列 3 レーン）

依存: Wave 1 完了（Wave 2 と並走可能だが、画像参照で衝突しないよう調整）

| レーン | 担当 | 成果物 |
|---|---|---|
| L3-A | `page-migrator` | `archive-works.php`, `single-works.php`, `inc/seed-works.php`（CLI 投稿 seed）|
| L3-B | `page-migrator` | `archive-news.php`, `single-news.php`, `inc/seed-news.php` |
| L3-C | `form-builder` | CF7 フォーム設定（コード側に shortcode 記録）、添付対応、`?type=quote` 連動 JS、自動返信テンプレ、WP Mail SMTP 設定ガイド |

**Wave 3 完了条件**:
- `/works/` で 9 件、`/news/` で 8 件のサンプル投稿が一覧表示
- 詳細ページが投稿 ID 毎に動作
- フォーム送信で管理者通知 + 自動返信メールが届く
- `/contact/?type=quote` で「見積もり依頼」がプリセットされる

### Wave 4 — 連結・SEO・リダイレクト（並列 3 レーン）

依存: Wave 2 + Wave 3 完了

| レーン | 担当 | 成果物 |
|---|---|---|
| L4-A | `page-migrator` | トップの Works/News プレビュー連動（`front-page.php` 更新、`WP_Query` で取得）|
| L4-B | `wp-theme-architect` | SEO メタタグ（title / description / OGP / canonical）。Yoast を入れるか手書きかは ACF と相談。`functions.php` で `wp_head` フック |
| L4-C | `wp-theme-architect` | `.htaccess` 301 リダイレクト（旧 `.html` → 新 permalink）、`single-works.php` / `single-news.php` で `?slug=xxx` クエリ受けの互換処理（30 日間の保険）|

---

## Day 3

### Wave 5 — レスポンシブ仕上げ + QA（並列 2 レーン）

| レーン | 担当 | 成果物 |
|---|---|---|
| L5-A | `responsive-fixer` | site.css の SP 仕上げ（特に Header ハンバーガー、Hero、Works/News カードの 375px〜560px）|
| L5-B | `qa-reviewer` | QA レポート（チェックリストベース）、見つけた issue を `.claude/plans/qa-findings.md` に記録 |

### Wave 6 — 納品準備（直列）

| ステップ | 担当 | 成果物 |
|---|---|---|
| S6-1 | ユーザー + `qa-reviewer` | 全 issue クローズ |
| S6-2 | `wp-theme-architect` | テーマ `.zip` パッケージング、不要ファイル除外 |
| S6-3 | `wp-theme-architect` | DB エクスポート（`.sql.gz`）、ACF JSON 同梱、`assets/` 確認 |
| S6-4 | ユーザー | 移行手順書（README.md）作成、クライアントへ送付 |

---

## ファイル責任マトリクス

> 同一ファイルを複数エージェントが触ると競合する。**所有者を明示** することで並列実行を安全にする。

| パス | Wave | 所有エージェント |
|---|---|---|
| `theme/lemonds/style.css` | 1 | wp-theme-architect |
| `theme/lemonds/functions.php` | 1, 4 | wp-theme-architect（4 では追記のみ）|
| `theme/lemonds/header.php` | 1 | wp-theme-architect |
| `theme/lemonds/footer.php` | 1 | wp-theme-architect |
| `theme/lemonds/template-parts/*.php` | 1 | wp-theme-architect |
| `theme/lemonds/inc/cpt-*.php` | 1 | cpt-builder |
| `theme/lemonds/inc/taxonomy-*.php` | 1 | cpt-builder |
| `theme/lemonds/inc/meta-box-*.php` | 1 | cpt-builder |
| `theme/lemonds/inc/seed-*.php` | 3 | page-migrator |
| `theme/lemonds/inc/data-*.php` | 2 | page-migrator |
| `theme/lemonds/inc/template-tags.php` | 1 | wp-theme-architect |
| `theme/lemonds/front-page.php` | 2, 4 | page-migrator |
| `theme/lemonds/page-*.php` | 2 | page-migrator |
| `theme/lemonds/archive-*.php` | 3 | page-migrator |
| `theme/lemonds/single-*.php` | 3 | page-migrator |
| `theme/lemonds/assets/css/*.css` | 1（コピー）/ 5（修正）| wp-theme-architect → responsive-fixer |
| `theme/lemonds/assets/js/*.js` | 3 | form-builder |
| `.htaccess` | 4 | wp-theme-architect |

---

## サブエージェント呼び出し規約

各 Wave のレーンを起動するときは:

1. **TaskCreate** でレーン単位のタスクを作る
2. **Agent** ツールで `subagent_type` に該当エージェント名を指定
3. プロンプトには以下を必ず含める:
   - `参照: .claude/plans/schema-registry.md, .claude/plans/decisions.md`
   - **担当ファイル**（責任マトリクスから抜粋）
   - **編集禁止ファイル**（他レーンが触るもの）
   - **完了条件（DOD）**
4. 完了後は **TaskUpdate** で `completed` にする

並列実行の指示:

> 「Wave 2 を起動」と言われたら、L2-A〜L2-E を **同一メッセージ内で 5 つの Agent ツール呼び出し** にする（順次ではなく並列）。

---

## リスクとフォールバック

| リスク | 影響 | 対策 |
|---|---|---|
| Gutenberg 本文での詳細記述がクライアントに難しい | Works 詳細の見栄えがバラつく | `handover.md` に Gutenberg 入力テンプレ（コピペ用ブロック例）を同梱、必要なら Phase 2 で ACF Pro 導入 |
| SMTP 設定遅延 | フォーム送信テストができない | CF7 単体テスト（メール送らず送信ログだけ確認）で進行、Wave 5 で本接続 |
| Local 環境立ち上げ遅延 | 全 Wave 停止 | ユーザーへの確認を最優先（Wave 0 として並走）|
| 仮文置き換え | クライアント差戻し | 「差し替え必要箇所」一覧を `qa-findings.md` に記録、納品書に同梱 |
| レスポンシブ不足 | クライアント差戻し | Wave 5 を最大 1.5 日に拡張可能、その分 Wave 4 の SEO を最小限に |
