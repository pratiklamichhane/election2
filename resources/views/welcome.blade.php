<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VoteSecure Nepal — Secure Digital Democracy</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --sky:        #e8f4fd;
      --sky-mid:    #d0eaf9;
      --sky-deep:   #b8ddf5;
      --blue:       #1a86c8;
      --blue-dark:  #0f5f92;
      --blue-xdark: #093a5c;
      --accent:     #0096d6;
      --white:      #ffffff;
      --ink:        #0b2237;
      --ink-mid:    #2c4a62;
      --ink-light:  #5a7a93;
      --border:     #c8dde9;
      --border-mid: #a8cce0;
      --success:    #0baa6c;
    }
    html { scroll-behavior: smooth; }
    body {
      background: var(--white);
      color: var(--ink);
      font-family: 'Sora', sans-serif;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    /* NAV */
    nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      padding: 0 5rem; height: 72px;
      display: flex; align-items: center; justify-content: space-between;
      background: rgba(255,255,255,0.93);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--border);
      transition: box-shadow 0.3s;
    }
    .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .logo-icon {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, var(--blue), var(--blue-dark));
      border-radius: 10px; display: flex; align-items: center; justify-content: center;
      color: white; font-size: 1rem; font-weight: 800; letter-spacing: -0.5px;
      box-shadow: 0 4px 14px rgba(26,134,200,0.35);
    }
    .logo-name { font-size: 1.2rem; font-weight: 700; color: var(--ink); letter-spacing: -0.03em; }
    .logo-name span { color: var(--blue); }
    .nav-links { display: flex; gap: 0.25rem; list-style: none; }
    .nav-links a {
      padding: 0.45rem 1rem; color: var(--ink-mid); text-decoration: none;
      font-size: 0.875rem; font-weight: 500; border-radius: 8px; transition: all 0.2s;
    }
    .nav-links a:hover { background: var(--sky); color: var(--blue); }
    .nav-cta { display: flex; align-items: center; gap: 0.75rem; }
    .btn-ghost-nav {
      padding: 0.5rem 1.25rem; border: 1.5px solid var(--border-mid);
      color: var(--ink-mid); background: transparent; border-radius: 8px;
      font-family: 'Sora', sans-serif; font-size: 0.875rem; font-weight: 500;
      cursor: pointer; text-decoration: none; transition: all 0.2s;
    }
    .btn-ghost-nav:hover { border-color: var(--blue); color: var(--blue); }
    .btn-fill-nav {
      padding: 0.5rem 1.35rem;
      background: linear-gradient(135deg, var(--blue), var(--blue-dark));
      color: white; border: none; border-radius: 8px;
      font-family: 'Sora', sans-serif; font-size: 0.875rem; font-weight: 600;
      cursor: pointer; text-decoration: none; transition: all 0.25s;
      box-shadow: 0 4px 14px rgba(26,134,200,0.3);
    }
    .btn-fill-nav:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(26,134,200,0.4); }

    /* HERO */
    .hero {
      padding-top: 72px; min-height: 100vh;
      display: grid; grid-template-columns: 1fr 1fr;
      background: var(--sky); position: relative; overflow: hidden;
    }
    .hero::before {
      content: ''; position: absolute; inset: 0;
      background-image: radial-gradient(circle, rgba(26,134,200,0.07) 1px, transparent 1px);
      background-size: 32px 32px; pointer-events: none;
    }
    .hero-left {
      display: flex; flex-direction: column; justify-content: center;
      padding: 5rem 4rem 5rem 5rem; position: relative; z-index: 2;
    }
    .hero-tag {
      display: inline-flex; align-items: center; gap: 0.5rem;
      background: rgba(26,134,200,0.1); border: 1px solid rgba(26,134,200,0.2);
      border-radius: 100px; padding: 0.3rem 0.9rem;
      font-size: 0.75rem; font-weight: 600; color: var(--blue);
      letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 1.75rem; width: fit-content;
    }
    .hero-tag::before {
      content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--blue);
      animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.4;transform:scale(0.7)} }
    .hero-h1 {
      font-family: 'Lora', serif;
      font-size: clamp(2.6rem, 4.5vw, 4rem);
      font-weight: 700; line-height: 1.12; letter-spacing: -0.02em;
      color: var(--ink); margin-bottom: 1.5rem;
    }
    .hero-h1 .italic { font-style: italic; color: var(--blue); }
    .hero-p { font-size: 1.05rem; line-height: 1.8; color: var(--ink-light); max-width: 480px; margin-bottom: 2.5rem; }
    .hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 3.5rem; }
    .btn-primary {
      display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.9rem 2rem;
      background: linear-gradient(135deg, var(--blue), var(--blue-dark));
      color: white; border: none; border-radius: 10px;
      font-family: 'Sora', sans-serif; font-size: 0.95rem; font-weight: 600;
      cursor: pointer; text-decoration: none; transition: all 0.3s;
      box-shadow: 0 6px 20px rgba(26,134,200,0.35);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,134,200,0.45); }
    .btn-outline {
      display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.9rem 2rem;
      background: white; color: var(--ink-mid); border: 1.5px solid var(--border-mid); border-radius: 10px;
      font-family: 'Sora', sans-serif; font-size: 0.95rem; font-weight: 500;
      cursor: pointer; text-decoration: none; transition: all 0.3s;
    }
    .btn-outline:hover { border-color: var(--blue); color: var(--blue); background: var(--sky); }
    .hero-trust { display: flex; gap: 2.5rem; padding-top: 2.5rem; border-top: 1px solid var(--border); }
    .trust-num { font-family: 'Lora', serif; font-size: 1.9rem; font-weight: 700; color: var(--blue); display: block; line-height: 1; }
    .trust-label { font-size: 0.775rem; font-weight: 500; color: var(--ink-light); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.3rem; display: block; }
    .hero-right { position: relative; overflow: hidden; }
    .hero-right img { width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; }
    .hero-right::before {
      content: ''; position: absolute; inset: 0;
      background: linear-gradient(to right, var(--sky) 0%, transparent 22%), linear-gradient(to top, var(--sky) 0%, transparent 25%);
      z-index: 1;
    }
    .candidate-badges { position: absolute; bottom: 2rem; right: 2rem; z-index: 2; display: flex; flex-direction: column; gap: 0.6rem; align-items: flex-end; }
    .cbadge {
      background: rgba(255,255,255,0.92); backdrop-filter: blur(10px);
      border: 1px solid var(--border); border-radius: 8px; padding: 0.5rem 1rem;
      font-size: 0.8rem; font-weight: 600; color: var(--ink);
      display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .cbadge-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--blue); flex-shrink:0; }

    /* BANNER */
    .banner {
      background: linear-gradient(135deg, var(--blue-dark), var(--blue));
      padding: 1rem 5rem; display: flex; align-items: center; gap: 3rem; overflow: hidden;
    }
    .banner-label { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: rgba(255,255,255,0.6); white-space: nowrap; }
    .banner-ticker { overflow: hidden; flex: 1; }
    .banner-inner {
      display: inline-block; white-space: nowrap; animation: scroll-ticker 28s linear infinite;
      font-size: 0.82rem; font-weight: 500; color: rgba(255,255,255,0.9); letter-spacing: 0.03em;
    }
    @keyframes scroll-ticker { from{transform:translateX(0)} to{transform:translateX(-50%)} }

    /* TRUST LOGOS */
    .trust-section { padding: 2.5rem 5rem; border-bottom: 1px solid var(--border); background: white; }
    .trust-row { display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
    .trust-title { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--ink-light); white-space: nowrap; }
    .trust-logos { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
    .trust-logo-item {
      padding: 0.45rem 1rem; border: 1px solid var(--border); border-radius: 8px;
      font-size: 0.78rem; font-weight: 600; color: var(--ink-mid); background: var(--sky);
    }

    /* SECTION COMMONS */
    .section-eyebrow {
      font-size: 0.72rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--blue);
      margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.6rem;
    }
    .section-eyebrow::after { content: ''; flex: 0 0 28px; height: 2px; background: var(--blue); border-radius: 2px; }
    .section-h { font-family: 'Lora', serif; font-size: clamp(1.9rem, 3vw, 2.6rem); font-weight: 700; line-height: 1.2; color: var(--ink); margin-bottom: 1rem; }
    .section-h em { font-style: italic; color: var(--blue); }
    .section-p { font-size: 1rem; line-height: 1.8; color: var(--ink-light); max-width: 540px; }
    .section-header { margin-bottom: 4rem; }

    /* FEATURES */
    .features-section { padding: 7rem 5rem; background: white; }
    .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .feat-card {
      padding: 2.25rem; border: 1.5px solid var(--border); border-radius: 16px;
      background: white; transition: all 0.3s; position: relative; overflow: hidden;
    }
    .feat-card::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, var(--blue), var(--accent));
      transform: scaleX(0); transform-origin: left; transition: transform 0.35s ease;
    }
    .feat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(26,134,200,0.12); border-color: var(--sky-deep); }
    .feat-card:hover::before { transform: scaleX(1); }
    .feat-icon-wrap { width: 52px; height: 52px; border-radius: 12px; background: var(--sky); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; }
    .feat-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin-bottom: 0.6rem; }
    .feat-text { font-size: 0.875rem; line-height: 1.75; color: var(--ink-light); }

    /* CANDIDATES */
    .candidates-section { padding: 7rem 5rem; background: var(--sky); }
    .candidates-layout { display: grid; grid-template-columns: 5fr 4fr; gap: 5rem; align-items: center; }
    .candidates-img-wrap { position: relative; }
    .candidates-img-wrap img { width: 100%; border-radius: 20px; box-shadow: 0 24px 60px rgba(26,134,200,0.18); display: block; }
    .img-tag {
      position: absolute; bottom: -1rem; left: 1.5rem;
      background: white; border-radius: 12px; padding: 1rem 1.5rem;
      box-shadow: 0 8px 28px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.75rem;
    }
    .img-tag-icon { width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, var(--blue), var(--blue-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem; }
    .img-tag-text .t1 { font-size: 0.8rem; font-weight: 700; color: var(--ink); }
    .img-tag-text .t2 { font-size: 0.72rem; color: var(--ink-light); margin-top: 1px; }
    .cand-card {
      background: white; border-radius: 16px; padding: 1.75rem; border: 1.5px solid var(--border);
      margin-bottom: 1.25rem; display: flex; gap: 1.25rem; align-items: flex-start;
      transition: all 0.25s; box-shadow: 0 2px 12px rgba(26,134,200,0.05);
    }
    .cand-card:hover { border-color: var(--blue); box-shadow: 0 8px 28px rgba(26,134,200,0.12); transform: translateX(4px); }
    .cand-avatar { width: 52px; height: 52px; border-radius: 12px; background: linear-gradient(135deg, var(--sky-deep), var(--blue)); display: flex; align-items: center; justify-content: center; font-family: 'Lora', serif; font-size: 1.2rem; font-weight: 700; color: white; flex-shrink: 0; }
    .cand-name { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin-bottom: 0.2rem; }
    .cand-role { font-size: 0.72rem; font-weight: 600; color: var(--blue); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.6rem; }
    .cand-desc { font-size: 0.855rem; line-height: 1.65; color: var(--ink-light); }

    /* STEPS */
    .steps-section { padding: 7rem 5rem; background: white; }
    .steps-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-top: 4rem; position: relative; }
    .steps-grid::before {
      content: ''; position: absolute; top: 2.9rem; left: calc(12.5% + 1.5rem); right: calc(12.5% + 1.5rem);
      height: 2px;
      background: repeating-linear-gradient(90deg, var(--blue) 0, var(--blue) 8px, transparent 8px, transparent 18px);
      opacity: 0.25;
    }
    .step-box { text-align: center; padding: 0 1rem; }
    .step-bubble {
      width: 60px; height: 60px; border-radius: 50%;
      background: linear-gradient(135deg, var(--blue), var(--blue-dark));
      color: white; display: flex; align-items: center; justify-content: center;
      font-family: 'Lora', serif; font-size: 1.3rem; font-weight: 700;
      margin: 0 auto 1.5rem; box-shadow: 0 8px 24px rgba(26,134,200,0.3);
      position: relative; z-index: 1; transition: transform 0.3s;
    }
    .step-box:hover .step-bubble { transform: scale(1.12); }
    .step-t { font-size: 1rem; font-weight: 700; color: var(--ink); margin-bottom: 0.6rem; }
    .step-d { font-size: 0.855rem; line-height: 1.7; color: var(--ink-light); }

    /* STATS */
    .stats-section { padding: 5rem; background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 100%); }
    .stats-inner { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; background: rgba(255,255,255,0.15); }
    .stat-cell { background: transparent; padding: 3rem 2rem; text-align: center; }
    .stat-n { font-family: 'Lora', serif; font-size: 3rem; font-weight: 700; line-height: 1; color: white; margin-bottom: 0.5rem; }
    .stat-l { font-size: 0.82rem; font-weight: 500; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.07em; }

    /* TESTIMONIALS */
    .testi-section { padding: 7rem 5rem; background: var(--sky); }
    .testi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 4rem; }
    .testi-card {
      background: white; border-radius: 16px; padding: 2rem; border: 1.5px solid var(--border);
      box-shadow: 0 4px 20px rgba(26,134,200,0.06); transition: all 0.3s;
    }
    .testi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(26,134,200,0.14); }
    .testi-stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 1.25rem; letter-spacing: 1px; }
    .testi-q { font-family: 'Lora', serif; font-style: italic; font-size: 1rem; line-height: 1.75; color: var(--ink-mid); margin-bottom: 1.5rem; }
    .testi-divider { height: 1px; background: var(--border); margin-bottom: 1.25rem; }
    .testi-meta { display: flex; align-items: center; gap: 0.85rem; }
    .testi-av { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, var(--sky-deep), var(--blue)); display: flex; align-items: center; justify-content: center; font-family: 'Lora', serif; font-size: 0.95rem; font-weight: 700; color: white; flex-shrink: 0; }
    .testi-name-t { font-size: 0.9rem; font-weight: 700; color: var(--ink); }
    .testi-loc { font-size: 0.75rem; color: var(--ink-light); margin-top: 1px; }

    /* SECURITY */
    .security-section { padding: 7rem 5rem; background: white; }
    .security-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
    .sec-list { margin-top: 2.5rem; }
    .sec-row { display: flex; gap: 1.25rem; align-items: flex-start; padding: 1.5rem 0; border-bottom: 1px solid var(--border); }
    .sec-icon-box { width: 46px; height: 46px; border-radius: 10px; background: var(--sky); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .sec-t { font-size: 0.95rem; font-weight: 700; color: var(--ink); margin-bottom: 0.3rem; }
    .sec-d { font-size: 0.855rem; line-height: 1.65; color: var(--ink-light); }
    .security-visual { background: var(--sky); border-radius: 20px; padding: 2.5rem; border: 1.5px solid var(--border); position: relative; }
    .sv-badge { position: absolute; top: -14px; right: 20px; background: var(--success); color: white; padding: 0.3rem 0.85rem; border-radius: 100px; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; }
    .sv-item { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: white; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 0.85rem; font-size: 0.875rem; font-weight: 500; color: var(--ink-mid); }
    .sv-item:last-child { margin-bottom: 0; }
    .sv-check { color: var(--success); font-size: 1.1rem; flex-shrink: 0; }
    .sv-bar { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-mid); }
    .sv-bar-label { display: flex; justify-content: space-between; font-size: 0.78rem; font-weight: 600; color: var(--ink-mid); margin-bottom: 0.5rem; }
    .sv-bar-track { height: 8px; background: var(--sky-deep); border-radius: 100px; overflow: hidden; }
    .sv-bar-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--blue), var(--accent)); width: 0; transition: width 1.8s ease; }
    .sv-bar-fill.animated { width: var(--w); }

    /* CTA */
    .cta-section { padding: 7rem 5rem; background: var(--sky); position: relative; overflow: hidden; }
    .cta-bg-circle { position: absolute; top: 50%; right: -8rem; transform: translateY(-50%); width: 600px; height: 600px; border-radius: 50%; background: radial-gradient(circle, var(--sky-deep) 0%, transparent 70%); pointer-events: none; }
    .cta-inner { position: relative; z-index: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
    .cta-tag { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(26,134,200,0.1); border: 1px solid rgba(26,134,200,0.2); border-radius: 100px; padding: 0.3rem 0.9rem; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--blue); margin-bottom: 1.25rem; width: fit-content; }
    .cta-h { font-family: 'Lora', serif; font-size: clamp(2rem, 3.5vw, 3rem); font-weight: 700; line-height: 1.15; color: var(--ink); margin-bottom: 1.25rem; }
    .cta-h em { font-style: italic; color: var(--blue); }
    .cta-p { font-size: 1rem; line-height: 1.8; color: var(--ink-light); margin-bottom: 2rem; }
    .cta-btns { display: flex; gap: 0.85rem; flex-wrap: wrap; }
    .cta-card { background: white; border-radius: 20px; padding: 2.5rem; border: 1.5px solid var(--border); box-shadow: 0 8px 30px rgba(26,134,200,0.1); }
    .cta-card-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin-bottom: 1.5rem; }
    .cta-input-row { display: flex; gap: 0.75rem; margin-bottom: 0.85rem; }
    .cta-input { flex: 1; padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'Sora', sans-serif; font-size: 0.875rem; color: var(--ink); background: var(--sky); outline: none; transition: border-color 0.2s; }
    .cta-input:focus { border-color: var(--blue); background: white; }
    .cta-mini { font-size: 0.75rem; color: var(--ink-light); line-height: 1.6; }
    .cta-mini a { color: var(--blue); text-decoration: none; }

    /* FOOTER */
    footer { background: var(--ink); color: white; padding: 5rem; padding-bottom: 3rem; }
    .footer-top { display: grid; grid-template-columns: 2.5fr 1fr 1fr 1fr; gap: 4rem; padding-bottom: 3.5rem; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 2.5rem; }
    .f-brand p { font-size: 0.875rem; color: rgba(255,255,255,0.45); line-height: 1.8; margin-top: 1rem; max-width: 280px; }
    .f-col h5 { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 1.25rem; }
    .f-col ul { list-style: none; }
    .f-col li { margin-bottom: 0.6rem; }
    .f-col a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
    .f-col a:hover { color: white; }
    .footer-bottom { display: flex; justify-content: space-between; align-items: center; }
    .footer-bottom p { font-size: 0.8rem; color: rgba(255,255,255,0.3); }
    .f-cert { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--blue); border: 1px solid rgba(26,134,200,0.35); padding: 0.35rem 0.85rem; border-radius: 6px; }

    /* REVEAL ANIMATIONS */
    .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.65s ease, transform 0.65s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-delay-1 { transition-delay: 0.12s; }
    .reveal-delay-2 { transition-delay: 0.24s; }
    .reveal-delay-3 { transition-delay: 0.36s; }

    /* RESPONSIVE */
    @media (max-width: 960px) {
      nav { padding: 0 1.5rem; }
      .nav-links { display: none; }
      .hero { grid-template-columns: 1fr; }
      .hero-right { display: none; }
      .hero-left { padding: 8rem 1.5rem 4rem; }
      .features-grid, .testi-grid { grid-template-columns: 1fr 1fr; }
      .steps-grid { grid-template-columns: 1fr 1fr; }
      .steps-grid::before { display: none; }
      .stats-inner { grid-template-columns: 1fr 1fr; }
      .candidates-layout, .security-layout, .cta-inner { grid-template-columns: 1fr; }
      .footer-top { grid-template-columns: 1fr 1fr; }
      .features-section, .candidates-section, .steps-section,
      .stats-section, .testi-section, .security-section, .cta-section { padding: 4rem 1.5rem; }
      .banner, .trust-section, footer { padding-left: 1.5rem; padding-right: 1.5rem; }
      .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
    }
    @media (max-width: 600px) {
      .features-grid, .testi-grid, .steps-grid, .stats-inner { grid-template-columns: 1fr; }
      .cta-input-row { flex-direction: column; }
    }
  </style>
