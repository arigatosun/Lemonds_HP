# Next.js + Headless WordPress 本番移行計画

作成日: 2026-05-13

このドキュメントは、現在の `Lemonds_HP` 実装を本番公開向けに Next.js + Headless WordPress 構成へ移行するための作業整理です。対象は、現在の見た目を活かしつつ、Works と News を投稿管理できる状態にすることです。

## 結論

現在の実装は、デザイン確認と画面実装のプロトタイプとしては有効です。ただし、一般公開用の会社サイトとして長く運用するには、公開前に本番向け構成へ移すのが望ましいです。

理由は主に以下です。

- React と Babel をブラウザで直接読み込んでいる
- `services.html` や `works-detail.html?slug=...` のような静的HTML/クエリURLになっている
- Works / News の投稿データが JSX 内に直書きされている
- Works / News の一覧データと詳細データが別ファイルで重複している
- お問い合わせフォームが実送信せず、送信完了ページへ遷移するだけになっている
- SEO、OGP、canonical、sitemap、redirect など本番公開時の周辺設定が未整備
- 1920px 固定キャンバスを `transform: scale()` で縮小する構成で、実レスポンシブはまだ未実装

最終的には、Next.js を公開サイト、WordPress を投稿管理用CMSとして使う構成が適しています。

```txt
Next.js frontend
  /
  /services
  /company
  /contact
  /contact/thanks
  /works
  /works/[slug]
  /news
  /news/[slug]
  /policy

WordPress CMS
  /wp-admin または /cms/wp-admin
  works 投稿タイプ
  news 投稿タイプ
```

## 現在の実装状況

### ファイル構成

現在はルート直下に HTML ファイルが並ぶ静的サイト構成です。

```txt
index.html
services.html
works.html
works-detail.html
news.html
news-detail.html
company.html
contact.html
contact-thanks.html
policy.html
```

React コンポーネントは `src/` 配下にあります。

```txt
src/home/*
src/pages/*
src/shared/*
```

CSS は主に以下です。

```txt
styles/colors_and_type.css
styles/site.css
```

画像は `assets/` 配下に配置されています。

### 実行方式

各 HTML が CDN の React / ReactDOM / Babel Standalone を読み込み、JSX をブラウザ上で変換しています。

```html
<script src="https://unpkg.com/react@18.3.1/umd/react.development.js"></script>
<script src="https://unpkg.com/react-dom@18.3.1/umd/react-dom.development.js"></script>
<script src="https://unpkg.com/@babel/standalone@7.29.0/babel.min.js"></script>
<script type="text/babel" src="src/pages/ServicesPage.jsx"></script>
```

これは開発・確認用としては扱いやすい一方、本番では避けたい構成です。Next.js へ移行すると、ビルド済みJS、ルーティング、画像最適化、SEO、API取得、フォーム処理を整理できます。

### 現在のページ対応

| 現在のファイル | 本番URL案 | 備考 |
|---|---|---|
| `index.html` | `/` | トップページ |
| `services.html` | `/services` | 固定ページとして Next.js 側で管理 |
| `works.html` | `/works` | WordPress の works 投稿一覧 |
| `works-detail.html?slug=xxx` | `/works/xxx` | WordPress の works 詳細 |
| `news.html` | `/news` | WordPress の news 投稿一覧 |
| `news-detail.html?slug=xxx` | `/news/xxx` | WordPress の news 詳細 |
| `company.html` | `/company` | 固定ページとして Next.js 側で管理 |
| `contact.html` | `/contact` | フォーム実送信が必要 |
| `contact-thanks.html` | `/contact/thanks` | フォーム送信後ページ |
| `policy.html` | `/policy` | 固定ページとして Next.js 側で管理 |

`/services/index.html` のようなURLが表に出るわけではありません。Next.js では `app/services/page.tsx` を作ると、公開URLは `/services` になります。

### 現在のデータ保持

`src/pages/WorksPage.jsx` では Works 一覧用の `items` 配列を持っています。

`src/pages/WorksDetailPage.jsx` では Works 詳細用の `ALL` 配列を別途持っています。

`src/pages/NewsPage.jsx` では News 一覧用の `ALL` 配列を持っています。

