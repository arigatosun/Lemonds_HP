// ContactThanksPage.jsx — 送信完了
function ContactThanksPage() {
  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[
        {label:'トップ', href:'index.html'},
        {label:'お問い合わせ', href:'contact.html'},
        {label:'送信完了'},
      ]}/>

      <section className="lm-section" data-screen-label="Thanks">
        <div className="lm-thanks">
          <div className="k">— THANK YOU</div>
          <h1>お問い合わせありがとうございました。</h1>
          <p>
            送信が完了いたしました。<br/>
            内容を確認のうえ、担当者より1〜2営業日以内にご連絡いたします。<br/>
            お急ぎの場合は、お電話（03-5969-9075）よりお問い合わせください。
          </p>
          <div className="ctas">
            <a href="index.html" className="lm-pill-outline" style={{textDecoration:'none'}}>
              <span>トップへ戻る</span><span className="circle">→</span>
            </a>
            <a href="services.html" className="lm-pill-outline" style={{textDecoration:'none'}}>
              <span>事業内容を見る</span><span className="circle">→</span>
            </a>
          </div>
        </div>
      </section>

      <Footer/>
    </>
  );
}
window.ContactThanksPage = ContactThanksPage;
