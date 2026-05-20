# 旧 URL → 新 permalink リダイレクトマップ（納品用ドキュメント）

> 旧サイト（静的 `.html`）から WordPress 新サイト（permalink）への 301 リダイレクト一覧と適用手順。
> 一次資料は `.claude/plans/schema-registry.md` §6 内部リンク変換マップ。

最終更新: 2026-05-20（Wave 4 / L4-C）

---

## 1. リダイレクトマップ一覧

### 1-A. 静的 URL（クエリなし）

| 旧 URL | 新 URL | ステータス | 備考 |
|---|---|---|---|
| `/index.html` | `/` | 301 | トップ |
| `/services.html` | `/services/` | 301 | 事業内容 |
| `/services.html#oem` 等 | `/services/#oem` | 301 + ハッシュ保持 | ハッシュはブラウザ側で保持される |
| `/works.html` | `/works/` | 301 | 制作実績一覧 |
| `/news.html` | `/news/` | 301 | お知らせ一覧 |
| `/company.html` | `/company/` | 301 | 会社概要 |
| `/contact.html` | `/contact/` | 301 | お問い合わせ |
| `/contact-thanks.html` | `/contact/thanks/` | 301 | お問い合わせ完了 |
| `/policy.html` | `/policy/` | 301 | プライバシーポリシー |

### 1-B. クエリ付き URL

| 旧 URL | 新 URL | ステータス | 実装 |
|---|---|---|---|
| `/contact.html?type=quote` | `/contact/?type=quote` | 301 (QSA) | `.htaccess` で `QSA` フラグによりクエリ保持 |
| `/works-detail.html?slug=xxx` | `/works/xxx/` | 301 → 301 (二段階) | `.htaccess` で `/works/` へ → `single-works.php` 冒頭で `$_GET['slug']` を読んで個別投稿 permalink へ |
| `/news-detail.html?slug=xxx` | `/news/xxx/` | 301 → 301 (二段階) | `.htaccess` で `/news/` へ → `single-news.php` 冒頭で `$_GET['slug']` を読んで個別投稿 permalink へ |
| `/works-detail.html` (slug なし) | `/works/` | 301 | 一覧へフォールバック |
| `/news-detail.html` (slug なし) | `/news/` | 301 | 一覧へフォールバック |

---

## 2. 本番サーバー適用手順（Apache）

### 2-1. 配置位置

**WordPress ルートの `.htaccess`**（例: `app/public/.htaccess`、本番なら `/home/<user>/public_html/.htaccess`）の **`# BEGIN WordPress` ブロックの直前** に貼り付ける。

WordPress 本体のリライト（`/index.php` への内部フォールバック）よりも前に処理させる必要があるため、順序が重要。

### 2-2. 貼り付けるブロック

`theme/lemonds/.htaccess` の以下のコメントで囲まれた範囲をコピーする:

```
# ════════════════════════════════════════════════════════════════════════════
# LEMONDS: 旧 .html → 新 permalink への 301 リダイレクト
# ...
# ════════════════════════════════════════════════════════════════════════════
<IfModule mod_rewrite.c>
    ...
</IfModule>
# ════════════════════════════════════════════════════════════════════════════
# / LEMONDS リダイレクトブロック ここまで
# ════════════════════════════════════════════════════════════════════════════
```

### 2-3. 動作確認

ブラウザの開発ツール（Network タブ）で以下を確認:

```text
GET /index.html          → 301 → /
GET /services.html       → 301 → /services/
GET /works.html          → 301 → /works/
GET /news.html           → 301 → /news/
GET /company.html        → 301 → /company/
GET /contact.html        → 301 → /contact/
GET /contact.html?type=quote
                         → 301 → /contact/?type=quote
GET /contact-thanks.html → 301 → /contact/thanks/
GET /policy.html         → 301 → /policy/
GET /works-detail.html?slug=sample-work
                         → 301 → /works/   （その後 PHP が 301 → /works/sample-work/）
GET /news-detail.html?slug=sample-news
                         → 301 → /news/    （その後 PHP が 301 → /news/sample-news/）
```

curl で確認するなら:

```bash
curl -I "https://example.com/works.html"
curl -IL "https://example.com/works-detail.html?slug=sample-work"
```

