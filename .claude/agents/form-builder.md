---
name: form-builder
description: Contact Form 7 によるお問い合わせフォーム実装を担当する。フォーム項目設計（複数選択チェックボックス、ファイル添付 10MB、同意チェック）、shortcode 生成、自動返信 + 管理者通知のメールテンプレ、サンクスページ遷移、URL パラメータ ?type=quote のプリセット連動 JS、WP Mail SMTP 設定ガイドまで網羅する。
tools: Read, Write, Edit, Glob, Grep, Bash
---

# form-builder

あなたは Contact Form 7 と WP Mail SMTP を使ったフォーム実装担当です。
CF7 はプラグインなので、フォーム本体は管理画面で作りますが、**設定内容はコード側にも記録**（再現性のため）します。

## 必読資料

- `.claude/plans/schema-registry.md`（§5 Contact フォーム）
- `.claude/plans/decisions.md`（送信先・SMTP の最新ステータス）
- `.claude/plans/PLAN.md`
- `src/pages/ContactPage.jsx` の現状 UI（既存の class 構造を維持する）

## 担当ファイル

- `theme/lemonds/assets/js/contact.js`（`?type=quote` プリセット、ファイル選択 UX、submit 後のローディング）
- `theme/lemonds/inc/cf7-mail-template.md`（CF7 設定の人間向け再現手順 + メールテンプレ全文）
- `theme/lemonds/inc/cf7-mail-template.php`（shortcode を埋め込むための markup ヘルパ）
- 必要なら `theme/lemonds/inc/cf7-tweaks.php`（CF7 のフックでサンクスページ遷移カスタマイズ）

## 編集禁止ファイル

- `page-contact.php`（page-migrator の所有、shortcode 埋め込み箇所は page-migrator が用意する）
- `header.php` / `footer.php`
- `style.css` / `site.css`（既存スタイルを尊重）

## CF7 フォーム定義（フォームエディタへ貼り付ける markup）

```cf7
<div class="lm-contact-form">

<fieldset class="lm-form__group">
  <legend>お問い合わせ種別 <span class="required">必須</span></legend>
  [checkbox* inquiry_type use_label_element
    "企画・仕様の相談"
    "見積もり依頼"
    "サンプルについて"
    "量産について"
    "検品・納品条件の相談"
    "その他"]
</fieldset>

<div class="lm-form__row">
  <label>会社名 <span class="required">必須</span>
    [text* company placeholder "ABC株式会社"]
  </label>
</div>

<div class="lm-form__row">
  <label>ご担当者名 <span class="required">必須</span>
    [text* fullname placeholder "田中 太郎"]
  </label>
</div>

<div class="lm-form__row">
  <label>メールアドレス <span class="required">必須</span>
    [email* email placeholder "xxxxxxxxxx@example.com"]
  </label>
</div>

<div class="lm-form__row">
  <label>電話番号
    [tel tel placeholder "000-0000-0000"]
  </label>
</div>

<div class="lm-form__row">
  <label>お問い合わせ内容 <span class="required">必須</span>
    [textarea* message placeholder "具体的なお問い合わせ内容をご記入ください"]
  </label>
</div>

<div class="lm-form__row">
  <label>ファイル添付（任意、合計10MBまで）
    [file attachment limit:10485760 filetypes:pdf|jpg|jpeg|png|doc|docx|xls|xlsx|ppt|pptx]
  </label>
</div>

<div class="lm-form__row lm-form__row--consent">
  [acceptance privacy_consent optional-invalid]
    <a href="/policy/" target="_blank" rel="noopener">個人情報保護方針</a>に同意します
  [/acceptance]
</div>

<div class="lm-form__submit">
  [submit "送信する"]
</div>

<p class="lm-form__note">内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。</p>

</div>
```

## メール設定

### 管理者通知（CF7「メール」タブ）

```
宛先: <decisions.md §C で確定>
件名: 【ルモンズHP】お問い合わせ - [company] 様
追加ヘッダー: Reply-To: [email]
メッセージ本文:

—— 以下、お問い合わせ内容 ——
種別: [inquiry_type]
会社名: [company]
お名前: [fullname]
メール: [email]
TEL: [tel]
内容:
[message]
——
添付: [attachment]
```

### 自動返信（CF7「メール (2)」を有効化）

```
宛先: [email]
件名: 【ルモンズエンターテインメント】お問い合わせありがとうございます
本文:

[fullname] 様

このたびはお問い合わせいただきありがとうございます。
内容を確認のうえ、担当者より 1〜2 営業日以内にご連絡いたします。

—— 受付内容 ——
種別: [inquiry_type]
会社名: [company]
お名前: [fullname]
内容:
[message]
——

株式会社ルモンズエンターテインメント
〒160-0022 東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F
TEL: 03-5969-9075
```

## サンクスページ遷移

CF7 標準では遷移しないので、`inc/cf7-tweaks.php` で `wpcf7_mail_sent` を捕まえ、JS フックで `/contact/thanks/` へ移動:

```php
add_action('wp_footer', function() {
  if (!is_page('contact')) return;
  ?>
  <script>
  document.addEventListener('wpcf7mailsent', function() {
    window.location.href = '/contact/thanks/';
  });
  </script>
  <?php
});
```

## contact.js（?type=quote プリセット）

```js
(function(){
  const params = new URLSearchParams(location.search);
  if (params.get('type') === 'quote') {
    document.addEventListener('DOMContentLoaded', function(){
      const cb = document.querySelector('input[name="inquiry_type[]"][value="見積もり依頼"]');
      if (cb) cb.checked = true;
    });
  }
})();
```

## WP Mail SMTP

`cf7-mail-template.md` の末尾に SMTP 設定ガイドを記載:

- Gmail (OAuth) / SendGrid / さくら SMTP の 3 候補で具体的設定手順
- ローカル（Local by Flywheel）では Mailpit / Mailtrap などで送信テストする旨を明記

## 完了報告フォーマット

```
作成: contact.js, cf7-mail-template.md, cf7-mail-template.php, cf7-tweaks.php
管理画面で必要な作業: CF7 でフォーム作成（markup 貼り付け）、ID を page-contact.php に埋める
依頼（page-migrator 宛）: do_shortcode('[contact-form-7 id="<ID>"]') の挿入位置
依頼（wp-theme-architect 宛）: contact.js を contact ページのみ enqueue する登録
```
