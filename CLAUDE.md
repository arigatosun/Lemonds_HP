# Lemonds HP — WordPress 移行プロジェクト

> 新セッション開始時に必ず読む。詳細は `.claude/plans/` と `docs/` 配下を参照。

---

## 概要

るもんずHP（株式会社ルモンズエンターテインメント）の既存静的サイト（HTML + JSX + ブラウザ Babel）を、Classic WordPress カスタムテーマ **`lemonds`** に移植する案件。

- **テーマ名**: `lemonds` (Text Domain: `lemonds`)
- **Local ドメイン**: `lemonds.local`
- **本番ホスティング**: 納品後別案件（このプロジェクトでは本番設置までは行わない）
- **納品物**: テーマ `.zip` + DB エクスポート (`.sql.gz`) + 移行手順書 (README.md)

---

## 納品期限と優先度

- **2026-05-22 (金) 中** に WP 移行実装を完了させる
- 完了後: **クライアント最終確認フェーズ → 微修正フェーズ** に入る
- **優先度: ★★★（最優先タスク）** ユーザー側で他の依頼が来ても本案件を優先する
- 微修正フェーズに持ち越して良いもの: 仮文差し替え、クライアント原稿依頼、本番 SMTP 設定、画像最適化

---

## 進捗ステータス（最終更新: 2026-05-21 13:30 / 木曜 14:00 プルーセル MTG 前 中断）

### Phase / Wave（コード本体）

| Phase | 内容 | コミット | 状況 |
|---|---|---|:---:|
| 0 | エージェント自律開発基盤 | `e4676af` | ✅ |
| 1 (Wave 1) | テーマ骨格 + CPT + テンプレパーツ | `6c23e18` | ✅ |
| 2 (Wave 2) | 静的ページ 6 枚を PHP テンプレに移植 | `c6fe589` | ✅ |
| 3 (Wave 3) | CPT archive/single + seed + CF7 | `d1513b7` | ✅ |
| 4 (Wave 4) | front-page WP_Query + SEO + 301 リダイレクト | `94c1a66` | ✅ |
| 4.5 | CF7 設定反映 + works seed の PHP 8 互換性修正 | `56c28f3` | ✅ |
| **5-A** | site.css の SP レスポンシブ仕上げ (375-560px) | — | ⏳ 木曜午後〜予定 |
| **5-B** | QA レポート (qa-findings.md 起票) | — | ⏳ 木曜午後〜予定 |
| **6** | テーマ .zip / DB エクスポート / README 納品手順書 | — | ⏳ 金曜予定 |

### Local セットアップ（`docs/local-setup-guide.md` 参照）

| Step | 内容 | 状況 |
|---|---|:---:|
| 1〜6 | Local 環境 + テーマ activate + 固定ページ 5 枚 | ✅ |
| 7-1 | CF7「フォーム」タブにマークアップ貼り付け | ✅ |
| 7-2 | CF7「メール」タブ（管理者通知）設定 | ✅ |
| 7-3 | CF7「メール (2)」タブ（自動返信）設定 + 保存 | ✅ |
| 8 | CF7 フォーム ID (`50e7785`) を `page-contact.php` に埋め込み | ✅ |
| 9 | WP-CLI で seed 実行（works 9 件 / news 8 件） | ✅ |
| **10** | **全 URL ブラウザ確認（HTML 旧版と左右比較）** | 🔶 **未着手、MTG 後再開** |
| 11 | qa-findings.md に気になった点を起票 | ⏳ |

最新の状況は `git log --oneline -10` で確認すること。

---

## 🔁 次セッション再開ポイント（2026-05-21 13:30 中断 / 14:00 プルーセル MTG 後再開予定）

### 前回終了時点の状況
- コード本体 + CF7 設定 + seed 投入まで完了（`56c28f3` まで push 済み）
- Works 9 件 (ID 30-44) / News 8 件 (ID 22-29) が DB 投入済み
- CF7 フォーム ID = **`50e7785`**、`page-contact.php` に反映済み
- WP_DEBUG / WP_DEBUG_LOG は ON、WP_DEBUG_DISPLAY は OFF にして締めた想定（要確認）
- ブラウザ動作確認は未着手、HTML 旧版との左右比較を MTG 後にやる予定

### Step 9 で発生して解決済みのトラブル（再発したら参照）
- `register_post_meta('works', 'sort_order', ['sanitize_callback' => 'intval'])` が PHP 8 で `ArgumentCountError: intval() expects at most 2 arguments, 4 given` を起こす → `intval` を `absint` に差し替えで解決（`cpt-works.php:88-95`）
- 同様の症状が他の CPT meta で出たら同じ手で直す（PHP 8 内部関数は引数数チェックが厳格、WP のフィルタは 4 引数渡してくる）

### 新セッション開始時に最初にやること

1. **CLAUDE.md と git log の確認**
   ```
   git log --oneline -10
   ```
