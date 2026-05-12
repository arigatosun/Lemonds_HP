// Works.jsx — WORKS / 制作実績 (B-variant: header + 5 NewsCards + CTA)
function NewsCard({ img, title, category, date, copy }) {
  return (
    <div className="lm-news-card" style={{backgroundImage:`url(assets/${img})`}}>
      <div className="label">
        <span className="eyebrow">{category}</span>
        <span className="title">{title}</span>
        {copy && <span className="copy" style={{font:'400 12px/1.6 var(--font-jp)', letterSpacing:'.04em', color:'rgba(17,17,17,.7)', marginTop:4}}>{copy}</span>}
        <span className="date">{date}</span>
      </div>
      <span className="arrow">→</span>
    </div>
  );
}

function Works() {
  const items = [
    { img:'top-works-live-goods.jpg', category:'EVENT / LIVE',
      title:'某ライブ公演向けグッズ制作', copy:'数千名規模の公演で配布する一体感をつくるグッズの企画〜量産。', date:'2026.04.10' },
    { img:'top-works-cosmetics-package.jpg', category:'COSMETICS',
      title:'新コスメブランドのパッケージ設計', copy:'ブランド世界観を表現するパッケージとノベルティのデザイン・調達。', date:'2026.03.22' },
    { img:'top-works-package-renewal.jpg', category:'PACKAGE RENEWAL',
      title:'既存パッケージのリニューアル', copy:'量販店向けパッケージを刷新し、店頭での視認性を改善。', date:'2026.02.15' },
    { img:'top-works-apparel-md.jpg',  category:'APPAREL / MD',
      title:'販売用アパレル商品の量産対応', copy:'販売を見据えたMD商品として、量産・在庫管理まで一貫対応。', date:'2026.01.30' },
    { img:'top-works-promotion-novelty.jpg', category:'PROMOTION',
      title:'プロモーション施策ノベルティ', copy:'販促キャンペーンで配布するノベルティの企画・小ロット製造。', date:'2025.12.18' },
  ];
  return (
    <section className="lm-section" data-screen-label="06 Works">
      <div className="lm-section-head">
        <div>
          <h2>WORKS</h2>
          <div className="ja">制作実績</div>
        </div>
        <p className="lead">
          イベント、エンタメ、企業販促、健康関連商品など、幅広い領域の制作に対応しています。<br/>
          匿名案件を含む実績の一部をご紹介します。
        </p>
      </div>

      <div style={{display:'grid', gridTemplateColumns:'repeat(3,1fr)', gap: 32}}>
        {items.slice(0,3).map(i => <NewsCard key={i.title} {...i}/>)}
      </div>
      <div style={{display:'grid', gridTemplateColumns:'repeat(2,1fr)', gap: 32, marginTop: 32}}>
        {items.slice(3).map(i => <NewsCard key={i.title} {...i}/>)}
      </div>

      <div style={{marginTop: 56, display:'flex', justifyContent:'center'}}>
        <button className="lm-pill-large">制作実績を見る</button>
      </div>
    </section>
  );
}
window.NewsCard = NewsCard;
window.Works = Works;
