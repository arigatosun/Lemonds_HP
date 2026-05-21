# Contact Form 7 セットアップ手順書（lemonds テーマ）

> 本書は WP 管理画面で行う **CF7 フォーム作成 + メール設定 + WP Mail SMTP 設定** の人間向け再現手順です。
> コードで自動化できない部分（プラグインの管理画面操作）はここに全て記載しています。
> フォーム markup / メール本文の **正本（Single Source of Truth）** は `cf7-mail-template.php` の PHP 定数です。本書とコード定数で文言が食い違った場合は **PHP 定数を優先** してください。

参照:
- `.claude/plans/schema-registry.md` §5 Contact フォーム
- `.claude/plans/decisions.md` §C メール送信
- `theme/lemonds/inc/cf7-mail-template.php`（フォーム markup / メール本文の定数）
- `theme/lemonds/inc/cf7-tweaks.php`（送信成功後の `/contact/thanks/` 遷移）
- `theme/lemonds/assets/js/contact.js`（`?type=quote` プリセット等）

---

## 1. 前提プラグインのインストール

WP 管理画面 → プラグイン → 新規追加 で以下をインストール + 有効化。

| プラグイン | 用途 | 備考 |
|---|---|---|
| Contact Form 7 | フォーム本体 | 必須 |
| WP Mail SMTP by WPForms | メール到達性確保 | 必須（本番）。Local では Mailpit / Mailtrap で代替可 |
| Flamingo（任意） | 送信履歴の DB 保存 | あると問い合わせ取りこぼし防止になる |

---

## 2. お問い合わせフォームの作成

### 2-1. 新規フォーム作成

1. WP 管理画面 → **お問い合わせ** → **新規追加**
2. タイトル: `お問い合わせ`（任意、管理用）
3. **「フォーム」タブ** に切り替え、デフォルトの内容を **すべて削除** してから、`cf7-mail-template.php` の `LEMONDS_CF7_FORM_MARKUP` 定数の中身（`<div class="lm-contact-form">` から `</div>` まで）をそのままコピー & ペースト。

> **重要**: name フィールドは CF7 の予約名 `name` と衝突するため `fullname` を使用しています。markup を改変するときは必ず `fullname` を維持してください（メールテンプレ側でも `[fullname]` で参照しています）。

### 2-2. フォーム項目仕様（参考）

| name | type | 必須 | 備考 |
|---|---|---|---|
| `inquiry_type[]` | checkbox 複数 | ✅ | 6 種類の選択肢、複数選択可 |
| `company` | text | ✅ | プレースホルダ `ABC株式会社` |
| `fullname` | text | ✅ | プレースホルダ `田中 太郎` |
| `email` | email | ✅ | プレースホルダ `xxxxxxxxxx@example.com` |
| `tel` | tel | — | プレースホルダ `000-0000-0000` |
| `message` | textarea | ✅ | プレースホルダ `具体的なお問い合わせ内容をご記入ください` |
| `file_attachment` | file | — | 合計 10MB、`pdf/jpg/jpeg/png/doc/docx/xls/xlsx/ppt/pptx`（CF7 予約名 `attachment` 衝突回避のためリネーム） |
| `privacy_consent` | acceptance | ✅ | `optional-invalid` を付与し、未チェック時は送信不可 |

### 2-3. 「保存」前に必ず確認

- フォームタブ右下の「保存」ボタンを押す前に、上部にエラーメッセージ（赤帯）が出ていないか確認
- 出ている場合は markup のタグ閉じ忘れ・スペル違いがほとんどなので、`LEMONDS_CF7_FORM_MARKUP` 定数と diff を取る

---

## 3. メール設定（管理者通知 = 「メール」タブ）

CF7 フォーム編集画面の **「メール」タブ** に切り替えて、以下のとおり入力。

