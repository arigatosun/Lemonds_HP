// Breadcrumb.jsx — 下層共通パンくず
function Breadcrumb({ trail }) {
  // trail: [{label, href}]  最後の要素はカレント（href不要）
  return (
    <nav className="lm-breadcrumb" aria-label="breadcrumb">
      {trail.map((t, i) => {
        const last = i === trail.length - 1;
        return (
          <React.Fragment key={i}>
            {last
              ? <span className="cur">{t.label}</span>
              : <a href={t.href}>{t.label}</a>}
            {!last && <span className="sep">・</span>}
          </React.Fragment>
        );
      })}
    </nav>
  );
}
window.Breadcrumb = Breadcrumb;
