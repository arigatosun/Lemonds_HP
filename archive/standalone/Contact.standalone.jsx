// Contact.jsx — A案 CONTACT (split photo, fixed)
function Contact() {
  return (
    <section className="lm-section" data-screen-label="08 Contact" style={{background:"#F4F4F4"}}>
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

      <div style={{
        display:"grid", gridTemplateColumns:"1fr 1fr", gap:0,
        borderRadius:28, overflow:"hidden",
        border:"1px solid rgba(17,17,17,.12)", background:"#fff",
      }}>
        {/* photo */}
        <div style={{
          position:"relative", minHeight:640,
          background:`url(${window.LM_ASSET('photo-business-meeting.jpg')}) center/cover`,
        }}>
          <div style={{
            position:"absolute", top:32, left:32,
            background:"#F4F4F4", color:"#111", padding:"10px 18px", borderRadius:9999,
            font:"700 12px/1 var(--font-en)", letterSpacing:".22em",
          }}>— IN PERSON</div>
          <div style={{
            position:"absolute", left:40, bottom:40, color:"#F4F4F4",
            textShadow:"0 1px 24px rgba(0,0,0,.5)", maxWidth:440,
          }}>
            <div style={{font:"700 11px/1 var(--font-en)", letterSpacing:".22em", opacity:.9}}>— STUDIO / TOKYO</div>
            <div style={{font:"700 32px/1.4 var(--font-jp)", letterSpacing:".06em", marginTop:10}}>
              直接お会いしての<br/>ご相談も承ります。
            </div>
          </div>
        </div>

        {/* body */}
        <div style={{padding:"80px 72px", display:"flex", flexDirection:"column", gap:24}}>
          <div style={{font:"700 12px/1 var(--font-en)", letterSpacing:".24em", color:"rgba(17,17,17,.55)"}}>
            — SECTION 06
          </div>
          <div>
            <h2 style={{font:"700 96px/1 var(--font-en)", letterSpacing:".01em", margin:0}}>CONTACT.</h2>
            <div style={{font:"700 22px/1 var(--font-jp)", letterSpacing:".12em", marginTop:14}}>お問い合わせ</div>
          </div>
          <p style={{
            font:"400 16px/1.95 var(--font-jp)", letterSpacing:".04em",
            color:"rgba(17,17,17,.78)", maxWidth:520, margin:0,
          }}>
            グッズ制作、MD、配送、オンライン施策。<br/>
            条件整理からお手伝いします。
          </p>

          <div style={{display:"flex", flexDirection:"column", gap:14, marginTop:8}}>
            <button style={{
              display:"flex", alignItems:"center", justifyContent:"space-between",
              height:88, padding:"0 36px", borderRadius:24, border:0, cursor:"pointer",
              background:"#111", color:"#F4F4F4",
              font:"700 22px/1 var(--font-jp)", letterSpacing:".12em", width:"100%",
            }}>
              <span>お問い合わせする</span>
              <span style={{
                width:44, height:44, borderRadius:"50%",
                background:"rgba(244,244,244,.18)", color:"#F4F4F4",
                display:"inline-flex", alignItems:"center", justifyContent:"center", fontSize:18,
              }}>→</span>
            </button>
            <button style={{
              display:"flex", alignItems:"center", justifyContent:"space-between",
              height:88, padding:"0 36px", borderRadius:24, border:"1px solid #111", cursor:"pointer",
              background:"#F4F4F4", color:"#111",
              font:"700 22px/1 var(--font-jp)", letterSpacing:".12em", width:"100%",
            }}>
              <span>見積もりを相談する</span>
              <span style={{
                width:44, height:44, borderRadius:"50%",
                background:"#111", color:"#F4F4F4",
                display:"inline-flex", alignItems:"center", justifyContent:"center", fontSize:18,
              }}>→</span>
            </button>
          </div>

          <div style={{
            display:"flex", gap:48, marginTop:16, paddingTop:24,
            borderTop:"1px solid rgba(17,17,17,.18)",
          }}>
            <div>
              <div style={{font:"700 12px/1 var(--font-en)", letterSpacing:".24em", color:"rgba(17,17,17,.55)"}}>EMAIL</div>
              <div style={{font:"700 18px/1 var(--font-en)", letterSpacing:".04em", marginTop:8}}>contact@lemonds.example</div>
            </div>
            <div>
              <div style={{font:"700 12px/1 var(--font-en)", letterSpacing:".24em", color:"rgba(17,17,17,.55)"}}>TEL</div>
              <div style={{font:"700 18px/1 var(--font-en)", letterSpacing:".04em", marginTop:8}}>03-0000-0000</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
window.Contact = Contact;
