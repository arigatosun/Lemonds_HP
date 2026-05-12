// Hero.jsx — B-variant faithful to Figma 【B】PC_トップ (node 1:3)
// Frame is 1920 wide. Masked photo region: x=80, y=124, 1760×760.
// The mask path carves a notch at top-right (562w × 200h) where the PICK UP card sits.
// Inside the mask we lay out photo TILES horizontally on a 2-row grid (no rotation, no collage PNG).
function Hero() {
  const [idx, setIdx] = React.useState(0);
  const items = [
    { img:'photo-concert-lights.jpg',  title:'000人導入のライブで一体感を生み出すグッズ作成', date:'2026.04.10' },
    { img:'photo-cosmetics-pink.jpg',  title:'新プロダクトの取り扱い開始！',                 date:'2026.04.10' },
    { img:'photo-cosmetics-mono.jpg',  title:'既存パッケージのリニューアルで売り上げ200%増', date:'2026.04.10' },
  ];
  const cur = items[idx];

  // Replicates Figma "Union": rounded rect with a top-right rectangular notch
  // (where the PICK UP card overlaps). Coordinates are within the 1760×760 box.
  const clipPath = `path('M 48 0 L 1712 0 C 1738.51 0 1760 21.49 1760 48 L 1760 152 \
    C 1760 178.51 1738.51 200 1712 200 L 1365 200 L 1365 336 L 1212 336 \
    C 1207.13 336 1202.43 335.27 1198 333.92 L 1198 712 \
    C 1198 738.51 1176.51 760 1150 760 L 48 760 \
    C 21.49 760 0 738.51 0 712 L 0 48 C 0 21.49 21.49 0 48 0 Z')`;

  // Two-row photo grid that fills the 1760×760 mask. Top row = 3 wide tiles, bottom row = 5 narrower tiles.
  const TOP = [
    { src:'photo-business-meeting.jpg', flex: 1.0, pos: '50% 40%' },
    { src:'photo-concert-lights.jpg',   flex: 1.4, pos: '50% 50%' },
    { src:'photo-friends-selfie.jpg',   flex: 1.0, pos: '50% 35%' },
  ];
  const BOTTOM = [
    { src:'photo-cosmetics-mono.jpg',  flex: 1.0, pos: '50% 50%' },
    { src:'photo-cosmetics-pink.jpg',  flex: 1.0, pos: '50% 50%' },
    { src:'photo-merch-flatlay.jpg',   flex: 1.0, pos: '50% 50%' },
    { src:'photo-business-meeting.jpg',flex: 1.0, pos: '60% 50%' },
    { src:'photo-friends-selfie.jpg',  flex: 1.0, pos: '50% 50%' },
  ];

  return (
    <section style={{position:'relative', width: 1920, margin:'0 auto', height: 850}}>
      <div style={{
        position:'absolute', left:'var(--lm-content-x)', top: 24,
        width: 1760, height: 760,
        transform:'scale(var(--lm-hero-scale))',
        transformOrigin:'top left',
      }}>
      {/* Masked photo grid */}
      <div style={{
        position:'absolute', left: 0, top: 0, width: 1760, height: 760,
        clipPath, WebkitClipPath: clipPath, background:'#DCDCDC',
      }}>
        <div style={{position:'absolute', inset: 0, display:'flex', flexDirection:'column', gap: 4}}>
          <div style={{display:'flex', height: 198, gap: 4}}>
            {TOP.map((t,i) => (
              <div key={i} style={{
                flex: t.flex, background:`url(assets/${t.src}) ${t.pos}/cover no-repeat`,
              }}/>
            ))}
          </div>
          <div style={{display:'flex', flex:'1 1 0', gap: 4}}>
            {BOTTOM.map((t,i) => (
              <div key={i} style={{
                flex: t.flex, background:`url(assets/${t.src}) ${t.pos}/cover no-repeat`,
              }}/>
            ))}
          </div>
        </div>
        {/* Cream protection wash at the bottom so the headline reads cleanly */}
        <div style={{
          position:'absolute', inset:0,
          background:'linear-gradient(180deg, rgba(244,244,244,0) 41%, rgba(244,244,244,0.79) 62%, rgba(244,244,244,1) 82%)',
          pointerEvents:'none',
        }}/>
      </div>

      {/* Headline group — bottom-left of masked photo */}
      <div style={{position:'absolute', left: 60, top: 476, width: 900, zIndex: 2}}>
        <h1 style={{
          font:'700 56px/1.4 var(--font-jp)',
          letterSpacing:'0.06em', color:'#111111', margin:0,
        }}>想いを、価値あるカタチに。</h1>
        <div style={{
          font:'500 18px/1.6 var(--font-jp)', letterSpacing:'0.06em',
          color:'rgba(17,17,17,.7)', marginTop: 18,
        }}>Turning your ideas into meaningful value.</div>
        <p style={{
          font:'400 16px/1.84 var(--font-jp)', letterSpacing:'0.04em',
          color:'#111111', marginTop: 28, maxWidth: 540,
        }}>企業のブランド価値を高めるオリジナルグッズを、<br/>企画から製造まで一貫してサポートします。</p>
      </div>

      {/* PICK UP card */}
      <div style={{
        position:'absolute', left: 1198, top: 198, width: 562, height: 562,
        background:'#F4F4F4', borderRadius:'72px 0 0 0', zIndex: 3,
      }}>
        <div style={{
          position:'absolute', left: 44, top: 44, width: 518, height: 518,
          background:'#fff', borderRadius: 48,
          padding: 40, boxSizing:'border-box', display:'flex', flexDirection:'column',
        }}>
          <div style={{display:'flex', alignItems:'flex-start', justifyContent:'space-between'}}>
            <div>
              <h2 style={{font:'700 32px/1 var(--font-en)', letterSpacing:'0.02em', textDecoration:'underline', textUnderlineOffset:6, margin:0, color:'#111'}}>PICK UP</h2>
              <div style={{font:'500 12px/1 var(--font-jp)', letterSpacing:'0.16em', color:'rgba(0,0,0,.55)', marginTop: 12}}>ピックアップ</div>
            </div>
            <div style={{display:'flex', gap: 8}}>
              <button onClick={()=>setIdx((idx-1+items.length)%items.length)} className="lm-arrow-btn" style={{width:36,height:36,fontSize:16}}>←</button>
              <button onClick={()=>setIdx((idx+1)%items.length)} className="lm-arrow-btn" style={{width:36,height:36,fontSize:16}}>→</button>
            </div>
          </div>
          <div style={{
            marginTop: 24, height: 200, borderRadius: 24,
            background:`url(assets/${cur.img}) center/cover`,
          }}/>
          <h3 style={{font:'500 22px/1.5 var(--font-jp)', color:'#111111', letterSpacing:'0.06em', marginTop: 24, marginBottom: 0}}>{cur.title}</h3>
          <div style={{font:'500 13px/1 var(--font-jp)', letterSpacing:'0.12em', color:'rgba(0,0,0,.5)', marginTop: 'auto', paddingTop: 16}}>{cur.date}</div>
        </div>
      </div>
      </div>
    </section>
  );
}
window.Hero = Hero;