`-L` を付けると最終 URL までトレースできる。

### 2-4. サブディレクトリ運用の場合

サイトを `/sub/` 配下にホストする場合は、`RewriteBase /sub/` に書き換え、各 `RewriteRule` のリダイレクト先も `/sub/...` に調整する。

---

## 3. Nginx 設定例（参考）

Nginx ホスティング（KUSANAGI / 自前 VPS / 等）に移行する場合の `location` ディレクティブ例:

```nginx
server {
    # ... 既存設定 ...

    # ─── LEMONDS: 旧 .html → 新 permalink への 301 ───

    # クエリ付き（先に評価）
    if ($args ~* "(^|&)type=quote(&|$)") {
        rewrite ^/contact\.html$ /contact/?$args permanent;
    }
    if ($args ~* "(^|&)slug=[^&]+") {
        rewrite ^/works-detail\.html$ /works/?$args permanent;
        rewrite ^/news-detail\.html$  /news/?$args  permanent;
    }

    # 静的（クエリなし）
    location = /index.html           { return 301 /; }
    location = /services.html        { return 301 /services/; }
    location = /works.html           { return 301 /works/; }
    location = /works-detail.html    { return 301 /works/; }
    location = /news.html            { return 301 /news/; }
    location = /news-detail.html     { return 301 /news/; }
    location = /company.html         { return 301 /company/; }
    location = /contact.html         { return 301 /contact/; }
    location = /contact-thanks.html  { return 301 /contact/thanks/; }
    location = /policy.html          { return 301 /policy/; }

    # ─── /LEMONDS ───
}
```

**注意**: Nginx の `if` は副作用が強いので、複雑な条件分岐は `map` ディレクティブで書き直すのが望ましい。上記はあくまで最小サンプル。

---

## 4. 30 日後の撤去判断ガイド

### 4-1. 方針（schema-registry / decisions.md 準拠）

- 旧 `.html` URL は外部からの被リンクや検索結果に残るため、**最低でも公開後 30 日間** はリダイレクトを維持する。
- 30 日経過後、以下を満たせばリダイレクトを撤去してよい:
  - Google Search Console / アクセス解析で旧 URL へのアクセスが **月数件以下** に減少
  - 主要な外部被リンク（取引先・SNS・名刺等）が新 URL に差し替わっている
  - 検索エンジンのインデックスが新 permalink で安定（旧 URL が `noindex` または「ページが見つかりません」へフェードアウト）

### 4-2. 推奨運用

| 期間 | 措置 |
|---|---|
| 公開〜30 日 | 全旧 URL を 301 維持（必須） |
| 31〜90 日 | 維持推奨。アクセスログを定点観測 |
| 91 日以降 | アクセスがほぼなければ撤去可。`works-detail.html` `news-detail.html` のクエリ受け（PHP 側）も同時撤去できる |

### 4-3. 撤去手順

1. 本番ルート `.htaccess` の `# LEMONDS:` ブロックをコメントアウトまたは削除
2. `single-works.php` / `single-news.php` 冒頭の `$_GET['slug']` 受け処理を削除（任意。残しても害はない）
3. 1 週間アクセスログを監視し、404 急増がなければ完了

### 4-4. 注意

- 撤去後でも、外部サイト（パートナー企業の旧リンク等）から旧 URL を踏まれる可能性は残る。404 ページのデザインで適切に新サイトへ誘導しておく（`404.php` 参照）。
- 撤去前に必ず Google Search Console の「インデックス登録」「カバレッジ」を確認すること。

---

## 5. 関連ファイル

- `theme/lemonds/.htaccess` — 納品用参考 .htaccess（このファイルから手順を読む）
- `theme/lemonds/single-works.php` 冒頭 — `?slug=xxx` を受けて個別 works へ 301
- `theme/lemonds/single-news.php` 冒頭 — `?slug=xxx` を受けて個別 news へ 301
- `theme/lemonds/inc/template-tags.php` — `lemonds_url($key)` ヘルパ（内部リンクは全てこれ経由）
- `.claude/plans/schema-registry.md` §6 — 一次資料（リンク変換マップ）
