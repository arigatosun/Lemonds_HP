// PolicyPage.jsx — ポリシー（企業ポリシー / 個人情報保護方針 / 反社方針）
function PolicyPage() {
  return (
    <>
      <SubHeader/>
      <Breadcrumb trail={[{label:'トップ', href:'index.html'}, {label:'ポリシー'}]}/>
      <PageHero
        en="POLICY"
        ja="企業ポリシー・各種方針"
        lead="ルモンズエンターテインメントの企業ポリシー、個人情報保護方針、および反社会的勢力に対する基本方針を掲載しています。"
      />

      <section className="lm-section" style={{paddingTop: 0}}>
        {/* 目次 */}
        <nav className="lm-policy-toc">
          <a href="#corporate">— 01 / 企業ポリシー</a>
          <a href="#privacy">— 02 / 個人情報保護方針</a>
          <a href="#antisocial">— 03 / 反社会的勢力に対する基本方針</a>
        </nav>

        {/* 1 */}
        <article id="corporate" className="lm-policy">
          <header>
            <div className="k">— 01</div>
            <h2>企業ポリシー</h2>
          </header>
          <div className="body">
            <p>ここに企業ポリシーの本文が入ります。短文・長文いずれかの版を選択のうえ、確定原稿を流し込みます。</p>
            <p>当社は、企画から納品までを一貫してお預かりするものづくりのパートナーとして、誠実な進行と品質管理を前提に事業活動を行っています。</p>
          </div>
        </article>

        {/* 2 */}
        <article id="privacy" className="lm-policy">
          <header>
            <div className="k">— 02</div>
            <h2>個人情報保護方針</h2>
          </header>
          <div className="body">
            <p>当社は、お客様からお預かりする個人情報の重要性を認識し、関連法令およびガイドラインを遵守のうえ、適切に取り扱います。</p>
            <ol>
              <li>個人情報は、お問い合わせ対応・お見積もり・契約履行・アフターサポートの目的の範囲内で利用いたします。</li>
              <li>取得した個人情報は、ご本人の同意なく第三者に提供いたしません。</li>
              <li>個人情報の正確性を保ち、漏えい・滅失・改ざん・不正アクセス等の防止に努めます。</li>
              <li>個人情報の開示・訂正・利用停止のご請求には、本人確認のうえ合理的な範囲で対応いたします。</li>
              <li>個人情報保護に関する社内体制を継続的に見直し、改善に努めます。</li>
            </ol>
            <p className="meta">改定日：20＿＿年＿月＿日 ／ 株式会社ルモンズエンターテインメント</p>
          </div>
        </article>

        {/* 3 */}
        <article id="antisocial" className="lm-policy">
          <header>
            <div className="k">— 03</div>
            <h2>反社会的勢力に対する基本方針</h2>
          </header>
          <div className="body">
            <p>当社は、市民社会の秩序や安全に脅威を与える反社会的勢力に対し、毅然とした態度で対応いたします。</p>
            <ol>
              <li>反社会的勢力との一切の関係を遮断し、不当要求には組織として対応いたします。</li>
              <li>反社会的勢力に対しては、いかなる名目においても資金提供を行いません。</li>
              <li>不当要求等が発生した場合は、警察・弁護士等の外部専門機関と緊密に連携のうえ対応いたします。</li>
            </ol>
          </div>
        </article>
      </section>

      <ContactCTA/>
      <Footer/>
    </>
  );
}
window.PolicyPage = PolicyPage;