`src/pages/NewsDetailPage.jsx` では News 詳細用の `ALL` 配列を別途持っています。

この状態だと、投稿追加・更新時に複数箇所を手作業で揃える必要があります。本番では WordPress を単一データソースにして、トップ、一覧、詳細、関連投稿、前後投稿をすべて同じ投稿データから生成します。

### 現在のリンク

共通ヘッダー、フッター、CTA、パンくず、詳細ページ内リンクは `.html` 前提です。

例:

```txt
services.html
works.html
news.html
company.html
contact.html
contact.html?type=quote
works-detail.html?slug=...
news-detail.html?slug=...
```

Next.js 移行時は、ルート定義を共通化して以下に寄せます。

```txt
/services
/works
/news
/company
/contact
/contact?type=quote
/works/[slug]
/news/[slug]
```

### 現在のレスポンシブ状態

現状は `#root` を 1920px 幅として、ビューポート幅に応じて `transform: scale(var(--page-scale))` で縮小しています。`styles/site.css` には現時点で `@media` ルールはありません。

そのため、見た目上は縮小表示されますが、スマートフォン向けにレイアウトが組み替わる実レスポンシブではありません。Next.js 移行前または移行後に、セクション幅、グリッド、ヘッダー、Hero、カード、フォームのレスポンシブ設計が必要です。

### 現在の注意点

PowerShell 上でソースを確認すると、一部の日本語テキストが文字化けして見える箇所があります。ブラウザ表示、元原稿、現行デザイン上の文言を基準に、移行時にテキストを再確定する必要があります。

`src/dev-inspector.js` はローカル確認用の補助スクリプトです。全HTMLから読み込まれていますが、実行条件は localhost / 127.0.0.1 のみです。本番移行時は Next.js 側には含めない方針が安全です。

## 目標構成

### 推奨技術構成

```txt
Next.js App Router
TypeScript
WordPress REST API または WPGraphQL
ACF または同等のカスタムフィールド
ISR / on-demand revalidation
Vercel などの Next.js 対応ホスティング
WordPress は別ホスト、サブドメイン、または /cms 配下
```

ユーザーが Next.js に慣れている前提では、Astro より Next.js を優先する判断で問題ありません。投稿更新、プレビュー、フォーム処理、将来の機能追加を考えると、Next.js の方が扱いやすいです。

### Next.js ルーティング案

```txt
app/
  page.tsx
  services/page.tsx
  company/page.tsx
  contact/page.tsx
  contact/thanks/page.tsx
  works/page.tsx
  works/[slug]/page.tsx
  news/page.tsx
  news/[slug]/page.tsx
  policy/page.tsx
  not-found.tsx
  layout.tsx
```

### コンポーネント移行案

```txt
components/
  layout/Header.tsx
  layout/Footer.tsx
  layout/Breadcrumb.tsx
  layout/ContactCTA.tsx
  common/PageHero.tsx
  common/PillButton.tsx
  home/Hero.tsx
  home/ServiceGrid.tsx
  home/Production.tsx
  home/WorksPreview.tsx
  home/NewsPreview.tsx
  home/CompanyPreview.tsx
  home/Contact.tsx
  works/WorksCard.tsx
  works/WorksDetail.tsx
  news/NewsRow.tsx
  news/NewsArticle.tsx
  contact/ContactForm.tsx
```

CSS は初期移行では既存の `styles/site.css` をグローバルCSSとして移植し、段階的に整理する方針が安全です。最初から CSS Modules や Tailwind に全面移行すると、見た目再現のリスクが上がります。

## WordPress 側の設計

### 投稿タイプ

Works と News は分けて管理するのが分かりやすいです。

```txt
works
news
```

News は WordPress 標準の `post` を使う方法もありますが、企業サイトのお知らせとして項目を固定したい場合は `news` カスタム投稿タイプが扱いやすいです。

### Works 投稿フィールド案

