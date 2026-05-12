// Reason.jsx — ルモンズの強み (3-column text-only summary)
function Reason() {
  const items = [
    { n:'01', title:'納期に合わせる進行力',
      copy:'イベント日程や発売時期に合わせて、仕様整理から製造・納品までのスケジュールを組み立てます。短納期の案件や急な調整にも、できる限り現実的な方法を探ります。' },
    { n:'02', title:'予算に応じた提案力',
      copy:'素材、仕様、数量、製造ルートを調整し、目的とコストのバランスが取れた形をご提案します。作りたいものに対して、無理のない進め方を一緒に設計します。' },
    { n:'03', title:'速く、柔軟な対応力',
      copy:'見積もり、仕様確認、進行中の相談までスピーディーに対応。案件ごとの条件に合わせて、製造先や現場とも連携しながら柔軟に進めます。' },
  ];
  return (
    <section className="lm-section" data-screen-label="04 Strengths">
      <div className="lm-section-head">
        <div>
          <h2>STRENGTHS</h2>
          <div className="ja">ルモンズの強み</div>
        </div>
        <p className="lead">
          コスト、納期、レスポンス。<br/>
          案件ごとの条件に合わせて、現実的な進行と確かな納品を支えます。
        </p>
      </div>

      <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr)', gap: 0, borderTop:'1px solid rgba(17,17,17,.18)'}}>
        {items.map((i, idx) => (
          <div key={i.n} style={{
            padding:'56px 48px 56px',
            borderRight: idx < items.length - 1 ? '1px solid rgba(17,17,17,.18)' : 'none',
            display:'flex', flexDirection:'column', gap: 20,
          }}>
            <div style={{font:'700 13px/1 var(--font-en)', letterSpacing:'.22em', color:'rgba(17,17,17,.55)'}}>— {i.n}</div>
            <h3 style={{font:'700 26px/1.4 var(--font-jp)', letterSpacing:'.06em', color:'#111', margin:0}}>{i.title}</h3>
            <p style={{font:'400 14px/1.95 var(--font-jp)', letterSpacing:'.04em', color:'rgba(17,17,17,.78)', margin:0, maxWidth: 420}}>{i.copy}</p>
          </div>
        ))}
      </div>
    </section>
  );
}
window.Reason = Reason;
