// News.jsx — NEWS / お知らせ
// Placed between Works and Company. List-type layout to differentiate from Works (photo cards).
function News() {
  const items = [
    { date: '2026.04.10', category: 'PRESS',    title: '健康サポート機器・グッズ事業の新規取り扱いブランドが決定しました。' },
    { date: '2026.03.22', category: 'NOTICE',   title: 'ゴールデンウィーク期間中の休業日についてのお知らせ。' },
    { date: '2026.02.28', category: 'PROJECT',  title: '某ライブ公演グッズの制作実績を公開しました。' },
    { date: '2026.01.15', category: 'COMPANY',  title: '本社オフィス移転のお知らせ（新宿区内）。' },
    { date: '2025.12.18', category: 'PRESS',    title: 'オンラインガチャ事業のサービス提供を開始しました。' },
  ];
  return (
    <section className="lm-section" data-screen-label="07 News">
      <div className="lm-section-head">
        <div>
          <h2>NEWS</h2>
          <div className="ja">お知らせ</div>
        </div>
        <p className="lead">
          新規取り扱い商品やプロジェクトリリース、休業日のご案内など、<br/>
          ルモンズエンターテインメントからのお知らせを掲載しています。
        </p>
      </div>

      <ul className="lm-news-list">
        {items.map((it) => (
          <li key={it.title} className="lm-news-row">
            <span className="date">{it.date}</span>
            <span className="cat">{it.category}</span>
            <span className="title">{it.title}</span>
            <span className="arrow">→</span>
          </li>
        ))}
      </ul>

      <div style={{marginTop: 56, display:'flex', justifyContent:'center'}}>
        <button className="lm-pill-large">お知らせ一覧を見る</button>
      </div>
    </section>
  );
}
window.News = News;