</head>
<body>

<!-- NAV -->
<nav id="nav">
  <a class="nav-logo" href="#">
    <div class="logo-icon">VS</div>
    <span class="logo-name">Vote<span>Secure</span></span>
  </a>
  <ul class="nav-links">
    <li><a href="#features">Features</a></li>
    <li><a href="#candidates">Candidates</a></li>
    <li><a href="#how">How It Works</a></li>
    <li><a href="#security">Security</a></li>
  </ul>
  <div class="nav-cta">
    <a href="{{ route('login') }}" class="btn-ghost-nav">Sign In</a>
    <a href="{{ route('register') }}" class="btn-fill-nav">Register to Vote</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-tag">Election 2026 · Registration Now Open</div>
    <h1 class="hero-h1">
      Your Vote.<br>
      <span class="italic">Your Democracy.</span><br>
      Your Nepal.
    </h1>
    <p class="hero-p">
      Participate in free, fair, and fully transparent elections from anywhere in the country. Every ballot is encrypted, verified, and counted — your democratic right, protected to the highest standard.
    </p>
    <div class="hero-btns">
      <a href="#" class="btn-primary">Register to Vote →</a>
      <a href="#how" class="btn-outline">▶ How It Works</a>
    </div>
    <div class="hero-trust">
      <div class="trust-item">
        <span class="trust-num">50K+</span>
        <span class="trust-label">Registered Voters</span>
      </div>
      <div class="trust-item">
        <span class="trust-num">100+</span>
        <span class="trust-label">Elections Held</span>
      </div>
      <div class="trust-item">
        <span class="trust-num">100%</span>
        <span class="trust-label">Vote Integrity</span>
      </div>
    </div>
  </div>
  <div class="hero-right">
    <img src="https://english.onlinekhabar.com/wp-content/uploads/2025/12/Rabi-Lamichhane-and-Balen-Shah-1024x697-1.jpg" alt="Rabi Lamichhane and Balen Shah — Nepal's Leaders" />
    <div class="candidate-badges">
      <div class="cbadge"><div class="cbadge-dot"></div>Rabi Lamichhane · RSP</div>
      <div class="cbadge"><div class="cbadge-dot"></div>Balen Shah · Independent</div>
    </div>
  </div>
