// ContactPage.jsx — お問い合わせフォーム
function ContactPage() {
  const initial = (() => {
    const t = new URLSearchParams(location.search).get('type');
    return t === 'quote' ? ['見積もり依頼'] : [];
  })();
  const [types, setTypes] = React.useState(initial);
  const toggle = (t) => setTypes(s => s.includes(t) ? s.filter(x => x !== t) : [...s, t]);
  const onSubmit = (e) => {
    e.preventDefault();
    location.href = 'contact-thanks.html';
  };

  const TYPES = [
    '企画・仕様の相談','見積もり依頼','サンプルについて',
    '量産について','検品・納品条件の相談','その他',
  ];

  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[{label:'トップ', href:'index.html'}, {label:'お問い合わせ'}]}/>
      <PageHero
        en="CONTACT"
        ja="お問い合わせ"
        lead="グッズ制作、MD、配送、オンライン施策など、まずはお気軽にご相談ください。内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。"
      />

      <section className="lm-section" style={{paddingTop: 0}}>
        <form className="lm-form" onSubmit={onSubmit}>
          {/* 種別 */}
          <div className="lm-form-block">
            <div className="lab"><span className="req">必須</span>お問い合わせ種別<span className="hint">複数選択可</span></div>
            <div className="types">
              {TYPES.map(t => (
                <label key={t} className={`type-chip ${types.includes(t) ? 'is-on' : ''}`}>
                  <input type="checkbox" checked={types.includes(t)} onChange={() => toggle(t)}/>
                  <span>{t}</span>
                </label>
              ))}
            </div>
          </div>

          <div className="lm-form-grid">
            <div className="lm-form-block">
              <div className="lab"><span className="req">必須</span>会社名</div>
              <input type="text" required placeholder="ABC株式会社"/>
            </div>
            <div className="lm-form-block">
              <div className="lab"><span className="req">必須</span>ご担当者名</div>
              <input type="text" required placeholder="田中 太郎"/>
            </div>
            <div className="lm-form-block">
              <div className="lab"><span className="req">必須</span>メールアドレス</div>
              <input type="email" required placeholder="xxxxxxxxxx@example.com"/>
            </div>
            <div className="lm-form-block">
              <div className="lab"><span className="opt">任意</span>電話番号</div>
              <input type="tel" placeholder="000-0000-0000"/>
            </div>
          </div>

          <div className="lm-form-block">
            <div className="lab"><span className="req">必須</span>お問い合わせ内容</div>
            <textarea rows="8" required placeholder="具体的なお問い合わせ内容をご記入ください"/>
          </div>

          <div className="lm-form-block">
            <div className="lab"><span className="opt">任意</span>ファイル添付</div>
            <label className="file-box">
              <input type="file" multiple/>
              <span className="cta">ファイルを選択</span>
              <span className="hint-text">PDF / 画像 / Office 形式（合計 10MB まで）</span>
            </label>
          </div>

          <div className="lm-form-block lm-form-agree">
            <label className="agree">
              <input type="checkbox" required/>
              <span><a href="policy.html">個人情報保護方針</a>に同意します。</span>
            </label>
          </div>

          <div className="lm-form-submit">
            <button type="submit" className="lm-contact-btn lm-form-submit-button">
              <span>送信する</span>
              <span className="arrow">→</span>
            </button>
            <p className="reply">内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。</p>
          </div>
        </form>
      </section>

      <Footer/>
    </>
  );
}
window.ContactPage = ContactPage;
