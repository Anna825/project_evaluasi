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
            <div class="w-12 h-12 shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/logo-polman.png') }}" alt="Logo Politeknik Manufaktur Bandung" class="w-full h-full object-contain">
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