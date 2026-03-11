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

                    <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Rekap Transaksi Anda per {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}</h2>
                    
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
                        
                        <!-- Filters & Actions -->
                        <div class="mt-6 space-y-4">
                            <div class="flex flex-col md:flex-row gap-4 items-end">
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

                                <button type="button" onclick="resetFilters()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-[#ff5b73] hover:bg-[ef4a62] text-white dark:text-slate-300 px-5 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 shrink-0"></i>
                                    Reset
                                </button>
                            </div>
                            
                            <div class="flex flex-wrap gap-3 items-center pt-4 border-t border-slate-100 dark:border-slate-800/80">

                                <button type="button" onclick="openImportModal()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-transparent hover:bg-[#136daf] border border-[#136daf] text-[#136daf] hover:text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="upload" class="w-4 h-4 shrink-0"></i>
                                    Import (CSV)
                                </button>
                                <button type="button" onclick="exportTransactions()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-transparent hover:bg-[#136daf] border border-[#136daf] text-[#136daf] hover:text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="download" class="w-4 h-4 shrink-0"></i>
                                    Ekspor (CSV)
                                </button>
                                <div class="hidden sm:block flex-1"></div>
                                <button type="button" id="btn-bulk-delete" onclick="confirmBulkDelete()" class="hidden w-full sm:w-auto flex-1 sm:flex-none items-center justify-center gap-2 bg-[#ff5b73] hover:bg-[#ef4a62] text-white px-6 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="trash-2" class="w-4 h-4 shrink-0"></i>
                                    <span id="bulk-delete-count">Hapus Terpilih</span>
                                </button>
                                <button type="button" onclick="openAddDrawer()" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-[#1b85d6] hover:bg-[#136daf] text-white px-6 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                                    <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                                    Tambah Transaksi
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
                                        <th class="px-6 py-4 w-12 font-bold text-center">
                                            <input type="checkbox" id="select-all" onclick="toggleSelectAll()" class="w-4 h-4 rounded border-slate-300 text-[#136daf] focus:ring-[#136daf] cursor-pointer">
                                        </th>
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
                                        <td class="px-6 py-4 text-center">
                                            <input type="checkbox" value="{{ $transaction->id }}" class="row-checkbox w-4 h-4 rounded border-slate-300 text-[#136daf] focus:ring-[#136daf] cursor-pointer" onclick="updateBulkDeleteState()">
                                        </td>
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
                                                @if(!empty($transaction->proofs))
                                                <button type="button" onclick="openGallery({{ $transaction->id }}, {{ count($transaction->proofs) }})" class="w-8 h-8 flex items-center justify-center rounded bg-[#136daf] text-white hover:bg-[#136daf] transition-colors shadow-sm cursor-pointer" title="Lihat Galeri Bukti">
                                                    <i data-lucide="image" class="w-4 h-4"></i>
                                                </button>
                                                @else
                                                <div class="w-8 h-8 flex items-center justify-center rounded bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 shadow-sm opacity-50 cursor-not-allowed" title="Tidak ada bukti">
                                                    <i data-lucide="image-off" class="w-4 h-4"></i>
                                                </div>
                                                @endif
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
                                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
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
                <form id="transaction-form" class="space-y-5" onsubmit="submitTransaction(event)" enctype="multipart/form-data">
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

                        <!-- Proofs -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Bukti Transaksi <span class="text-[10px] font-normal text-slate-400">(Opsional)</span></label>
                            
                            <div class="mt-2 text-center" id="proof-dropzone-container">
                                <label for="transaction-proofs" id="proof-dropzone-label" class="relative flex flex-col items-center justify-center w-full py-6 border-2 border-slate-300 dark:border-slate-700/80 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 dark:bg-[#111318] dark:hover:bg-slate-800/50 transition-all group overflow-hidden">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 mb-3 mt-2 rounded-full bg-[#136daf]/10 text-[#136daf] flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        </div>
                                        <p class="mb-1 text-sm text-slate-600 dark:text-slate-300 font-semibold group-hover:text-[#136daf] transition-colors"><span class="text-[#136daf]">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Pilih hingga 5 gambar sekaligus (JPEG, PNG). Maks 2MB per gambar.</p>
                                    </div>
                                    <input type="file" id="transaction-proofs" name="proofs[]" multiple accept="image/*" class="hidden" onchange="previewImages(event)" />
                                </label>
                            </div>

                            <div id="image-previews" class="flex flex-wrap gap-3 mt-3 empty:hidden"></div>
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

    <!-- 8. Bulk Delete Confirmation Modal -->
    <div id="bulk-delete-modal-overlay" class="fixed inset-0 bg-slate-900/50 dark:bg-[#0b0c10]/80 backdrop-blur-sm z-60 hidden transition-opacity opacity-0" onclick="closeBulkDeleteModal()">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div id="bulk-delete-modal" class="bg-white dark:bg-[#1a1d24] rounded-2xl shadow-xl w-full max-w-sm transform transition-all scale-95 opacity-0 overflow-hidden" onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="p-6 pb-0 flex justify-between items-start">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Hapus Transaksi?</h3>
                    <button type="button" onclick="closeBulkDeleteModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
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
                    <button type="button" onclick="closeBulkDeleteModal()" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex items-center justify-center">
                        Batal
                    </button>
                    <button type="button" id="btn-submit-bulk-delete" onclick="submitBulkDelete()" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-[#ff4757] text-white hover:bg-[#ff6b81] transition-colors shadow-sm flex items-center justify-center min-w-20 disabled:opacity-50 disabled:cursor-not-allowed">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- 8. Import CSV Modal -->
    <div id="import-modal-overlay" class="fixed inset-0 bg-slate-900/50 dark:bg-[#0b0c10]/80 backdrop-blur-sm z-60 hidden transition-opacity opacity-0" onclick="closeImportModal()">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div id="import-modal" class="bg-white dark:bg-[#1a1d24] rounded-2xl shadow-xl w-full max-w-md transform transition-all scale-95 opacity-0 overflow-hidden" onclick="event.stopPropagation()">
                <!-- Header -->
                <div class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i data-lucide="upload-cloud" class="w-5 h-5 text-[#10b981]"></i>
                        Import Transaksi (CSV)
                    </h3>
                    <button type="button" onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <form id="import-form" onsubmit="submitImport(event)" enctype="multipart/form-data">
                        <div class="w-full flex justify-center">
                            <label for="import-file" id="dropzone-label" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 dark:border-slate-700 hover:border-[#10b981] dark:hover:border-[#10b981] border-dashed rounded-xl cursor-pointer bg-slate-50 dark:bg-[#111318] hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center select-none pointer-events-none">
                                    <i data-lucide="file-spreadsheet" class="w-10 h-10 mb-3 text-slate-400"></i>
                                    <p class="mb-2 text-sm text-slate-700 dark:text-slate-300 font-semibold"><span class="text-[#10b981]">Klik untuk pilih</span> atau drag & drop file</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Format: CSV atau TXT</p>
                                </div>
                                <input id="import-file" name="file" type="file" accept=".csv, .txt" class="hidden" onchange="handleFileSelect(event)" required />
                            </label>
                        </div>
                        <div id="selected-file-name" class="mt-3 text-sm text-[#10b981] font-medium text-center flex items-center justify-center gap-2">
                           <i data-lucide="check-circle" class="w-4 h-4"></i>
                           <span></span>
                        </div>
                        <div class="mt-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800/50">
                            <p class="text-xs text-amber-800 dark:text-amber-400 font-medium flex gap-2">
                                <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                                <span>Pastikan CSV memiliki header dan kolom berurutan: <strong>Tanggal, Deskripsi, Jenis, Nominal (Rp)</strong> seperti file ekspor.</span>
                            </p>
                        </div>
                    </form>
                </div>
                
                <!-- Footer -->
                <div class="p-6 pt-0 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm cursor-pointer">
                        Batal
                    </button>
                    <button id="btn-submit-import" type="submit" form="import-form" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-[#10b981] text-white hover:bg-[#059669] transition-colors shadow-sm cursor-pointer flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        Import Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 9. Gallery Side Drawer -->
    <div id="gallery-overlay" class="fixed inset-0 bg-slate-900/50 dark:bg-[#0b0c10]/80 backdrop-blur-sm z-60 hidden transition-opacity opacity-0" onclick="closeGallery()"></div>
    <div id="gallery-drawer" class="fixed inset-y-0 right-0 z-70 w-full md:w-3/4 max-w-2xl bg-slate-50 dark:bg-[#111318] shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#1a1d24] flex items-center justify-between shadow-sm z-10 shrink-0">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="images" class="w-5 h-5 text-[#136daf]"></i>
                    Galeri Bukti Transaksi
                </h2>
                <p id="gallery-count-text" class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Memuat...</p>
            </div>
            <button type="button" onclick="closeGallery()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors cursor-pointer">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Actions -->
        <div class="px-6 py-3 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-white/50 dark:bg-[#1a1d24]/50 backdrop-blur-sm z-10 sticky top-0 shrink-0">
            <span id="selected-count-text" class="text-sm font-medium text-slate-600 dark:text-slate-400 hidden">0 dipilih</span>
            <div class="flex justify-end gap-3 w-full sm:w-auto ml-auto">
                <button id="btn-download-selected" type="button" onclick="downloadSelected()" class="hidden flex-1 sm:flex-none items-center justify-center gap-2 bg-transparent border-2 border-[#136daf] hover:border-[#136daf] hover:bg-[#136daf] text-[#136daf] hover:text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Unduh Terpilih</span>
                </button>
                <a id="btn-download-all" href="#" class="flex flex-1 sm:flex-none items-center justify-center gap-2 bg-[#1b85d6] hover:bg-[#136daf] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-colors cursor-pointer">
                    <i data-lucide="download-cloud" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Unduh Semua (ZIP)</span>
                </a>
            </div>
        </div>

        <!-- Body / Grid -->
        <div class="p-6 overflow-y-auto flex-1 h-full bg-slate-50/50 dark:bg-[#111318]/50">
            <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 auto-rows-max">
                <!-- Images will be injected here via JS -->
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
                document.getElementById('image-previews').innerHTML = ''; // clear preview
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
                document.getElementById('image-previews').innerHTML = ''; // Clear previews on edit
                
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

        // Check for Session Flash Messages
        @if(session('success'))
            showToast('success', 'Berhasil', '{{ session('success') }}');
        @endif
        @if(session('error'))
            showToast('error', 'Gagal', '{{ session('error') }}');
        @endif

        // JS Logic for Image Previews
        function previewImages(event) {
            const container = document.getElementById('image-previews');
            container.innerHTML = ''; 
            
            const files = event.target.files;
            if (!files || files.length === 0) return;

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                
                const url = URL.createObjectURL(file);
                const imgWrap = document.createElement('div');
                imgWrap.className = 'relative w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm group';
                
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-full h-full object-cover';
                
                imgWrap.appendChild(img);
                container.appendChild(imgWrap);
            });
        }

        // Setup Drag and Drop for Proof Images
        document.addEventListener('DOMContentLoaded', () => {
            const proofDropzoneLabel = document.getElementById('proof-dropzone-label');
            const proofFileInput = document.getElementById('transaction-proofs');

            if (proofDropzoneLabel && proofFileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    proofDropzoneLabel.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    proofDropzoneLabel.addEventListener(eventName, () => {
                        proofDropzoneLabel.classList.add('border-[#136daf]', 'bg-[#136daf]/5', 'dark:bg-[#136daf]/10');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    proofDropzoneLabel.addEventListener(eventName, () => {
                        proofDropzoneLabel.classList.remove('border-[#136daf]', 'bg-[#136daf]/5', 'dark:bg-[#136daf]/10');
                    });
                });

                proofDropzoneLabel.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files && files.length > 0) {
                        proofFileInput.files = files;
                        previewImages({ target: { files: files } });
                    }
                });
            }
        });

        // Gallery Side Drawer Logic
        const galleryOverlay = document.getElementById('gallery-overlay');
        const galleryDrawer = document.getElementById('gallery-drawer');
        let currentGalleryTransactionId = null;

        function openGallery(transactionId, proofCount) {
            currentGalleryTransactionId = transactionId;
            galleryOverlay.classList.remove('hidden');
            
            document.getElementById('gallery-count-text').textContent = proofCount + ' gambar terlampir';
            document.getElementById('btn-download-all').href = `/transactions/${transactionId}/proofs/download-all`;
            
            const grid = document.getElementById('gallery-grid');
            grid.innerHTML = '';
            
            // Render loading boxes that fill with images
            for(let i = 0; i < proofCount; i++) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#1a1d24] shadow-sm aspect-square relative group isolate';
                
                const spinner = document.createElement('div');
                spinner.className = 'absolute inset-0 flex items-center justify-center bg-slate-50 dark:bg-[#111318] z-0';
                spinner.innerHTML = '<i data-lucide="loader-2" class="w-6 h-6 text-slate-400 animate-spin"></i>';
                imgContainer.appendChild(spinner);
                
                const imgUrl = `/transactions/${transactionId}/proof/${i}`;
                const img = document.createElement('img');
                img.src = imgUrl;
                img.className = 'w-full h-full object-contain relative z-10 opacity-0 transition-opacity duration-300';
                img.onload = () => { img.classList.remove('opacity-0'); };
                
                // Clicking opens full size in new tab
                const link = document.createElement('a');
                link.href = imgUrl;
                link.target = '_blank';
                link.className = 'absolute inset-0 z-20 flex items-center justify-center bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 cursor-pointer';
                link.innerHTML = '<i data-lucide="external-link" class="w-8 h-8 text-white"></i>';
                
                // Checkbox for selective download
                const checkboxLabel = document.createElement('label');
                checkboxLabel.className = 'absolute top-3 right-3 z-30 cursor-pointer bg-white/80 dark:bg-slate-900/80 rounded p-1.5 shadow-sm hover:bg-white dark:hover:bg-slate-900 transition-colors flex items-center justify-center';
                checkboxLabel.innerHTML = `<input type="checkbox" value="${i}" class="gallery-checkbox w-4 h-4 rounded border-slate-300 text-[#136daf] focus:ring-[#136daf] cursor-pointer" onclick="event.stopPropagation(); toggleSelectedDownload()">`;
                
                imgContainer.appendChild(img);
                imgContainer.appendChild(link);
                imgContainer.appendChild(checkboxLabel);
                grid.appendChild(imgContainer);
            }
            
            lucide.createIcons();
            toggleSelectedDownload(); // reset state

            setTimeout(() => {
                galleryOverlay.classList.remove('opacity-0');
                galleryDrawer.classList.remove('translate-x-full');
            }, 10);
        }

        function toggleSelectedDownload() {
            const checkboxes = document.querySelectorAll('.gallery-checkbox:checked');
            const btn = document.getElementById('btn-download-selected');
            const countText = document.getElementById('selected-count-text');
            
            if(checkboxes.length > 0) {
                btn.classList.remove('hidden');
                btn.classList.add('flex');
                countText.classList.remove('hidden');
                countText.textContent = checkboxes.length + ' dipilih';
            } else {
                btn.classList.remove('flex');
                btn.classList.add('hidden');
                countText.classList.add('hidden');
            }
        }

        function downloadSelected() {
            const checkboxes = document.querySelectorAll('.gallery-checkbox:checked');
            if(checkboxes.length === 0 || !currentGalleryTransactionId) return;
            
            const indices = Array.from(checkboxes).map(cb => cb.value).join(',');
            window.location.href = `/transactions/${currentGalleryTransactionId}/proofs/download-all?indices=${indices}`;
        }

        function closeGallery() {
            galleryOverlay.classList.add('opacity-0');
            galleryDrawer.classList.add('translate-x-full');
            setTimeout(() => {
                galleryOverlay.classList.add('hidden');
                document.getElementById('gallery-grid').innerHTML = ''; // free up memory
            }, 300);
        }

        // Import Logic
        const importModalOverlay = document.getElementById('import-modal-overlay');
        const importModal = document.getElementById('import-modal');
        const importFileInput = document.getElementById('import-file');
        const btnSubmitImport = document.getElementById('btn-submit-import');
        const selectedFileNameDiv = document.getElementById('selected-file-name');

        function openImportModal() {
            importFormReset();
            importModalOverlay.classList.remove('hidden');
            setTimeout(() => {
                importModalOverlay.classList.remove('opacity-0');
                importModal.classList.remove('opacity-0', 'scale-95');
                importModal.classList.add('scale-100');
            }, 10);
        }

        function closeImportModal() {
            importModalOverlay.classList.add('opacity-0');
            importModal.classList.remove('scale-100');
            importModal.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                importModalOverlay.classList.add('hidden');
            }, 300);
        }

        function importFormReset() {
            document.getElementById('import-form').reset();
            selectedFileNameDiv.classList.add('hidden');
            btnSubmitImport.disabled = true;
            btnSubmitImport.innerHTML = 'Import Data';
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if(file) {
                selectedFileNameDiv.classList.remove('hidden');
                selectedFileNameDiv.querySelector('span').textContent = file.name;
                btnSubmitImport.disabled = false;
            } else {
                selectedFileNameDiv.classList.add('hidden');
                btnSubmitImport.disabled = true;
            }
        }

        async function submitImport(event) {
            event.preventDefault();
            const file = importFileInput.files[0];
            if(!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            btnSubmitImport.disabled = true;
            btnSubmitImport.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengimpor...';
            lucide.createIcons();

            try {
                const response = await fetch('{{ route("transactions.import") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Import Berhasil', result.message);
                    closeImportModal();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('error', 'Import Gagal', result.message || 'Terjadi kesalahan saat mengimpor data.');
                    btnSubmitImport.disabled = false;
                    btnSubmitImport.innerHTML = 'Import Data';
                }
            } catch (error) {
                showToast('error', 'Error Sistem', 'Terjadi kesalahan sistem, coba lagi.');
                btnSubmitImport.disabled = false;
                btnSubmitImport.innerHTML = 'Import Data';
            }
        }

        // Drag and drop for modal wrapper
        const dropzoneLabel = document.getElementById('dropzone-label');
        if(dropzoneLabel) {
            dropzoneLabel.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzoneLabel.classList.add('border-[#10b981]', 'bg-[#10b981]/5', 'dark:bg-[#10b981]/10');
            });
            dropzoneLabel.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropzoneLabel.classList.remove('border-[#10b981]', 'bg-[#10b981]/5', 'dark:bg-[#10b981]/10');
            });
            dropzoneLabel.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzoneLabel.classList.remove('border-[#10b981]', 'bg-[#10b981]/5', 'dark:bg-[#10b981]/10');
                if(e.dataTransfer.files.length) {
                    importFileInput.files = e.dataTransfer.files;
                    handleFileSelect({target: {files: e.dataTransfer.files}});
                }
            });
        }

        // Bulk Delete Logic
        function toggleSelectAll() {
            const selectAllCheck = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAllCheck.checked;
            });
            updateBulkDeleteState();
        }

        function updateBulkDeleteState() {
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            const selectAllCheck = document.getElementById('select-all');
            const btnBulkDelete = document.getElementById('btn-bulk-delete');
            const countSpan = document.getElementById('bulk-delete-count');

            if (rowCheckboxes.length > 0) {
                selectAllCheck.checked = (checkedCount === rowCheckboxes.length);
            }

            if (checkedCount > 0) {
                btnBulkDelete.classList.remove('hidden');
                btnBulkDelete.classList.add('flex');
                countSpan.textContent = `Hapus Terpilih (${checkedCount})`;
            } else {
                btnBulkDelete.classList.remove('flex');
                btnBulkDelete.classList.add('hidden');
            }
        }

        function confirmBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if(checkedBoxes.length === 0) return;

            openBulkDeleteModal();
        }

        function openBulkDeleteModal() {
            const overlay = document.getElementById('bulk-delete-modal-overlay');
            const modal = document.getElementById('bulk-delete-modal');
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('scale-100');
            }, 10);
        }

        function closeBulkDeleteModal() {
            const overlay = document.getElementById('bulk-delete-modal-overlay');
            const modal = document.getElementById('bulk-delete-modal');
            overlay.classList.add('opacity-0');
            modal.classList.remove('scale-100');
            modal.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }

        async function submitBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if(checkedBoxes.length === 0) return;

            const ids = Array.from(checkedBoxes).map(cb => parseInt(cb.value));
            const btn = document.getElementById('btn-submit-bulk-delete');
            const originalText = 'Hapus';
            
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin mr-2"></i>Hapus...';
            lucide.createIcons();

            try {
                const response = await fetch('{{ route("transactions.bulkDelete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids: ids })
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Berhasil', result.message);
                    closeBulkDeleteModal();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast('error', 'Gagal', result.message || 'Gagal menghapus transaksi.');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                showToast('error', 'Error Sistem', 'Terjadi kesalahan sistem.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
</body>
</html>
