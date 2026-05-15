// ServiceGrid.jsx — B-variant faithful to Figma 【B】PC_トップ
// Layout: large white panel left (x=100, y=1024, 456×1281, r=28),
// holds SERVICE eyebrow + JP subhead + intro copy + VIEW MORE pill.
// Right side: 2-column grid of service cards starting at x=616, y=1024.
// Each tile: image 348×197 r=28, title 20/700, copy 14/regular.
function ServiceGrid() {
  const services = [
    { title:'OEM・ODM事業', img:'photo-business-meeting.jpg',
      copy:'オリジナルグッズ製造サービス。企業・ブランド向けのオリジナルグッズやノベルティを中心に、商品企画から製造、納品までを一貫して支援するOEM事業を展開しています。' },
    { title:'MD事業', img:'photo-merch-flatlay.jpg',
      copy:'販売を見据えた商品企画・量産対応。販売・流通を前提としたMD（マーチャンダイジング）事業として、企画性と実用性を両立した商品開発を行っています。' },
    { title:'健康サポート機器・グッズ事業', img:'service-health-support-ems-a.jpg',
      copy:'EMS健康機器や各種アパレル商品（機能性Tシャツ・サポートウエア）を中心に、法人様向けに仕入れ・供給いたします。小ロットからの対応はもちろん、OEM・受注生産にも柔軟に対応しております。' },
    { title:'倉庫・配送代行事業', img:'service-logistics-fulfillment-a.jpg',
      copy:'販売形態に合わせた物流サポート。自社通販、個人EC、同人関連販売など、小規模事業者様の多様な販売形態に対応した倉庫保管および配送代行サービスを提供しています。' },
    { title:'オンラインガチャ（くじ）事業', img:'service-online-gacha-a.jpg',
      copy:'販売体験を広げるオンライン施策。オンライン上で楽しめるガチャ（くじ）形式の販売施策を通じて、商品販売の新たな形を提案しています。' },
    { title:'パッケージ・グラフィックデザイン事業', img:'service-package-design-a.jpg',
      copy:'パッケージ、グッズデザインを中心としたブランディングおよび各種グラフィックデザインを手がけております。' },
    { title:'輸出入通関事業（準備中）', img:'service-import-export-a.jpg',
      copy:'海外生産や輸入を見据えた通関対応について、現在サービス体制を準備しています。今後、輸出入までを一貫して管理できる体制を構築予定です。' },
    { title:'HP・ECサイト制作事業（準備中）', img:'service-web-ec-a.jpg',
      copy:'オリジナルグッズやMD販売に適した。HP・ECサイト制作サービスを準備しています。商品設計から販売導線までを一貫して考えた販売基盤の構築を支援予定です。' },
  ];
  return (
    <section className="lm-home-service">
      <div className="lm-home-service__inner">
      {/* Left: SERVICE intro */}
      <div className="lm-home-service__intro">
        <h2 className="lm-home-service__title">SERVICE</h2>
        <div className="lm-home-service__ja">事業内容</div>
        <p className="lm-home-service__copy">
          アパレル、ノベルティ、健康機器、EC施策まで。幅広く対応する総合グッズカンパニーとして、お客様のニーズにお応えします。
        </p>
        <div className="lm-home-service__action">
          <button className="lm-pill-outline lm-pill-outline--section-action">
            <span>詳細を見る</span><span className="circle">→</span>
          </button>
        </div>
      </div>

      {/* Right: 3-column grid */}
      <div className="lm-home-service__grid">
        {services.map(s => (
          <div key={s.title} className="lm-svc-card-b">
            <div className="thumb" style={{backgroundImage:`url(assets/${s.img})`}}/>
            <div className="title">{s.title}</div>
            <div className="copy">{s.copy}</div>
          </div>
        ))}
      </div>
      </div>
    </section>
  );
}
window.ServiceGrid = ServiceGrid;
