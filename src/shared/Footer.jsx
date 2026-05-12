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
    <footer style={{
      background:'#F4F4F4', color:'#111', padding:'80px var(--lm-content-x) 40px',
      borderTop:'1px solid rgba(17,17,17,.18)',
    }}>
      {/* compact wordmark + tagline */}
      <div style={{display:'grid', gridTemplateColumns:'auto 1fr', alignItems:'end', gap: 40, paddingBottom: 28, borderBottom:'1px solid rgba(17,17,17,.18)'}}>
        <div aria-hidden="true" style={{
          font:'700 56px/.95 var(--font-en)', letterSpacing:'-.01em', color:'#111',
        }}>
          LEMONDS<span style={{color:'rgba(17,17,17,.18)'}}>.</span>
        </div>
        <div style={{font:'600 11px/1 var(--font-en)', letterSpacing:'.24em', color:'rgba(17,17,17,.55)', textAlign:'right'}}>
          ENTERTAINMENT Co.,Ltd · TURNING IDEAS INTO MEANINGFUL VALUE
        </div>
      </div>

      {/* sitemap */}
      <div style={{display:'grid', gridTemplateColumns:'1.2fr repeat(3, 1fr)', gap: 56, paddingTop: 56}}>
        <div>
          <div style={{font:'700 13px/1 var(--font-en)', letterSpacing:'.22em', color:'rgba(17,17,17,.55)'}}>— ADDRESS</div>
          <p style={{font:'500 14px/1.84 var(--font-jp)', letterSpacing:'.04em', margin:'18px 0 0'}}>
            〒000-0000<br/>東京都新宿区◯◯町 0-0-0<br/>ルモンズビル 3F
          </p>
          <div style={{font:'700 13px/1 var(--font-en)', letterSpacing:'.22em', color:'rgba(17,17,17,.55)', marginTop: 32}}>— CONTACT</div>
          <p style={{font:'500 14px/1.84 var(--font-jp)', letterSpacing:'.04em', margin:'18px 0 0'}}>
            TEL  03-0000-0000<br/>MAIL  contact@lemonds.example
          </p>
        </div>
        {groups.map(col => (
          <div key={col.h}>
            <div style={{font:'700 13px/1 var(--font-en)', letterSpacing:'.22em', color:'rgba(17,17,17,.55)'}}>— {col.h}</div>
            <ul style={{margin:'18px 0 0', padding:0, listStyle:'none', display:'flex', flexDirection:'column', gap: 14}}>
              {col.items.map(i => (
                <li key={i.l} style={{font:'500 14px/1.4 var(--font-jp)', letterSpacing:'.04em'}}>
                  <a href={i.href} style={{color:'#111', textDecoration:'none', borderBottom:'1px solid transparent', transition:'border-color 160ms'}}>{i.l}</a>
                </li>
              ))}
            </ul>
          </div>
        ))}
      </div>

      {/* bottom bar */}
      <div style={{
        borderTop:'1px solid rgba(17,17,17,.18)', marginTop: 56, paddingTop: 24,
        display:'flex', justifyContent:'space-between', alignItems:'center',
        font:'500 11px/1 var(--font-en)', letterSpacing:'.18em', color:'rgba(17,17,17,.55)',
      }}>
        <span>© 2026 LEMONDS ENTERTAINMENT Co.,Ltd</span>
        <span style={{display:'flex', gap: 24}}>
          <a href="policy.html" style={{color:'inherit', textDecoration:'none'}}>PRIVACY POLICY</a>
          <a href="policy.html" style={{color:'inherit', textDecoration:'none'}}>TERMS</a>
          <a href="policy.html" style={{color:'inherit', textDecoration:'none'}}>SITEMAP</a>
        </span>
      </div>
    </footer>
  );
}
window.Footer = Footer;
