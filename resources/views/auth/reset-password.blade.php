<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Reset Password | DySign</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/dyci1.jpg') }}">
    <style>
        :root {
            --navy: #101064;
            --gold: #D4A017;
            --ink: #131650;
            --muted: #69738c;
            --lavender: #e6e9f6;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: var(--ink); background: var(--lavender); }
        button, input { font: inherit; }
        a { color: inherit; }
        .page { display: grid; grid-template-columns: 53% 47%; min-height: 100vh; min-height: 100dvh; width: 100%; }
        .visual {
            position: relative; isolation: isolate; display: flex; flex-direction: column;
            justify-content: space-between; min-height: 100vh; min-height: 100dvh;
            overflow: hidden; padding: clamp(28px, 4vw, 64px) clamp(28px, 6.6vw, 116px) 34px;
            color: #fff; background: var(--navy);
        }
        .slides, .slide, .visual-overlay { position: absolute; inset: 0; }
        .slides { z-index: -3; }
        .slide { width: 100%; height: 100%; object-fit: cover; object-position: center; opacity: 0; transform: scale(1.035); transition: opacity 1.2s ease, transform 6.5s ease; }
        .slide.is-active { opacity: 1; transform: scale(1); }
        .visual-overlay {
            z-index: -2;
            background: linear-gradient(93deg, rgba(8,10,55,.94) 0%, rgba(16,16,100,.83) 56%, rgba(16,16,100,.58) 100%),
                        linear-gradient(to top, rgba(8,9,56,.66), transparent 45%);
        }
        .ring { position: absolute; z-index: -1; border: 1px solid rgba(255,255,255,.14); border-radius: 50%; pointer-events: none; }
        .ring-one { width: 520px; height: 520px; top: -220px; right: -160px; }
        .ring-two { width: 400px; height: 400px; top: -155px; right: -93px; }
        .school-brand { display: flex; align-items: center; gap: 13px; }
        .seal { display: grid; place-items: center; width: 52px; height: 52px; padding: 5px; border-radius: 50%; background: rgba(255,255,255,.97); flex-shrink: 0; }
        .seal img { width: 100%; height: 100%; object-fit: contain; }
        .brand-top { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .15em; line-height: 1.6; }
        .brand-bottom { color: #e8bc48; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .19em; margin-top: 2px; }
        .hero-copy { padding: 34px 0 55px; max-width: 720px; }
        .eyebrow { display: flex; align-items: center; gap: 12px; color: #f1c64f; font-size: 11px; font-weight: 800; letter-spacing: .25em; text-transform: uppercase; }
        .eyebrow::before { content: ''; width: 35px; height: 2px; background: #e9b62e; }
        .hero-copy h1 { font-family: Georgia, 'Times New Roman', serif; margin: 26px 0 26px; font-size: clamp(46px, 5vw, 86px); font-weight: 400; letter-spacing: -.045em; line-height: 1.08; }
        .hero-copy h1 em { color: #efc03a; font-weight: 400; }
        .hero-copy p { max-width: 460px; color: #e9eaff; font-size: clamp(14px, 1.08vw, 17px); line-height: 1.85; }
        .visual-footer { border-top: 1px solid rgba(255,255,255,.24); padding-top: 21px; display: flex; justify-content: space-between; align-items: end; gap: 15px; }
        .footer-label { font-size: 10px; letter-spacing: .24em; text-transform: uppercase; color: #efc03a; font-weight: 800; }
        .visual-footer p { font-size: 11px; color: #d6dafa; margin: 8px 0 0; }
        .slide-dots { display: flex; gap: 6px; }
        .slide-dot { width: 6px; height: 6px; border-radius: 9px; background: rgba(255,255,255,.46); transition: width .25s, background .25s; }
        .slide-dot.is-active { width: 24px; background: #efc03a; }
        .form-side { display: flex; flex-direction: column; min-width: 0; min-height: 100vh; min-height: 100dvh; background: radial-gradient(circle at 20% 8%, rgba(255,255,255,.52), transparent 38%), var(--lavender); }
        .form-top { padding: 36px clamp(26px, 5vw, 70px) 12px; display: flex; justify-content: space-between; align-items: center; gap: 18px; }
        .home-link { color: #273060; text-decoration: none; font-size: 12px; font-weight: 700; }
        .home-link:hover { color: #a2760b; }
        .secure-label { display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: .15em; color: #596482; font-size: 10px; font-weight: 800; white-space: nowrap; }
        .secure-label svg { width: 14px; height: 14px; stroke: var(--gold); }
        .form-area { flex: 1; display: flex; justify-content: center; align-items: center; padding: 34px clamp(24px, 5vw, 62px) 52px; }
        .reset-card {
            width: 100%; max-width: 464px; padding: clamp(28px, 3vw, 45px);
            border: 1px solid rgba(255,255,255,.86); border-radius: 25px;
            background: rgba(255,255,255,.84); -webkit-backdrop-filter: blur(22px); backdrop-filter: blur(22px);
            box-shadow: 0 25px 65px rgba(16,22,79,.13);
        }
        .card-eyebrow { display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: .21em; color: #a2760b; font-size: 10px; font-weight: 800; }
        .card-eyebrow::before { content:''; width: 28px; height: 3px; border-radius: 4px; background: var(--gold); }
        .reset-card h2 { font-family: Georgia, 'Times New Roman', serif; font-size: clamp(37px, 3.3vw, 53px); line-height: 1.09; letter-spacing: -.04em; font-weight: 400; margin: 23px 0 14px; color: var(--navy); }
        .reset-card h2 span { color: var(--gold); }
        .description { margin: 0 0 28px; font-size: 13px; color: var(--muted); line-height: 1.8; }
        .field { margin-bottom: 18px; }
        .field label { display: block; margin-bottom: 9px; font-size: 12px; font-weight: 700; color: #252c55; }
        .input-wrap { position: relative; }
        .input-wrap > svg { position: absolute; left: 14px; top: 50%; width: 18px; height: 18px; transform: translateY(-50%); fill: none; stroke: #8690a7; stroke-width: 1.8; pointer-events: none; }
        .field input { display: block; width: 100%; min-height: 46px; padding: 12px 14px 12px 43px; border: 1px solid #d5dced; border-radius: 11px; outline: none; color: #17204b; background: #edf2fc; font-size: 13px; transition: background .2s, box-shadow .2s, border-color .2s; }
        .field input::placeholder { color: #929bb1; }
        .field input:focus { border-color: #101064; background: #fff; box-shadow: 0 0 0 4px rgba(16,16,100,.09); }
        .field input.has-error { border-color: #d05757; }
        .input-wrap.has-toggle input { padding-right: 52px; }
        .toggle-password { position: absolute; top: 50%; right: 9px; transform: translateY(-50%); border: 0; background: transparent; border-radius: 8px; color: #596887; padding: 8px; font-size: 11px; font-weight: 700; cursor: pointer; }
        .toggle-password:hover, .toggle-password:focus-visible { color: var(--navy); background: #e4eaf6; }
        .error-text { color: #b42330; font-size: 12px; line-height: 1.5; margin: 7px 0 0; }
        .error-banner { border: 1px solid #edbaba; border-radius: 10px; color: #8e2632; background: #fff2f2; padding: 12px 14px; font-size: 12px; line-height: 1.6; margin-bottom: 20px; }
        .status-banner { border: 1px solid #abdbbd; border-radius: 10px; color: #20633e; background: #ebf8f0; padding: 12px 14px; font-size: 12px; line-height: 1.6; margin-bottom: 20px; }
        .submit-button { width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 49px; border: 0; border-radius: 11px; color: #fff; background: var(--navy); font-size: 13px; font-weight: 800; cursor: pointer; margin-top: 5px; box-shadow: 0 12px 24px rgba(16,16,100,.17); transition: background .2s, transform .2s; }
        .submit-button:hover { background: #202080; transform: translateY(-1px); }
        .submit-button:focus-visible { outline: 3px solid var(--gold); outline-offset: 3px; }
        .back-login { margin-top: 25px; text-align: center; }
        .back-login a { text-decoration: none; color: var(--navy); font-size: 12px; font-weight: 800; }
        .back-login a:hover { text-decoration: underline; text-decoration-color: var(--gold); text-underline-offset: 4px; }
        .form-footer { border-top: 1px solid rgba(16,16,100,.1); padding: 17px clamp(26px, 4vw, 57px); display: flex; flex-wrap: wrap; justify-content: space-between; gap: 8px; color: #66728a; font-size: 10px; }
        @media (max-width: 980px) { .page { grid-template-columns: 45% 55%; } .visual { padding-left: 35px; padding-right: 30px; } .hero-copy h1 { font-size: clamp(42px, 5vw, 66px); } .brand-top { font-size: 9px; } }
        @media (max-width: 760px) { .page { display: block; } .visual { min-height: 210px; padding: 25px; } .hero-copy, .visual-footer, .ring { display: none; } .form-side { min-height: calc(100dvh - 210px); } .form-top { padding: 20px 24px 0; } .form-area { padding: 30px 20px; } .reset-card { max-width: 500px; } }
        @media (max-width: 420px) { .reset-card { padding: 26px 22px; } .secure-label { font-size: 9px; } .form-top { gap: 8px; } .form-footer { justify-content: center; text-align: center; } }
        @media (prefers-reduced-motion: reduce) { .slide, .submit-button { transition: none; } }
    </style>
</head>
<body>
    <main class="page">
        <section class="visual" aria-label="Dr. Yanga's Colleges campus">
            <div class="slides" aria-hidden="true">
                <img class="slide is-active" src="{{ asset('images/dyci1.jpg') }}" alt="" fetchpriority="high">
                <img class="slide" src="{{ asset('images/dyci2.jpg') }}" alt="" loading="lazy">
                <img class="slide" src="{{ asset('images/dyci3.jpg') }}" alt="" loading="lazy">
                <img class="slide" src="{{ asset('images/dyci4.jpg') }}" alt="" loading="lazy">
                <img class="slide" src="{{ asset('images/dyci5.jpg') }}" alt="" loading="lazy">
            </div>
            <div class="visual-overlay" aria-hidden="true"></div>
            <div class="ring ring-one" aria-hidden="true"></div>
            <div class="ring ring-two" aria-hidden="true"></div>
            <div class="school-brand">
                <div class="seal"><img src="{{ asset('images/logo.png') }}" alt="DYCI logo"></div>
                <div>
                    <div class="brand-top">Dr. Yanga's Colleges, Inc.</div>
                    <div class="brand-bottom">Elida Campus · Bulacan</div>
                </div>
            </div>
            <div class="hero-copy">
                <div class="eyebrow">A more connected campus</div>
                <h1>Every event.<br>Every presence.<br><em>Every DYCIan.</em></h1>
                <p>One digital space for student event attendance, participation, and meaningful campus experiences.</p>
            </div>
            <div class="visual-footer">
                <div><div class="footer-label">DySign</div><p>Digital Identity &amp; Event Participation Management</p></div>
                <div class="slide-dots" aria-hidden="true">
                    <span class="slide-dot is-active"></span><span class="slide-dot"></span><span class="slide-dot"></span><span class="slide-dot"></span><span class="slide-dot"></span>
                </div>
            </div>
        </section>
        <section class="form-side" aria-label="Reset your DySign password">
            <div class="form-top">
                <a class="home-link" href="{{ route('landing') }}">← Back to Home</a>
                <span class="secure-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                    Secure account access
                </span>
            </div>
            <div class="form-area">
                <div class="reset-card">
                    <div class="card-eyebrow">DySign · Account recovery</div>
                    <h2>Set a new<br>password<span>.</span></h2>
                    <p class="description">Create a strong password to securely access your DySign account.</p>
                    @if (session('status'))
                        <div class="status-banner" role="status">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="error-banner" role="alert">Please review the highlighted fields and try again.</div>
                    @endif
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="field">
                            <label for="email">Email address</label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username" inputmode="email" class="{{ $errors->has('email') ? 'has-error' : '' }}" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                            </div>
                            @error('email') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label for="password">New password</label>
                            <div class="input-wrap has-toggle">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                                <input id="password" type="password" name="password" required autocomplete="new-password" minlength="8" placeholder="Enter your new password" class="{{ $errors->has('password') ? 'has-error' : '' }}" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                                <button type="button" class="toggle-password" data-target="password" aria-label="Show new password" aria-pressed="false">Show</button>
                            </div>
                            @error('password') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="field">
                            <label for="password_confirmation">Confirm new password</label>
                            <div class="input-wrap has-toggle">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" minlength="8" placeholder="Re-enter your password">
                                <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password confirmation" aria-pressed="false">Show</button>
                            </div>
                            @error('password_confirmation') <p class="error-text" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="submit-button">Reset Password <span aria-hidden="true">→</span></button>
                    </form>
                    <div class="back-login"><a href="{{ route('login') }}">← Back to Sign In</a></div>
                </div>
            </div>
            <footer class="form-footer"><span>© {{ date('Y') }} DySign · Dr. Yanga's Colleges, Inc.</span><span>Elida Campus</span></footer>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = Array.from(document.querySelectorAll('.slide'));
            const dots = Array.from(document.querySelectorAll('.slide-dot'));
            let active = 0;
            if (slides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                setInterval(() => {
                    if (document.hidden) return;
                    slides[active].classList.remove('is-active');
                    dots[active].classList.remove('is-active');
                    active = (active + 1) % slides.length;
                    slides[active].classList.add('is-active');
                    dots[active].classList.add('is-active');
                }, 5000);
            }
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', () => {
                    const field = document.getElementById(button.dataset.target);
                    if (!field) return;
                    const reveal = field.type === 'password';
                    field.type = reveal ? 'text' : 'password';
                    button.textContent = reveal ? 'Hide' : 'Show';
                    button.setAttribute('aria-pressed', String(reveal));
                    button.setAttribute('aria-label', (reveal ? 'Hide' : 'Show') + ' ' + (button.dataset.target === 'password' ? 'new password' : 'password confirmation'));
                });
            });
        });
    </script>
</body>
</html>
