// Footer.jsx — A案: editorial / large wordmark on cream
function Footer() {
  const groups = [
    { h:'SERVICE', items:[
        {l:'OEM・ODM 事業', href:'services.html#oem'},
        {l:'MD 事業', href:'services.html#md'},
        {l:'健康サポート機器・グッズ', href:'services.html#health'},
        {l:'倉庫・配送代行', href:'services.html#logistics'},
        {l:'オンラインガチャ', href:'services.html#gacha'},
        {l:'グラフィックデザイン', href:'services.html#graphic'},
    ]},
    { h:'COMPANY', items:[
        {l:'会社概要', href:'company.html'},
        {l:'制作実績', href:'works.html'},
        {l:'お知らせ', href:'news.html'},
    ]},
    { h:'CONTACT', items:[
        {l:'お問い合わせフォーム', href:'contact.html'},
        {l:'見積もり依頼', href:'contact.html?type=quote'},
    ]},
  ];
  return (
    <footer className="lm-footer">
      {/* compact wordmark + tagline */}
      <div className="lm-footer__top">
        <div aria-hidden="true" className="lm-footer__brand">
          LEMONDS<span className="lm-footer__brand-dot">.</span>
        </div>
        <div className="lm-footer__tagline">
          ENTERTAINMENT Co.,Ltd · TURNING IDEAS INTO MEANINGFUL VALUE
        </div>
      </div>

      {/* sitemap */}
      <div className="lm-footer__grid">
        <div>
          <div className="lm-footer__title">— ADDRESS</div>
          <p className="lm-footer__text">
            〒000-0000<br/>東京都新宿区◯◯町 0-0-0<br/>ルモンズビル 3F
          </p>
          <div className="lm-footer__title lm-footer__title--spaced">— CONTACT</div>
          <p className="lm-footer__text">
            TEL  03-0000-0000<br/>MAIL  contact@lemonds.example
          </p>
        </div>
        {groups.map(col => (
          <div key={col.h}>
            <div className="lm-footer__title">— {col.h}</div>
            <ul className="lm-footer__list">
              {col.items.map(i => (
                <li key={i.l} className="lm-footer__item">
                  <a href={i.href} className="lm-footer__link">{i.l}</a>
                </li>
              ))}
            </ul>
          </div>
        ))}
      </div>

      {/* bottom bar */}
      <div className="lm-footer__bottom">
        <span>© 2026 LEMONDS ENTERTAINMENT Co.,Ltd</span>
        <span className="lm-footer__legal">
          <a href="policy.html">PRIVACY POLICY</a>
          <a href="policy.html">TERMS</a>
          <a href="policy.html">SITEMAP</a>
        </span>
      </div>
    </footer>
  );
}
window.Footer = Footer;
