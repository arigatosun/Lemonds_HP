// ContactCTA.jsx — 下層共通の下部CTA「CONTACT / ご連絡」
function ContactCTA() {
  return (
    <section className="lm-section lm-section--dark lm-contact-cta" data-screen-label="CTA Contact">
      <div className="lm-contact-cta__panel">
        <div className="lm-contact-cta__photo">
          <div className="lm-contact-cta__badge">— IN PERSON</div>
          <div className="lm-contact-cta__caption">
            <div className="lm-contact-cta__caption-k">— STUDIO / TOKYO</div>
            <div className="lm-contact-cta__caption-title">
              直接お会いしての<br/>ご相談も承ります。
            </div>
          </div>
        </div>

        <div className="lm-contact-cta__body">
          <div className="lm-contact-cta__eyebrow">— SECTION 08</div>
          <div>
            <h2 className="lm-contact-cta__title">CONTACT.</h2>
            <div className="lm-contact-cta__ja">お問い合わせ</div>
          </div>
          <p className="lm-contact-cta__copy">
            <span className="lm-contact-cta__copy-unit">グッズ制作、MD、配送、</span>
            <span className="lm-contact-cta__copy-unit">オンライン施策まで、</span><br/>
            <span className="lm-contact-cta__copy-unit">条件整理からお手伝いします。</span>
          </p>

          <div className="lm-contact-cta__actions">
            <a href="contact.html" className="lm-contact-btn lm-contact-btn--primary">
              <span>お問い合わせする</span><span className="arrow">→</span>
            </a>
            <a href="contact.html?type=quote" className="lm-contact-btn lm-contact-btn--secondary">
              <span>見積もりを相談する</span><span className="arrow">→</span>
            </a>
          </div>

          <div className="lm-contact-cta__info">
            <div>
              <div className="lm-contact-cta__info-label">EMAIL</div>
              <div className="lm-contact-cta__info-value">contact@lemonds.example</div>
            </div>
            <div>
              <div className="lm-contact-cta__info-label">TEL</div>
              <div className="lm-contact-cta__info-value">03-0000-0000</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
window.ContactCTA = ContactCTA;