| フィールド | 用途 |
|---|---|
| `title` | 実績タイトル |
| `slug` | `/works/[slug]` 用 |
| `status` | 公開/下書き |
| `date` | 実績日または公開日 |
| `category` | EVENT / LIVE、COSMETICS など |
| `client_name` | クライアント名、非公開可 |
| `lead` | 詳細ページ冒頭文 |
| `excerpt` | 一覧カード用説明文 |
| `thumbnail` | 一覧・トップ表示用画像 |
| `gallery` | 詳細ページの複数画像 |
| `detail_rows` | ラベル/本文の繰り返し項目 |
| `is_featured` | トップ掲載対象 |
| `sort_order` | トップや一覧の表示順補助 |
| `seo_title` | SEOタイトル |
| `seo_description` | meta description |
| `og_image` | OGP画像 |

現在の `WorksDetailPage.jsx` にある `details` 配列は、ACF の Repeater Field と相性が良いです。

### News 投稿フィールド案

| フィールド | 用途 |
|---|---|
| `title` | お知らせタイトル |
| `slug` | `/news/[slug]` 用 |
| `status` | 公開/下書き |
| `published_at` | 表示日 |
| `category` | PRESS、NOTICE、PROJECT、COMPANY など |
| `body` | 本文 |
| `excerpt` | 一覧用要約 |
| `thumbnail` | 必要なら一覧/OGP用 |
| `related_posts` | 任意の関連記事指定 |
| `seo_title` | SEOタイトル |
| `seo_description` | meta description |
| `og_image` | OGP画像 |

現在の `NewsDetailPage.jsx` では同カテゴリ優先で関連投稿を出しています。WordPress 側で手動指定を持たせつつ、未指定時は同カテゴリから自動取得する設計が良いです。

### Services / Company / Policy の扱い

初期本番化では、Services / Company / Policy は Next.js の固定ページとしてコード管理で問題ありません。

理由:

- 更新頻度が Works / News より低い
- レイアウトの作り込みが強い
- WordPress 化すると入力設計の工数が増える

将来的にクライアントが編集したい場合は、WordPress の固定ページ、オプションページ、または ACF Options へ移すことを検討します。

## データ取得方針

### 推奨

Next.js 側で WordPress API から投稿を取得し、ISR でキャッシュします。

```txt
WordPress publish/update
  -> webhook
  -> Next.js on-demand revalidation
  -> /works, /works/[slug], /news, /news/[slug] を再生成
```

投稿反映を即時にしたい場合は、該当ページのみ revalidate する構成にします。

### API選択

初期は WordPress REST API で十分です。フィールド構造が複雑になったり、取得項目を厳密に制御したくなった場合は WPGraphQL を検討します。

## URL / リダイレクト設計

本番URLは以下を基準にします。

```txt
/
/services
/works
/works/[slug]
/news
/news/[slug]
/company
/contact
/contact/thanks
/policy
```

既存URLからのリダイレクトも設定します。

| 旧URL | 新URL |
|---|---|
| `/index.html` | `/` |
| `/services.html` | `/services` |
| `/works.html` | `/works` |
| `/works-detail.html?slug=xxx` | `/works/xxx` |
| `/news.html` | `/news` |
| `/news-detail.html?slug=xxx` | `/news/xxx` |
| `/company.html` | `/company` |
| `/contact.html` | `/contact` |
| `/contact.html?type=quote` | `/contact?type=quote` |
| `/contact-thanks.html` | `/contact/thanks` |
| `/policy.html` | `/policy` |

Next.js の `redirects()` で対応できます。ただしクエリ付きの `works-detail.html?slug=xxx` と `news-detail.html?slug=xxx` は、middleware で処理した方が確実です。

## お問い合わせフォーム

現在の `ContactPage.jsx` は `preventDefault()` 後に `contact-thanks.html` へ遷移するだけです。本番では送信処理が必要です。

候補:

- Next.js Route Handler からメール送信
- SendGrid / Resend / Amazon SES などのメールサービス
- Google reCAPTCHA / Cloudflare Turnstile などのスパム対策
- 添付ファイルが必要ならストレージ保存または添付制限設計
- WordPress 側に問い合わせ投稿として保存する設計も可能

まずは Route Handler + メール送信 + スパム対策が扱いやすいです。

## SEO / 公開品質で必要な作業

最低限、以下を本番前に整備します。

