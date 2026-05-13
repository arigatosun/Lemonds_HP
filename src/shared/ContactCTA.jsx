// ContactCTA.jsx — 下層共通の下部CTA「CONTACT / ご連絡」
function ContactCTA() {
  return (
    <section className="lm-section lm-section--dark" data-screen-label="CTA Contact">
      <div className="lm-section-head">
        <div>
          <h2>CONTACT</h2>
          <div className="ja">ご連絡</div>
        </div>
        <p className="lead">
          グッズ制作、MD、配送、オンライン施策など、まずはお気軽にご相談ください。<br/>
          初回ご相談は無料、専任担当が条件整理からお手伝いします。
        </p>
      </div>
      <div className="lm-contact">
        <div>
          <p className="copy">
            お見積もりとご相談、それぞれ最適な窓口をご用意しています。<br/>
            ご相談内容が固まっていない段階でも構いません。
          </p>
          <div className="chips">
            <span className="chip">企画・仕様の相談</span>
            <span className="chip">見積もり</span>
            <span className="chip">サンプル</span>
            <span className="chip">量産</span>
            <span className="chip">検品・納品</span>
          </div>
        </div>
        <div className="ctas">
          <a href="contact.html?type=quote" className="lm-contact-btn lm-contact-btn--primary">
            <span>見積もりを依頼する</span><span className="arrow">→</span>
          </a>
          <a href="contact.html" className="lm-contact-btn lm-contact-btn--secondary">
            <span>お問い合わせをする</span><span className="arrow">→</span>
          </a>
        </div>
      </div>
    </section>
  );
}
window.ContactCTA = ContactCTA;
