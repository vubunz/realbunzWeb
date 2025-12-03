<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Countdown Tết Bính Ngọ 2026 | Lịch Vạn Niên</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0ea5e9", // sky
                        secondary: "#38bdf8",
                        dark: {
                            bg: "#0f172a",
                            card: "#1e293b",
                            text: "#f1f5f9",
                        },
                    },
                    fontFamily: {
                        sans: ["Inter", "system-ui", "sans-serif"],
                    },
                },
            },
        };
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- Custom Styles -->
    <link rel="stylesheet" href="/css/styles.css" />
</head>

<body
    class="min-h-screen font-sans bg-[#e0f2fe] dark:bg-slate-950 text-slate-800 dark:text-slate-100">
    <!-- ==================== HEADER ==================== -->
    <header
        class="sticky top-0 z-50 border-b border-[#93c5fd]/30 bg-[#e0f2fe]/90 dark:bg-slate-950/90 backdrop-blur">
        <div
            class="container mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="/#home" class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-sky-400 flex items-center justify-center shadow-md border border-white/60">
                    <span class="text-2xl">🧧</span>
                </div>
                <div class="leading-tight">
                    <p
                        class="text-lg md:text-xl font-extrabold bg-gradient-to-r from-blue-600 to-sky-500 bg-clip-text text-transparent">
                        Tết Bính Ngọ 2026
                    </p>
                    <p class="text-[11px] text-slate-500">
                        Countdown • Lịch vạn niên • Blog
                    </p>
                </div>
            </a>

            <nav
                class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-700 dark:text-slate-200">
                <a href="/#home" class="hover:text-blue-600 dark:hover:text-blue-400">Trang chủ</a>
                <a href="/#about" class="hover:text-blue-600 dark:hover:text-blue-400">About</a>
                <a href="/blog" class="hover:text-blue-600 dark:hover:text-blue-400">Blog</a>
                <a href="/contact" class="hover:text-blue-600 dark:hover:text-blue-400">Contact</a>
            </nav>

            <button
                id="themeToggle"
                class="p-2.5 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 shadow ml-auto"
                aria-label="Toggle theme">
                <svg
                    id="sunIcon"
                    class="w-5 h-5 hidden dark:block text-yellow-400"
                    fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        fill-rule="evenodd"
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        clip-rule="evenodd" />
                </svg>
                <svg
                    id="moonIcon"
                    class="w-5 h-5 block dark:hidden text-slate-700"
                    fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                        d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
            </button>
        </div>
    </header>