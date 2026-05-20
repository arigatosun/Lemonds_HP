<?php
/**
 * Contact Form 7 のフォーム markup / メール設定を「コード側にも記録」するための定数集
 *
 * CF7 はプラグインであり、フォーム本体・メール設定は WP 管理画面の DB に保存される。
 * このファイル自体は WordPress の挙動を変えないが、再現性のために CF7 管理画面に
 * 貼り付ける文字列を PHP 定数として保持する。手順書（cf7-mail-template.md）からも
 * 参照され、別環境でのフォーム復元時の正本（Single Source of Truth）となる。
 *
 * 使い方:
 * - WP 管理画面 → お問い合わせ → コンタクトフォームの編集タブで、
 *   下記定数の中身をそのままコピー & ペーストする
 * - メールタブ・自動返信タブ（メール (2)）も同様
 *
 * 参照:
 * - inc/cf7-mail-template.md（人間向けセットアップ手順）
 * - schema-registry.md §5 Contact フォーム
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * CF7 フォーム markup（「フォーム」タブに貼り付け）
 *
 * 注意:
 * - name フィールドは CF7 の予約名 `name` 衝突回避のため `fullname` を使う
 * - 添付ファイルの合計上限は 10485760 bytes (10MB)
 * - acceptance は optional-invalid を付け、未チェックなら送信不可
 */
if (!defined('LEMONDS_CF7_FORM_MARKUP')) {
    define('LEMONDS_CF7_FORM_MARKUP', <<<'CF7'
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
CF7
    );
}

/**
 * 管理者通知メール（「メール」タブに貼り付け）
 *
 * - 宛先（To）は decisions.md §C で確定したアドレスに差し替え
 * - From は WP Mail SMTP 側で設定した送信元アドレスに合わせる
 *   （CF7 標準の wordpress@<domain> は SPF/DKIM 落ちしやすいため）
 */
if (!defined('LEMONDS_CF7_MAIL_ADMIN_SUBJECT')) {
    define('LEMONDS_CF7_MAIL_ADMIN_SUBJECT', '【ルモンズHP】お問い合わせ - [company] 様');
}

if (!defined('LEMONDS_CF7_MAIL_ADMIN_BODY')) {
    define('LEMONDS_CF7_MAIL_ADMIN_BODY', <<<'MAIL'
ルモンズHP のお問い合わせフォームから新しい送信があります。

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

--
このメールは [_site_title] ([_site_url]) のお問い合わせフォームから送信されました。
MAIL
    );
}

/**
 * 自動返信メール（「メール (2)」タブに貼り付け）
 */
if (!defined('LEMONDS_CF7_MAIL_REPLY_SUBJECT')) {
    define('LEMONDS_CF7_MAIL_REPLY_SUBJECT', '【ルモンズエンターテインメント】お問い合わせありがとうございます');
}

if (!defined('LEMONDS_CF7_MAIL_REPLY_BODY')) {
    define('LEMONDS_CF7_MAIL_REPLY_BODY', <<<'MAIL'
[fullname] 様

このたびはお問い合わせいただきありがとうございます。
以下の内容でお問い合わせを受け付けました。
内容を確認のうえ、担当者より 1〜2 営業日以内にご連絡いたします。

—— 受付内容 ——
種別: [inquiry_type]
会社名: [company]
お名前: [fullname]
メール: [email]
TEL: [tel]
内容:
[message]
——

※ このメールは自動返信です。本メールへの返信はお控えください。

----------------------------------------
株式会社ルモンズエンターテインメント
〒160-0022 東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F
TEL: 03-5969-9075
[_site_url]
----------------------------------------
MAIL
    );
}
