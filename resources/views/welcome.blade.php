<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoTrack Ethiopia — Every Car Has a Chart</title>
    <meta name="description" content="AutoTrack Ethiopia gives every vehicle a permanent service chart — diagnosis, treatment, and history, verifiable by QR code.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --paper: #F1F0EA;
            --paper-2: #E8E6DD;
            --card: #FCFBF8;
            --ink: #23281F;
            --ink-muted: #5B5F55;
            --ink-faint: #8B8E82;
            --navy: #1F3A44;
            --navy-dim: rgba(31,58,68,0.08);
            --clay: #A8503B;
            --clay-dim: rgba(168,80,59,0.1);
            --teal: #2F6F62;
            --teal-dim: rgba(47,111,98,0.1);
            --line: #D3D0C3;
            --line-strong: #B8B4A3;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--paper);
            color: var(--ink);
            font-family: 'IBM Plex Sans', sans-serif;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, .display {
            font-family: 'Source Serif 4', serif;
            font-weight: 600;
            line-height: 1.15;
            color: var(--ink);
        }
        .mono { font-family: 'IBM Plex Mono', monospace; }
        a { color: inherit; text-decoration: none; }
        img, svg { display: block; max-width: 100%; }
        .wrap { max-width: 1140px; margin: 0 auto; padding: 0 32px; }
        .section { padding: 92px 0; }
        .tab-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11.5px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--clay);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px 5px 0;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--clay);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 600;
            font-size: 14px;
            padding: 13px 24px;
            border-radius: 2px;
            cursor: pointer;
            transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
            border: 1.5px solid transparent;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: var(--navy); color: var(--paper); }
        .btn-primary:hover { background: #16292F; }
        .btn-ghost { border-color: var(--line-strong); color: var(--ink); }
        .btn-ghost:hover { border-color: var(--navy); color: var(--navy); }

        /* NAV */
        nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(241,240,234,0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1.5px solid var(--line);
        }
        nav .wrap { display: flex; align-items: center; justify-content: space-between; height: 78px; }
        .logo { display: flex; align-items: center; gap: 10px; font-family: 'Source Serif 4', serif; font-size: 21px; font-weight: 600; }
        .logo-mark { width: 30px; height: 30px; background: var(--navy); border-radius: 3px; display: flex; align-items: center; justify-content: center; color: var(--paper); font-size: 14px; font-weight: 700; font-family: 'IBM Plex Mono', monospace; }
        .nav-links { display: flex; align-items: center; gap: 34px; }
        .nav-links a { font-size: 14px; color: var(--ink-muted); transition: color 0.15s; }
        .nav-links a:hover { color: var(--navy); }
        .nav-cta { display: flex; align-items: center; gap: 14px; }
        .nav-cta .btn { padding: 9px 18px; }

        /* HERO */
        .hero { padding: 80px 0 60px; }
        .hero-grid { display: grid; grid-template-columns: 1fr 0.92fr; gap: 60px; align-items: center; }
        .hero h1 { font-size: 48px; margin-bottom: 20px; }
        .hero h1 em { font-style: italic; color: var(--clay); }
        .hero p.lede { font-size: 17.5px; color: var(--ink-muted); max-width: 460px; margin-bottom: 32px; }
        .hero-actions { display: flex; gap: 14px; margin-bottom: 30px; }
        .hero-trust { font-size: 13px; color: var(--ink-faint); border-left: 2px solid var(--teal); padding-left: 12px; max-width: 380px; }

        /* CHART CARD — signature element */
        .chart-card {
            background: var(--card);
            border: 1px solid var(--line-strong);
            border-radius: 3px;
            position: relative;
            box-shadow: 6px 6px 0 var(--paper-2), 6px 6px 0 1px var(--line);
        }
        .chart-tabs { display: flex; gap: 2px; padding: 0 18px; transform: translateY(-1px); }
        .chart-tab {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10.5px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--ink-faint);
            background: var(--paper-2);
            border: 1px solid var(--line-strong);
            border-bottom: none;
            padding: 7px 14px 5px;
            border-radius: 3px 3px 0 0;
        }
        .chart-tab.active { background: var(--card); color: var(--navy); font-weight: 600; }
        .chart-head {
            padding: 20px 24px 16px;
            border-bottom: 1px dashed var(--line-strong);
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .chart-head .plate { font-family: 'IBM Plex Mono', monospace; font-size: 15px; letter-spacing: 0.04em; }
        .chart-head .sub { font-size: 11.5px; color: var(--ink-faint); margin-top: 4px; }
        .chart-status { text-align: right; }
        .chart-status .val { font-family: 'Source Serif 4', serif; font-size: 24px; color: var(--teal); font-weight: 600; }
        .chart-status .lbl { font-size: 10px; color: var(--ink-faint); text-transform: uppercase; letter-spacing: 0.06em; }
        .chart-body { padding: 20px 24px; }
        .vital-row { display: flex; align-items: baseline; gap: 12px; padding: 9px 0; border-bottom: 1px dotted var(--line); font-family: 'IBM Plex Mono', monospace; font-size: 12.5px; }
        .vital-row:last-child { border-bottom: none; }
        .vital-name { color: var(--ink-muted); min-width: 100px; }
        .vital-bar-track { flex: 1; height: 4px; background: var(--paper-2); position: relative; }
        .vital-bar-fill { height: 100%; }
        .vital-val { min-width: 26px; text-align: right; }
        .chart-note {
            margin: 18px 24px 22px; padding: 12px 14px;
            background: var(--clay-dim); border-left: 2px solid var(--clay);
            font-size: 12.5px; color: var(--ink-muted); font-style: italic;
        }
        .chart-footer {
            border-top: 1px dashed var(--line-strong);
            padding: 14px 24px;
            display: flex; align-items: center; gap: 10px;
            font-size: 11.5px; color: var(--ink-faint);
            font-family: 'IBM Plex Mono', monospace;
        }
        .qr-mini { width: 30px; height: 30px; border: 1px solid var(--line-strong); display: flex; align-items: center; justify-content: center; font-size: 15px; color: var(--ink-faint); }

        /* STAT STRIP */
        .stat-strip { border-top: 1.5px solid var(--line); border-bottom: 1.5px solid var(--line); background: var(--paper-2); }
        .stat-strip .wrap { display: grid; grid-template-columns: repeat(4, 1fr); padding: 26px 32px; }
        .stat-item { text-align: center; border-right: 1px dotted var(--line-strong); padding: 0 12px; }
        .stat-item:last-child { border-right: none; }
        .stat-item .val { font-family: 'Source Serif 4', serif; font-size: 24px; color: var(--navy); font-weight: 600; }
        .stat-item .lbl { font-size: 11.5px; color: var(--ink-faint); margin-top: 4px; font-family: 'IBM Plex Mono', monospace; }

        /* PROBLEM */
        .problem-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--line); border: 1px solid var(--line); margin-top: 44px; }
        .problem-card { background: var(--card); padding: 30px; }
        .problem-card .mono { font-size: 11px; color: var(--clay); display: block; margin-bottom: 14px; }
        .problem-card h3 { font-size: 17px; font-weight: 600; margin-bottom: 8px; }
        .problem-card p { font-size: 14px; color: var(--ink-muted); }

        /* HOW IT WORKS */
        .steps { display: flex; flex-direction: column; gap: 0; margin-top: 44px; border-left: 2px solid var(--line-strong); }
        .step { padding: 0 0 40px 32px; position: relative; }
        .step:last-child { padding-bottom: 0; }
        .step::before { content: ''; position: absolute; left: -6px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--paper); border: 2px solid var(--clay); }
        .step-num { font-family: 'IBM Plex Mono', monospace; font-size: 12px; color: var(--clay); margin-bottom: 8px; }
        .step h3 { font-size: 20px; font-weight: 600; margin-bottom: 8px; }
        .step p { font-size: 14.5px; color: var(--ink-muted); max-width: 500px; }

        /* FEATURES */
        .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--line); border: 1px solid var(--line); margin-top: 44px; }
        .feature-card { background: var(--card); padding: 30px; }
        .feature-card .mono { font-size: 10.5px; color: var(--teal); text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 14px; }
        .feature-card h3 { font-size: 16.5px; font-weight: 600; margin-bottom: 8px; }
        .feature-card p { font-size: 13.5px; color: var(--ink-muted); }

        /* SHOWCASE */
        .showcase { background: var(--paper-2); border-top: 1.5px solid var(--line); border-bottom: 1.5px solid var(--line); }
        .showcase-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .showcase h2 { font-size: 32px; margin-bottom: 18px; }
        .showcase p.lede { color: var(--ink-muted); font-size: 15.5px; margin-bottom: 26px; max-width: 440px; }
        .showcase-list { display: flex; flex-direction: column; gap: 14px; }
        .showcase-list li { display: flex; gap: 12px; align-items: flex-start; list-style: none; font-size: 14px; color: var(--ink-muted); }
        .showcase-list .check { font-family: 'IBM Plex Mono', monospace; color: var(--teal); flex-shrink: 0; }

        /* TRUST */
        .trust-band { text-align: center; }
        .trust-band .tab-label { display: inline-flex; }
        .trust-band h2 { font-size: 26px; max-width: 620px; margin: 0 auto 16px; font-weight: 600; }
        .trust-band p { color: var(--ink-muted); max-width: 540px; margin: 0 auto; font-size: 15px; }
        .trust-logos { display: flex; justify-content: center; gap: 14px; margin-top: 40px; flex-wrap: wrap; }
        .trust-logos span { font-family: 'IBM Plex Mono', monospace; font-size: 12px; color: var(--ink-muted); border: 1px solid var(--line-strong); padding: 9px 16px; background: var(--card); }

        /* CTA */
        .cta-banner { text-align: center; }
        .cta-banner h2 { font-size: 32px; margin-bottom: 14px; }
        .cta-banner p { color: var(--ink-muted); margin-bottom: 30px; }
        .cta-actions { display: flex; gap: 14px; justify-content: center; }

        /* FOOTER */
        footer { border-top: 1.5px solid var(--line); padding: 52px 0 30px; }
        .footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 44px; }
        .footer-brand p { color: var(--ink-faint); font-size: 13px; margin-top: 14px; max-width: 260px; }
        .footer-col h4 { font-family: 'IBM Plex Mono', monospace; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--ink-faint); margin-bottom: 15px; }
        .footer-col a { display: block; font-size: 14px; color: var(--ink-muted); margin-bottom: 9px; }
        .footer-col a:hover { color: var(--navy); }
        .footer-bottom { border-top: 1px dotted var(--line-strong); padding-top: 22px; display: flex; justify-content: space-between; font-size: 12px; color: var(--ink-faint); font-family: 'IBM Plex Mono', monospace; }

        @media (max-width: 860px) {
            .nav-links { display: none; }
            .hero-grid, .showcase-grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 34px; }
            .problem-grid, .feature-grid { grid-template-columns: 1fr; }
            .stat-strip .wrap { grid-template-columns: repeat(2, 1fr); gap: 18px 0; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<nav>
    <div class="wrap">
        <a href="/" class="logo">
            <span class="logo-mark">A</span>
            AutoTrack <span style="color:var(--ink-faint); font-weight:400;">ET</span>
        </a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How it works</a>
            <a href="#chart">The Chart</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="nav-cta">
            <a href="{{ route('login') }}" class="btn btn-ghost">Sign in</a>
            <a href="#contact" class="btn btn-primary">Request a demo</a>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="wrap hero-grid">
        <div>
            <div class="tab-label">Patient chart, for cars</div>
            <h1>Every vehicle deserves <em>a chart.</em></h1>
            <p class="lede">AutoTrack Ethiopia gives every car a permanent record — intake, diagnosis, treatment, history — the same way a hospital keeps a patient's chart.</p>
            <div class="hero-actions">
                <a href="#contact" class="btn btn-primary">Request a demo</a>
                <a href="#how-it-works" class="btn btn-ghost">See how it works</a>
            </div>
            <p class="hero-trust">Validated on-site with working garage owners in Addis Ababa before a single screen was built.</p>
        </div>

        <div class="chart-card">
            <div class="chart-tabs">
                <div class="chart-tab active">Chart</div>
                <div class="chart-tab">History</div>
                <div class="chart-tab">Invoices</div>
            </div>
            <div class="chart-head">
                <div>
                    <div class="plate">AA&nbsp;3-45678</div>
                    <div class="sub">Toyota Corolla · 2018 · Patient since Mar 2022</div>
                </div>
                <div class="chart-status">
                    <div class="val">80</div>
                    <div class="lbl">Vitals score</div>
                </div>
            </div>
            <div class="chart-body">
                <div class="vital-row">
                    <span class="vital-name">Engine</span>
                    <div class="vital-bar-track"><div class="vital-bar-fill" style="width:92%; background:var(--teal);"></div></div>
                    <span class="vital-val" style="color:var(--teal);">92</span>
                </div>
                <div class="vital-row">
                    <span class="vital-name">Brakes</span>
                    <div class="vital-bar-track"><div class="vital-bar-fill" style="width:61%; background:#B8863C;"></div></div>
                    <span class="vital-val" style="color:#8A6329;">61</span>
                </div>
                <div class="vital-row">
                    <span class="vital-name">Tires</span>
                    <div class="vital-bar-track"><div class="vital-bar-fill" style="width:55%; background:#B8863C;"></div></div>
                    <span class="vital-val" style="color:#8A6329;">55</span>
                </div>
                <div class="vital-row">
                    <span class="vital-name">AC system</span>
                    <div class="vital-bar-track"><div class="vital-bar-fill" style="width:38%; background:var(--clay);"></div></div>
                    <span class="vital-val" style="color:var(--clay);">38</span>
                </div>
            </div>
            <div class="chart-note">"AC compressor showing signs of failure. Recommend service before summer." — Hassan K., mechanic</div>
            <div class="chart-footer">
                <div class="qr-mini">▦</div>
                Scan the sticker to view this vehicle's full chart
            </div>
        </div>
    </div>
</section>

<div class="stat-strip">
    <div class="wrap">
        <div class="stat-item"><div class="val">3-in-1</div><div class="lbl">workshop · CRM · passport</div></div>
        <div class="stat-item"><div class="val">4</div><div class="lbl">languages supported</div></div>
        <div class="stat-item"><div class="val">0</div><div class="lbl">paper job cards</div></div>
        <div class="stat-item"><div class="val">ETB</div><div class="lbl">Telebirr &amp; CBE Birr ready</div></div>
    </div>
</div>

<section class="section">
    <div class="wrap">
        <div class="tab-label">The problem</div>
        <h2 style="font-size:32px; max-width:560px;">Most garages have no chart at all.</h2>
        <div class="problem-grid">
            <div class="problem-card">
                <span class="mono">01</span>
                <h3>No patient history</h3>
                <p>A car walks in and the mechanic starts from zero — no record of what was done last time, or why.</p>
            </div>
            <div class="problem-card">
                <span class="mono">02</span>
                <h3>No way to check in</h3>
                <p>Customers call repeatedly asking for a status update, because there's nothing for them to check themselves.</p>
            </div>
            <div class="problem-card">
                <span class="mono">03</span>
                <h3>No second opinion</h3>
                <p>A buyer or new garage has no way to verify a car's real condition or service record — it's just a claim.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="how-it-works" style="padding-top:0;">
    <div class="wrap">
        <div class="tab-label">How it works</div>
        <h2 style="font-size:32px; max-width:560px;">Intake, treatment, and a chart that never closes.</h2>
        <div class="steps">
            <div class="step">
                <div class="step-num">Intake</div>
                <h3>The vehicle checks in</h3>
                <p>Mileage, symptoms, and photos are logged the moment the car arrives — replacing the paper job card entirely.</p>
            </div>
            <div class="step">
                <div class="step-num">Treatment</div>
                <h3>The mechanic writes the chart</h3>
                <p>Services performed, parts used, and a full inspection checklist attach directly to this visit's record.</p>
            </div>
            <div class="step">
                <div class="step-num">Discharge</div>
                <h3>The chart updates itself</h3>
                <p>An invoice is generated automatically, and the vehicle's permanent chart reflects the new visit — for life.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="features">
    <div class="wrap">
        <div class="tab-label">Everything in one system</div>
        <h2 style="font-size:32px; max-width:560px;">Built for the whole practice, not just the desk.</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <span class="mono">Workshop</span>
                <h3>Digital job cards</h3>
                <p>Check-in, assign mechanics, track status from pending to delivered.</p>
            </div>
            <div class="feature-card">
                <span class="mono">Supply</span>
                <h3>Inventory &amp; parts</h3>
                <p>Real-time stock levels, low-stock alerts, and supplier price tracking.</p>
            </div>
            <div class="feature-card">
                <span class="mono">Billing</span>
                <h3>Invoicing &amp; payments</h3>
                <p>Auto-generated invoices with Telebirr, CBE Birr, and cash tracking.</p>
            </div>
            <div class="feature-card">
                <span class="mono">Record</span>
                <h3>Digital Vehicle Passport</h3>
                <p>A QR-linked, permanent chart — service records, health scores, ownership.</p>
            </div>
            <div class="feature-card">
                <span class="mono">Access</span>
                <h3>Customer portal</h3>
                <p>Owners track their own vehicles, reminders, and loyalty points online.</p>
            </div>
            <div class="feature-card">
                <span class="mono">Follow-up</span>
                <h3>SMS &amp; WhatsApp alerts</h3>
                <p>Automatic status updates and maintenance reminders customers actually read.</p>
            </div>
        </div>
    </div>
</section>

<section class="section showcase" id="chart">
    <div class="wrap showcase-grid">
        <div>
            <div class="tab-label">The differentiator</div>
            <h2>A chart should outlive the clinic that opened it.</h2>
            <p class="lede">Every vehicle registered in AutoTrack gets a permanent chart — accessible by QR code, for as long as the car exists, no matter which garage it visits next.</p>
            <ul class="showcase-list">
                <li><span class="check">→</span> Full treatment and parts history, timestamped and attributed to the mechanic</li>
                <li><span class="check">→</span> Odometer readings logged at every visit to flag tampering</li>
                <li><span class="check">→</span> Vitals per system — engine, brakes, tires, AC — scored on every visit</li>
                <li><span class="check">→</span> A shareable chart that increases resale trust and value</li>
            </ul>
        </div>
        <div>
            <div class="chart-card" style="max-width:400px; margin-left:auto;">
                <div class="chart-tabs">
                    <div class="chart-tab">Chart</div>
                    <div class="chart-tab active">History</div>
                </div>
                <div class="chart-head">
                    <div>
                        <div class="plate">AA&nbsp;1-77821</div>
                        <div class="sub">Toyota HiAce · 14 visits on chart</div>
                    </div>
                    <div class="chart-status">
                        <div class="val">71</div>
                        <div class="lbl">Vitals score</div>
                    </div>
                </div>
                <div class="chart-body" style="padding-bottom:8px;">
                    <div class="vital-row"><span class="vital-name">Engine</span><div class="vital-bar-track"><div class="vital-bar-fill" style="width:75%; background:#B8863C;"></div></div><span class="vital-val" style="color:#8A6329;">75</span></div>
                    <div class="vital-row"><span class="vital-name">Brakes</span><div class="vital-bar-track"><div class="vital-bar-fill" style="width:80%; background:var(--teal);"></div></div><span class="vital-val" style="color:var(--teal);">80</span></div>
                    <div class="vital-row"><span class="vital-name">Suspension</span><div class="vital-bar-track"><div class="vital-bar-fill" style="width:58%; background:#B8863C;"></div></div><span class="vital-val" style="color:#8A6329;">58</span></div>
                </div>
                <div class="chart-footer">
                    <div class="qr-mini">▦</div>
                    Last visit 18 Jun 2026 · AutoTrack Bole
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section trust-band">
    <div class="wrap">
        <div class="tab-label">Designed with the practice</div>
        <h2>Shaped directly by conversations with garage owners across Addis Ababa.</h2>
        <p>Before writing a line of code, the workflow, language, and payment methods in AutoTrack were validated on-site with working garages — not guessed at from a spreadsheet.</p>
        <div class="trust-logos">
            <span>ADDIS ABABA</span>
            <span>BOLE</span>
            <span>AMHARIC · OROMIFFA · TIGRINYA</span>
            <span>TELEBIRR · CBE BIRR</span>
        </div>
    </div>
</section>

<section class="section cta-banner" id="contact">
    <div class="wrap">
        <h2>Ready to open a chart for every car you see?</h2>
        <p>Request a walkthrough and we'll set up your first branch, staff accounts, and vehicle charts.</p>
        <div class="cta-actions">
            <a href="mailto:hello@autotrack.et" class="btn btn-primary">Request a demo</a>
            <a href="{{ route('login') }}" class="btn btn-ghost">Sign in to your garage</a>
        </div>
    </div>
</section>

<footer>
    <div class="wrap">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="logo"><span class="logo-mark">A</span> AutoTrack <span style="color:var(--ink-faint); font-weight:400;">ET</span></div>
                <p>Garage management, CRM, and Digital Vehicle Passport — built for the Ethiopian automotive service market.</p>
            </div>
            <div class="footer-col">
                <h4>Product</h4>
                <a href="#features">Features</a>
                <a href="#how-it-works">How it works</a>
                <a href="#chart">The Chart</a>
            </div>
            <div class="footer-col">
                <h4>Company</h4>
                <a href="#contact">Contact</a>
                <a href="{{ route('login') }}">Sign in</a>
            </div>
            <div class="footer-col">
                <h4>Get in touch</h4>
                <a href="mailto:hello@autotrack.et">hello@autotrack.et</a>
                <a href="tel:+251111234567">+251 11 123 4567</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} AutoTrack Ethiopia. All rights reserved.</span>
            <span>Addis Ababa, Ethiopia</span>
        </div>
    </div>
</footer>

</body>
</html>
