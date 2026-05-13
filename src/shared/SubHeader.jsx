// SubHeader.jsx — 下層ページ用ヘッダー（トップと同等／ナビ最終形）
function SubHeader() {
  const links = [
    { l: '事業内容', href: 'services.html' },
    { l: '制作実績', href: 'works.html' },
    { l: 'お知らせ', href: 'news.html' },
    { l: '会社概要', href: 'company.html' },
  ];
  return (
    <header className="lm-header">
      <a href="index.html" className="lm-header__logo">
        <img src="assets/lemonds-logo-wordmark.svg" alt="LEMONDS"/>
      </a>
      <nav className="lm-header__nav">
        {links.map(({l, href}) => (
          <a key={l} href={href}>{l}</a>
        ))}
      </nav>
      <div className="lm-header__actions">
        <a href="contact.html?type=quote" className="lm-btn lm-btn-secondary lm-header__button">見積もり依頼</a>
        <a href="contact.html" className="lm-btn lm-btn-primary lm-header__button">お問い合わせ</a>
      </div>
    </header>
  );
}
window.SubHeader = SubHeader;
