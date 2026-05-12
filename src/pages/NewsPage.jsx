// NewsPage.jsx — お知らせ一覧
function NewsPage() {
  const ALL = [
    { slug:'health-brand-launch', date:'2026.04.10', category:'PRESS',   title:'健康サポート機器・グッズ事業の新規取り扱いブランドが決定しました。' },
    { slug:'gw-2026',             date:'2026.03.22', category:'NOTICE',  title:'ゴールデンウィーク期間中の休業日についてのお知らせ。' },
    { slug:'live-merch-released', date:'2026.02.28', category:'PROJECT', title:'某ライブ公演グッズの制作実績を公開しました。' },
    { slug:'office-relocation',   date:'2026.01.15', category:'COMPANY', title:'本社オフィス移転のお知らせ（新宿区内）。' },
    { slug:'gacha-launch',        date:'2025.12.18', category:'PRESS',   title:'オンラインガチャ事業のサービス提供を開始しました。' },
    { slug:'hp-renewal',          date:'2025.11.01', category:'COMPANY', title:'コーポレートサイトをリニューアルしました。' },
    { slug:'event-exhibitor',     date:'2025.10.10', category:'NOTICE',  title:'グッズ展示会への出展のお知らせ。' },
    { slug:'design-team',         date:'2025.09.01', category:'COMPANY', title:'デザインチームを新設しました。' },
  ];
  const cats = ['ALL', ...Array.from(new Set(ALL.map(i => i.category)))];
  const [filter, setFilter] = React.useState('ALL');
  const list = filter === 'ALL' ? ALL : ALL.filter(i => i.category === filter);

  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[{label:'トップ', href:'index.html'}, {label:'お知らせ'}]}/>
      <PageHero
        en="NEWS"
        ja="お知らせ"
        lead="新規取り扱い商品やプロジェクトリリース、休業日のご案内など、ルモンズエンターテインメントからのお知らせを掲載しています。"
      />

      <section className="lm-section" style={{paddingTop: 0, paddingBottom: 56}}>
        <div className="lm-works-filter">
          {cats.map(c => (
            <button key={c} className={`chip ${filter === c ? 'is-on' : ''}`} onClick={() => setFilter(c)}>
              {c === 'ALL' ? 'すべて' : c}
            </button>
          ))}
          <span className="count">{list.length} 件</span>
        </div>
      </section>

      <section className="lm-section" style={{paddingTop: 0}}>
        <ul className="lm-news-list">
          {list.map(it => (
            <li key={it.slug} className="lm-news-row" onClick={() => location.href = `news-detail.html?slug=${it.slug}`}>
              <span className="date">{it.date}</span>
              <span className="cat">{it.category}</span>
              <span className="title">{it.title}</span>
              <span className="arrow">→</span>
            </li>
          ))}
        </ul>

        {/* ページネーション */}
        <div className="lm-pagination">
          <button className="page" disabled>←</button>
          <button className="page is-on">1</button>
          <button className="page">2</button>
          <button className="page">3</button>
          <button className="page">→</button>
        </div>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.NewsPage = NewsPage;