</section>

<!-- TICKER BANNER -->
<div class="banner">
  <span class="banner-label">Live Updates</span>
  <div class="banner-ticker">
    <div class="banner-inner">
      &nbsp;&nbsp;🗳 Voter registration is now open &nbsp;·&nbsp; Cast your ballot securely from anywhere &nbsp;·&nbsp; Blockchain-verified elections for a stronger Nepal &nbsp;·&nbsp; 50,000+ citizens already registered &nbsp;·&nbsp; 24/7 voter support available &nbsp;·&nbsp; Election Commission certified platform &nbsp;·&nbsp; 🗳 Voter registration is now open &nbsp;·&nbsp; Cast your ballot securely from anywhere &nbsp;·&nbsp; Blockchain-verified elections for a stronger Nepal &nbsp;·&nbsp; 50,000+ citizens already registered &nbsp;·&nbsp; 24/7 voter support available &nbsp;·&nbsp; Election Commission certified platform &nbsp;·&nbsp;
    </div>
  </div>
</div>

<!-- TRUST LOGOS -->
<div class="trust-section">
  <div class="trust-row">
    <span class="trust-title">Certified &amp; Trusted By</span>
    <div class="trust-logos">
      <div class="trust-logo-item">Election Commission of Nepal</div>
      <div class="trust-logo-item">Ministry of Home Affairs</div>
      <div class="trust-logo-item">ISO 27001 Certified</div>
      <div class="trust-logo-item">GDPR Compliant</div>
      <div class="trust-logo-item">Blockchain Audited</div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<section class="features-section" id="features">
  <div class="section-header reveal">
    <div class="section-eyebrow">Platform Features</div>
    <h2 class="section-h">Built for <em>Trustworthy</em> Democracy</h2>
    <p class="section-p">Every feature was engineered with one priority — making sure your vote is cast securely, counted accurately, and verified with complete transparency.</p>
  </div>
  <div class="features-grid">
    <div class="feat-card reveal">
      <div class="feat-icon-wrap">🔒</div>
      <div class="feat-title">End-to-End Encryption</div>
      <div class="feat-text">Your ballot is encrypted the moment you submit it — before it ever leaves your device. AES-256 military-grade protection on every single vote.</div>
    </div>
    <div class="feat-card reveal reveal-delay-1">
      <div class="feat-icon-wrap">⛓</div>
      <div class="feat-title">Blockchain Ledger</div>
      <div class="feat-text">Votes are recorded on an immutable, publicly auditable blockchain. Tampering requires breaking thousands of cryptographic proofs simultaneously — making fraud mathematically impossible.</div>
    </div>
    <div class="feat-card reveal reveal-delay-2">
      <div class="feat-icon-wrap">📱</div>
      <div class="feat-title">Vote From Anywhere</div>
      <div class="feat-text">Cast your vote from your phone, laptop, or tablet at any time during the election window. No queues, no travel, no barriers.</div>
    </div>
    <div class="feat-card reveal">
      <div class="feat-icon-wrap">⚡</div>
      <div class="feat-title">Real-Time Results</div>
      <div class="feat-text">Watch live results as polls close. Automatic tallying eliminates human error and delivers results hours faster than traditional counting.</div>
    </div>
    <div class="feat-card reveal reveal-delay-1">
      <div class="feat-icon-wrap">✅</div>
      <div class="feat-title">Biometric Verification</div>
      <div class="feat-text">KYC-backed biometric authentication ensures only eligible, registered citizens can cast a ballot — one person, one vote, every time.</div>
    </div>
    <div class="feat-card reveal reveal-delay-2">
      <div class="feat-icon-wrap">♿</div>
      <div class="feat-title">Accessible for All</div>
      <div class="feat-text">Multilingual support, screen-reader compatibility, and a UI optimized for entry-level devices — ensuring every Nepali citizen can participate.</div>
    </div>
  </div>
