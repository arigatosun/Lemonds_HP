// Header.jsx - LEMONDS Marketing Site
function Header() {
  const [open, setOpen] = React.useState(false);
  const links = [
    { l: '事業内容', href: 'services.html' },
    { l: '制作実績', href: 'works.html' },
    { l: 'お知らせ', href: 'news.html' },
    { l: '会社概要', href: 'company.html' },
  ];

  return (
    <header className={`lm-header${open ? ' is-open' : ''}`}>
      <a href="index.html" className="lm-header__logo">
        <img src="assets/lemonds-logo-wordmark.svg" alt="LEMONDS"/>
      </a>

      <nav className="lm-header__nav" aria-label="Primary navigation">
        {links.map(({l, href}) => (
          <a key={l} href={href}>{l}</a>
        ))}
      </nav>

      <div className="lm-header__actions">
        <a href="contact.html?type=quote" className="lm-btn lm-btn-secondary lm-header__button">見積もり依頼</a>
        <a href="contact.html" className="lm-btn lm-btn-primary lm-header__button">お問い合わせ</a>
      </div>

      <button
        type="button"
        className="lm-header__menu"
        aria-label="Menu"
        aria-expanded={open}
        onClick={() => setOpen(!open)}
      >
        <span/>
        <span/>
      </button>

      <div className="lm-header__drawer">
        <nav className="lm-header__drawer-nav" aria-label="Mobile navigation">
          {links.map(({l, href}) => (
            <a key={l} href={href} onClick={() => setOpen(false)}>{l}</a>
          ))}
        </nav>
        <div className="lm-header__drawer-actions">
          <a href="contact.html?type=quote" className="lm-btn lm-btn-secondary lm-header__button" onClick={() => setOpen(false)}>見積もり依頼</a>
          <a href="contact.html" className="lm-btn lm-btn-primary lm-header__button" onClick={() => setOpen(false)}>お問い合わせ</a>
        </div>
      </div>
    </header>
  );
}
window.Header = Header;
