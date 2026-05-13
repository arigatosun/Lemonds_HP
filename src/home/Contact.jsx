// Contact.jsx — A案 CONTACT (split photo, fixed)
function Contact() {
  return (
    <section className="lm-section lm-home-contact" data-screen-label="08 Contact">
      <div className="lm-section-head">
        <div>
          <h2>CONTACT</h2>
          <div className="ja">お問い合わせ</div>
        </div>
        <p className="lead">
          グッズ制作、MD、配送、オンライン施策など、まずはお気軽にご相談ください。<br/>
          初回ご相談は無料、専任担当が条件整理からお手伝いします。
        </p>
      </div>

      <div className="lm-home-contact__panel">
        {/* photo */}
        <div className="lm-home-contact__photo">
          <div className="lm-home-contact__badge">— IN PERSON</div>
          <div className="lm-home-contact__caption">
            <div className="lm-home-contact__caption-k">— STUDIO / TOKYO</div>
            <div className="lm-home-contact__caption-title">
              直接お会いしての<br/>ご相談も承ります。
            </div>
          </div>
        </div>

        {/* body */}
        <div className="lm-home-contact__body">
          <div className="lm-home-contact__eyebrow">
            — SECTION 06
          </div>
          <div>
            <h2 className="lm-home-contact__title">CONTACT.</h2>
            <div className="lm-home-contact__ja">お問い合わせ</div>
          </div>
          <p className="lm-home-contact__copy">
            グッズ制作、MD、配送、オンライン施策。<br/>
            条件整理からお手伝いします。
          </p>

          <div className="lm-home-contact__actions">
            <button className="lm-contact-btn lm-contact-btn--dark">
              <span>お問い合わせする</span>
              <span className="arrow">→</span>
            </button>
            <button className="lm-contact-btn lm-contact-btn--light-outline">
              <span>見積もりを相談する</span>
              <span className="arrow">→</span>
            </button>
          </div>

          <div className="lm-home-contact__info">
            <div>
              <div className="lm-home-contact__info-label">EMAIL</div>
              <div className="lm-home-contact__info-value">contact@lemonds.example</div>
            </div>
            <div>
              <div className="lm-home-contact__info-label">TEL</div>
              <div className="lm-home-contact__info-value">03-0000-0000</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
window.Contact = Contact;