</section>

<!-- CANDIDATES -->
<section class="candidates-section" id="candidates">
  <div class="candidates-layout">
    <div class="candidates-img-wrap reveal">
      <img src="https://english.onlinekhabar.com/wp-content/uploads/2025/12/Rabi-Lamichhane-and-Balen-Shah-1024x697-1.jpg" alt="Rabi Lamichhane and Balen Shah" />
      <div class="img-tag">
        <div class="img-tag-icon">🗳</div>
        <div class="img-tag-text">
          <div class="t1">Election 2025 · Nepal</div>
          <div class="t2">Explore all registered candidates →</div>
        </div>
      </div>
    </div>
    <div class="reveal reveal-delay-1">
      <div class="section-eyebrow">Featured Candidates</div>
      <h2 class="section-h">Meet the <em>Voices of Change</em></h2>
      <p class="section-p" style="margin-bottom:2rem;">Nepal's democracy is strongest when citizens are informed. Read each candidate's background and policy positions before casting your ballot.</p>
      <div class="cand-card">
        <div class="cand-avatar">RL</div>
        <div>
          <div class="cand-name">Rabi Lamichhane</div>
          <div class="cand-role">RSP · Chitwan-2 · Federal Parliament</div>
          <div class="cand-desc">Former investigative journalist turned politician. Leader of Rastriya Swatantra Party, championing anti-corruption reform and youth-led governance for a new Nepal.</div>
        </div>
      </div>
      <div class="cand-card">
        <div class="cand-avatar">BS</div>
        <div>
          <div class="cand-name">Balen Shah</div>
          <div class="cand-role">Independent · Mayor, Kathmandu Metropolitan</div>
          <div class="cand-desc">Rapper, civil engineer, and independent politician who won Kathmandu's mayoral race on a platform of urban development, radical transparency, and citizen-first governance.</div>
        </div>
      </div>
      <a href="#" class="btn-primary" style="margin-top:1.5rem; display:inline-flex;">View All Candidates →</a>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="steps-section" id="how">
  <div class="section-header reveal" style="text-align:center; max-width:520px; margin:0 auto 0;">
    <div class="section-eyebrow" style="justify-content:center;">How It Works</div>
    <h2 class="section-h">Vote in <em>4 Simple Steps</em></h2>
    <p class="section-p" style="margin:0 auto;">The entire process — from registration to receiving your blockchain receipt — takes under 10 minutes.</p>
  </div>
  <div class="steps-grid">
    <div class="step-box reveal">
      <div class="step-bubble">1</div>
      <div class="step-t">Create Account</div>
      <div class="step-d">Register using your Citizenship Certificate number and valid email. Quick and secure — under 2 minutes.</div>
    </div>
    <div class="step-box reveal reveal-delay-1">
      <div class="step-bubble">2</div>
      <div class="step-t">Verify Identity</div>
      <div class="step-d">Submit your documents for KYC review. Our team verifies and approves your eligibility within 24 hours.</div>
    </div>
    <div class="step-box reveal reveal-delay-2">
      <div class="step-bubble">3</div>
      <div class="step-t">Review Candidates</div>
      <div class="step-d">Browse profiles, read full manifestos, and compare policy positions. Make a fully informed choice.</div>
    </div>
    <div class="step-box reveal reveal-delay-3">
      <div class="step-bubble">4</div>
      <div class="step-t">Cast Your Vote</div>
      <div class="step-d">Confirm your selection with biometric authentication and receive instant cryptographic proof of your ballot.</div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats-section" id="stats">
  <div class="stats-inner">
    <div class="stat-cell reveal">
      <div class="stat-n">50,000+</div>
      <div class="stat-l">Active Voters</div>
    </div>
    <div class="stat-cell reveal reveal-delay-1">
      <div class="stat-n">100+</div>
      <div class="stat-l">Elections Conducted</div>
    </div>
    <div class="stat-cell reveal reveal-delay-2">
      <div class="stat-n">Zero</div>
      <div class="stat-l">Fraudulent Votes</div>
    </div>
    <div class="stat-cell reveal reveal-delay-3">
      <div class="stat-n">95%</div>
      <div class="stat-l">Citizen Satisfaction</div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testi-section">
  <div class="section-header reveal" style="text-align:center; max-width:480px; margin:0 auto;">
    <div class="section-eyebrow" style="justify-content:center;">Citizen Voices</div>
    <h2 class="section-h">What <em>Voters</em> Are Saying</h2>
  </div>
  <div class="testi-grid">
    <div class="testi-card reveal">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-q">"I voted from Pokhara in minutes. The whole process was transparent and I received a blockchain receipt instantly. This is what Nepal's democracy needed."</p>
      <div class="testi-divider"></div>
      <div class="testi-meta">
        <div class="testi-av">PK</div>
        <div>
          <div class="testi-name-t">Puja KC</div>
          <div class="testi-loc">Pokhara, Gandaki Province</div>
        </div>
      </div>
    </div>
    <div class="testi-card reveal reveal-delay-1">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-q">"As a teacher far from the polling station, I always struggled to vote. VoteSecure gave me my democratic right back — right from my phone."</p>
      <div class="testi-divider"></div>
      <div class="testi-meta">
        <div class="testi-av">RB</div>
        <div>
          <div class="testi-name-t">Ram Bahadur</div>
          <div class="testi-loc">Dharan, Province No. 1</div>
        </div>
      </div>
    </div>
    <div class="testi-card reveal reveal-delay-2">
      <div class="testi-stars">★★★★★</div>
      <p class="testi-q">"The blockchain receipt proved my vote was correctly recorded. For the very first time, I completely trusted the election process. Extraordinary."</p>
      <div class="testi-divider"></div>
      <div class="testi-meta">
        <div class="testi-av">SM</div>
        <div>
          <div class="testi-name-t">Sunita Maharjan</div>
          <div class="testi-loc">Lalitpur, Bagmati Province</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECURITY -->
