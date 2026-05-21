/**
 * 1920px 基準のデザインをビューポート幅に等比でフィットさせる。
 *
 * 旧 HTML 版 index.html の inline script を移植したもの。
 * <body><div id="lm-stage"><div id="lm-root">...</div></div></body> 構造に対し、
 * #lm-root を transform: scale() でビューポート幅に合わせて縮小し、
 * #lm-stage の高さに scale 後の自然高さを反映することで縦スクロール量を保つ。
 *
 * - 1024px 以下では scale=1（モバイル/タブレット側 CSS が個別レイアウトを担当）
 * - 1024px 超〜1920px 未満では scale = w / 1920
 * - 1920px 以上では scale = 1
 */
(function () {
  var STAGE_ID = 'stage';
  var ROOT_ID = 'root';

  function fitPage() {
    var w = document.documentElement.clientWidth;
    var root = document.getElementById(ROOT_ID);
    var stage = document.getElementById(STAGE_ID);
    if (!root || !stage) return;

    // ≤1199px は scale=1 とし、レスポンシブ CSS に任せる
    // (≤1024 だけだと 1025-1199 で文字が極端に縮むため、bento 切替と揃える)
    if (w <= 1199) {
      root.style.setProperty('--page-scale', 1);
      stage.style.removeProperty('--page-height');
      return;
    }

    var scale = Math.min(1, w / 1920);
    root.style.setProperty('--page-scale', scale);
    var naturalH = root.offsetHeight; // transform は offsetHeight に影響しない
    stage.style.setProperty('--page-height', (naturalH * scale) + 'px');
  }

  window.addEventListener('resize', fitPage);
  window.addEventListener('load', fitPage);
  requestAnimationFrame(fitPage);
  setTimeout(fitPage, 300);
  setTimeout(fitPage, 1200);
  setTimeout(fitPage, 3000);

  // 画像のロード完了で高さが変わるケースに追従
  document.addEventListener('load', function (e) {
    if (e.target && e.target.tagName === 'IMG') fitPage();
  }, true);

  if (window.ResizeObserver) {
    var rootEl = document.getElementById(ROOT_ID);
    if (rootEl) {
      var ro = new ResizeObserver(fitPage);
      ro.observe(rootEl);
    }
  }
})();
