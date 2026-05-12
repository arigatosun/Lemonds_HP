// Header.jsx — LEMONDS Marketing Site (B-variant accurate)
// Figma: nav links centered around x=794 (left) at top=72; CTAs at right edge x=1464/1676 top=38
function Header() {
  return (
    <header style={{
      position: 'sticky', top: 0, zIndex: 50,
      display: 'flex', alignItems: 'center', justifyContent: 'space-between',
      padding: '32px var(--lm-content-x) 24px', background: 'rgba(244,244,244,0.94)',
      backdropFilter: 'saturate(140%) blur(8px)',
      WebkitBackdropFilter: 'saturate(140%) blur(8px)',
    }}>
      <a href="index.html" style={{display:'inline-flex'}}>
        <img src="assets/lemonds-logo-wordmark.svg" alt="LEMONDS" style={{height: 36}}/>
      </a>
      <nav style={{display:'flex', gap: 60}}>
        {[
          {l:'事業内容', href:'services.html'},
          {l:'制作実績', href:'works.html'},
          {l:'お知らせ', href:'news.html'},
          {l:'会社概要', href:'company.html'},
        ].map(({l, href}) => (
          <a key={l} href={href} style={{
            font: '500 16px/1 var(--font-jp)', color: '#111111',
            letterSpacing: '0.04em', textDecoration: 'none',
          }}>{l}</a>
        ))}
      </nav>
      <div style={{display:'flex', gap: 16}}>
        <a href="contact.html?type=quote" className="lm-btn lm-btn-secondary" style={{width:180,height:48,textDecoration:'none',display:'inline-flex',alignItems:'center',justifyContent:'center'}}>見積もり依頼</a>
        <a href="contact.html" className="lm-btn lm-btn-primary" style={{width:180,height:48,textDecoration:'none',display:'inline-flex',alignItems:'center',justifyContent:'center'}}>お問い合わせ</a>
      </div>
    </header>
  );
}
window.Header = Header;