| フィールド | 値 |
|---|---|
| 送信先 | `<decisions.md §C で確定した宛先>`（未確定なら `info@lemonds.local` 等の暫定値で OK）|
| 送信元 | `[_site_title] <wordpress@<your-domain>>`（WP Mail SMTP の From と一致させる）|
| 題名 | `LEMONDS_CF7_MAIL_ADMIN_SUBJECT` の値（= `【ルモンズHP】お問い合わせ - [company] 様`）|
| 追加ヘッダー | `Reply-To: [email]` |
| ファイル添付 | `[file_attachment]` |
| メッセージ本文 | `LEMONDS_CF7_MAIL_ADMIN_BODY` の中身をそのまま貼り付け |
| HTML 形式 | チェックを外す（プレーンテキスト）|

> **送信先（To）** は `.claude/plans/decisions.md` §C で確定後に差し替えてください。未確定のまま本番に出さないこと。

---

## 4. 自動返信メール（「メール (2)」タブ）

「メール」タブ最下部の **「メール (2) を使用」** チェックボックスを ON にし、「メール (2)」タブに以下を設定。

| フィールド | 値 |
|---|---|
| 送信先 | `[email]` |
| 送信元 | `株式会社ルモンズエンターテインメント <wordpress@<your-domain>>` |
| 題名 | `LEMONDS_CF7_MAIL_REPLY_SUBJECT` の値（= `【ルモンズエンターテインメント】お問い合わせありがとうございます`）|
| 追加ヘッダー | `Reply-To: [_site_admin_email]`（または管理者の問い合わせ受付アドレス）|
| ファイル添付 | 空欄（自動返信に添付は送らない）|
| メッセージ本文 | `LEMONDS_CF7_MAIL_REPLY_BODY` の中身をそのまま貼り付け |
| HTML 形式 | チェックを外す |

---

## 5. メッセージ文言（「メッセージ」タブ）

基本はデフォルトで OK。必要なら以下を日本語の言い回しに調整：

| メッセージキー | 推奨文言 |
|---|---|
| メッセージ本文の送信が成功した場合 | `お問い合わせありがとうございました。送信が完了しました。`（※実際には `/contact/thanks/` へ遷移するためほぼ表示されない）|
| メッセージ本文の送信が失敗した場合 | `送信中にエラーが発生しました。時間をおいて再度お試しください。` |
| 入力内容に不備があるため送信に失敗した場合 | `入力内容に誤りがあります。赤字の箇所をご確認のうえ、再度送信してください。` |
| 必須項目が未入力の場合 | `この項目は必須です。` |

---

## 6. フォーム ID の取得と `page-contact.php` への反映

### 6-1. ID 確認

1. フォームを「保存」した後、フォーム一覧画面（**お問い合わせ → コンタクトフォーム**）に戻る
2. 該当フォーム行の **「ショートコード」** 列を確認すると `[contact-form-7 id="123" title="お問い合わせ"]` のような表記がある
3. `id="..."` の中の **数値（例: `123`）** が CF7 フォーム ID

### 6-2. `page-contact.php` のプレースホルダ置換

`theme/lemonds/page-contact.php` 内の以下の行を編集：

```php
<?= do_shortcode('[contact-form-7 id="CF7_FORM_ID" title="お問い合わせ"]') ?>
```

↓ `CF7_FORM_ID` を 6-1 で確認した実 ID（例: `123`）に置換：

```php
<?= do_shortcode('[contact-form-7 id="123" title="お問い合わせ"]') ?>
```

> **注意**: このファイルは page-migrator の所有物です。form-builder は markup そのものを編集せず、本書の通り **数値だけ書き換える** のが手順です。git のコミットメッセージ例: `Phase 3: CF7 フォーム ID を反映`。

### 6-3. 動作確認

1. `/contact/` をブラウザで開く
2. フォームが表示され、必須項目を入力 → 送信
3. `/contact/thanks/` に遷移すれば OK（CF7 単体では遷移しないので、ここが効いていれば `cf7-tweaks.php` が正しく動いている）
4. `?type=quote` 付きで開いたとき「見積もり依頼」がプリチェックされていれば `contact.js` 正常

