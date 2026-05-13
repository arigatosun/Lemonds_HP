// CompanyPage.jsx — 会社概要
function CompanyPage() {
  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[{label:'トップ', href:'index.html'}, {label:'会社概要'}]}/>

      {/* About us */}
      <section className="lm-section lm-company-about" data-screen-label="About">
        <div className="lm-section-head">
          <div>
            <h2>ABOUT US</h2>
            <div className="ja">私たちについて</div>
          </div>
          <p className="lead">信頼のOEMパートナーとして。<br/>企画から納品までを一貫してお引き受けします。</p>
        </div>

        <div className="lm-about-body">
          <div className="copy">
            <p>株式会社ルモンズエンターテインメントは、企業・ブランド向けのオリジナルグッズ製造を軸に、企画提案・仕様設計・製造進行までを一貫して手がけるグッズ製造事業を展開しています。</p>
            <p>アーティストのツアーグッズをはじめとした多様な商品制作で培った経験とノウハウを活かし、企画性・実用性・品質のバランスを重視した「使われること」を前提としたものづくりを行っています。</p>
            <p>企画提案から設計、量産、納品までをワンストップで対応し、小ロットから量産まで、用途や目的に応じた柔軟な生産体制を構築。国内外の工場ネットワークと独自の品質チェック体制により、安定した品質と納期管理を実現しています。</p>
          </div>
          <div className="lm-about-collage" aria-hidden="true">
            <div className="lm-about-collage__tile lm-about-collage__tile--edge" style={{backgroundImage:'url(assets/about-product-samples.png)'}}/>
            <div className="lm-about-collage__tile lm-about-collage__tile--left" style={{backgroundImage:'url(assets/photo-merch-flatlay.jpg)'}}/>
            <div className="lm-about-collage__tile lm-about-collage__tile--center" style={{backgroundImage:'url(assets/photo-business-meeting.jpg)'}}/>
            <div className="lm-about-collage__tile lm-about-collage__tile--right" style={{backgroundImage:'url(assets/about-fulfillment-work.png)'}}/>
            <div className="lm-about-collage__tile lm-about-collage__tile--large" style={{backgroundImage:'url(assets/about-goods-planning.png)'}}/>
          </div>
        </div>
      </section>

      {/* COMPANY */}
      <section className="lm-section" style={{paddingTop: 0}} data-screen-label="Company Info">
        <div className="lm-section-head">
          <div>
            <h2>COMPANY</h2>
            <div className="ja">会社概要</div>
          </div>
        </div>

        <dl className="lm-company-table">
          {[
            ['会社名','株式会社ルモンズエンターテインメント'],
            ['英語表記','LEMONDS ENTERTAINMENT CO.,LTD.'],
            ['所在地','〒160-0022 東京都新宿区新宿6丁目24-20 KDX新宿6丁目ビル8F'],
            ['TEL','03-5969-9075'],
            ['設立','20＿＿年＿月'],
            ['資本金','＿＿＿万円'],
            ['決算月','＿＿月'],
            ['役員','代表取締役 ＿＿＿＿＿＿ ／ 取締役 ＿＿＿＿＿＿'],
            ['売上高','＿＿億＿＿＿万円（20＿＿年実績）'],
            ['従業員数','＿＿名'],
            ['所属団体','＿＿＿＿＿＿ ／ ＿＿＿＿＿＿'],
          ].map(([k,v]) => (
            <div className="row" key={k}>
              <dt>{k}</dt>
              <dd>{v}</dd>
            </div>
          ))}
        </dl>
      </section>

      {/* PHILOSOPHY */}
      <section className="lm-section lm-section--dark" data-screen-label="Philosophy">
        <div className="lm-section-head">
          <div>
            <h2>PHILOSOPHY</h2>
            <div className="ja">経営理念</div>
          </div>
          <p className="lead">想いを、価値あるカタチに。</p>
        </div>

        <div className="lm-philosophy">
          <div className="portrait" style={{backgroundImage:'url(assets/photo-business-meeting.jpg)'}}/>
          <div className="msg">
            <div className="k">— MESSAGE FROM CEO</div>
            <p>ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
            <p>ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。ここにテキストが入ります。</p>
            <div className="sign">
              <span className="role">代表取締役</span>
              <span className="name">横山 駿</span>
            </div>
          </div>
        </div>
      </section>

      {/* MAP */}
      <section className="lm-section" data-screen-label="Map">
        <div className="lm-section-head">
          <div>
            <h2>MAP</h2>
            <div className="ja">所在地</div>
          </div>
          <p className="lead">JR・東京メトロ各線「新宿三丁目」「新宿御苑前」よりアクセス。</p>
        </div>

        <div className="lm-map">
          <div className="map-area">
            <div className="placeholder">— GOOGLE MAP PLACEHOLDER —</div>
          </div>
          <div className="addr">
            <div className="k">— ADDRESS</div>
            <p className="v">
              〒160-0022<br/>
              東京都新宿区新宿6丁目24-20<br/>
              KDX新宿6丁目ビル8F
            </p>
            <div className="k" style={{marginTop:32}}>— TEL</div>
            <p className="v">03-5969-9075</p>
          </div>
        </div>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.CompanyPage = CompanyPage;
