// WorksDetailPage.jsx — 制作実績詳細
function WorksDetailPage() {
  // 一覧と同じデータをここに（実運用ではCMS）
  const ALL = [
    { slug:'live-merch-2026', img:'photo-concert-lights.jpg', category:'EVENT / LIVE', client:'某ライブ公演', date:'2026.04.10',
      title:'某ライブ公演向けグッズ制作',
      lead:'数千名規模の公演で配布する一体感をつくるグッズの企画〜量産。',
      gallery:['photo-concert-lights.jpg','photo-merch-flatlay.jpg','photo-friends-selfie.jpg'],
      details:[
        ['依頼背景', 'ファンクラブ会員と一般動員が混在する公演で、客席に一体感を作りたいという背景。視覚演出と連動したグッズ制作を依頼いただきました。'],
        ['制作した商品', 'ペンライト連動Tシャツ／公演限定タオル／アクリルキーホルダー／ステッカーセット'],
        ['仕様・素材', 'Tシャツ：6.0oz / 綿100% / シルクスクリーン4色刷り。タオル：今治ジャガード。アクキー：2mm両面印刷。'],
        ['数量', '合計 3,200 点（4SKU）'],
        ['納期', 'デザイン確定から量産納品まで 5週間'],
        ['対応ポイント', 'リハーサル日程に合わせた段階納品。会場直送と物販導線を踏まえた個包装仕様で、当日の販売オペレーションを軽量化。'],
      ]},
    { slug:'cosmetics-package', img:'photo-cosmetics-pink.jpg', category:'COSMETICS', client:'株式会社ABC', date:'2026.03.22',
      title:'新コスメブランドのパッケージ設計',
      lead:'ブランド世界観を表現するパッケージとノベルティのデザイン・調達。',
      gallery:['photo-cosmetics-pink.jpg','photo-cosmetics-mono.jpg','photo-merch-flatlay.jpg'],
      details:[
        ['依頼背景','新規ブランド立ち上げに伴うパッケージ・販促物のトータル設計のご依頼。'],
        ['制作した商品','化粧箱／インナートレー／販促ノベルティ（ポーチ・ステッカー）'],
        ['仕様・素材','板紙特殊コート＋空押し／インナーは再生紙トレー'],
        ['数量','1.2万点'],
        ['納期','企画から初回納品まで 8週間'],
        ['対応ポイント','量販店での視認性とSNS映えを両立する版面設計。色校3回・撮影立会いまでサポート。'],
      ]},
    { slug:'package-renewal', img:'photo-cosmetics-mono.jpg', category:'PACKAGE RENEWAL', client:'非公開', date:'2026.02.15',
      title:'既存パッケージのリニューアル',
      lead:'量販店向けパッケージを刷新し、店頭での視認性を改善。',
      gallery:['photo-cosmetics-mono.jpg','photo-cosmetics-pink.jpg','photo-business-meeting.jpg'],
      details:[
        ['依頼背景','既存パッケージが店頭で埋もれてしまうという課題への対応。'],
        ['制作した商品','商品パッケージ／POP'],
        ['仕様・素材','コート紙＋部分マットOPP'],
        ['数量','3万点'],
        ['納期','6週間'],
        ['対応ポイント','棚割りを踏まえた色面設計と、品番違いの差別化レイアウト。'],
      ]},
    { slug:'apparel-md', img:'photo-merch-flatlay.jpg', category:'APPAREL / MD', client:'株式会社XYZ', date:'2026.01.30',
      title:'販売用アパレル商品の量産対応',
      lead:'販売を見据えたMD商品として、量産・在庫管理まで一貫対応。',
      gallery:['photo-merch-flatlay.jpg','photo-business-meeting.jpg','photo-friends-selfie.jpg'],
      details:[
        ['依頼背景','EC販売用にMD設計から量産・物流までを一括で委ねたいというご依頼。'],
        ['制作した商品','Tシャツ／フーディ／キャップ'],
        ['仕様・素材','コットンUSA／フードはヘビーオンス'],
        ['数量','合計 5,000 点'],
        ['納期','12週間'],
        ['対応ポイント','売れ筋予測に応じたサイズ別生産比率と段階入庫。'],
      ]},
    { slug:'promo-novelty', img:'photo-friends-selfie.jpg', category:'PROMOTION', client:'非公開', date:'2025.12.18',
      title:'プロモーション施策ノベルティ',
      lead:'販促キャンペーンで配布するノベルティの企画・小ロット製造。',
      gallery:['photo-friends-selfie.jpg','photo-merch-flatlay.jpg','photo-cosmetics-pink.jpg'],
      details:[
        ['依頼背景','短期キャンペーン用に小ロットノベルティをスピード制作する必要があった。'],
        ['制作した商品','エコバッグ／ステッカー／ピンバッジ'],
        ['仕様・素材','コットンキャンバス／オフセット印刷'],
        ['数量','合計 800 点'],
        ['納期','3週間'],
        ['対応ポイント','短納期前提の素材選定と、現場直送の物流設計。'],
      ]},
  ];

  const slug = new URLSearchParams(location.search).get('slug') || ALL[0].slug;
  const cur = ALL.find(x => x.slug === slug) || ALL[0];
  const idx = ALL.findIndex(x => x.slug === cur.slug);
  const prev = ALL[(idx - 1 + ALL.length) % ALL.length];
  const next = ALL[(idx + 1) % ALL.length];

  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[
        {label:'トップ', href:'index.html'},
        {label:'制作実績', href:'works.html'},
        {label: cur.title},
      ]}/>

      {/* ヘッダー */}
      <section className="lm-section" style={{paddingTop: 32, paddingBottom: 64}} data-screen-label="Work Header">
        <div className="lm-work-detail-head">
          <div className="meta">
            <span className="cat">{cur.category}</span>
            <span className="date">{cur.date}</span>
          </div>
          <h1 className="t">{cur.title}</h1>
          <p className="lead">{cur.lead}</p>
          <div className="client">— CLIENT / {cur.client}</div>
        </div>
      </section>

      {/* メイン画像＋ギャラリー */}
      <section className="lm-section" style={{paddingTop: 0, paddingBottom: 80}}>
        <div className="lm-work-gallery">
          <div className="main" style={{backgroundImage:`url(assets/${cur.gallery[0]})`}}/>
          <div className="subs">
            {cur.gallery.slice(1).map((g,i) => (
              <div key={i} className="sub" style={{backgroundImage:`url(assets/${g})`}}/>
            ))}
          </div>
        </div>
      </section>

      {/* プロジェクト詳細 */}
      <section className="lm-section" style={{paddingTop: 0}} data-screen-label="Work Details">
        <div className="lm-work-detail-grid">
          <div className="aside">
            <div className="k">— PROJECT</div>
            <div className="v">{cur.title}</div>
            <div className="k" style={{marginTop:32}}>— CLIENT</div>
            <div className="v">{cur.client}</div>
            <div className="k" style={{marginTop:32}}>— DATE</div>
            <div className="v">{cur.date}</div>
            <div className="k" style={{marginTop:32}}>— CATEGORY</div>
            <div className="v">{cur.category}</div>
          </div>
          <dl className="lm-work-detail-table">
            {cur.details.map(([k,v]) => (
              <div key={k} className="row">
                <dt>{k}</dt>
                <dd>{v}</dd>
              </div>
            ))}
          </dl>
        </div>
      </section>

      {/* 前後ナビ */}
      <section className="lm-section" style={{paddingTop: 80}}>
        <div className="lm-work-pager">
          <a href={`works-detail.html?slug=${prev.slug}`} className="prev">
            <span className="k">← PREV</span>
            <span className="t">{prev.title}</span>
          </a>
          <a href="works.html" className="index">一覧へ戻る</a>
          <a href={`works-detail.html?slug=${next.slug}`} className="next">
            <span className="k">NEXT →</span>
            <span className="t">{next.title}</span>
          </a>
        </div>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.WorksDetailPage = WorksDetailPage;
