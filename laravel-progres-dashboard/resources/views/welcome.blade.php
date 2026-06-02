<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                color-scheme: light;
                --bg: #fbf8ff;
                --surface: rgba(255, 255, 255, 0.92);
                --surface-strong: #ffffff;
                --border: #e3e1ec;
                --text: #1a1b22;
                --text-soft: #504533;
                --primary: #7c5800;
                --primary-strong: #fbb717;
            }

            * { box-sizing: border-box; }
            html, body { margin: 0; min-height: 100%; }
            body {
                font-family: Inter, sans-serif;
                background:
                    radial-gradient(circle at top left, rgba(251, 183, 23, 0.16), transparent 28%),
                    linear-gradient(180deg, #fdfcff 0%, var(--bg) 100%);
                color: var(--text);
            }
            .shell {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 32px 20px;
            }
            .card {
                width: min(920px, 100%);
                display: grid;
                grid-template-columns: 1.2fr 0.8fr;
                gap: 20px;
                padding: 28px;
                border: 1px solid var(--border);
                border-radius: 24px;
                background: var(--surface);
                box-shadow: 0 24px 60px -40px rgb(26 27 34 / 0.45);
            }
            .eyebrow {
                display: inline-flex;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(251, 183, 23, 0.14);
                color: var(--primary);
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }
            h1 {
                margin: 14px 0 10px;
                font-family: Hanken Grotesk, sans-serif;
                font-size: clamp(2rem, 4vw, 3.5rem);
                line-height: 1;
                letter-spacing: -0.03em;
            }
            p {
                margin: 0;
                color: var(--text-soft);
                line-height: 1.75;
                max-width: 58ch;
            }
            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 24px;
            }
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 44px;
                padding: 0 16px;
                border-radius: 14px;
                border: 1px solid var(--border);
                background: var(--surface-strong);
                color: var(--text);
                text-decoration: none;
                font-weight: 600;
            }
            .btn--primary {
                background: linear-gradient(135deg, var(--primary-strong), #ffd36b);
                color: var(--primary);
                border-color: rgba(251, 183, 23, 0.4);
            }
            .panel {
                border-radius: 20px;
                padding: 20px;
                border: 1px solid var(--border);
                background:
                    radial-gradient(circle at top right, rgba(251, 183, 23, 0.2), transparent 30%),
                    linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(244, 242, 253, 0.96));
            }
            .metric {
                padding: 16px;
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(227, 225, 236, 0.95);
                margin-bottom: 12px;
            }
            .metric span {
                display: block;
                font-size: 12px;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: rgba(80, 69, 51, 0.7);
            }
            .metric strong {
                display: block;
                margin-top: 8px;
                font-size: 20px;
                font-family: Hanken Grotesk, sans-serif;
            }
            @media (max-width: 840px) {
                .card { grid-template-columns: 1fr; }
            }
        </style>
    </head>
    <body>
        <div class="shell">
            <div class="card">
                <div>
                    <div class="eyebrow">Light Mode Only</div>
                    <h1>Dashboard Progres</h1>
                    <p>
                        Aplikasi ini sudah dikunci ke tampilan terang agar konsisten dengan referensi desain.
                        Silakan masuk ke panel admin untuk melihat dashboard utama.
                    </p>
                    <div class="actions">
                        <a class="btn btn--primary" href="{{ url('/admin') }}">Buka Admin</a>
                        <a class="btn" href="{{ url('/admin/login') }}">Login</a>
                    </div>
                </div>

                <div class="panel">
                    <div class="metric">
                        <span>Theme</span>
                        <strong>Light only</strong>
                    </div>
                    <div class="metric">
                        <span>Panel</span>
                        <strong>Filament Admin</strong>
                    </div>
                    <div class="metric">
                        <span>Mode gelap</span>
                        <strong>Dinonaktifkan</strong>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
