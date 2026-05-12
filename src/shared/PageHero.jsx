// PageHero.jsx — 下層ページ共通の見出しブロック
// props: en (e.g. 'SERVICE'), ja (e.g. '事業内容'), lead (string)
function PageHero({ en, ja, lead }) {
  return (
    <section className="lm-page-hero">
      <div className="inner">
        <div className="head">
          <h1 className="en">{en}</h1>
          <div className="ja">{ja}</div>
        </div>
        {lead && <p className="lead">{lead}</p>}
      </div>
    </section>
  );
}
window.PageHero = PageHero;
