// Company.jsx — COMPANY / 会社情報 (photo-led layout)
function Company() {
  return (
    <section className="lm-section" data-screen-label="07 Company">
      <div className="lm-section-head">
        <div>
          <h2>COMPANY</h2>
          <div className="ja">私たちについて</div>
        </div>
        <p className="lead">
          グッズ制作・MD・物流・オンライン施策を通じて、企業や事業者のものづくりを支えています。<br/>
          現場ごとの条件に合わせ、企画から納品までを一気通貫で。
        </p>
      </div>

      {/* photo-led mosaic */}
      <div className="lm-co-mosaic">
        <div className="m-large" style={{backgroundImage:'url(assets/top-company-office.jpg)'}}>
          <div className="cap"><span className="k">— OFFICE / TOKYO</span><span className="t">想いを、価値あるカタチに。</span></div>
        </div>
        <div className="m-tall" style={{backgroundImage:'url(assets/top-company-materials.jpg)'}}/>
        <div className="m-mid" style={{backgroundImage:'url(assets/top-company-logistics.jpg)'}}/>
        <div className="m-mid" style={{backgroundImage:'url(assets/top-company-event.jpg)'}}/>
        <div className="m-wide" style={{backgroundImage:'url(assets/top-company-flow.jpg)'}}/>
      </div>

      {/* small overview strip */}
      <div className="lm-co-strip">
        <div className="meta">
          <div className="k">— ABOUT US</div>
          <h3>株式会社ルモンズエンターテインメント</h3>
          <p>
            企画・仕様整理から、製造・検品・配送・販売現場での納品まで。<br/>
            ジャンルを問わず、現場の条件に合わせた提案と進行を心がけています。
          </p>
          <button className="lm-pill-outline lm-pill-outline--section-action">
            <span>会社概要を見る</span><span className="circle">→</span>
          </button>
        </div>
        <dl className="facts">
          <div><dt>所在地</dt><dd>東京都新宿区新宿6丁目24番20号</dd></div>
          <div><dt>設立</dt><dd>2017年11月7日</dd></div>
          <div><dt>事業</dt><dd>商品企画 / 仕様プランニング / 制作進行 / 販売管理</dd></div>
        </dl>
      </div>
    </section>
  );
}
window.Company = Company;
