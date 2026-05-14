// NewsDetailPage.jsx — お知らせ詳細
function NewsDetailPage() {
  const ALL = [
    { slug:'health-brand-launch', date:'2026.04.10', category:'PRESS',
      title:'健康サポート機器・グッズ事業の新規取り扱いブランドが決定しました。',
      body:[
        'いつもお世話になっております。株式会社ルモンズエンターテインメントです。',
        'このたび、健康サポート機器・グッズ事業において、新たに取り扱いを開始するブランドが決定しましたのでお知らせいたします。EMS機器および機能性アパレル領域での法人向け供給ラインナップを拡充し、これまでよりも幅広い用途・ロットでのご提案が可能となります。',
        '具体的な商品ラインナップ・販売開始時期につきましては、改めて当ページおよび営業担当よりご案内いたします。各種お問い合わせはお問い合わせフォームよりお気軽にご連絡ください。',
      ]},
    { slug:'gw-2026', date:'2026.03.22', category:'NOTICE',
      title:'ゴールデンウィーク期間中の休業日についてのお知らせ。',
      body:[
        '平素より大変お世話になっております。',
        '誠に勝手ながら、ゴールデンウィーク期間中の休業日を下記の通りとさせていただきます。',
        '休業期間：2026年5月3日（土）〜5月6日（火）。期間中にいただきましたお問い合わせ・ご注文につきましては、5月7日（水）以降に順次対応させていただきます。',
        'お客様にはご不便をおかけいたしますが、何卒ご了承くださいますようお願い申し上げます。',
      ]},
    { slug:'live-merch-released', date:'2026.02.28', category:'PROJECT',
      title:'某ライブ公演グッズの制作実績を公開しました。',
      body:[
        '制作実績ページを更新いたしました。某ライブ公演向けに制作したグッズの実績を公開しております。',
        '公演演出と連動した一体感のある商品設計と、当日の物販導線を踏まえた段階納品など、進行面での工夫もまとめております。詳細は制作実績ページよりご覧ください。',
      ]},
    { slug:'office-relocation', date:'2026.01.15', category:'COMPANY',
      title:'本社オフィス移転のお知らせ（新宿区内）。',
      body:['本社オフィス移転に関する詳細をお知らせいたします。']},
    { slug:'gacha-launch', date:'2025.12.18', category:'PRESS',
      title:'オンラインガチャ事業のサービス提供を開始しました。',
      body:['新規事業としてオンラインガチャ（くじ）事業の提供を開始いたしました。']},
  ];
  const slug = new URLSearchParams(location.search).get('slug') || ALL[0].slug;
  const cur = ALL.find(x => x.slug === slug) || ALL[0];
  const idx = ALL.findIndex(x => x.slug === cur.slug);
  const prev = ALL[(idx - 1 + ALL.length) % ALL.length];
  const next = ALL[(idx + 1) % ALL.length];

  // 関連お知らせ: 同カテゴリ優先 → 不足分は新しい順で補完（自分は除外）
  const others = ALL.filter(x => x.slug !== cur.slug);
  const sameCat = others.filter(x => x.category === cur.category);
  const related = [...sameCat, ...others.filter(x => !sameCat.includes(x))].slice(0, 3);

  // 日付を YYYY / MM / DD に分解
  const [y, m, d] = cur.date.split('.');

  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[
        {label:'トップ', href:'index.html'},
        {label:'お知らせ', href:'news.html'},
        {label: cur.title},
      ]}/>

      <PageHero
        en="NEWS"
        ja="お知らせ"
        lead="新規取り扱い商品やプロジェクトリリース、休業日のご案内など、ルモンズエンターテインメントからのお知らせを掲載しています。"
      />

      <article className="lm-section lm-article-wrap" style={{paddingTop: 0, paddingBottom: 96}} data-screen-label="News Article">
        <div className="lm-article">
          {/* エディトリアル日付 */}
          <div className="lm-article-meta">
            <div className="lm-article-date">
              <span className="full">{cur.date}</span>
              <span className="y">{y}</span>
              <span className="sep">/</span>
              <span className="md">{m}<span className="sep small">/</span>{d}</span>
            </div>

            <div className={`lm-article-cat lm-cat--${cur.category.toLowerCase()}`}>{cur.category}</div>
          </div>

          {/* タイトル */}
          <h1 className="lm-article-title">{cur.title}</h1>

          {/* 本文 */}
          <div className="lm-article-body">
            {cur.body.map((p, i) => <p key={i}>{p}</p>)}
          </div>

          {/* 一覧へ戻る */}
          <div className="lm-article-foot">
            <a href="news.html" className="lm-pill-outline lm-pill-outline--section-action lm-pill-outline--back-action" style={{textDecoration:'none'}}>
              <span className="circle">←</span>
              <span className="label">お知らせ一覧へ戻る</span>
            </a>
          </div>
        </div>
      </article>

      {/* 関連お知らせ */}
      <section className="lm-section" style={{paddingTop: 0, paddingBottom: 80}} data-screen-label="Related News">
        <div className="lm-section-head">
          <div>
            <h2>RELATED</h2>
            <div className="ja">関連のお知らせ</div>
          </div>
        </div>
        <ul className="lm-news-list">
          {related.map(it => (
            <li key={it.slug} className="lm-news-row" onClick={() => location.href = `news-detail.html?slug=${it.slug}`}>
              <span className="date">{it.date}</span>
              <span className="cat">{it.category}</span>
              <span className="title">{it.title}</span>
              <span className="arrow">→</span>
            </li>
          ))}
        </ul>
      </section>

      {/* PREV / NEXT — 2カラム + 下にindex */}
      <section className="lm-section" style={{paddingTop: 0, paddingBottom: 80}}>
        <div className="lm-news-pager">
          <a href={`news-detail.html?slug=${prev.slug}`} className="prev">
            <span className="k">← PREV</span>
            <span className="t">{prev.title}</span>
          </a>
          <a href={`news-detail.html?slug=${next.slug}`} className="next">
            <span className="k">NEXT →</span>
            <span className="t">{next.title}</span>
          </a>
        </div>
        <div className="lm-news-pager-index">
          <a href="news.html" className="index lm-pill-outline lm-pill-outline--section-action lm-pill-outline--back-action">
            <span className="circle">←</span>
            <span className="label">お知らせ一覧へ</span>
          </a>
        </div>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.NewsDetailPage = NewsDetailPage;