2. **ユーザーに 1 つだけ確認**: 「Step 10（ブラウザでの全 URL 動作確認）を開始しますか？ HTML 旧版を `python -m http.server 8000` で並走させて左右比較する想定で進めて OK？」
3. **HTML 旧版起動の案内**: 新ターミナルで `cd C:\Users\OWNER\Desktop\Project\rumoen\Lemonds_HP` → `python -m http.server 8000`。  
   旧版 = `http://localhost:8000/` / 新版 = `http://lemonds.local/`
4. **URL 対応表** はチャット末尾参照、または再生成（front / services / company / contact / policy / works archive / works single / news archive / news single + 301 リダイレクト 4 種）
5. **マネージャーモード継続**: ページ単位で「ここ違う/ここ OK」をユーザーが報告 → 差分は `.claude/plans/qa-findings.md` に severity 付きで記録

### Step 10 終了後にやること

| 順序 | 内容 |
|---|---|
| Step 11 | `.claude/plans/qa-findings.md` を新規作成し、Step 10 で見つけた issue を severity (High/Mid/Low) 付きで記録 |
| Wave 5-A | `responsive-fixer` Agent 起動: `theme/lemonds/assets/css/site.css` の SP 375-560px 仕上げ |
| Wave 5-B | `qa-reviewer` Agent 起動: 静的解析（リンク切れ / エスケープ漏れ / コンソールエラー）。Wave 5-A と並列発射可 |
| 5-A/5-B 完了 | 致命/中重要度 issue を修正対応 → コミット |
| Wave 6 | テーマ .zip パッケージング / DB `.sql.gz` エクスポート / 移行手順書 README → クライアント納品 |

### 残り工数見込み（5/22 金曜納品まで）
- Step 10 + 11 + Wave 5 + 修正対応: 4-5h
- Wave 6 + クライアント送付準備: 2-3h
- → 合計 6-8h（木曜午後〜金曜）

---

## 推奨スケジュール（残 2 営業日 + 半日）

| 日 | ユーザー作業 | Claude 並列タスク |
|---|---|---|
| **水 5/20 残り** | Local セットアップ + 表示確認 (2-3h) | （ユーザー確認待ち、起動は反映後） |
| **木 5/21** | 表示の手動確認 + qa-findings レビュー + 重要 issue クローズ (3-4h) | Wave 5-A (responsive-fixer) + Wave 5-B (qa-reviewer) 並列起動 |
| **金 5/22 午前** | 残 issue クローズ (2-3h) | issue 修正対応 |
| **金 5/22 午後** | Wave 6 納品準備 → クライアント納品 (1-2h) | テーマ zip 化補助 / 移行手順書 |

合計ユーザー稼働見込み: **8-12 時間**

---

## 一次資料（着手前に必ず読む）

| ファイル | 用途 |
|---|---|
| `.claude/plans/PLAN.md` | Wave 計画と責任マトリクス |
| `.claude/plans/schema-registry.md` | データスキーマ（CPT / フォーム / URL マップ） |
| `.claude/plans/decisions.md` | 確定事項ログ（方針の唯一の真実源） |
| `docs/local-setup-guide.md` | Local 環境セットアップ 10 ステップ手順書 |
| `docs/lower-pages.md` | 下層ページ構成詳細 |
| `theme/lemonds/inc/cf7-mail-template.md` | CF7 設定 + WP Mail SMTP 設定ガイド |
| `theme/lemonds/inc/redirect-map.md` | 旧 URL → 新 permalink マップと本番適用手順 |

矛盾が起きたら `decisions.md` と `schema-registry.md` を **正** とする。

---

## サブエージェント一覧

`.claude/agents/*.md` に定義。Wave 起動時はこれらを並列発射する。

- `wp-theme-architect` — テーマ骨格・functions・SEO・.htaccess
- `cpt-builder` — CPT / Taxonomy / メタボックス（ACF 不使用）
- `page-migrator` — JSX/HTML → PHP テンプレ移植（1 呼び出し = 1 ページ）
- `form-builder` — CF7 フォーム / メールテンプレ / contact.js
- `responsive-fixer` — site.css の SP 仕上げ
- `qa-reviewer` — 全ページ動作確認とレポート

---

## 確定方針（重要、覆さない）

### 技術スタック
- **Classic WordPress テーマ**（Underscores ベース、ブロックテーマ不使用）
- **ACF / ACF Pro 不使用**。WP 標準（post_meta + Gutenberg + Taxonomy + Featured Image）で構築。meta_key は ACF 互換命名（先頭アンダースコアなし、小文字スネークケース）で将来導入余地を残す
- **CPT 化対象**: Works / News のみ。Services / Company / Policy / Contact は固定ページ + `inc/data-*.php` 配列
- **必須プラグイン**: Contact Form 7 / WP Mail SMTP
- **Local 環境**: Local by Flywheel、PHP 8.x / MySQL 8.x / WP 最新

### URL 戦略
- 全 URL は permalink（拡張子なし、末尾スラッシュあり）
- 旧 `.html` URL は `.htaccess` で 301 リダイレクト
- `works-detail.html?slug=xxx` / `news-detail.html?slug=xxx` は PHP 側で個別 redirect（30 日保険）

