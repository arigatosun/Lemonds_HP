/**
 * lemonds contact page enhancements
 *
 * - URL クエリ `?type=quote` で「見積もり依頼」チェックボックスをプリセット
 * - 添付ファイル選択時にファイル名を表示する UX 改善
 * - submit ボタン押下後に多重送信を防ぐローディング表示
 *
 * このファイルは `is_page('contact')` のときのみ functions.php から enqueue される。
 * 参照: schema-registry.md §5 Contact フォーム
 *
 * @package lemonds
 */
(function () {
    'use strict';

    /**
     * `?type=quote` のとき「見積もり依頼」チェックボックスを ON にする
     */
    function presetInquiryType() {
        var params = new URLSearchParams(window.location.search);
        if (params.get('type') !== 'quote') {
            return;
        }

        // CF7 の checkbox は input[name="inquiry_type[]"] の形式で出力される
        var candidates = document.querySelectorAll('input[name="inquiry_type[]"]');
        if (!candidates.length) {
            return;
        }

        Array.prototype.forEach.call(candidates, function (input) {
            if (input.value === '見積もり依頼') {
                input.checked = true;

                // CF7 が wpcf7-list-item の親要素にスタイルを当てている場合の見た目同期
                var wrap = input.closest('.wpcf7-list-item');
                if (wrap) {
                    wrap.classList.add('is-checked');
                }

                // change イベントを発火して、他の連動 UI があれば反応させる
                var ev;
                try {
                    ev = new Event('change', { bubbles: true });
                } catch (e) {
                    // IE 互換のフォールバック（念のため）
                    ev = document.createEvent('Event');
                    ev.initEvent('change', true, true);
                }
                input.dispatchEvent(ev);
            }
        });
    }

    /**
     * ファイル添付欄の UX 改善
     * - ファイル選択時にファイル名（複数なら件数）を表示
     * - 既存の .lm-form のスタイルに合わせて、近くのテキスト領域へ書き込む
     */
    function enhanceFileInput() {
        var fileInputs = document.querySelectorAll('.lm-form input[type="file"]');
        if (!fileInputs.length) {
            return;
        }

        Array.prototype.forEach.call(fileInputs, function (input) {
            // 表示用要素を input 直後に挿入（既になければ）
            var label = input.parentNode ? input.parentNode.querySelector('.lm-form__file-name') : null;
            if (!label) {
                label = document.createElement('span');
                label.className = 'lm-form__file-name';
                label.setAttribute('aria-live', 'polite');
                input.parentNode && input.parentNode.appendChild(label);
            }

            input.addEventListener('change', function () {
                if (!input.files || input.files.length === 0) {
                    label.textContent = '';
                    return;
                }
                if (input.files.length === 1) {
                    label.textContent = input.files[0].name;
                } else {
                    label.textContent = input.files.length + ' 件のファイルを選択中';
                }
            });
        });
    }

    /**
     * submit 後のローディング表示
     * - CF7 はデフォルトで wpcf7-submitting クラスを付けるが、
     *   ボタン文言を「送信中...」に切り替えてユーザーに明示する
     * - 完了 / 失敗時は元に戻す
     */
    function bindSubmitLoading() {
        var form = document.querySelector('.wpcf7-form');
        if (!form) {
            return;
        }

        var submitBtn = form.querySelector('input[type="submit"], button[type="submit"]');
        if (!submitBtn) {
            return;
        }

        var originalLabel = submitBtn.value || submitBtn.textContent;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            if ('value' in submitBtn && submitBtn.tagName === 'INPUT') {
                submitBtn.value = '送信中...';
            } else {
                submitBtn.textContent = '送信中...';
            }
        });

        // CF7 のイベントで状態を戻す（mailfailed / invalid / spam いずれも復帰）
        var reset = function () {
            submitBtn.disabled = false;
            if ('value' in submitBtn && submitBtn.tagName === 'INPUT') {
                submitBtn.value = originalLabel;
            } else {
                submitBtn.textContent = originalLabel;
            }
        };

        document.addEventListener('wpcf7invalid', reset);
        document.addEventListener('wpcf7spam', reset);
        document.addEventListener('wpcf7mailfailed', reset);
        // mailsent 時は cf7-tweaks.php 側で /contact/thanks/ へ遷移するため、
        // 復帰させずローディング状態のまま画面遷移させる。
    }

    function init() {
        presetInquiryType();
        enhanceFileInput();
        bindSubmitLoading();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