---

## 7. WP Mail SMTP 設定ガイド（3 候補）

WP Mail SMTP プラグインの **設定 → 一般** で「メーラー」を選択し、以下のいずれかで設定する。

### 7-A. Gmail / Google Workspace (OAuth)

| 項目 | 値 |
|---|---|
| メーラー | Google / Gmail |
| Return Path | チェックを入れる |
| クライアント ID / クライアントシークレット | Google Cloud Console → API とサービス → 認証情報 → OAuth 2.0 クライアント ID を発行（Web アプリ）|
| 承認済みのリダイレクト URI | WP Mail SMTP の設定画面に表示される URL（例: `https://your-domain/wp-admin/admin.php?page=wp-mail-smtp...`）|
| From Email | Gmail / Workspace のメールアドレス（本人のもの）|
| From Name | `ルモンズエンターテインメント` |

**設定後**:
1. 「Google で認証する」ボタンを押し、OAuth フローで許可
2. 「テストメール送信」で疎通確認

**注意点**:
- 無料 Gmail は 1 日 500 通の送信上限あり（業務量によっては Google Workspace を推奨）
- 2 要素認証が有効でも OAuth なら問題なし（アプリパスワードは不要）

### 7-B. SendGrid

| 項目 | 値 |
|---|---|
| メーラー | SendGrid |
| API キー | SendGrid 管理画面 → Settings → API Keys → 「Mail Send」フル権限で作成 |
| Sending Domain | SendGrid で **Domain Authentication（SPF/DKIM）** 済みのドメイン |
| From Email | 認証済みドメイン配下のアドレス（例: `noreply@lemonds.co.jp`）|
| From Name | `ルモンズエンターテインメント` |

**設定後**:
1. SendGrid 側で Domain Authentication 完了（DNS に CNAME × 3 を設定）
2. WP Mail SMTP の「テストメール送信」で疎通確認

**注意点**:
- 無料プラン 100 通/日。問い合わせ規模ならこれで十分
- DNS 反映に最大 24 時間かかる場合がある
- Single Sender Verification（DNS 不要）でも可だが本番運用は Domain Authentication 推奨

### 7-C. さくらインターネット SMTP

さくらのレンタルサーバで本番運用する場合の標準パターン。

| 項目 | 値 |
|---|---|
| メーラー | その他の SMTP |
| SMTP ホスト | `<initial-domain>.sakura.ne.jp`（コントロールパネルで確認）|
| 暗号化 | SSL |
| SMTP ポート | `465` |
| 認証 | 有効 |
| SMTP ユーザー名 | さくらメール管理画面で作成したアドレス全体（例: `info@lemonds.co.jp`）|
| SMTP パスワード | 同上のメールアカウントのパスワード（**WP の wp-config.php に直書きすれば DB に保存されない**）|
| From Email | SMTP ユーザー名と同じアドレス |
| From Name | `ルモンズエンターテインメント` |

**パスワード直書き例**（`wp-config.php` の `/* That's all, stop editing! */` の上に追加）:

```php
define('WPMS_ON', true);
define('WPMS_SMTP_PASS', '<さくらメールのパスワード>');
```

**注意点**:
- 「SMTP の利用許可」をさくらコントロールパネルで ON にしておく必要がある
- 大量送信には向かない（業務量 100〜200 通/月程度ならこれで十分）

---

## 8. Local 環境での送信テスト

本番 SMTP は使えない / 使いたくない開発フェーズでは、ローカル MTA で送信メールを「捕獲」して確認する。

### 8-A. Mailpit（推奨、Local by Flywheel と相性◎）

