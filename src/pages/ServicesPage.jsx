// ServicesPage.jsx — 事業内容ページ本体
function ServicesPage() {
  const services = [
    {
      id:'oem',
      no:'01',
      jp:'OEM・ODM事業',
      en:'OEM / ODM',
      tagline:'企画から納品まで、一貫して支援するOEM事業。',
      img:'photo-business-meeting.jpg',
      body:'企業・ブランド向けのオリジナルグッズやノベルティを中心に、商品企画から製造、納品までを一貫して支援するOEM事業を展開しています。お客様のブランド価値や販売目的を丁寧にヒアリングしたうえで、仕様設計・素材選定・製造方法の最適化を行い、小ロットから量産まで柔軟に対応可能な商品開発体制を構築します。企業プロモーション、イベント、EC販売など、多様な用途に対応した商品開発をサポートしています。',
      scope:[
        'コストバランスを考慮した製品設計と納期管理を含む生産支援',
        '販売促進や流通展開を考慮した商品企画・開発のサポート',
        'アクリル製品、布製品、金属製品など多素材対応',
        '各種特殊加工（印刷・装飾・加工技術）',
      ],
      status:null,
    },
    {
      id:'md',
      no:'02',
      jp:'MD事業',
      en:'Merchandising',
      tagline:'販売を見据えた商品企画・量産対応。',
      img:'photo-merch-flatlay.jpg',
      body:'販売・流通を前提としたMD（マーチャンダイジング）事業として、企画性と実用性を両立した商品開発を行っています。販売シーンやターゲットを意識した企画設計を行い、製造工程・数量設計・コストバランスを含めた総合的な商品プランをご提案。イベント販売やEC展開など、実際の販売導線を見据えたMD企画をサポートします。',
      scope:[
        '販売チャネルに合わせた商品企画・仕様設計',
        '数量設計・原価設計を含めた量産プランニング',
        'イベント販売・EC展開を見据えた商品ラインナップ提案',
        '在庫・物流・販売導線までを通したMD設計',
      ],
      status:null,
    },
    {
      id:'health',
      no:'03',
      jp:'健康サポート機器・グッズ事業',
      en:'Health Devices & Goods',
      tagline:'EMS機器・機能性アパレルの法人供給。',
      img:'photo-cosmetics-mono.jpg',
      body:'EMS健康機器や各種アパレル商品（機能性Tシャツ・サポートウエア）を中心に、法人様向けに仕入れ・供給いたします。小ロットからの対応はもちろん、OEM・受注生産にも柔軟に対応しており、ご要望に合わせた最適な商品提案が可能です。取り扱う商品は、家庭用EMS機器や健康グッズ、機能性Tシャツなど幅広く、品質管理や安全性にも十分配慮しております。',
      scope:[
        'EMS機器',
        '健康グッズ',
        '機能性Tシャツ／サポートウエア',
        'オリジナル制作（OEM）',
      ],
      status:null,
    },
    {
      id:'logistics',
      no:'04',
      jp:'倉庫・配送代行事業',
      en:'Warehouse & Fulfillment',
      tagline:'販売形態に合わせた物流サポート。',
      img:'photo-cosmetics-pink.jpg',
      body:'自社通販、個人EC、同人関連販売など、小規模事業者様の多様な販売形態に対応した倉庫保管および配送代行サービスを提供しています。商品の保管・梱包・発送業務を一括して代行することで、出荷業務の効率化と物流コストの最適化を支援。また、イベント出展時の搬入や急な納品対応など、即時性が求められる案件にも対応可能なチャーター便配送体制を備えており、状況に応じた柔軟な物流対応が可能です。',
      scope:[
        '商品の保管・梱包・発送代行',
        '物流業務全般の運用支援',
        'チャーター便による緊急配送対応',
        'イベント出展時の搬入・納品支援',
      ],
      status:null,
    },
    {
      id:'gacha',
      no:'05',
      jp:'オンラインガチャ（くじ）事業',
      en:'Online Gacha',
      tagline:'販売体験を広げるオンライン施策。',
      img:'photo-friends-selfie.jpg',
      body:'オンライン上で楽しめるガチャ（くじ）形式の販売施策を通じて、商品販売の新たな形を提案しています。グッズ製造から在庫管理、配送までを一貫して設計することで、運用負荷を抑えつつ、企画性の高い販売施策を実現。ファンとの接点創出や継続的な購買促進につながる仕組みづくりをサポートします。',
      scope:[
        'オンラインガチャ商品の企画設計',
        'グッズ製造・在庫管理',
        '販売・配送オペレーション設計',
        'キャンペーン・イベント連動施策',
      ],
      status:null,
    },
    {
      id:'design',
      no:'06',
      jp:'パッケージ・グラフィックデザイン事業',
      en:'Package & Graphic Design',
      tagline:'スピード感と独自性を重視したデザイン。',
      img:'photo-cosmetics-pink.jpg',
      body:'パッケージ、グッズデザインを中心としたブランディングおよび各種グラフィックデザインを手がけております。商品やブランドの価値を的確に捉え、目的に沿った表現をデザインします。スピード感を強みとし、ご相談から制作まで柔軟かつ迅速に対応。発想力と独自性を活かした新しいデザイン提案を行います。',
      scope:[
        'ロゴ・ブランドデザイン',
        'パンフレット・チラシ・各種販促ツール制作',
        'オリジナルグッズ・ノベルティの企画・デザイン',
        'デザインに関する設計・企画支援',
      ],
      status:null,
    },
    {
      id:'trade',
      no:'07',
      jp:'輸出入通関事業',
      en:'Import / Export',
      tagline:'海外生産・輸入を見据えた通関対応。',
      img:'photo-business-meeting.jpg',
      body:'海外生産や輸入を見据えた通関対応について、現在サービス体制を準備しています。今後、輸出入までを一貫して管理できる体制を構築予定です。',
      scope:['準備中','準備中','準備中','準備中'],
      status:'準備中',
    },
    {
      id:'web',
      no:'08',
      jp:'HP・ECサイト制作事業',
      en:'Web / EC Production',
      tagline:'販売基盤の構築まで一貫支援（準備中）。',
      img:'photo-cosmetics-mono.jpg',
      body:'オリジナルグッズやMD販売に適したHP・ECサイト制作サービスを準備しています。商品設計から販売導線までを一貫して考えた販売基盤の構築を支援予定です。',
      scope:['準備中','準備中','準備中','準備中'],
      status:'準備中',
    },
  ];

  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[
        { label:'トップ', href:'index.html' },
        { label:'事業内容' },
      ]}/>
      <PageHero
        en="SERVICE"
        ja="事業内容"
        lead="アパレル、ノベルティ、健康機器、EC施策まで。幅広く対応する総合グッズカンパニーとして、企画から納品まで一気通貫でお客様のニーズにお応えします。"
      />

      {/* 事業カテゴリ一覧（アンカー目次） */}
      <section className="lm-section" style={{paddingTop: 0}} data-screen-label="Services Index">
        <ul className="lm-svc-index">
          {services.map(s => (
            <li key={s.id}>
              <a href={`#${s.id}`}>
                <span className="no">{s.no}</span>
                <span className="jp">{s.jp}{s.status && <em className="status">（{s.status}）</em>}</span>
                <span className="arrow">↓</span>
              </a>
            </li>
          ))}
        </ul>
      </section>

      {/* 各事業の詳細セクション */}
      <section className="lm-section" style={{paddingTop: 0}}>
        <div className="lm-svc-list">
          {services.map((s, i) => (
            <article
              key={s.id}
              id={s.id}
              className={`lm-svc-detail ${s.status ? 'is-pending' : ''} ${i % 2 === 1 ? 'is-flip' : ''}`}
            >
              <div className="media">
                <div className="photo" style={{backgroundImage:`url(assets/${s.img})`}}/>
                {s.status && <span className="badge">{s.status}</span>}
              </div>
              <div className="body">
                <div className="no">{s.no} / {String(services.length).padStart(2,'0')}</div>
                <h2 className="jp">{s.jp}</h2>
                <div className="en">{s.en}</div>
                <div className="tagline">{s.tagline}</div>
                <p className="body-copy">{s.body}</p>
                <div className="scope">
                  <div className="k">対応範囲</div>
                  <ul>
                    {s.scope.map((x,j) => <li key={j}>{x}</li>)}
                  </ul>
                </div>
                {!s.status && (
                  <a href="contact.html" className="lm-pill-outline lm-svc-consult-btn" style={{marginTop: 8, textDecoration:'none'}}>
                    <span>この事業について相談する</span><span className="circle">→</span>
                  </a>
                )}
              </div>
            </article>
          ))}
        </div>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.ServicesPage = ServicesPage;