<section class="security-section" id="security">
  <div class="security-layout">
    <div class="reveal">
      <div class="section-eyebrow">Security Architecture</div>
      <h2 class="section-h">Built on <em>Unbreakable</em> Foundations</h2>
      <p class="section-p">Every technical decision prioritizes one thing: protecting your democratic right to a secret, accurately counted vote.</p>
      <div class="sec-list">
        <div class="sec-row">
          <div class="sec-icon-box">🔒</div>
          <div>
            <div class="sec-t">End-to-End Encryption</div>
            <div class="sec-d">Your vote is encrypted before leaving your device. Not even VoteSecure staff can view your selection at any point.</div>
          </div>
        </div>
        <div class="sec-row">
          <div class="sec-icon-box">⛓</div>
          <div>
            <div class="sec-t">Immutable Blockchain Record</div>
            <div class="sec-d">Each vote appends an immutable block. Altering any record requires simultaneously breaking thousands of cryptographic proofs.</div>
          </div>
        </div>
        <div class="sec-row">
          <div class="sec-icon-box">🔍</div>
          <div>
            <div class="sec-t">Independent Audit Trail</div>
            <div class="sec-d">Third-party auditors can verify the complete election record without compromising any voter's anonymity or privacy.</div>
          </div>
        </div>
        <div class="sec-row" style="border:none;">
          <div class="sec-icon-box">🧬</div>
          <div>
            <div class="sec-t">Biometric Authentication</div>
            <div class="sec-d">Multi-factor biometric checks ensure one voter, one vote — without exception — on every election conducted on this platform.</div>
          </div>
        </div>
      </div>
    </div>
    <div class="security-visual reveal reveal-delay-1">
      <div class="sv-badge">✓ All Systems Secure</div>
      <div class="sv-item"><span class="sv-check">✔</span> Voter identity verified via biometrics</div>
      <div class="sv-item"><span class="sv-check">✔</span> Ballot encrypted with AES-256</div>
      <div class="sv-item"><span class="sv-check">✔</span> Vote signed with RSA private key</div>
      <div class="sv-item"><span class="sv-check">✔</span> Block appended to public ledger</div>
      <div class="sv-item"><span class="sv-check">✔</span> Blockchain receipt issued &nbsp;<code style="font-size:0.72rem;color:var(--blue);">0x7a9f…3c12</code></div>
      <div class="sv-item"><span class="sv-check">✔</span> Third-party audit log updated</div>
      <div class="sv-bar">
        <div class="sv-bar-label"><span>Election Integrity Score</span><span>100%</span></div>
        <div class="sv-bar-track"><div class="sv-bar-fill" style="--w:100%;"></div></div>
      </div>
      <div class="sv-bar" style="margin-top:1rem;padding-top:0.75rem;border:none;">
        <div class="sv-bar-label"><span>System Uptime</span><span>99.97%</span></div>
        <div class="sv-bar-track"><div class="sv-bar-fill" style="--w:99.97%;"></div></div>
      </div>
      <div class="sv-bar" style="margin-top:1rem;padding-top:0.75rem;border:none;">
        <div class="sv-bar-label"><span>Voter Satisfaction</span><span>95%</span></div>
        <div class="sv-bar-track"><div class="sv-bar-fill" style="--w:95%;"></div></div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="cta-bg-circle"></div>
  <div class="cta-inner">
    <div class="reveal">
      <div class="cta-tag">Registration Open Now</div>
      <h2 class="cta-h">Ready to Make <em>Your Voice Heard?</em></h2>
      <p class="cta-p">Join 50,000+ Nepali citizens who have already registered. Your participation strengthens our democracy — registration takes less than 5 minutes.</p>
      <div class="cta-btns">
        <a href="#" class="btn-primary" style="font-size:1rem;padding:0.95rem 2.25rem;">Register to Vote →</a>
        <a href="#features" class="btn-outline" style="font-size:1rem;padding:0.95rem 2rem;">Learn More</a>
      </div>
    </div>
    <div class="cta-card reveal reveal-delay-1">
      <div class="cta-card-title">Quick Registration</div>
      <div class="cta-input-row">
        <input class="cta-input" type="text" placeholder="Full Name (as on Citizenship)" />
      </div>
      <div class="cta-input-row">
        <input class="cta-input" type="email" placeholder="Email Address" />
        <input class="cta-input" type="text" placeholder="Citizenship No." style="max-width:155px;" />
      </div>
      <a href="#" class="btn-primary" style="width:100%;justify-content:center;margin-bottom:1rem;">Create My Voter Account →</a>
      <p class="cta-mini">By registering you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. Your data is protected under Nepal's data protection regulations.</p>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="f-brand">
      <div class="nav-logo" style="margin-bottom:0.5rem;">
        <div class="logo-icon">VS</div>
        <span class="logo-name" style="color:white;">Vote<span>Secure</span></span>
      </div>
      <p>Empowering Nepal's democracy through secure, transparent, and accessible digital elections. Designed for every Nepali citizen.</p>
    </div>
    <div class="f-col">
      <h5>Platform</h5>
      <ul>
        <li><a href="#">Features</a></li>
        <li><a href="#">Candidates</a></li>
        <li><a href="#">How It Works</a></li>
        <li><a href="#">Security</a></li>
        <li><a href="#">Live Results</a></li>
      </ul>
    </div>
    <div class="f-col">
      <h5>Support</h5>
      <ul>
        <li><a href="#">Help Center</a></li>
        <li><a href="#">FAQs</a></li>
        <li><a href="#">Contact Us</a></li>
        <li><a href="#">Report Issue</a></li>
        <li><a href="#">24/7 Hotline</a></li>
      </ul>
    </div>
    <div class="f-col">
      <h5>Legal</h5>
      <ul>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Compliance</a></li>
        <li><a href="#">Cookie Policy</a></li>
        <li><a href="#">Audit Reports</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 VoteSecure Nepal. All rights reserved. Built for Democracy.</p>
    <div class="f-cert">Election Commission Certified</div>
  </div>
</footer>

<script>
  // Scroll reveal
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        // Animate progress bars when security section comes in
        e.target.querySelectorAll && e.target.querySelectorAll('.sv-bar-fill').forEach(b => b.classList.add('animated'));
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

  // Nav shadow
  window.addEventListener('scroll', () => {
    document.getElementById('nav').style.boxShadow =
      window.scrollY > 30 ? '0 2px 24px rgba(26,134,200,0.1)' : 'none';
  });
</script>
</body>
</html>