@if ($paginator->hasPages())
    <div class="book-pagination-container my-4" aria-label="Lật trang sách">
        
        <!-- ========================================================
             1. KHUNG ĐIỀU HƯỚNG LẬT SÁCH (BOOK FLIPPER BAR)
             ======================================================== -->
        <div class="book-flipper-bar">
            
            {{-- Nút LẬT TRANG TRƯỚC (Previous Button) --}}
            @if ($paginator->onFirstPage())
                <span class="book-flip-btn book-flip-prev disabled" aria-disabled="true" title="Đang ở trang đầu tiên">
                    <span class="flip-icon">⮜</span>
                    <span class="flip-text">Trang trước</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="book-flip-btn book-flip-prev" 
                   rel="prev" 
                   data-flip="prev"
                   aria-label="Lật về trang trước">
                    <span class="flip-icon">⮜</span>
                    <span class="flip-text">Trang trước</span>
                </a>
            @endif

            {{-- Danh sách SỐ TRANG (Page Numbers) --}}
            <div class="book-page-list">
                @foreach ($elements as $element)
                    {{-- Dấu ba chấm phân tách --}}
                    @if (is_string($element))
                        <span class="book-page-item dots disabled" aria-disabled="true">
                            <span>···</span>
                        </span>
                    @endif

                    {{-- Mảng liên kết các trang --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="book-page-item active" 
                                      aria-current="page"
                                      title="Trang hiện tại: {{ $page }}">
                                    <span>{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="book-page-item" 
                                   data-flip="{{ $page > $paginator->currentPage() ? 'next' : 'prev' }}"
                                   data-page="{{ $page }}"
                                   aria-label="Lật đến trang {{ $page }}">
                                    <span>{{ $page }}</span>
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Nút LẬT TRANG SAU (Next Button) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="book-flip-btn book-flip-next" 
                   rel="next" 
                   data-flip="next"
                   aria-label="Lật sang trang sau">
                    <span class="flip-text">Trang sau</span>
                    <span class="flip-icon">⮞</span>
                </a>
            @else
                <span class="book-flip-btn book-flip-next disabled" aria-disabled="true" title="Đã đến trang cuối">
                    <span class="flip-text">Trang sau</span>
                    <span class="flip-icon">⮞</span>
                </span>
            @endif

        </div>

        <!-- ========================================================
             2. DÒNG THÔNG BÁO TRANG SÁCH VINTAGE
             ======================================================== -->
        <div class="book-page-info">
            <span class="page-info-badge">
                📖 Trang <strong class="text-gold">{{ $paginator->currentPage() }}</strong> / {{ $paginator->lastPage() }}
            </span>
            <span class="page-info-sub">
                (Đang mở từ cuốn số {{ $paginator->firstItem() }} đến {{ $paginator->lastItem() }} trên kệ {{ $paginator->total() }} tác phẩm)
            </span>
        </div>

    </div>
@endif
