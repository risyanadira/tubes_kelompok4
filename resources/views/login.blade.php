<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Bakso</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#eef4ff] overflow-hidden relative">

    <!-- BACKGROUND -->
    <div class="absolute top-[-120px] left-[-120px] w-[400px] h-[400px] bg-blue-300/40 rounded-full blur-3xl"></div>

    <div class="absolute bottom-[-150px] right-[-100px] w-[400px] h-[400px] bg-cyan-200/50 rounded-full blur-3xl"></div>

    <div class="relative min-h-screen flex items-center justify-center px-5 py-10">

        <!-- CARD -->
        <div class="w-full max-w-6xl grid lg:grid-cols-2 bg-white/70 backdrop-blur-2xl rounded-[40px] overflow-hidden border border-white shadow-[0_25px_80px_rgba(59,130,246,0.18)]">

            <!-- LEFT -->
            <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-400 p-14 relative overflow-hidden">

                <!-- DECOR -->
                <div class="absolute top-[-100px] right-[-100px] w-80 h-80 bg-white/10 rounded-full"></div>

                <div class="absolute bottom-[-120px] left-[-100px] w-96 h-96 bg-cyan-300/20 rounded-full"></div>

                <div class="relative z-10">

                    <!-- LOGO -->
                    <div class="w-28 h-28 rounded-3xl bg-white flex items-center justify-center shadow-2xl overflow-hidden">

                        <img src="/logo.png">

                    </div>

                    <!-- TITLE -->
                    <h1 class="text-5xl font-bold text-white mt-10 leading-tight">
                        Kasir <br> Bakso
                    </h1>

                    <p class="text-blue-100 text-lg mt-6 leading-relaxed max-w-md">
                        Sistem kasir modern dengan tampilan elegan,
                        cepat, dan nyaman digunakan untuk membantu
                        operasional usaha setiap hari.
                    </p>

                </div>

                <!-- FOOTER -->
                <div class="relative z-10 text-sm text-blue-100">
                    © 2026 POS Kelompok 4
                </div>

            </div>

            <!-- RIGHT -->
            <div class="p-8 lg:p-16 flex flex-col justify-center">

                <!-- MOBILE -->
                <div class="lg:hidden text-center mb-10">

                    <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-blue-700 to-cyan-400 mx-auto flex items-center justify-center shadow-xl overflow-hidden">

                        <img 
                            src="{{ asset('logo.png') }}"
                            alt="Logo"
                            class="w-16 h-16 object-contain"
                        >

                    </div>

                    <h1 class="text-3xl font-bold text-gray-800 mt-5">
                        Kasir Bakso
                    </h1>

                </div>

                <!-- TEXT -->
                <div class="mb-10">

                    <h2 class="text-4xl font-bold text-gray-800">
                        Welcome Back 👋
                    </h2>

                    <p class="text-gray-500 mt-3 text-lg">
                        Silakan login untuk masuk ke dashboard kasir.
                    </p>

                </div>

                <!-- FORM -->
                <form action="{{ route('login') }}" method="POST">

                    @csrf

                    <!-- USERNAME -->
                    <div class="mb-6">

                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Email
                        </label>

                        <input
                            type="text"
                            name="email"
                            placeholder="Masukkan Email"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-gray-700 shadow-sm"
                        >

                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-8">

                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-gray-700 shadow-sm"
                        >

                    </div>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-400 text-white font-semibold text-lg shadow-xl hover:scale-[1.01] hover:shadow-2xl transition duration-300"
                    >
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>
</html>
