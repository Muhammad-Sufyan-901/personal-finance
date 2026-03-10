<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Sistem Manajemen Keuangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Check local storage for theme setup
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.remove('light');
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        }
    </script>
    <style>
        .dark .style-color-scheme-dark { color-scheme: dark; }
        .sidebar-collapsed .sidebar-text, .sidebar-collapsed .sidebar-header { display: none; opacity: 0; }
        .sidebar-collapsed { width: 4.5rem; }
        .sidebar-collapsed .sidebar-brand { justify-content: center; padding-left: 0; padding-right: 0;}
        .sidebar-collapsed .sidebar-nav { justify-content: center; }
        #sidebar { transition: width 0.3s ease; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#111318] text-slate-900 dark:text-slate-200 font-sans antialiased flex h-screen overflow-hidden transition-colors duration-200">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white dark:bg-[#1a1d24] border-r border-slate-200 dark:border-slate-800 flex-col justify-between hidden md:flex z-20 shadow-sm transition-all duration-300">
        <div>
            <!-- Branding -->
            <div class="h-16 flex items-center px-6 border-b border-slate-200 dark:border-slate-800 sidebar-brand">
                <div class="flex items-center gap-3 text-[#136daf] overflow-hidden whitespace-nowrap">
                    <div class="w-8 h-8 shrink-0 rounded-lg bg-[#136daf] text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="font-bold text-lg tracking-tight text-slate-800 dark:text-slate-100 sidebar-header">Keuangan <span class="text-[#136daf]">Ku</span></span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="px-3 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-nav flex items-center gap-3 px-3 py-2.5 bg-[#136daf] text-white rounded-lg shadow-sm font-medium transition-all group overflow-hidden whitespace-nowrap">
                    <i data-lucide="home" class="w-5 h-5 shrink-0"></i>
                    <span class="sidebar-text">Dashboard Transaksi</span>
                </a>
                
                <!-- <a href="#" class="sidebar-nav flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 rounded-lg font-medium transition-all group overflow-hidden whitespace-nowrap">
                    <i data-lucide="wallet" class="w-5 h-5 shrink-0 group-hover:text-[#136daf] dark:group-hover:text-[#4299e1]"></i>
                    <span class="sidebar-text">Transaksi</span>
                </a> -->
            </div>
        </div>

        <!-- Sidebar footer -->
        <div class="p-3 border-t border-slate-200 dark:border-slate-800">
            <button onclick="toggleSidebar()" class="sidebar-nav flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 rounded-lg font-medium w-full transition-colors overflow-hidden whitespace-nowrap">
                <i data-lucide="panel-left-close" id="collapse-icon" class="w-5 h-5 shrink-0"></i>
                <span class="text-sm sidebar-text">Collapse</span>
            </button>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- Navbar -->
        <header class="h-16 bg-white dark:bg-[#1a1d24] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between lg:justify-end px-6 lg:px-8 z-10 shrink-0 transition-colors duration-200">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 font-medium md:hidden lg:flex lg:mr-auto">
                 <i data-lucide="home" class="w-4 h-4"></i>
                 <i data-lucide="chevron-right" class="w-4 h-4"></i>
                 <span>Dashboard</span>
            </div>

            <div class="flex items-center gap-6 relative">
                <!-- Theme Toggler -->
                <button onclick="toggleTheme()" class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors">
                    <i data-lucide="moon" id="theme-icon" class="w-5 h-5"></i>
                </button>
                
                <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>

                <!-- Profile -->
                <div class="relative">
                    <button onclick="toggleProfileMenu()" class="flex items-center gap-3 text-left group">
                        <!-- In Laravel you might use auth()->user()->name -->
                        <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <i data-lucide="user" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-[14px] font-semibold text-slate-800 dark:text-slate-100 group-hover:text-[#136daf] dark:group-hover:text-[#4299e1] transition-colors leading-none mb-1">{{ Auth::user()->name }}</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 dark:text-slate-500 hidden md:block"></i>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div id="profile-menu" class="hidden absolute right-0 mt-3 w-[280px] bg-white dark:bg-[#1f2229] rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-2 z-50">
                        <div class="px-4 py-4 border-b border-slate-100 dark:border-slate-700/80 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <i data-lucide="user" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[15px] font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="px-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-white hover:bg-red-400 rounded-lg transition-colors">
                                    <i data-lucide="log-out" class="w-[18px] h-[18px]"></i>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content (Scrollable) -->
        <main class="flex-1 overflow-y-auto bg-slate-50/50 dark:bg-[#111318] p-6 lg:p-8 transition-colors duration-200">
            <div class="max-w-[1400px] mx-auto space-y-10">
                <!-- Cards Section -->
                <section>
                    <!-- Alert Notice -->
                    <div class="mb-8 bg-[#136daf]/10 border border-[#136daf]/30 dark:border-[#136daf]/40 rounded-xl p-4 flex gap-3 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-[#136daf]"></div>
                        <i data-lucide="info" class="w-5 h-5 text-[#136daf] shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <h3 class="font-bold text-[#136daf] text-[15px] mb-0.5 capitalize">Jangan lupa untuk memasukan data transaksi anda hari ini!</h3>
                            <p class="text-[14px] text-slate-600 dark:text-slate-300 leading-snug">Ingatlah untuk selalu mencatat pengeluaran dan pemasukan anda agar dapat memantau keuangan anda dengan baik.</p>
                        </div>
                        <div>
                            <button type="button" onclick="openAddDrawer()" class="cursor-pointer flex items-center justify-center gap-2 bg-[#1b85d6] hover:bg-[#136daf] text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors">
                                <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                                Tambah Transaksi
                            </button>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Rekap Transaksi Anda per Maret 2026</h2>
                    
                    <div id="recap-cards" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Balance -->
                        <div class="bg-linear-to-br from-[#1b6cb4] to-[#165a96] rounded-xl p-6 text-white shadow-sm relative overflow-hidden group">
                            <div class="relative z-10">
                                <h3 class="text-white/90 font-bold text-sm mb-1 text-shadow-sm">Total Saldo</h3>
                                <div class="text-4xl font-extrabold tracking-tight mt-1 flex items-baseline gap-2">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </div>
                                <div class="mt-2 text-xs font-semibold text-white/80">Semua Waktu</div>
                            </div>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none group-hover:scale-110 group-hover:opacity-30 transition-all duration-500">
                                <div class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center">
                                    <i data-lucide="wallet" class="w-8 h-8 shrink-0"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Income -->
                        <div class="bg-linear-to-br from-[#14da96] to-[#04c480] rounded-xl p-6 text-white shadow-sm relative overflow-hidden group">
                            <div class="relative z-10">
                                <h3 class="text-white/90 font-bold text-sm mb-1 text-shadow-sm">Total Pemasukan</h3>
                                <div class="text-4xl font-extrabold tracking-tight mt-1">Rp {{ number_format($income, 0, ',', '.') }}</div>
                                <div class="mt-2 text-xs font-semibold text-white/80">Berdasarkan Filter</div>
                            </div>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none group-hover:scale-110 group-hover:opacity-30 transition-all duration-500">
                                <div class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center">
                                    <i data-lucide="arrow-down-left" class="w-8 h-8 shrink-0"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Expense -->
                        <div class="bg-linear-to-br from-[#ff5b73] to-[#ef4a62] rounded-xl p-6 text-white shadow-sm relative overflow-hidden group">
                            <div class="relative z-10">
                                <h3 class="text-white/90 font-bold text-sm mb-1 text-shadow-sm">Total Pengeluaran</h3>
                                <div class="text-4xl font-extrabold tracking-tight mt-1">Rp {{ number_format($expense, 0, ',', '.') }}</div>
                                <div class="mt-2 text-xs font-semibold text-white/80">Berdasarkan Filter</div>
                            </div>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none group-hover:scale-110 group-hover:opacity-30 transition-all duration-500">
                                <div class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center">
                                     <i data-lucide="arrow-up-right" class="w-8 h-8 shrink-0"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Data Table Section -->
                <section class="bg-white dark:bg-[#1a1d24] rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors duration-200">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Data Transaksi</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Berikut adalah data transaksi anda</p>
                        
                        <!-- Filters -->
                        <div class="mt-6 flex flex-col md:flex-row gap-4 items-end">
                            <div class="flex-1 w-full space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Cari</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i data-lucide="search" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari deskripsi atau nama..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500" />
                                </div>
                            </div>
                            
                            <div class="w-full md:w-[170px] space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Dari Tanggal</label>
                                <input type="date" id="from-date" value="{{ request('from_date') }}" class="w-full px-3 py-2 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-700 dark:text-slate-300 style-color-scheme-dark" />
                            </div>

                            <div class="w-full md:w-[170px] space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Sampai Tanggal</label>
                                <input type="date" id="to-date" value="{{ request('to_date') }}" class="w-full px-3 py-2 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-700 dark:text-slate-300 style-color-scheme-dark" />
                            </div>

                            <div class="w-full md:w-[150px] space-y-1.5">
                                <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Jenis Transaksi</label>
                                <select id="type-select" class="w-full px-3 py-2 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all outline-none text-slate-700 dark:text-slate-300">
                                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Semua</option>
                                    <option value="pemasukan" {{ request('type') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="pengeluaran" {{ request('type') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                            </div>

                            <div class="flex gap-2.5 w-full md:w-auto mt-4 md:mt-0">
                                <button type="button" onclick="resetFilters()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-[#ff5b73] hover:bg-[#ef4a62] text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 shrink-0"></i>
                                    Reset
                                </button>
                                <button type="button" onclick="openAddDrawer()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-[#1b85d6] hover:bg-[#136daf] text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                                    Tambah Transaksi
                                </button>
                                <button type="button" onclick="exportTransactions()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-[#1b85d6] hover:bg-[#136daf] text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="download" class="w-4 h-4 shrink-0"></i>
                                    Ekspor
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div id="transaction-table-container">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-white dark:bg-[#1a1d24] text-slate-600 dark:text-slate-400 text-sm font-medium tracking-wide border-b border-slate-200 dark:border-slate-800/80">
                                        <th class="px-6 py-4 w-48 font-bold">Tanggal</th>
                                        <th class="px-6 py-4 font-bold">Deskripsi</th>
                                        <th class="px-6 py-4 w-32 font-bold">Nominal</th>
                                        <th class="px-6 py-4 w-32 font-bold">Status</th>
                                        <th class="px-6 py-4 w-48 font-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-sm text-slate-700 dark:text-slate-300 bg-white dark:bg-[#1a1d24]">
                                    @forelse($transactions as $transaction)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-[#111318]/50 transition-colors">
                                        <td class="px-6 py-4 font-medium whitespace-nowrap">{{ \Carbon\Carbon::parse($transaction->date)->locale('id')->translatedFormat('d F Y') }}</td>
                                        <td class="px-6 py-4 truncate max-w-sm" title="{{ $transaction->description }}">{{ $transaction->description }}</td>
                                        <td class="px-6 py-4 font-semibold whitespace-nowrap {{ $transaction->type === 'pemasukan' ? 'text-[#14da96]' : 'text-[#ff5b73]' }}">
                                            {{ $transaction->type === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($transaction->type === 'pemasukan')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-[#14da96] text-white shadow-sm">
                                                Pemasukan
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-[#ff5b73] text-white shadow-sm">
                                                Pengeluaran
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button class="w-8 h-8 flex items-center justify-center rounded bg-[#ff5b73] text-white hover:bg-[#ef4a62] transition-colors shadow-sm cursor-pointer" title="Hapus" onclick="deleteTransaction({{ $transaction->id }})">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                                <button class="w-8 h-8 flex items-center justify-center rounded bg-[#fbbf24] text-white hover:bg-[#f59e0b] transition-colors shadow-sm cursor-pointer" title="Edit" onclick="editTransaction({{ $transaction->id }})">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                            Tidak ada transaksi yang ditemukan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($transactions->hasPages())
                        <div id="pagination-container" class="p-6 border-t border-slate-100 dark:border-slate-800">
                            {{ $transactions->links() }}
                        </div>
                        @endif
                    </div>
                </section>

            </div>
        </main>
    </div>

    <!-- Drawer Overlay -->
    <div id="drawer-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300" onclick="closeDrawer()"></div>

    <!-- Drawer Content -->
    <div id="transaction-drawer" class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-[#1a1d24] rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 mx-auto w-full flex flex-col min-h-[75vh] md:min-h-[60vh] h-fit">
        <!-- Drag Handle -->
        <div class="flex justify-center p-3 shrink-0">
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
        </div>
        
        <div class="md:max-w-xl mx-auto">
            <!-- Drawer Header -->
            <div class="px-6 pb-4 border-b border-slate-100 dark:border-slate-800 shrink-0 flex items-center justify-between">
                <div>
                    <h2 id="drawer-title" class="text-xl font-bold text-slate-800 dark:text-white">Tambah Transaksi</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Masukkan detail transaksi baru Anda di bawah ini.</p>
                </div>
                <button type="button" onclick="closeDrawer()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Drawer Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <form id="transaction-form" class="space-y-5" onsubmit="submitTransaction(event)">
                    <input type="hidden" id="transaction_id" name="transaction_id" value="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Type -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center justify-center gap-2 p-3 border border-slate-200 dark:border-slate-700/80 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-[#111318]/50 transition-colors has-checked:border-[#14da96] has-checked:bg-[#14da96]/5 dark:has-checked:bg-[#14da96]/10">
                                    <input type="radio" id="type-pemasukan" name="type" value="pemasukan" class="sr-only" checked>
                                    <span class="text-sm font-semibold text-green-500">Pemasukan</span>
                                </label>
                                <label class="relative flex items-center justify-center gap-2 p-3 border border-slate-200 dark:border-slate-700/80 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-[#111318]/50 transition-colors has-checked:border-[#ff5b73] has-checked:bg-[#ff5b73]/5 dark:has-checked:bg-[#ff5b73]/10">
                                    <input type="radio" id="type-pengeluaran" name="type" value="pengeluaran" class="sr-only">
                                    <span class="text-sm font-semibold text-red-700">Pengeluaran</span>
                                </label>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Tanggal</label>
                            <input type="date" id="transaction-date" name="date" required class="w-full px-3 py-2.5 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-700 dark:text-slate-300 style-color-scheme-dark" />
                        </div>

                        <!-- Amount -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Nominal (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium text-sm">Rp</span>
                                </div>
                                <input type="number" id="transaction-amount" name="amount" placeholder="0" required min="0" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Deskripsi</label>
                            <textarea id="transaction-description" name="description" rows="3" placeholder="Contoh: Gaji bulanan, Beli makan siang..." required class="w-full px-3 py-3 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 resize-y"></textarea>
                        </div>

                        <!-- Proof -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Bukti Transaksi (Struk Belanja)</label>
                            <input type="file" id="transaction-proof" name="proof" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#136daf]/50 focus:border-[#136daf] transition-all text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500" />
                        </div>
                    </div>
                </form>
            </div>

            <!-- Drawer Footer -->
            <div class="p-6 border-t border-slate-100 dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-[#1a1d24] mt-auto">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" form="transaction-form" class="w-full flex items-center justify-center gap-2 bg-[#136daf] text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                        Submit
                    </button>
                    <button type="button" onclick="closeDrawer()" class="w-full flex items-center justify-center gap-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 px-6 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Delete Confirmation Modal -->
    <div id="delete-modal-overlay" class="fixed inset-0 bg-slate-900/50 dark:bg-[#0b0c10]/80 backdrop-blur-sm z-60 hidden transition-opacity opacity-0" onclick="closeDeleteModal()">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div id="delete-modal" class="bg-white dark:bg-[#1a1d24] rounded-2xl shadow-xl w-full max-w-sm transform transition-all scale-95 opacity-0 overflow-hidden" onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="p-6 pb-0 flex justify-between items-start">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Hapus Transaksi?</h3>
                    <button type="button" onclick="closeDeleteModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Apakah anda yakin ingin menghapus data ini?</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <!-- Modal Footer -->
                <div class="p-6 pt-0 flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="button" onclick="confirmDeleteTransaction()" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-[#ff4757] text-white hover:bg-[#ff6b81] transition-colors shadow-sm">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. Toast Notification -->
    <div id="toast-notification" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-md transform transition-all duration-300 -translate-y-8 opacity-0 pointer-events-none">
        <div id="toast-content" class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 shadow-lg flex gap-3 pointer-events-auto">
            <div id="toast-icon-container" class="shrink-0 mt-0.5 text-emerald-600">
                <i id="toast-icon" data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <div class="flex-1">
                <h4 id="toast-title" class="text-sm font-bold text-emerald-800">Title</h4>
                <p id="toast-message" class="text-sm text-emerald-700/80 mt-1">Message</p>
            </div>
            <button id="toast-close" onclick="hideToast()" class="shrink-0 text-emerald-600 hover:text-emerald-800 transition-colors pt-0.5">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <script>
        // Init Lucide immediately
        lucide.createIcons();

        // 1. Sidebar Collapse Logic
        const sidebar = document.getElementById('sidebar');
        const collapseIcon = document.getElementById('collapse-icon');
        let isCollapsed = false;

        function toggleSidebar() {
            isCollapsed = !isCollapsed;
            if (isCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                sidebar.classList.remove('w-64');
                collapseIcon.setAttribute('data-lucide', 'panel-right-close');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('w-64');
                collapseIcon.setAttribute('data-lucide', 'panel-left-close');
            }
            lucide.createIcons();
        }

        // 2. Dark Mode Toggle Logic
        const themeIcon = document.getElementById('theme-icon');
        const updateThemeIcon = () => {
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.setAttribute('data-lucide', 'sun');
            } else {
                themeIcon.setAttribute('data-lucide', 'moon');
            }
            lucide.createIcons();
        }
        
        // Initial setup for icon
        updateThemeIcon();

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcon();
        }

        // 3. Profile Dropdown Logic
        const profileMenu = document.getElementById('profile-menu');
        
        function toggleProfileMenu() {
            profileMenu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const profileBtn = e.target.closest('button[onclick="toggleProfileMenu()"]');
            const menu = e.target.closest('#profile-menu');
            
            if (!profileBtn && !menu && !profileMenu.classList.contains('hidden')) {
                profileMenu.classList.add('hidden');
            }
        });

        // 4. Drawer Logic
        const drawerOverlay = document.getElementById('drawer-overlay');
        const transactionDrawer = document.getElementById('transaction-drawer');

        function openAddDrawer() {
            document.getElementById('transaction-form').reset();
            document.getElementById('transaction_id').value = '';
            document.getElementById('drawer-title').textContent = 'Tambah Transaksi';
            
            openDrawer();
        }

        function openDrawer() {
            drawerOverlay.classList.remove('hidden');
            // slight delay to allow display block to apply before animating opacity
            setTimeout(() => {
                drawerOverlay.classList.remove('opacity-0');
                transactionDrawer.classList.remove('translate-y-full');
            }, 10);
        }

        function closeDrawer() {
            drawerOverlay.classList.add('opacity-0');
            transactionDrawer.classList.add('translate-y-full');
            setTimeout(() => {
                drawerOverlay.classList.add('hidden');
            }, 300);
        }

        // 5. Data Fetching & Filters Logic
        let fetchTimeout;
        const searchInput = document.getElementById('search-input');
        const fromDate = document.getElementById('from-date');
        const toDate = document.getElementById('to-date');
        const typeSelect = document.getElementById('type-select');

        function fetchTransactions(targetUrl = null) {
            let url = targetUrl;
            
            if (!url) {
                const params = new URLSearchParams({ 
                    search: searchInput.value, 
                    from_date: fromDate.value, 
                    to_date: toDate.value, 
                    type: typeSelect.value 
                });
                url = `{{ route('dashboard') }}?${params.toString()}`;
            }

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    document.getElementById('recap-cards').innerHTML = doc.getElementById('recap-cards').innerHTML;
                    document.getElementById('transaction-table-container').innerHTML = doc.getElementById('transaction-table-container').innerHTML;
                    
                    lucide.createIcons();
                });
        }

        function handleFilterChange(e) {
            clearTimeout(fetchTimeout);
            // Debounce for text input, immediate for others
            const delay = e.target.id === 'search-input' ? 500 : 0;
            fetchTimeout = setTimeout(() => fetchTransactions(), delay);
        }

        searchInput.addEventListener('input', handleFilterChange);
        fromDate.addEventListener('change', handleFilterChange);
        toDate.addEventListener('change', handleFilterChange);
        typeSelect.addEventListener('change', handleFilterChange);

        function resetFilters() {
            searchInput.value = '';
            fromDate.value = ''; 
            toDate.value = '';
            typeSelect.value = 'all';
            fetchTransactions();
        }

        // Handle AJAX Pagination
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('#pagination-container a, #pagination-container button');
            if(paginationLink && paginationLink.tagName.toLowerCase() === 'a') {
                e.preventDefault();
                fetchTransactions(paginationLink.href);
            }
        });
        // 6. Transaction CRUD Logic
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function submitTransaction(e) {
            e.preventDefault();
            const form = document.getElementById('transaction-form');
            const formData = new FormData(form);
            const transactionId = document.getElementById('transaction_id').value;
            
            let url = '/transactions';
            let method = 'POST';

            if (transactionId) {
                url = `/transactions/${transactionId}`;
                formData.append('_method', 'PUT'); // Laravel spoofing since the form itself is POST
            }

            fetch(url, {
                method: 'POST', // Use POST here, laravel catches the _method PUT in formData
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if(!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                closeDrawer();
                fetchTransactions(); // Refresh data
                // Reset form
                form.reset();
                document.getElementById('transaction_id').value = '';
                showToast('success', 'Berhasil', 'Transaksi berhasil disimpan!');
            })
            .catch(error => {
                console.error('Error submitting transaction:', error);
                showToast('error', 'Gagal', 'Terjadi kesalahan saat menyimpan transaksi.');
            });
        }

        function editTransaction(id) {
            fetch(`/transactions/${id}/edit`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('transaction_id').value = data.id;
                document.getElementById('transaction-date').value = data.date;
                document.getElementById('transaction-amount').value = data.amount;
                document.getElementById('transaction-description').value = data.description;
                
                if(data.type === 'pemasukan') {
                    document.getElementById('type-pemasukan').checked = true;
                } else {
                    document.getElementById('type-pengeluaran').checked = true;
                }
                
                document.getElementById('drawer-title').textContent = 'Edit Transaksi';
                
                openDrawer();
            })
            .catch(error => {
                console.error('Error fetching transaction details:', error);
                showToast('error', 'Gagal', 'Gagal mengambil data transaksi.');
            });
        }

        let transactionIdToDelete = null;
        const deleteModalOverlay = document.getElementById('delete-modal-overlay');
        const deleteModal = document.getElementById('delete-modal');

        function openDeleteModal(id) {
            transactionIdToDelete = id;
            deleteModalOverlay.classList.remove('hidden');
            
            // Animation
            setTimeout(() => {
                deleteModalOverlay.classList.remove('opacity-0');
                deleteModal.classList.remove('scale-95', 'opacity-0');
            }, 10);
        }

        function closeDeleteModal() {
            transactionIdToDelete = null;
            deleteModalOverlay.classList.add('opacity-0');
            deleteModal.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                deleteModalOverlay.classList.add('hidden');
            }, 300);
        }

        function deleteTransaction(id) {
            openDeleteModal(id);
        }

        function confirmDeleteTransaction() {
            if (!transactionIdToDelete) return;

            fetch(`/transactions/${transactionIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if(!response.ok) throw new Error('Failed to delete');
                return response.json();
            })
            .then(data => {
                closeDeleteModal();
                fetchTransactions(); // Refresh data
                showToast('success', 'Berhasil', 'Transaksi berhasil dihapus!');
            })
            .catch(error => {
                console.error('Error deleting transaction:', error);
                closeDeleteModal();
                showToast('error', 'Gagal', 'Gagal menghapus transaksi.');
            });
        }

        function exportTransactions() {
            const search = document.getElementById('search-input').value;
            const fromDate = document.getElementById('from-date').value;
            const toDate = document.getElementById('to-date').value;
            const type = document.getElementById('type-select').value;
            
            const params = new URLSearchParams();
            if(search) params.append('search', search);
            if(fromDate) params.append('from_date', fromDate);
            if(toDate) params.append('to_date', toDate);
            if(type) params.append('type', type);
            
            window.location.href = `/transactions/export?${params.toString()}`;
        }

        // Toast Notification Logic
        function showToast(type, title, message) {
            const toast = document.getElementById('toast-notification');
            const toastContent = document.getElementById('toast-content');
            const toastIconContainer = document.getElementById('toast-icon-container');
            const toastIcon = document.getElementById('toast-icon');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            const toastClose = document.getElementById('toast-close');

            // Reset classes
            toastContent.className = 'rounded-xl p-4 shadow-lg flex gap-3 pointer-events-auto border';
            toastIconContainer.className = 'shrink-0 mt-0.5';
            toastTitle.className = 'text-sm font-bold';
            toastMessage.className = 'text-sm mt-1';
            toastClose.className = 'shrink-0 transition-colors pt-0.5';

            if (type === 'success') {
                toastContent.classList.add('bg-[#f0fdf4]', 'border-[#bbf7d0]', 'dark:bg-emerald-950/40', 'dark:border-emerald-900/50');
                toastIconContainer.classList.add('text-emerald-600', 'dark:text-emerald-400');
                toastTitle.classList.add('text-emerald-800', 'dark:text-emerald-300');
                toastMessage.classList.add('text-emerald-700/80', 'dark:text-emerald-400/80');
                toastClose.classList.add('text-emerald-600', 'hover:text-emerald-800', 'dark:text-emerald-400', 'dark:hover:text-emerald-300');
                toastIcon.setAttribute('data-lucide', 'check-circle');
            } else {
                toastContent.classList.add('bg-[#fef2f2]', 'border-[#fecaca]', 'dark:bg-rose-950/40', 'dark:border-rose-900/50');
                toastIconContainer.classList.add('text-rose-600', 'dark:text-rose-400');
                toastTitle.classList.add('text-rose-800', 'dark:text-rose-300');
                toastMessage.classList.add('text-rose-700/80', 'dark:text-rose-400/80');
                toastClose.classList.add('text-rose-600', 'hover:text-rose-800', 'dark:text-rose-400', 'dark:hover:text-rose-300');
                toastIcon.setAttribute('data-lucide', 'alert-triangle');
            }

            toastTitle.textContent = title;
            toastMessage.textContent = message;
            lucide.createIcons();

            // Show animation
            toast.classList.remove('-translate-y-8', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            // Auto hide setup
            if (window.toastTimeout) clearTimeout(window.toastTimeout);
            window.toastTimeout = setTimeout(() => {
                hideToast();
            }, 5000);
        }

        function hideToast() {
            const toast = document.getElementById('toast-notification');
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('-translate-y-8', 'opacity-0');
        }
    </script>
</body>
</html>
