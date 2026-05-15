// Hero.jsx - top page visual lead
function Hero() {
  const [idx, setIdx] = React.useState(0);
  const items = [
    { img:'photo-concert-lights.jpg', title:'大型ライブ公演向けグッズ制作', date:'2026.04.10' },
    { img:'photo-cosmetics-pink.jpg', title:'新プロダクトの取り扱い開始', date:'2026.04.10' },
    { img:'photo-cosmetics-mono.jpg', title:'既存パッケージのリニューアル', date:'2026.04.10' },
  ];
  const cur = items[idx];

  return (
    <section className="lm-hero">
      <div className="lm-hero__stage">
        <div className="lm-hero__media">
          <div className="lm-hero__media-image" aria-hidden="true"/>
          <div className="lm-hero__wash" aria-hidden="true"/>
        </div>

        <div className="lm-hero__headline">
          <h1>
            <span>想いを、価値ある</span><br className="lm-hero__sp-break"/>
            <span>カタチに。</span>
          </h1>
          <div className="lm-hero__en">Turning your ideas into meaningful value.</div>
          <p>
            企業のブランド価値を高めるオリジナルグッズを、<br/>
            企画から製造まで一貫してサポートします。
          </p>
        </div>

        <div className="lm-hero__pickup">
          <div className="lm-hero__pickup-inner">
            <div className="lm-hero__pickup-head">
              <div>
                <h2>PICK UP</h2>
                <div className="lm-hero__pickup-ja">ピックアップ</div>
              </div>
              <div className="lm-hero__pickup-arrows">
                <button onClick={()=>setIdx((idx-1+items.length)%items.length)} className="lm-arrow-btn">←</button>
                <button onClick={()=>setIdx((idx+1)%items.length)} className="lm-arrow-btn">→</button>
              </div>
            </div>
            <div className="lm-hero__pickup-image" style={{backgroundImage:`url(assets/${cur.img})`}}/>
            <h3>{cur.title}</h3>
            <div className="lm-hero__pickup-date">{cur.date}</div>
          </div>
        </div>
      </div>
    </section>
  );
}
window.Hero = Hero;