- ページ別 `title`
- ページ別 `meta description`
- canonical URL
- OGP / Twitter Card
- `robots.txt`
- `sitemap.xml`
- 404ページ
- favicon / app icons
- 旧URLからの 301 redirect
- 画像の `alt`
- Lighthouse確認
- Search Console 登録準備

Works / News の詳細ページでは、WordPress 投稿ごとの title、description、OGP画像を出し分けます。

## 画像移行

現在の画像は `assets/` 配下にあります。

初期移行では以下の方針が安全です。

- 固定ページ用画像は Next.js の `public/assets/` に移す
- Works / News の投稿画像は WordPress メディアライブラリで管理する
- トップ掲載用の Works / News 画像も WordPress 側の投稿画像を使う
- `next/image` を使い、表示サイズに応じた最適化を行う

現在 CSS の `background-image` で表示している箇所は、必要に応じて `Image` コンポーネント + `object-fit: cover` に置き換えます。見た目再現を優先する箇所は、初期移行では CSS background のままでも構いません。

## 作業工程

### Phase 1: 現行デザインの確定

目的: Next.js 移行前に、現在の見た目の完成形を固める。

作業:

- デスクトップの見た目修正を完了
- レスポンシブ方針を確定
- Hero、ヘッダー、セクション幅、カード、フォームのモバイル表示を整理
- Works / News / Company / Services の必要文言を確定
- 文字化け・仮文言・仮住所・仮電話番号を整理

目安: 進行中のデザイン修正量による。レスポンシブまで含めるなら 2〜5営業日程度。

### Phase 2: Next.js プロジェクト作成

目的: 本番向けの土台を作る。

作業:

- Next.js App Router + TypeScript のプロジェクト作成
- ESLint / Prettier / tsconfig 整備
- グローバルCSS移植
- `public/assets/` へ固定画像を移動
- 共通レイアウト作成
- ルート定義作成

目安: 0.5〜1営業日。

### Phase 3: 静的ページの移植

目的: 現在の見た目を Next.js 上で再現する。

作業:

- トップページ移植
- Services ページ移植
- Company ページ移植
- Contact ページ移植
- Policy ページ移植
- Header / Footer / ContactCTA / Breadcrumb / PageHero の共通化
- `.html` 前提リンクを Next.js ルートへ変更

目安: 1.5〜3営業日。

### Phase 4: WordPress CMS 設計・実装

目的: Works / News を投稿管理できるようにする。

作業:

- WordPress 環境準備
- `works` カスタム投稿タイプ作成
- `news` カスタム投稿タイプ作成
- カテゴリ/タクソノミー設計
- ACF フィールド設計
- REST API または WPGraphQL の公開項目整理
- 投稿サンプル登録
- 画像登録ルール整理

目安: 1〜2営業日。サーバー準備やログイン情報待ちがある場合は別途。

### Phase 5: Next.js と WordPress の接続

目的: 投稿データを Next.js 側で表示する。

作業:

- WordPress APIクライアント作成
- Works一覧取得
- Works詳細取得
- News一覧取得
- News詳細取得
- トップページの Works / News プレビュー取得
- 関連投稿・前後投稿ロジック実装
- 投稿未取得時の 404 対応
- ISR / revalidation 設計

目安: 1.5〜3営業日。

### Phase 6: フォーム本番対応

目的: お問い合わせを実際に受け取れるようにする。

作業:

- 入力バリデーション
- Route Handler 実装
- メール送信サービス接続
- スパム対策
- 送信完了ページ遷移
- エラー表示
- 添付ファイルの扱い確定

目安: 0.5〜2営業日。添付ファイル対応の有無で変動。

### Phase 7: SEO / URL / 本番設定

目的: 公開品質を整える。

作業:

- metadata API 設計
- 投稿別メタ情報出し分け
- sitemap生成
- robots.txt
- canonical
- OGP
- 301 redirect
- 404ページ
- analytics / Search Console 用設定

目安: 0.5〜1.5営業日。

### Phase 8: QA / デプロイ

目的: 公開前に表示・導線・投稿更新を確認する。

作業:

- デスクトップ表示確認
- スマートフォン表示確認
- Safari / Chrome / Edge 確認
- Works / News 投稿追加テスト
- 既存URL redirect テスト
- フォーム送信テスト
- Lighthouse確認
- 本番ビルド確認
- ホスティング設定

