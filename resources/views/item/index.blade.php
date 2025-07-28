<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Daftar Barang - PLN UID Kalselteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        ::-webkit-scrollbar {width: 8px; height: 8px;}
        ::-webkit-scrollbar-track {background: #f1f1f1; border-radius: 10px;}
        ::-webkit-scrollbar-thumb {background: #fb923c; border-radius: 10px;}
        ::-webkit-scrollbar-thumb:hover {background: #f97316;}
        input::-webkit-search-cancel-button {-webkit-appearance: none;}
        .sidebar-link-active {background-color: #c2410c; font-weight: 600;}
        .table-container thead th {position: sticky; top: 0; z-index: 10; background-color: #f3f4f6;}
        .table-fixed-layout {table-layout: fixed;}
        *:focus-visible {outline: 2px solid #ea580c; outline-offset: 2px;}
        .empty-state-row td {padding: 2.5rem; text-align: center; color: #6b7280; font-size: 1.125rem;}
        .modal-item-grid {display: grid; grid-template-columns: auto 1fr; gap: 0.5rem 1rem;}
        .modal-item-grid dt {font-weight: 600; color: #4b5563;}
        .modal-item-grid dd {color: #1f2937; word-break: break-word;}
        .pagination-button {display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; margin: 0 0.125rem; font-size: 0.875rem; font-weight: 500; color: #374151; background-color: white; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: background-color 0.15s, color 0.15s, border-color 0.15s; cursor: pointer;}
        .pagination-button:hover:not([disabled]) {background-color: #f9fafb; color: #1f2937;}
        .pagination-button.active {background-color: #f97316; border-color: #f97316; color: white; font-weight: 600; cursor: default;}
        .pagination-button:disabled {opacity: 0.5; cursor: not-allowed;}
        .table-container-adaptive-height {max-height: 65vh;}
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">
<div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-b from-orange-400 to-orange-600 text-white flex flex-col py-8 px-6 shadow-lg min-h-screen fixed left-0 top-0 bottom-0 z-40">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 mb-10 select-none">
            <img src="{{ asset('img/logo_pln.png') }}" alt="Logo PLN" class="w-10 h-10 object-contain" onerror="this.onerror=null; this.src='https://placehold.co/40x40/FDBA74/FFFFFF?text=PLN';">
            <div>
                <h1 class="text-xl font-extrabold leading-tight">PLN</h1>
                <p class="text-xs font-semibold">UID KALSELTENG</p>
            </div>
        </a>
        <nav class="flex flex-col space-y-3 w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('dashboard*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-tachometer-alt text-lg" aria-hidden="true"></i><span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="{{ route('item.list') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('item.list*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-box text-lg" aria-hidden="true"></i><span class="text-sm font-medium">Daftar Barang</span>
            </a>
            <a href="{{ route('stock.list') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('stock.list*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-exchange-alt text-lg" aria-hidden="true"></i><span class="text-sm font-medium">Kontrol Stok</span>
            </a>
            <a href="{{ route('users.list') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-700/80 transition-colors duration-150 {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}">
                <i class="fas fa-users-cog text-lg" aria-hidden="true"></i><span class="text-sm font-medium">Manajemen Pengguna</span>
            </a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="w-full mt-auto">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded hover:bg-red-700/80 transition-colors duration-150 text-left">
                <i class="fas fa-sign-out-alt text-lg" aria-hidden="true"></i><span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </aside>

    <main class="flex-1 p-6 md:p-8 overflow-x-auto ml-64">
        <div class="w-full max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <h2 class="font-extrabold text-2xl md:text-3xl text-gray-800 select-none">Daftar Barang</h2>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-72 md:w-96">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400" aria-hidden="true"></i>
                        </span>
                        <input id="searchInput" type="search" placeholder="Cari barang (nama, tipe, spek, SN, ket, ref)..." aria-label="Cari barang"
                               class="w-full bg-white text-gray-700 placeholder-gray-500 text-sm rounded-full py-2.5 pl-10 pr-4 shadow-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"/>
                    </div>
                    <div class="relative">
                        <button id="sortToggle" aria-expanded="false" aria-controls="sortDropdown" class="flex items-center justify-center sm:justify-start w-full sm:w-auto space-x-2 border border-gray-300 rounded-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                            <i class="fas fa-sort-amount-down text-sm text-gray-500" aria-hidden="true"></i><span>Urutkan</span><i class="fas fa-chevron-down ml-1.5 text-xs text-gray-500" aria-hidden="true"></i>
                        </button>
                        <div id="sortDropdown" class="hidden absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-20">
                            <div class="p-4 space-y-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Urutkan berdasarkan:</p>
                                <div>
                                    <label for="sortSelect" class="sr-only">Kriteria Pengurutan</label>
                                    <select id="sortSelect" name="sort_criteria" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500">
                                        <option value="default" selected hidden>Default (ID)</option>
                                        <option value="name_asc">Nama Perangkat (A-Z)</option>
                                        <option value="name_desc">Nama Perangkat (Z-A)</option>
                                        <option value="volume_asc">Volume (Terendah ke Tertinggi)</option>
                                        <option value="volume_desc">Volume (Tertinggi ke Terendah)</option>
                                    </select>
                                </div>
                                <button type="button" id="applySort" class="w-full bg-orange-600 text-white rounded-md py-2 mt-3 font-semibold hover:bg-orange-700 transition text-sm">Terapkan Urutan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div role="status" aria-live="polite" class="mb-4 p-4 bg-green-50 text-green-700 border border-green-300 rounded-lg shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500" aria-hidden="true"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div role="alert" class="mb-4 p-4 bg-red-50 text-red-700 border border-red-300 rounded-lg shadow-sm">
                   <p class="font-semibold mb-1">Harap perbaiki kesalahan berikut:</p>
                   <ul class="list-disc list-inside text-sm">
                       @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                       @endforeach
                   </ul>
                </div>
            @endif
             @if ($errors->any() && !session('success') && !session('error'))
                <div role="alert" class="mb-4 p-4 bg-red-50 text-red-700 border border-red-300 rounded-lg shadow-sm">
                    <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-auto table-container table-container-adaptive-height">
                <table class="w-full text-left text-sm text-gray-700 table-fixed-layout" id="itemsTable">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600 text-center" style="width: 5%;">No.</th>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600" style="width: 30%;">Nama Perangkat</th>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600" style="width: 15%;">Tipe</th>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600 text-right" style="width: 10%;">Volume</th>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600" style="width: 10%;">Satuan</th>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600 text-right" style="width: 15%;">Serial Number</th>
                            <th scope="col" class="py-3 px-4 font-semibold text-gray-600 text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $initiallyEmpty = $items->isEmpty(); @endphp
                        @forelse($items as $item)
                        <tr class="border-b border-gray-100 hover:bg-orange-50 transition-colors duration-150"
                            data-id="{{ $item->itemid }}" data-nama="{{ $item->nama_perangkat }}" data-tipe="{{ $item->type }}"
                            data-spesifikasi="{{ $item->spesifikasi }}" data-volume="{{ $item->volume ?? 0 }}" data-satuan="{{ $item->satuan ?? '' }}"
                            data-serialnumber="{{ $item->serialnumber ?? '' }}" data-keterangan="{{ $item->keterangan ?? '' }}"
                            data-referensi="{{ $item->referensi ?? '' }}" data-created_at="{{ $item->created_at->toIso8601String() }}">
                            <td class="py-3 px-4 font-medium text-gray-500 text-center tabular-nums"></td>
                            <td class="py-3 px-4 text-gray-800 font-medium truncate">{{ $item->nama_perangkat }}</td>
                            <td class="py-3 px-4 truncate">{{ $item->type ?: '-' }}</td>
                            <td class="py-3 px-4 text-right tabular-nums @if(($item->volume ?? 0) <= 0) text-red-600 font-semibold @elseif(($item->volume ?? 0) < 5 && ($item->volume ?? 0) > 0) text-yellow-600 @endif">{{ $item->volume ?? 0 }}</td>
                            <td class="py-3 px-4 truncate">{{ $item->satuan ?? '-' }}</td>
                            <td class="py-3 px-4 text-right tabular-nums truncate">{{ $item->serialnumber ?: '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <button class="detail-button text-orange-600 hover:text-orange-800 font-medium text-xs py-1 px-2 rounded-md border border-orange-500 hover:bg-orange-100 transition-colors duration-150" aria-label="Lihat Detail">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="noItemsRow" class="empty-state-row"><td colspan="7">Tidak ada barang ditemukan di database.</td></tr>
                        @endforelse
                        <tr id="noMatchingItemsRow" class="empty-state-row" style="display: none;"><td colspan="7">Tidak ada barang yang cocok dengan kriteria Anda.</td></tr>
                    </tbody>
                </table>
            </div>
            <div id="paginationContainer" class="flex flex-col sm:flex-row items-center justify-between mt-4 px-2">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3 sm:mb-0">
                    <input type="checkbox" id="showAllCheckbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                    <label for="showAllCheckbox">Tampilkan Semua</label>
                </div>
                <div id="paginationControls" class="flex items-center"></div>
            </div>
        </div>
    </main>
</div>

<div id="itemDetailModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center hidden z-50 p-4" aria-labelledby="modalTitle" role="dialog" aria-modal="true">
    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl w-full max-w-xl mx-auto transform transition-all duration-300 ease-out scale-95 opacity-0" id="modalContent">
        <div class="flex justify-between items-center mb-6 pb-3 border-b border-gray-200">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900" id="modalItemName"></h3>
            <button id="closeModalButton" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl sm:text-3xl" aria-label="Tutup modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-3 text-sm sm:text-base modal-item-grid max-h-[60vh] overflow-y-auto pr-2">
            <dt>Tipe:</dt> <dd id="modalItemTipe">-</dd>
            <dt>Spesifikasi:</dt> <dd id="modalItemSpesifikasi">-</dd>
            <dt>Volume:</dt> <dd id="modalItemVolume">-</dd>
            <dt>Satuan:</dt> <dd id="modalItemSatuan">-</dd>
            <dt>Serial Number:</dt> <dd id="modalItemSN">-</dd>
            <dt>Keterangan:</dt> <dd id="modalItemKeterangan">-</dd>
            <dt>Referensi:</dt> <dd id="modalItemReferensi">-</dd>
            <dt>Ditambahkan:</dt> <dd id="modalItemCreatedAt">-</dd>
            <template id="lastStockOutTemplate">
                <dt class="col-span-2 text-sm font-bold text-orange-700 pt-2 border-t border-gray-200 mt-2">Detail Stok Terakhir Keluar:</dt>
                <dt>Tanggal Keluar:</dt> <dd id="modalLastStockOutDate" class="text-red-600 font-semibold">-</dd>
                <dt>Pengambil:</dt> <dd id="modalLastStockOutRecipient" class="text-red-600 font-semibold">-</dd>
                <dt>Keterangan:</dt> <dd id="modalLastStockOutKeterangan" class="text-red-600 font-semibold">-</dd>
            </template>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-200 text-right">
            <button id="modalOkButton" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">OK</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const initiallyEmpty = {{ $initiallyEmpty ? 'true' : 'false' }};
    let allTableRows = [];
    let filteredAndSortedRows = [];
    let currentPage = 1;
    let rowsPerPage = 10;
    let showAll = false;

    const searchInput = document.getElementById('searchInput');
    const sortToggleBtn = document.getElementById('sortToggle');
    const sortDropdown = document.getElementById('sortDropdown');
    const sortSelect = document.getElementById('sortSelect');
    const applySortBtn = document.getElementById('applySort');
    const itemsTableBody = document.querySelector('#itemsTable tbody');
    const noItemsRow = document.getElementById('noItemsRow');
    const noMatchingItemsRow = document.getElementById('noMatchingItemsRow');
    const paginationContainer = document.getElementById('paginationContainer');
    const paginationControls = document.getElementById('paginationControls');
    const showAllCheckbox = document.getElementById('showAllCheckbox');
    const showAllCheckboxContainer = showAllCheckbox.parentElement;
    
    const itemDetailModal = document.getElementById('itemDetailModal');
    const modalContent = document.getElementById('modalContent');
    const closeModalButton = document.getElementById('closeModalButton');
    const modalOkButton = document.getElementById('modalOkButton');

    const modalElements = {
        name: document.getElementById('modalItemName'),
        tipe: document.getElementById('modalItemTipe'),
        spesifikasi: document.getElementById('modalItemSpesifikasi'),
        volume: document.getElementById('modalItemVolume'),
        satuan: document.getElementById('modalItemSatuan'),
        sn: document.getElementById('modalItemSN'),
        keterangan: document.getElementById('modalItemKeterangan'),
        referensi: document.getElementById('modalItemReferensi'),
        createdAt: document.getElementById('modalItemCreatedAt'),
    };
    const lastStockOutTemplate = document.getElementById('lastStockOutTemplate');

    const debounce = (func, delay) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const storeInitialRows = () => {
        allTableRows = Array.from(itemsTableBody.querySelectorAll('tr[data-id]'));
    };
    
    const updateNoItemsMessage = (isFilteringOrSorting) => {
        const hasVisibleRows = filteredAndSortedRows.length > 0;
        if (noItemsRow) noItemsRow.style.display = 'none';
        if (noMatchingItemsRow) noMatchingItemsRow.style.display = 'none';

        if (!hasVisibleRows) {
            if (isFilteringOrSorting || !initiallyEmpty) {
                if (noMatchingItemsRow) noMatchingItemsRow.style.display = '';
            } else if (initiallyEmpty) {
                if (noItemsRow) noItemsRow.style.display = '';
            }
            paginationContainer.style.display = 'none';
        } else {
             paginationContainer.style.display = 'flex';
        }
    };

    const renderTablePage = () => {
        itemsTableBody.innerHTML = '';
        
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = showAll ? filteredAndSortedRows.length : startIndex + rowsPerPage;
        const pageRows = filteredAndSortedRows.slice(startIndex, endIndex);

        pageRows.forEach((row, index) => {
            const newRow = row.cloneNode(true);
            const firstCell = newRow.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = startIndex + index + 1;
            }
            itemsTableBody.appendChild(newRow);
        });

        const isFilteringOrSorting = searchInput.value.trim() !== '' || sortSelect.value !== 'default';
        updateNoItemsMessage(isFilteringOrSorting);
        renderPaginationControls();
    };

    const renderPaginationControls = () => {
        paginationControls.innerHTML = '';
        const totalItems = filteredAndSortedRows.length;

        if (totalItems === 0) {
            showAllCheckboxContainer.style.visibility = 'hidden';
            paginationContainer.classList.add('justify-center');
            return;
        }

        if (showAll || totalItems <= rowsPerPage) {
            showAllCheckboxContainer.style.visibility = totalItems > rowsPerPage ? 'visible' : 'hidden';
            if (totalItems <= rowsPerPage && !showAll) {
                 paginationContainer.classList.add('sm:justify-start');
                 paginationContainer.classList.remove('sm:justify-between');
                 if(totalItems <= rowsPerPage) {
                    showAllCheckboxContainer.style.visibility = 'hidden';
                 }
            } else {
                 paginationContainer.classList.add('sm:justify-start'); 
                 paginationContainer.classList.remove('sm:justify-between');
            }
            return;
        }
        
        showAllCheckboxContainer.style.visibility = 'visible';
        paginationContainer.classList.remove('sm:justify-start');
        paginationContainer.classList.add('sm:justify-between');


        const pageCount = Math.ceil(totalItems / rowsPerPage);

        const prevButton = document.createElement('button');
        prevButton.innerHTML = `<i class="fas fa-chevron-left"></i>`;
        prevButton.classList.add('pagination-button');
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTablePage();
            }
        });
        paginationControls.appendChild(prevButton);

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(pageCount, currentPage + 2);

        if (currentPage <= 3) {
            endPage = Math.min(pageCount, 5);
        }
        if (currentPage > pageCount - 3) {
            startPage = Math.max(1, pageCount - 4);
        }

        if (startPage > 1) {
            const firstPageButton = document.createElement('button');
            firstPageButton.textContent = '1';
            firstPageButton.classList.add('pagination-button');
            firstPageButton.addEventListener('click', () => { currentPage = 1; renderTablePage(); });
            paginationControls.appendChild(firstPageButton);
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'px-2 py-1 text-gray-500';
                ellipsis.textContent = '...';
                paginationControls.appendChild(ellipsis);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('pagination-button');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderTablePage();
            });
            paginationControls.appendChild(pageButton);
        }

        if (endPage < pageCount) {
            if (endPage < pageCount - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'px-2 py-1 text-gray-500';
                ellipsis.textContent = '...';
                paginationControls.appendChild(ellipsis);
            }
            const lastPageButton = document.createElement('button');
            lastPageButton.textContent = pageCount;
            lastPageButton.classList.add('pagination-button');
            lastPageButton.addEventListener('click', () => { currentPage = pageCount; renderTablePage(); });
            paginationControls.appendChild(lastPageButton);
        }


        const nextButton = document.createElement('button');
        nextButton.innerHTML = `<i class="fas fa-chevron-right"></i>`;
        nextButton.classList.add('pagination-button');
        nextButton.disabled = currentPage === pageCount;
        nextButton.addEventListener('click', () => {
            if (currentPage < pageCount) {
                currentPage++;
                renderTablePage();
            }
        });
        paginationControls.appendChild(nextButton);
    };

    const applySearchAndSort = () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const sortCriteria = sortSelect.value;
        
        filteredAndSortedRows = allTableRows.filter(row => {
            if (!searchTerm) return true;
            const searchableText = `${row.dataset.nama} ${row.dataset.tipe} ${row.dataset.spesifikasi} ${row.dataset.serialnumber} ${row.dataset.keterangan} ${row.dataset.referensi}`.toLowerCase();
            return searchableText.includes(searchTerm);
        });

        const sortFunctions = {
            name_asc: (a, b) => (a.dataset.nama || '').localeCompare(b.dataset.nama || ''),
            name_desc: (a, b) => (b.dataset.nama || '').localeCompare(a.dataset.nama || ''),
            volume_asc: (a, b) => parseFloat(a.dataset.volume || 0) - parseFloat(b.dataset.volume || 0),
            volume_desc: (a, b) => parseFloat(b.dataset.volume || 0) - parseFloat(a.dataset.volume || 0),
            default: (a, b) => parseInt(a.dataset.id || 0) - parseInt(b.dataset.id || 0),
        };

        if (sortFunctions[sortCriteria]) {
            filteredAndSortedRows.sort(sortFunctions[sortCriteria]);
        }
        
        currentPage = 1;
        renderTablePage();
    };

    const openItemDetailModal = async (rowData) => {
        if (!rowData) return;
        modalElements.name.textContent = rowData.nama || '-';
        modalElements.tipe.textContent = rowData.tipe || '-';
        modalElements.spesifikasi.textContent = rowData.spesifikasi || '-';
        modalElements.volume.textContent = rowData.volume || '0';
        modalElements.satuan.textContent = rowData.satuan || '-';
        modalElements.sn.textContent = rowData.serialnumber || '-';
        modalElements.keterangan.textContent = rowData.keterangan || '-';
        modalElements.referensi.textContent = rowData.referensi || '-';
        
        if (rowData.created_at) {
            const date = new Date(rowData.created_at);
            modalElements.createdAt.textContent = `${date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })} WITA`;
        } else {
            modalElements.createdAt.textContent = '-';
        }

        // Remove any existing last stock out details
        const existingLastStockOutDetails = itemDetailModal.querySelector('.last-stock-out-details');
        if (existingLastStockOutDetails) {
            existingLastStockOutDetails.remove();
        }

        // Fetch additional detail from API for last stock out if volume is 0
        if (parseInt(rowData.volume) === 0) {
            try {
                const response = await fetch(`/items/${rowData.id}`);
                const data = await response.json();
                if (data.last_stock_out) {
                    const clonedTemplate = lastStockOutTemplate.content.cloneNode(true);
                    clonedTemplate.querySelector('#modalLastStockOutDate').textContent = data.last_stock_out.date;
                    clonedTemplate.querySelector('#modalLastStockOutRecipient').textContent = data.last_stock_out.recipient;
                    clonedTemplate.querySelector('#modalLastStockOutKeterangan').textContent = data.last_stock_out.keterangan || '-';
                    const parentDiv = modalElements.createdAt.parentElement;
                    const container = document.createElement('div');
                    container.classList.add('modal-item-grid', 'last-stock-out-details');
                    container.appendChild(clonedTemplate);
                    parentDiv.appendChild(container);
                }
            } catch (error) {
                console.error('Error fetching last stock out details:', error);
            }
        }

        itemDetailModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
            modalOkButton.focus();
        });
        document.body.style.overflow = 'hidden';
    };

    const closeItemDetailModal = () => {
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            itemDetailModal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    };
    
    searchInput.addEventListener('input', debounce(applySearchAndSort, 300));
    
    sortToggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sortDropdown.classList.toggle('hidden');
        sortToggleBtn.setAttribute('aria-expanded', String(!sortDropdown.classList.contains('hidden')));
    });

    applySortBtn.addEventListener('click', () => {
        applySearchAndSort();
        sortDropdown.classList.add('hidden');
        sortToggleBtn.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('click', (e) => {
        if (!sortToggleBtn.contains(e.target) && !sortDropdown.contains(e.target)) {
            sortDropdown.classList.add('hidden');
            sortToggleBtn.setAttribute('aria-expanded', 'false');
        }
    });

    showAllCheckbox.addEventListener('change', () => {
        showAll = showAllCheckbox.checked;
        currentPage = 1;
        renderTablePage();
    });

    itemsTableBody.addEventListener('click', (event) => {
        const targetButton = event.target.closest('.detail-button');
        if (targetButton) {
            const row = targetButton.closest('tr');
            if (row && row.dataset.id) {
                openItemDetailModal(row.dataset);
            }
        }
    });

    closeModalButton.addEventListener('click', closeItemDetailModal);
    modalOkButton.addEventListener('click', closeItemDetailModal);
    itemDetailModal.addEventListener('click', (e) => e.target === itemDetailModal && closeItemDetailModal());
    document.addEventListener('keydown', (e) => e.key === 'Escape' && closeItemDetailModal());

    const highlightItemOnLoad = () => {
        const params = new URLSearchParams(window.location.search);
        const highlightItemId = params.get('highlight_item_id');
        if (!highlightItemId) return;

        const itemIndex = filteredAndSortedRows.findIndex(row => row.dataset.id === highlightItemId);
        if (itemIndex === -1) return;

        currentPage = showAll ? 1 : Math.floor(itemIndex / rowsPerPage) + 1;
        renderTablePage();

        setTimeout(() => {
            const itemRow = itemsTableBody.querySelector(`tr[data-id="${highlightItemId}"]`);
            if (itemRow) {
                itemRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                itemRow.classList.add('bg-yellow-100');
                setTimeout(() => itemRow.classList.remove('bg-yellow-100'), 3000);
            }
        }, 100);
    };

    storeInitialRows();
    applySearchAndSort();
    highlightItemOnLoad();
    sessionStorage.removeItem('activeStockTab');
});
</script>
</body>
</html>