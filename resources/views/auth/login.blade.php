<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Financial Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex antialiased font-sans bg-white text-gray-900">

    <div class="flex min-h-screen w-full">
        <!-- Left Side: Gradient -->
        <div class="hidden lg:flex w-[60%] flex-col relative px-16 py-12 justify-center text-white overflow-hidden bg-linear-to-br from-[#136daf] from-60% to-[#fde047]">
            
            <!-- Logo Section -->
            <div class="absolute top-12 left-16 flex items-center space-x-3">
                <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="text-xl font-bold tracking-tight drop-shadow-md">Sistem Manajemen Keuangan</span>
            </div>

            <!-- Content Section -->
            <div class="relative z-10 w-full max-w-2xl mt-8">
                <!-- Badge -->
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-8 border border-white/20 shadow-sm">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#fde047]"></div>
                    <span class="text-xs font-semibold tracking-wide">Sistem Manajemen Keuangan Pribadi Anda</span>
                </div>

                <!-- Headline -->
                <h1 class="text-[64px] leading-[1.05] font-extrabold tracking-tight mb-8">
                    Pantau Arus Kas<br>
                    Wujudkan Impian<br>
                    <span class="text-[#fde047]">Mencapai Kebebasan Finansial.</span>
                </h1>

                <!-- Slogan Subheadline -->
                <p class="text-[17px] font-medium text-white/90 leading-relaxed max-w-md mb-8">
                    Atur keuangan, lacak pengeluaran, dan capai tujuan finansial Anda dengan mudah.
                </p>
            </div>

            <!-- Footer Section -->
            <div class="absolute bottom-12 left-16 text-white/50 text-xs font-medium font-mono tracking-wide">
                © 2026 Sistem Manajemen Keuangan Pribadi Anda.
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-[40%] flex items-center justify-center p-8 lg:p-12 xl:p-20 bg-white">
            <div class="w-full max-w-[420px]">
                <!-- Header -->
                <div class="mb-10">
                    <h2 class="text-[32px] font-extrabold text-slate-900 mb-2 tracking-tight">Selamat Datang</h2>
                    <p class="text-slate-500 text-[15px] font-medium">Silahkan masukkan detail akun Anda untuk mengakses sistem.</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 text-red-600 text-sm p-4 rounded-xl mb-6 shadow-sm border border-red-100 font-medium">
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" 
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/20 focus:border-[#136daf] transition-all duration-200 shadow-sm placeholder:text-slate-400 text-slate-900 font-medium" required />
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" name="password" placeholder="••••••••" 
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/20 focus:border-[#136daf] transition-all duration-200 shadow-sm placeholder:text-slate-400 text-slate-900 font-medium" required />
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center space-x-2.5 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" name="remember" class="peer appearance-none w-5 h-5 border border-slate-300 rounded-sm checked:bg-white checked:border-[#136daf] transition-all cursor-pointer focus:ring-2 focus:ring-[#136daf]/20 focus:outline-none">
                                <div class="absolute pointer-events-none outline-none opacity-0 peer-checked:opacity-100 w-4 h-4 rounded-sm bg-[#136daf]"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full flex items-center justify-center py-3 px-4 bg-linear-to-r from-[#136daf] via-[#136daf] to-[#3980b3] hover:to-[#136daf] text-white text-[15px] font-bold rounded-xl hover:opacity-90 transition-all duration-300 shadow-[0_8px_20px_rgba(19,109,175,0.25)] group">
                            Masuk ke Sistem
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>

                <p class="text-center text-sm text-slate-500 mt-8 font-medium">
                    Belum memiliki akun? <a href="{{ route('register') }}" class="text-[#136daf] font-bold hover:underline transition-colors">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>