/**
 * Header ハンバーガーメニューのトグル制御
 *
 * 旧 src/shared/SubHeader.jsx と src/home/Header.jsx の useState ロジックを移植。
 * `[data-lm-menu-toggle]` ボタンを押すと親 .lm-header に is-open クラスを付け外し、
 * モバイル用ドロワー（.lm-header__drawer）の表示を CSS で切り替える。
 *
 * 追加挙動:
 * - ドロワー内のリンクを押したらメニューを閉じる
 * - 画面幅が 1025px 以上に戻ったらメニュー状態をリセット
 * - Esc キーで閉じる
 */
(function () {
  function init() {
    var toggle = document.querySelector('[data-lm-menu-toggle]');
    if (!toggle) return;

    var header = toggle.closest('.lm-header');
    if (!header) return;

    var drawer = header.querySelector('.lm-header__drawer');

    function open() {
      header.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
    }
    function close() {
      header.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    }
    function isOpen() {
      return header.classList.contains('is-open');
    }

    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      if (isOpen()) close(); else open();
    });

    if (drawer) {
      drawer.addEventListener('click', function (e) {
        var link = e.target.closest('a');
        if (link) close();
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && isOpen()) close();
    });

    var mql = window.matchMedia('(min-width: 1025px)');
    function syncOnResize(ev) {
      if (ev.matches && isOpen()) close();
    }
    if (mql.addEventListener) {
      mql.addEventListener('change', syncOnResize);
    } else if (mql.addListener) {
      mql.addListener(syncOnResize);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