目安: 1〜3営業日。

## 全体工数目安

現在の見た目修正とレスポンシブを別工程として進める場合、Next.js + Headless WordPress 化そのものは 5〜10営業日程度が目安です。

デザイン確定、レスポンシブ実装、投稿設計、本番フォーム、SEO、デプロイまでまとめて見る場合は、全体で 1.5〜3週間程度を見ておくのが現実的です。

変動要因:

- WordPress 環境がすでにあるか
- ACF Pro を使えるか
- 投稿プレビューが必要か
- 添付ファイル付きフォームが必要か
- 原稿・画像・会社情報が確定しているか
- レスポンシブのデザイン調整量
- 本番ドメイン、DNS、ホスティング設定を誰が担当するか

## 先に決めるべきこと

作業に入る前に、以下は決めておくと進行が安定します。

1. WordPress の設置場所

```txt
cms.example.com
example.com/cms
```

どちらでも可能ですが、`example.com/cms` にする場合はホスティングやリバースプロキシ設定が絡むことがあります。実装の単純さでは `cms.example.com` が扱いやすいです。

2. WordPress API方式

```txt
REST API
WPGraphQL
```

初期は REST API で十分です。

3. ACF の利用可否

Works の `details` や `gallery` を管理画面で扱いやすくするなら ACF が有力です。

4. 投稿プレビューの必要性

下書きプレビューが必要なら、Next.js 側に preview/draft mode の実装が必要です。

5. フォームの要件

```txt
添付ファイルあり/なし
送信先メールアドレス
自動返信の有無
WordPress側への保存有無
スパム対策
```

6. 本番ホスティング

Next.js は Vercel が最も簡単です。WordPress は別途管理が必要です。

## 推奨する進め方

1. 現在のデスクトップ修正を完了する
2. レスポンシブ実装を行い、見た目を確定する
3. Works / News の投稿項目を確定する
4. Next.js プロジェクトを作成する
5. 現在の固定ページを Next.js に移植する
6. WordPress 側に Works / News 投稿タイプを作る
7. Next.js 側で WordPress 投稿を表示する
8. フォーム、SEO、redirect、OGPを整える
9. ステージングで確認してから本番公開する

現時点では、まず見た目とレスポンシブを固めてから移行した方が効率的です。ただし、Works / News の投稿項目設計だけは早めに決めておくと、後工程の手戻りが減ります。

## 本番移行時に残さないもの

以下は本番 Next.js には持ち込まない方針です。

- ブラウザ上の Babel Standalone
- UMD React / ReactDOM の CDN 読み込み
- `script type="text/babel"` による JSX 読み込み
- `.html` 前提の内部リンク
- `works-detail.html?slug=...` / `news-detail.html?slug=...` 形式
- JSX 内の Works / News 投稿配列
- ローカル確認用の `src/dev-inspector.js`
- 1920px 固定キャンバスを縮小するだけのレスポンシブ代替

## 本番移行時に活かすもの

以下はそのまま設計資産として活かせます。

- 現在のビジュアル方向性
- `styles/site.css` に整理し始めている共通クラス
- Header / Footer / ContactCTA / Breadcrumb / PageHero のコンポーネント分割
- Worksカード、News一覧、Services詳細、ContactフォームのUI構造
- `assets/` の固定ページ用画像
- Works / News のサンプル投稿データ
- `/services`, `/works`, `/news`, `/company`, `/contact` というページ設計

## 実装判断

このサイトでは、Next.js + Headless WordPress が妥当です。

Astro の方が静的サイトとしては軽量ですが、ユーザーが Next.js に慣れていること、Works / News の投稿機能、将来的なプレビューやフォーム拡張を考えると、Next.js の方が運用上のメリットが大きいです。

初期本番化の優先順位は以下です。

1. 見た目の完成度
2. レスポンシブ品質
3. Works / News の投稿運用
4. 綺麗なURL
5. SEO / OGP
6. フォーム送信
7. デプロイと更新フロー

この順番で進めると、現在の実装を無駄にせず、本番公開できる構成へ自然に移行できます。
