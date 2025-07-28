<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Dashboard - PLN UID Kalselteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #f97316; }
        input::-webkit-search-cancel-button { -webkit-appearance: none; }
        .sidebar-link-active { background-color: #c2410c; font-weight: 600; }
        .card { background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); display: flex; flex-direction: column; }
        .card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
        .card-title { font-size: 1.125rem; font-weight: 600; color: #374151; display: flex; align-items: center; }
        .card-body { padding: 0; flex-grow: 1; }
        .card-footer { padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; }
        .btn-primary { background-color: #f97316; color: white; padding: 0.625rem 1.25rem; border-radius: 0.375rem; font-weight: 500; text-align: center; transition: background-color 0.2s ease-in-out; display: inline-block; }
        .btn-primary:hover { background-color: #ea580c; }
        .btn-secondary { background-color: #f3f4f6; color: #374151; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background-color: #e5e7eb; color: #1f2937; }
        .table-hover tbody tr:hover { background-color: #fffbeb; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #fb923c; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-track { background-color: #fed7aa; }
        *:focus-visible { outline: 2px solid #ea580c; outline-offset: 2px; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">
<div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-b from-orange-400 to-orange-600 text-white flex flex-col py-8 px-6 shadow-lg min-h-screen fixed left-0 top-0 bottom-0 z-40">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 mb-10 select-none">
            <img src="{{ asset('img/logo_pln.png') }}" alt="Logo PLN" class="w-10 h-10 object-contain" />
            <div>
                <h1 class="text-xl font-extrabold leading-tight">PLN</h1>
                <p class="text-xs font-semibold">UID KALSELTENG</p>
            </div>
        </a>

        <nav class="flex flex-col space-y-3 w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('dashboard*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-tachometer-alt text-lg" aria-hidden="true"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="{{ route('item.list') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('item.list*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-box text-lg" aria-hidden="true"></i>
                <span class="text-sm font-medium">Daftar Barang</span>
            </a>
            <a href="{{ route('stock.list') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('stock.list*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-exchange-alt text-lg" aria-hidden="true"></i>
                <span class="text-sm font-medium">Kontrol Stok</span>
            </a>
            <a href="{{ route('users.list') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-users-cog text-lg" aria-hidden="true"></i>
                <span class="text-sm font-medium">Manajemen Pengguna</span>
            </a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="w-full mt-auto">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded hover:bg-red-700/80 transition-colors duration-150 text-left">
                <i class="fas fa-sign-out-alt text-lg" aria-hidden="true"></i>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </aside>
    
    <main class="flex-1 p-6 sm:p-8 md:p-10 overflow-x-hidden ml-64">
        <div class="w-full">
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h2 class="font-bold text-2xl sm:text-3xl text-gray-800 select-none">Dashboard</h2>
                    @if(isset($dashboardLastRefreshed))
                    <p class="text-xs text-gray-500 mt-1">
                        Terakhir disegarkan: {{ $dashboardLastRefreshed->format('d M Y, H:i:s') }}
                    </p>
                    @endif
                </div>
                <div class="w-full sm:w-auto mt-4 sm:mt-0">
                    <div class="relative">
                        <label for="dashboardSearchInput" class="sr-only">Cari kartu dashboard</label>
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400" aria-hidden="true"></i>
                        </span>
                        <input id="dashboardSearchInput" type="search" placeholder="Cari barang, stok..."
                               aria-label="Cari di semua kartu dashboard"
                               class="w-full sm:w-80 bg-white text-gray-700 placeholder-gray-500 text-sm rounded-full py-2.5 pl-10 pr-4 shadow-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"/>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div role="alert" class="mb-6 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg text-green-500" aria-hidden="true"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
                <div class="card xl:col-span-1" data-card-name="Aktivitas Barang Terbaru">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bolt text-yellow-500 mr-2.5" aria-hidden="true"></i>Aktivitas Barang Terbaru</h3>
                    </div>
                    <div class="card-body custom-scrollbar max-h-[350px] overflow-y-auto">
                        @if(isset($itemsByLatestActivity) && $itemsByLatestActivity->count() > 0)
                        <ul class="divide-y divide-gray-100">
                            @foreach($itemsByLatestActivity as $item)
                            <li class="p-4 hover:bg-orange-50 transition-colors item-activity-row">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-orange-600 truncate" title="{{ $item->nama_perangkat }}">{{ Str::limit($item->nama_perangkat, 30) }}</h4>
                                        <p class="text-xs text-gray-500">{{ $item->type ?: '-' }}</p>
                                    </div>
                                    <a href="{{ route('item.list', ['highlight_item_id' => $item->itemid]) }}" class="btn-secondary" aria-label="Lihat detail untuk {{ $item->nama_perangkat }}">
                                        Detail <i class="fas fa-arrow-right text-xs ml-1.5" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">
                                    Volume: {{ $item->volume }} {{ $item->satuan ?? '' }} <span class="mx-1">&bull;</span> Aktivitas terakhir: {{ $item->updated_at->diffForHumans() }}
                                </p>
                            </li>
                            @endforeach
                        </ul>
                        <p class="dashboard-card-message text-gray-500 text-center py-10 px-4" style="display: none;"></p>
                        @else
                        <p class="dashboard-card-message text-gray-500 text-center py-10 px-4" data-original-text="Tidak ada aktivitas barang untuk ditampilkan." data-initially-visible="true">Tidak ada aktivitas barang untuk ditampilkan.</p>
                        @endif
                    </div>
                    @if(isset($totalItemCount) && $totalItemCount > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('item.list') }}" class="btn-primary w-full sm:w-auto">
                            Lihat Semua {{ $totalItemCount }} Barang
                        </a>
                    </div>
                    @endif
                </div>

                <div class="card xl:col-span-1" data-card-name="Stok Masuk Terbaru">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-arrow-trend-up text-green-500 mr-2.5" aria-hidden="true"></i>Stok Masuk Terbaru</h3>
                    </div>
                    <div class="card-body custom-scrollbar max-h-[350px] overflow-y-auto">
                        @if(isset($recentStockIns) && $recentStockIns->count() > 0)
                        <table class="w-full text-sm text-left text-gray-700 table-hover stock-in-table">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentStockIns as $record)
                                <tr class="stock-movement-row">
                                    <td class="py-3 px-4 truncate" title="{{ $record->item->nama_perangkat ?? 'Barang tidak ditemukan' }}">{{ Str::limit($record->item->nama_perangkat ?? 'N/A', 25) }}</td>
                                    <td class="py-3 px-2 text-center font-semibold text-green-600 whitespace-nowrap">+{{ $record->volume }}</td>
                                    <td class="py-3 px-4 text-xs text-gray-500 text-right whitespace-nowrap">{{ $record->created_at->format('d M, H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="dashboard-card-message text-gray-500 text-center py-10 px-4" style="display: none;"></p>
                        @else
                        <p class="dashboard-card-message text-gray-500 text-center py-10 px-4" data-original-text="Tidak ada catatan stok masuk terbaru." data-initially-visible="true">Tidak ada catatan stok masuk terbaru.</p>
                        @endif
                    </div>
                    @if(isset($recentStockIns) && $recentStockIns->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('stock.list') }}?active_tab=stockIn" class="btn-primary w-full sm:w-auto" onclick="sessionStorage.setItem('activeStockControlTab', 'stockIn');">
                            Lihat Semua Stok Masuk
                        </a>
                    </div>
                    @endif
                </div>

                <div class="card xl:col-span-1" data-card-name="Stok Keluar Terbaru">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-arrow-trend-down text-red-500 mr-2.5" aria-hidden="true"></i>Stok Keluar Terbaru</h3>
                    </div>
                    <div class="card-body custom-scrollbar max-h-[350px] overflow-y-auto">
                        @if(isset($recentStockOuts) && $recentStockOuts->count() > 0)
                        <table class="w-full text-sm text-left text-gray-700 table-hover stock-out-table">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentStockOuts as $record)
                                <tr class="stock-movement-row">
                                    <td class="py-3 px-4 truncate" title="{{ $record->item->nama_perangkat ?? 'Barang tidak ditemukan' }}">{{ Str::limit($record->item->nama_perangkat ?? 'N/A', 25) }}</td>
                                    <td class="py-3 px-2 text-center font-semibold text-red-600 whitespace-nowrap">-{{ $record->volume }}</td>
                                    <td class="py-3 px-4 text-xs text-gray-500 text-right whitespace-nowrap">{{ $record->created_at->format('d M, H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="dashboard-card-message text-gray-500 text-center py-10 px-4" style="display: none;"></p>
                        @else
                        <p class="dashboard-card-message text-gray-500 text-center py-10 px-4" data-original-text="Tidak ada catatan stok keluar terbaru." data-initially-visible="true">Tidak ada catatan stok keluar terbaru.</p>
                        @endif
                    </div>
                    @if(isset($recentStockOuts) && $recentStockOuts->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('stock.list') }}?active_tab=stockOut" class="btn-primary w-full sm:w-auto" onclick="sessionStorage.setItem('activeStockControlTab', 'stockOut');">
                            Lihat Semua Stok Keluar
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const searchInput = document.getElementById('dashboardSearchInput');

    const performSearch = () => {
        const filter = searchInput.value.toLowerCase().trim();
        
        document.querySelectorAll('.item-activity-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? 'list-item' : 'none';
        });

        document.querySelectorAll('.stock-movement-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? 'table-row' : 'none';
        });

        document.querySelectorAll('.card').forEach(card => {
            const messageP = card.querySelector('.dashboard-card-message');
            if (!messageP) return;

            if (messageP.dataset.originalText === undefined) {
                messageP.dataset.originalText = messageP.textContent.trim();
                messageP.dataset.initiallyVisible = (window.getComputedStyle(messageP).display !== 'none').toString();
            }
            
            let hasVisibleContent = false;
            const cardItems = card.querySelectorAll('.item-activity-row, .stock-movement-row');
            cardItems.forEach(item => {
                if (item.style.display !== 'none') {
                    hasVisibleContent = true;
                }
            });

            const cardHasDataStructure = card.querySelector('ul, table');
            const initiallyHadData = messageP.dataset.initiallyVisible === 'false' && cardItems.length > 0 ;
            const wasInitiallyEmpty = messageP.dataset.initiallyVisible === 'true' || cardItems.length === 0;


            if (filter) {
                if (hasVisibleContent) {
                    messageP.style.display = 'none';
                } else {
                    if (cardHasDataStructure && (initiallyHadData || cardItems.length > 0) ) {
                        messageP.textContent = 'Tidak ada catatan yang cocok dengan pencarian Anda.';
                        messageP.style.display = 'block';
                    } else {
                        messageP.textContent = messageP.dataset.originalText;
                        messageP.style.display = 'block';
                    }
                }
            } else {
                 if (initiallyHadData || hasVisibleContent) {
                    messageP.style.display = 'none';
                } else if (wasInitiallyEmpty) {
                    messageP.textContent = messageP.dataset.originalText;
                    messageP.style.display = 'block';
                } else { 
                    messageP.textContent = messageP.dataset.originalText;
                    messageP.style.display = 'block';
                }
            }
        });
    };

    if (searchInput) {
        searchInput.addEventListener('input', debounce(performSearch, 250));
        if (searchInput.value) {
            performSearch();
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        if (searchInput.value) {
             performSearch();
        }
    });
</script>
</body>
</html>