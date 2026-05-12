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
      <a href="#" style={{display:'inline-flex'}}>
        <img src={window.LM_ASSET('lemonds-logo-wordmark.svg')} alt="LEMONDS" style={{height: 36}}/>
      </a>
      <nav style={{display:'flex', gap: 60}}>
        {['事業内容','制作実績','弊社の強み','お知らせ','会社概要'].map(l => (
          <a key={l} href={`#${l}`} style={{
            font: '500 16px/1 var(--font-jp)', color: '#111111',
            letterSpacing: '0.04em', textDecoration: 'none',
          }}>{l}</a>
        ))}
      </nav>
      <div style={{display:'flex', gap: 16}}>
        <button className="lm-btn lm-btn-secondary" style={{width:180,height:48}}>見積もり依頼</button>
        <button className="lm-btn lm-btn-primary"   style={{width:180,height:48}}>お問い合わせ</button>
      </div>
    </header>
  );
}
window.Header = Header;
