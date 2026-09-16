<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Student Portal') }}</title>
        <style>
            * { box-sizing: border-box; }
            html, body { margin: 0; height: 100%; }
            body {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(180deg, #0a6a52 0%, #0a5d47 100%);
                font-family: Arial, Helvetica, sans-serif;
                color: #fff;
            }
            .hero {
                width: min(100%, 1100px);
                padding: 32px 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .brand-wrap {
                display: flex;
                align-items: center;
                gap: 24px;
            }
            .brand-mark {
                width: 58px;
                height: 58px;
                border-radius: 50%;
                background: #f5c94b;
                color: #0d5d4a;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                font-weight: 700;
                box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
            }
            .title {
                margin: 0;
                font-weight: 700;
                font-size: clamp(2.5rem, 5vw, 5rem);
                line-height: 1;
                letter-spacing: -0.06em;
            }
            .subtitle {
                margin: 20px 0 0;
                max-width: 620px;
                font-size: clamp(1rem, 1.7vw, 1.4rem);
                line-height: 1.5;
                color: rgba(255,255,255,0.92);
            }
            .feature-list {
                list-style: none;
                padding: 0;
                margin: 24px 0 0;
                display: grid;
                gap: 14px;
                font-size: clamp(1rem, 1.5vw, 1.3rem);
                color: rgba(255,255,255,0.98);
            }
            .feature-list li {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            .dot {
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: #f5c94b;
                box-shadow: 0 0 0 4px rgba(245,201,75,0.2);
            }
            .login-btn {
                display: inline-block;
                margin-top: 28px;
                text-decoration: none;
                background: #f5c94b;
                color: #0c5d4b;
                font-weight: 700;
                padding: 12px 22px;
                border-radius: 10px;
                box-shadow: 0 8px 22px rgba(0,0,0,0.12);
            }
            @media (max-width: 700px) {
                .brand-wrap {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .hero {
                    padding: 28px 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="hero">
            <div class="brand-wrap">
                <div class="brand-mark">S</div>
                <div>
                    <h1 class="title">Student Portal</h1>
                    <p class="subtitle">Manage student records, review course offerings, and access academic tools in one place.</p>

                    <ul class="feature-list">
                        <li><span class="dot"></span>View courses and schedules</li>
                        <li><span class="dot"></span>Track enrollment and availability</li>
                        <li><span class="dot"></span>Access student portal reports</li>
                    </ul>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="login-btn">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="login-btn">Log in</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
