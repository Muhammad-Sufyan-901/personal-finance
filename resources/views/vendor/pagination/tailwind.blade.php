@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-1 mt-6">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Previous</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" aria-label="{{ __('pagination.previous') }}">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Previous</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span aria-disabled="true">
                    <span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $element }}</span>
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page">
                            <span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-900 dark:text-white bg-slate-50 dark:bg-[#111318] border border-slate-200 dark:border-slate-700/80 rounded-xl shadow-sm transition-all">{{ $page }}</span>
                        </span>
                    @else
                        <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-[#111318] rounded-xl transition-all" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors" aria-label="{{ __('pagination.next') }}">
                <span class="hidden sm:inline">Next</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        @else
            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-400 dark:text-slate-500 cursor-not-allowed transition-colors">
                <span class="hidden sm:inline">Next</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </span>
        @endif
        
    </nav>
    <script>
        // Ensure Lucide icons are rendered for pagination
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endif
