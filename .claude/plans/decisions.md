# 確定事項ログ

> プロジェクト方針の単一の真実源（Single Source of Truth）。

最終更新: 2026-05-15

---

## ✅ 確定事項

### アーキテクチャ
- **形態**: Classic WordPress テーマ（PHP テンプレート）
- **ベース**: Underscores (_s) を fork してカスタマイズ
- **CMS 範囲**: Works / News のみ CPT 化、Services / Company / Policy / Contact は固定ページ
- **データソース**: WordPress 単一データソース（JSX 内の重複配列は撤去）

### スコープ
- **絞り込み**: なし、現状規模でやり切る
- **Works**: 9 件全部移行（詳細未実装の 4 件は ACF 雛形で空入力）
- **News**: 8 件全部移行
- **仮文・空欄**: そのまま掲載（クライアントが CMS から後で更新する前提）

### スケジュール
- **総工数**: 実質 3 営業日
- **ピーク日**: 平日休み中のため、開発者が連続稼働できる前提
- **納品**: Local + テーマ .zip + DB エクスポート + 移行手順書

### 技術スタック
- WordPress: Local by Flywheel（PHP 8.x、MySQL 8.x、ドメイン: **`lemonds.local`**）
- テーマ名: `lemonds`（Text Domain: `lemonds`）
- **ACF は不使用**（後から導入可能な設計で進める）
- 必須プラグイン:
  - **Contact Form 7**
  - **WP Mail SMTP**（メール到達性確保）
- 不使用:
  - **ACF / ACF Pro**: 将来必要になれば導入可能なよう、meta_key を ACF 互換命名にする
  - **Custom Post Type UI**: コードで CPT 登録（バージョン管理対象に乗せるため）
- 既存資産の活用:
  - `styles/colors_and_type.css` → テーマ `assets/css/colors_and_type.css` にコピー
  - `styles/site.css` → テーマ `assets/css/site.css` にコピー
  - `assets/*.{png,jpg,svg}` → テーマ `assets/img/` にコピー（固定ページ用）

### 本番移行に持ち込まないもの
- ブラウザ上の Babel Standalone
- UMD React / ReactDOM の CDN 読み込み
- `<script type="text/babel">` での JSX 読み込み
- `src/dev-inspector.js`（テーマには含めない）
- `.html` 拡張子の内部リンク
- `?slug=xxx` クエリ形式の詳細ページ URL
- JSX 内の Works/News 直書き配列

### URL 戦略
- 全 URL は permalink（拡張子なし、末尾スラッシュあり）
- 旧 `.html` URL は `.htaccess` で 301 redirect

---

## ✅ 確定（追加分）

### A. ACF 不使用方針
- **判断**: ACF / ACF Pro は **使用しない**。WordPress 標準（post_meta + Gutenberg + Taxonomy + Featured Image）で構築
- **影響**: Works の `details` は Gutenberg 本文に記述、`gallery` も Gutenberg ギャラリーブロックで対応
- **互換性**: meta_key を ACF 命名規則（先頭アンダースコアなし・小文字スネークケース）に揃え、将来 ACF Pro を導入したときに既存データを取り込めるようにする
- **クライアント運用**: Gutenberg を使った Works 詳細の書き方ガイドを `handover.md` に同梱

### B. Local 環境
- Local by Flywheel **インストール済み**、これからサイト作成
- **ドメイン: `lemonds.local`**
- PHP 8.x / MySQL 8.x / WP 最新

## ⚠️ 未確定（Day 2 開始までに決定が必要）

### C. メール送信（CF7 + SMTP）
- 送信先メールアドレス（管理者通知）← **後で提供**
- 自動返信メールの差出人と文面 ← デフォルトテンプレ済（schema-registry §5、調整可）
- Local テスト中は Mailpit / Mailtrap で確認
- 本番 SMTP プロバイダ（SendGrid / Gmail OAuth / さくら SMTP / 等）← 本番移行時に決定
- 添付ファイル 10MB を許容するか（運用負荷あり）← 初期は許容で進める、CF7 設定で簡単に変更可

### G. Git 管理範囲
- `.claude/` ディレクトリは **コミットしてチーム共有**（クリーンな履歴を保つ）
- 納品物（テーマ zip）からは `.claude/` を除外

### H. 本番ホスティング（参考、納品後の話）
- 本番サーバー（エックスサーバー / さくら / KUSANAGI / 等）
- ドメイン
- SSL 証明書
- 移行作業の責任分界

### I. 原稿・素材
- 仮文の差し替え（クライアント納品時に「ここ要差し替え」リストを添付する想定で進める）
- Works 9 件分の本物の画像 / 説明文（未提供分は仮置きのまま）

---

## 🔁 変更履歴

| 日付 | 変更 | 理由 |
|---|---|---|
| 2026-05-15 | 初版作成 | Classic WP テーマ方針確定 |
| 2026-05-15 | ACF 不使用に確定 | ユーザー判断、3 日納品の確実性優先。Gutenberg 本文 + 最小メタボックスで構築、将来 ACF Pro 導入可能な命名規則を維持 |
| 2026-05-15 | Local ドメイン `lemonds.local` に確定 | — |
| 2026-05-15 | `.claude/` を git 管理に確定 | チーム共有方針 |
| 2026-05-15 | `cpt-acf-builder` を `cpt-builder` にリネーム | ACF 不使用方針に合わせる |
