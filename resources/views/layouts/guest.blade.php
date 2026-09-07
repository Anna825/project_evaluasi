<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Evaluasi PBM')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --background: #f0f2f7;
            --foreground: #0f1c2e;
            --card: #ffffff;
            --primary: #1a3a5c;
            --secondary: #e8edf5;
            --muted-foreground: #5a6e8a;
            --accent: #b8952a;
            --border: #c8d3e6;
        }
        * { font-family: 'Outfit', sans-serif; }
        h1, h2, .font-serif-display { font-family: 'Fraunces', serif; }
        input:focus, select:focus { outline: none; box-shadow: 0 0 0 2px var(--primary); }
    </style>
</head>
<body style="background-color: var(--background); color: var(--foreground);">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--accent);">
                <svg width="20" height="20" viewBox="0 0 44 44" fill="none">
                    <path d="M22 4L38 14v8H6v-8L22 4z" fill="white" opacity="0.9" />
                    <rect x="8" y="22" width="4" height="14" fill="white" opacity="0.85" />
                    <rect x="20" y="22" width="4" height="14" fill="white" opacity="0.85" />
                    <rect x="32" y="22" width="4" height="14" fill="white" opacity="0.85" />
                    <rect x="4" y="36" width="36" height="3" rx="1.5" fill="white" opacity="0.9" />
                </svg>
            </div>
            <div>
                <p class="text-xs" style="color: var(--muted-foreground);">Polman Bandung</p>
                <p class="font-bold text-sm">SI EVALUASI PBM</p>
            </div>
        </div>

        <div class="w-full max-w-md rounded-2xl border p-8" style="background: var(--card); border-color: var(--border);">
            @yield('content')
        </div>
    </div>
</body>
</html>