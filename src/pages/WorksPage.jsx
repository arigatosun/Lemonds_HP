// WorksPage.jsx — 制作実績一覧ページ
function WorksPage() {
  const items = [
    { slug:'live-merch-2026',     img:'photo-concert-lights.jpg',   category:'EVENT / LIVE',     client:'某ライブ公演',
      title:'某ライブ公演向けグッズ制作',
      copy:'数千名規模の公演で配布する一体感をつくるグッズの企画〜量産。',
      date:'2026.04.10' },
    { slug:'cosmetics-package',   img:'photo-cosmetics-pink.jpg',   category:'COSMETICS',        client:'株式会社ABC',
      title:'新コスメブランドのパッケージ設計',
      copy:'ブランド世界観を表現するパッケージとノベルティのデザイン・調達。',
      date:'2026.03.22' },
    { slug:'package-renewal',     img:'photo-cosmetics-mono.jpg',   category:'PACKAGE RENEWAL',  client:'非公開',
      title:'既存パッケージのリニューアル',
      copy:'量販店向けパッケージを刷新し、店頭での視認性を改善。',
      date:'2026.02.15' },
    { slug:'apparel-md',          img:'photo-merch-flatlay.jpg',    category:'APPAREL / MD',     client:'株式会社XYZ',
      title:'販売用アパレル商品の量産対応',
      copy:'販売を見据えたMD商品として、量産・在庫管理まで一貫対応。',
      date:'2026.01.30' },
    { slug:'promo-novelty',       img:'photo-friends-selfie.jpg',   category:'PROMOTION',        client:'非公開',
      title:'プロモーション施策ノベルティ',
      copy:'販促キャンペーンで配布するノベルティの企画・小ロット製造。',
      date:'2025.12.18' },
    { slug:'health-apparel',      img:'photo-business-meeting.jpg', category:'HEALTH / APPAREL', client:'株式会社DEF',
      title:'機能性アパレルの法人供給',
      copy:'EMS関連のサポートウエアを小ロットからOEM供給。',
      date:'2025.11.20' },
    { slug:'online-gacha',        img:'photo-cosmetics-pink.jpg',   category:'ONLINE GACHA',     client:'非公開',
      title:'オンラインガチャ施策の運用設計',
      copy:'グッズ製造から在庫・配送までを通したオンラインくじの設計。',
      date:'2025.10.08' },
    { slug:'logistics-event',     img:'photo-merch-flatlay.jpg',    category:'LOGISTICS',        client:'非公開',
      title:'イベント出展時の搬入・物流支援',
      copy:'チャーター便を含む緊急配送と当日搬入のオペレーション。',
      date:'2025.09.12' },
    { slug:'graphic-branding',    img:'photo-cosmetics-mono.jpg',   category:'GRAPHIC',          client:'株式会社GHI',
      title:'ブランドロゴ・パッケージのトータル設計',
      copy:'ロゴ刷新からパッケージ・販促ツールまでをワンストップで制作。',
      date:'2025.08.05' },
  ];

  const categories = ['ALL', ...Array.from(new Set(items.map(i => i.category)))];
  const [filter, setFilter] = React.useState('ALL');
  const list = filter === 'ALL' ? items : items.filter(i => i.category === filter);

  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[{label:'トップ', href:'index.html'}, {label:'制作実績'}]}/>
      <PageHero
        en="WORKS"
        ja="制作実績"
        lead="イベント、エンタメ、企業販促、健康関連商品など、幅広い領域の制作に対応しています。匿名案件を含む実績の一部をご紹介します。"
      />

      {/* フィルタ */}
      <section className="lm-section" style={{paddingTop: 0, paddingBottom: 56}}>
        <div className="lm-works-filter">
          {categories.map(c => (
            <button
              key={c}
              className={`chip ${filter === c ? 'is-on' : ''}`}
              onClick={() => setFilter(c)}
            >{c === 'ALL' ? 'すべて' : c}</button>
          ))}
          <span className="count">{list.length} 件</span>
        </div>
      </section>

      {/* 一覧 */}
      <section className="lm-section" style={{paddingTop: 0}}>
        <div className="lm-works-grid">
          {list.map(it => (
            <a key={it.slug} href={`works-detail.html?slug=${it.slug}`} className="lm-work-card">
              <div className="thumb" style={{backgroundImage:`url(assets/${it.img})`}}>
                <span className="arrow">→</span>
              </div>
              <div className="meta">
                <span className="cat">{it.category}</span>
                <span className="date">{it.date}</span>
              </div>
              <h3 className="t">{it.title}</h3>
              <p className="c">{it.copy}</p>
              <div className="client">— {it.client}</div>
            </a>
          ))}
        </div>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.WorksPage = WorksPage;