1. [Mailpit](https://github.com/axllent/mailpit) を Local マシンにインストール（`scoop install mailpit` / `brew install mailpit` 等）
2. 起動: `mailpit`（デフォルトで SMTP `localhost:1025` / Web UI `http://localhost:8025`）
3. WP Mail SMTP の設定:

| 項目 | 値 |
|---|---|
| メーラー | その他の SMTP |
| SMTP ホスト | `localhost` |
| 暗号化 | なし |
| SMTP ポート | `1025` |
| 認証 | 無効 |
| From Email | `wordpress@lemonds.local` |
| From Name | `ルモンズエンターテインメント` |

4. `/contact/` から送信 → `http://localhost:8025` で **管理者通知 + 自動返信** の両方が捕獲されることを確認

### 8-B. Mailtrap（クラウド、複数人で共有確認したい場合）

1. [Mailtrap](https://mailtrap.io) で無料アカウントを作成 → Inbox の「Show Credentials」から SMTP 情報を取得
2. WP Mail SMTP の設定:

| 項目 | 値 |
|---|---|
| メーラー | その他の SMTP |
| SMTP ホスト | `sandbox.smtp.mailtrap.io` |
| 暗号化 | TLS |
| SMTP ポート | `2525` |
| 認証 | 有効 |
| SMTP ユーザー名 / パスワード | Mailtrap Inbox のクレデンシャル |
| From Email | `wordpress@lemonds.local` |

3. 送信 → Mailtrap の Web UI で受信確認

### 8-C. WP Mail Logging（補助）

`WP Mail Logging` プラグインを併用すると、WP から送信を試みたメール（成功・失敗問わず）がすべて DB に記録される。送信周りのデバッグ時に有用。

---

## 9. 動作確認チェックリスト（本書セットアップ完了時に消化）

- [ ] `/contact/` でフォーム 8 項目（種別 / 会社名 / 担当者名 / メール / TEL / 内容 / 添付 / 同意）が表示される
- [ ] 必須項目を空欄で送信するとエラー表示される
- [ ] 同意チェック未でも送信ボタンが押せない（または送信不可エラー）
- [ ] 添付ファイル選択時にファイル名が表示される（`contact.js` の UX）
- [ ] 10MB 超のファイルを選ぶとサイズエラーになる
- [ ] 正常送信で `/contact/thanks/` に遷移する（`cf7-tweaks.php`）
- [ ] 管理者通知メールが届く（Mailpit / 本番 SMTP 経由）
- [ ] 自動返信メールが [email] に届く
- [ ] `/contact/?type=quote` を開くと「見積もり依頼」がプリチェックされる
- [ ] スマホ（375px）でフォームが破綻なく表示される

---

## 10. トラブルシューティング

| 症状 | 原因と対処 |
|---|---|
| 送信ボタンが反応しない | `wpcf7-form` 要素が無い / JS エラー。ブラウザの開発者ツール Console を確認 |
| サンクスページに遷移しない | `cf7-tweaks.php` が読み込まれていない可能性。`functions.php` の `$lemonds_includes` 配列に `inc/cf7-tweaks.php` が含まれているか確認（wp-theme-architect 依頼項目）|
| メールが届かない（Local） | Mailpit / Mailtrap が起動 & WP Mail SMTP 設定済か確認 |
| メールが届かない（本番） | WP Mail SMTP の「Email Test」を実行 → エラーログを確認。SPF / DKIM の DNS 設定漏れが多い |
| 添付ファイルが付かない | CF7「メール」タブの「ファイル添付」欄に `[attachment]` を記載したか確認 |
| `[fullname]` が本文に空欄で出る | フォーム markup 側の name が `fullname` になっているか確認（`name` ではない）|

---

## 11. 引き渡し物（form-builder → 他レーン）

- **page-migrator** への依頼: `page-contact.php` 内の `CF7_FORM_ID` プレースホルダは本書 §6-2 の手順で人手で書き換える（form-builder は触らない）
- **wp-theme-architect** への依頼: `functions.php` の `$lemonds_includes` 配列に `'inc/cf7-tweaks.php'` を追加（詳細は完了報告書）

以上。
