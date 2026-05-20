# Local by Flywheel セットアップ手順

> Wave 4 までで実装したテーマを Local 環境で動作確認する手順。
> 本番移行時もほぼ同じ流れ（手順 1 のテーマ配置先が本番サーバーになる程度の違い）。

---

## 0. 前提確認

- Local by Flywheel で `lemonds.local` サイトが稼働中（PHP 8.x / MySQL 8.x / WordPress 最新）
- このリポジトリのパス: `C:\Users\OWNER\Desktop\Project\rumoen\Lemonds_HP\`
- Local サイトの Site folder（実体）: 通常 `C:\Users\OWNER\Local Sites\lemonds\app\public\`
  - 違う場合は Local の左メニュー「Go to site folder」で正確な場所を確認

---

## 1. テーマファイルの配置

### 方法 A: ディレクトリジャンクション（推奨）

管理者権限の PowerShell（または cmd）で:

```powershell
# 既に lemonds テーマフォルダが Local 側にあれば、まず退避
Remove-Item "C:\Users\OWNER\Local Sites\lemonds\app\public\wp-content\themes\lemonds" -Recurse -Force -ErrorAction SilentlyContinue

# ジャンクション作成（symlink と違い管理者権限なしで可）
cmd /c mklink /J "C:\Users\OWNER\Local Sites\lemonds\app\public\wp-content\themes\lemonds" "C:\Users\OWNER\Desktop\Project\rumoen\Lemonds_HP\theme\lemonds"
```

このリポジトリでファイルを修正したら、Local 側にもリアルタイム反映される（コピー不要）。

### 方法 B: フォルダごとコピー

エクスプローラーで `theme/lemonds/` を `wp-content/themes/` 配下にコピー。
※ 修正のたびに再コピーが必要。

---

## 2. テーマを有効化

WP 管理画面 → **外観 → テーマ** → 「LEMONDS ENTERTAINMENT」を有効化

---

## 3. パーマリンク設定（重要）

WP 管理画面 → **設定 → パーマリンク** → 「**投稿名**」を選択 → 「変更を保存」

これをやらないと CPT（works / news）のリライトが効かず、`/works/<slug>/` 等が 404 になる。

---

## 4. 必須プラグインインストール

WP 管理画面 → **プラグイン → 新規追加** から以下 2 つをインストール＋有効化:

| プラグイン | 用途 |
|---|---|
| **Contact Form 7** | お問い合わせフォーム本体 |
| **WP Mail SMTP** | メール到達性確保（Local テスト時は未設定でも OK） |

---

## 5. 固定ページ 5 枚を作成

WP 管理画面 → **固定ページ → 新規追加** で以下を作成:

| タイトル | URL スラッグ | ページ属性 → テンプレート | 親ページ |
|---|---|---|---|
| 事業内容 | `services` | 事業内容 | — |
| 会社概要 | `company` | 会社概要 | — |
| お問い合わせ | `contact` | お問い合わせ | — |
| 送信完了 | `thanks` | お問い合わせ送信完了 | お問い合わせ |
| ポリシー | `policy` | （自動: slug マッチ） | — |

> 「ページ属性 → テンプレート」のドロップダウンから対応するテンプレ名を選択する。
> ポリシーだけは `page-policy.php` がファイル名規約で slug 自動マッチするのでテンプレ選択不要。

---

## 6. CF7 でフォーム作成

1. WP 管理画面 → **お問い合わせ → 新規追加**
2. タイトルを「お問い合わせ」とする
3. `theme/lemonds/inc/cf7-mail-template.md` を開き、以下をコピペ:
   - **§2** の markup → CF7「フォーム」タブに貼り付け
   - **§3** → 「メール」タブを上書き設定
   - **§4** → 「メール (2)」タブを有効化＋設定
4. 保存 → 編集画面 URL の `?post=XX&action=edit` の **XX が CF7 フォーム ID**

---

## 7. CF7 フォーム ID を埋め込み

`theme/lemonds/page-contact.php` を開いて、以下のプレースホルダを実 ID に置換:

```php
// 変更前
do_shortcode('[contact-form-7 id="CF7_FORM_ID" title="お問い合わせ"]')

// 変更後（例: ID が 7 の場合）
do_shortcode('[contact-form-7 id="7" title="お問い合わせ"]')
```

---

## 8. WP-CLI で seed 実行（Works 9 件 / News 8 件投入）

Local の左メニュー → 対象サイト → **Open site shell** をクリック（Local 内蔵のターミナルが開く）。

```bash
wp eval-file wp-content/themes/lemonds/inc/seed-works.php
wp eval-file wp-content/themes/lemonds/inc/seed-news.php
```

両方とも冪等性あり（再実行で重複しない）。再投入したい場合は:

```bash
wp eval-file wp-content/themes/lemonds/inc/seed-works.php -- --force
```

---

## 9. WP Mail SMTP 設定（Local テスト時は任意）

Local では `Mailpit`（Local 内蔵）でメール送信ログを確認できる:
- Local の左メニュー → サイトの **Tools → Open Mailpit**

本格設定は `inc/cf7-mail-template.md` §7 参照（Gmail OAuth / SendGrid / さくら SMTP の 3 候補）。

---

## 10. ブラウザで動作確認

http://lemonds.local/ を開き、以下を一通りチェック:

| URL | 確認内容 |
|---|---|
| `/` | Hero / Services / Production / Works プレビュー (5 件) / News プレビュー (5 件) / Company / Contact |
| `/services/` | 8 事業の一覧と詳細セクション、`status=準備中` の trade / web がグレーアウト |
| `/works/` | 9 件の一覧、カテゴリタブでフィルタ |
| `/works/<slug>/` | 詳細ページ（旧 `/works-detail.html?slug=xxx` でアクセスしても 301 で同じ場所へ） |
| `/news/` | 8 件、ページネーション |
| `/news/<slug>/` | 詳細 + 関連投稿 |
| `/company/` | 会社概要テーブル + MAP iframe |
| `/contact/` | CF7 フォーム表示、送信テスト → Mailpit でメール受信確認 → `/contact/thanks/` 遷移 |
| `/contact/?type=quote` | 「見積もり依頼」がプリセット |
| `/policy/` | 3 つの方針セクション |
| `/news.html` | `/news/` へ 301 リダイレクト（テスト時は本番 .htaccess に貼った後） |

---

## トラブルシューティング

| 症状 | 対処 |
|---|---|
| `/works/` が 404 | パーマリンク設定が「投稿名」になっているか確認 → 再保存 |
| フォーム送信後遷移しない | `inc/cf7-tweaks.php` が `functions.php` の include 配列に入っているか確認（Wave 3 で対応済み） |
| CF7 フォームが表示されない | `page-contact.php` の `CF7_FORM_ID` を実 ID に置換したか確認 |
| サムネが出ない | seed 実行ログで `media_sideload_image()` エラーがないか確認 / `assets/img/top-works-*.jpg` が theme 内に存在するか |
| 会社住所が空欄 | `inc/data-company.php` の値は schema-registry §4 を反映済み（仮文は TODO コメント付きで保持） |

---

## 動作確認で気になる点があれば

`/.claude/plans/qa-findings.md` に記録 → Wave 5 (responsive-fixer / qa-reviewer) に渡す。