### 本番に持ち込まないもの
- ブラウザ Babel Standalone / UMD React CDN
- `<script type="text/babel">` での JSX 読み込み
- `src/dev-inspector.js`
- `.html` 拡張子の内部リンク（全て `lemonds_url('key')` ヘルパ経由に変換済）

---

## Wave 進行ルール（並列実行が必須）

Wave 単位で並列実行する。Wave 内のレーン (L*-A〜L*-E) は **同一メッセージ内で複数 Agent ツール呼び出し** で並列発射する（順次ではない）。

### Agent 起動時に必ずプロンプトに含める要素
1. **参照**: `.claude/plans/schema-registry.md`, `decisions.md`, `PLAN.md` の絶対パス
2. **担当ファイル**（PLAN.md の責任マトリクスから抜粋、編集可能範囲）
3. **編集禁止ファイル**（他レーンが触るもの、競合回避）
4. **完了条件 (DOD)**

### TaskCreate / TaskUpdate の運用
- 各レーンに 1 タスク
- 起動と同時に `in_progress` に更新
- 完了報告を受けたら `completed` に更新
- 並列起動と並列クローズを徹底

---

## 残作業 (2026-05-20 時点)

### ユーザー側に残っている手作業

1. **Local セットアップ**: `docs/local-setup-guide.md` の 10 ステップ通り
   - テーマ配置（mklink /J 推奨）
   - パーマリンク「投稿名」設定
   - CF7 + WP Mail SMTP インストール
   - 固定ページ 5 枚作成（services / company / contact / thanks / policy）
   - CF7 フォーム作成 + `page-contact.php` の `CF7_FORM_ID` を実 ID で置換
   - WP-CLI で seed 実行（works 9 件 / news 8 件）
2. **動作確認**: 全 URL を一通り表示確認、気になる点を `.claude/plans/qa-findings.md` に記録

### Claude 側で並列起動する残レーン

- **Wave 5-A** (responsive-fixer): `theme/lemonds/assets/css/site.css` の SP 375-560px 仕上げ。@media 15 箇所、3,613 行ベース
- **Wave 5-B** (qa-reviewer): 静的解析、リンク切れチェック、エスケープ漏れ、qa-findings.md 起票
- **Wave 6** (wp-theme-architect): テーマ .zip パッケージング、DB エクスポート手順書、README 納品書

ユーザーの Local 動作確認結果を受けてから Wave 5-A を起動する方が手戻りが少ない（CSS 修正の対象が見えてから動く）。Wave 5-B (静的解析) は並行可能。

---

## クライアント差し替え必須項目（微修正フェーズへ持ち越し可）

`theme/` 配下に TODO コメントが 10 箇所。主なもの:

| 項目 | 場所 | 緊急度 |
|---|---|:---:|
| CF7 フォーム ID の実値置換 | `page-contact.php:47,57` | 高（今週中） |
| 会社英語表記の確認 | `data-company.php` | 中 |
| 代表メッセージ本文 | `page-company.php:97` | 中（クライアント原稿待ち） |
| 経営理念詳細 | `page-company.php` | 中 |
| Google Maps 公式埋め込みコード | `page-company.php:121` | 低 |
| 企業ポリシー本文 / 改定日 | `page-policy.php:54,75` | 中 |
| info@lemonds.page 等のメール表記 | `front-page.php:517` | 中 |
| 会社概要の空欄項目（決算月 / 売上高 / 従業員数 / 所属団体） | `data-company.php` | 低 |

`grep -rn "TODO" theme/lemonds/ --include="*.php"` で常に最新の TODO 一覧を取得可能。

---

## 実装規模リファレンス

### 既存ソース（移植元）
- HTML 9 ファイル / JSX 22 ファイル / 既存 CSS 3,817 行 / 画像 38 枚

### 実装済み theme/lemonds/ (移植先)
- PHP 33 ファイル / JS 1 / CSS 2 / MD 2 / 画像 38 枚 / 合計 約 8,870 行

### CPT 投稿シード
- `seed-works.php` で **9 件**、`seed-news.php` で **8 件**を WP-CLI から冪等に投入

---

## トラブル時の参照順

1. `git log --oneline -10` で直近の変更を把握
2. `.claude/plans/PLAN.md` で現在の Wave 位置と責任マトリクスを確認
3. `.claude/plans/decisions.md` で方針が確定しているか確認
4. `.claude/plans/qa-findings.md`（存在すれば）で既知 issue を確認
5. `docs/local-setup-guide.md` § トラブルシューティングを確認

---

## このファイルを更新するタイミング

- Wave / Phase 完了時に「進捗ステータス表」を更新
- スケジュールが変動したら「推奨スケジュール」を更新
- 確定方針が変わったら（あれば）「確定方針」を更新 + decisions.md 側も更新
- 納品完了後は「微修正フェーズ用のメモ」セクションを追記
