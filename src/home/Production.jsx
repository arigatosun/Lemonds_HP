// Production.jsx — PRODUCTION SYSTEM / ものづくりを支える体制
function Production() {
  const steps = [
    { n:'01', title:'企画・仕様整理', img:'top-production-planning.jpg',
      copy:'目的、数量、予算、納期、仕様を整理。グッズの方向性を一緒に固めます。' },
    { n:'02', title:'製造・生産管理', img:'top-production-manufacturing.jpg',
      copy:'国内外の製造先と連携し、サンプル確認から量産までの進行を一貫して管理します。' },
    { n:'03', title:'検品・品質確認', img:'top-production-quality.jpg',
      copy:'仕上がり、数量、不良、梱包状態を確認。出荷前のチェック工程を社内で完結します。' },
    { n:'04', title:'梱包・配送・納品', img:'top-production-delivery.jpg',
      copy:'倉庫保管、発送、イベント会場納品まで対応。販売現場まで途切れないオペレーションです。' },
  ];
  return (
    <section className="lm-section" data-screen-label="05 Production">
      <div className="lm-section-head">
        <div>
          <h2>PRODUCTION SYSTEM</h2>
          <div className="ja">ものづくりを支える体制</div>
        </div>
        <p className="lead">
          企画・仕様整理から製造、検品、梱包、納品まで。<br/>
          案件ごとの条件に合わせて、品質と納期を両立できる体制を整えています。
        </p>
      </div>

      {/* 連結線つきステップトラック */}
      <div className="lm-track">
        <div className="lm-track-line"/>
        {steps.map((s, i) => (
          <div key={s.n} className="lm-track-item">
            <div className="lm-track-photo" style={{backgroundImage:`url(assets/${s.img})`}}/>
            <div className="lm-track-marker">
              <span className="lm-track-dot"/>
              <span className="lm-track-n">STEP {s.n}</span>
              <span className="lm-track-line-after" aria-hidden="true"/>
            </div>
            <h3 className="lm-track-title">{s.title}</h3>
            <p className="lm-track-copy">{s.copy}</p>
          </div>
        ))}
      </div>
    </section>
  );
}
window.Production = Production;
