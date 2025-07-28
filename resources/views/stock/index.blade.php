<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Kontrol Stok - PLN UID Kalselteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #f97316; }
        input::-webkit-search-cancel-button { -webkit-appearance: none; }
        .sidebar-link-active { background-color: #c2410c; font-weight: 600; }
        .tab-button-active { background-color: #fb923c; color: white; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06); }
        .tab-button-inactive { background-color: #e5e7eb; color: #374151; }
        .tab-button-inactive:hover { background-color: #d1d5db; }
        .table-sticky-header thead th { position: sticky; top: 0; z-index: 10; background-color: #f3f4f6; }
        *:focus-visible { outline: 2px solid #ea580c; outline-offset: 2px; }
        .empty-state-row td { padding: 2.5rem; text-align: center; color: #6b7280; font-size: 1.125rem; }
        .pagination-button { min-width: 32px; height: 32px; padding: 0 0.5rem; }
        .pagination-button.active { background-color: #fb923c; color: white; font-weight: 600; border-color: #fb923c; }
        .searchable-dropdown-item:hover { background-color: #f9fafb; }
        @media print {
          body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
          .no-print { display: none !important; }
          .table-sticky-header thead th { position: static; }
          .main-content-area { margin-left: 0 !important; }
          .table-container { height: auto !important; overflow: visible !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">
<div class="flex min-h-screen">
    <aside class="w-64 bg-gradient-to-b from-orange-400 to-orange-600 text-white flex flex-col py-8 px-6 shadow-lg min-h-screen fixed left-0 top-0 bottom-0 z-40 no-print">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 mb-10 select-none">
            <img src="{{ asset('img/logo_pln.png') }}" alt="Logo PLN" class="w-10 h-10 object-contain" />
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

    <main class="flex-1 p-6 md:p-8 overflow-x-auto ml-64 main-content-area">
        <div class="w-full max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 no-print">
                <h2 class="font-extrabold text-2xl md:text-3xl text-gray-800 select-none">Kontrol Stok</h2>
                <button id="openExportModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg px-5 py-2.5 shadow-md transition flex items-center justify-center sm:justify-start w-full sm:w-auto">
                    <i class="fas fa-file-export mr-2" aria-hidden="true"></i>Export Data
                </button>
            </div>

            <div class="mb-6 no-print">
                <div class="flex items-center justify-between mb-2">
                    <div role="tablist" aria-label="Jenis Catatan Stok" class="flex border border-gray-300 rounded-lg p-1 bg-gray-100 shadow-sm">
                        <button id="tabStockIn" role="tab" aria-selected="true" aria-controls="stockInTabPanel" class="flex-1 py-2 px-4 text-sm font-medium rounded-md focus:outline-none transition-colors duration-150 tab-button-active">Riwayat Stok Masuk</button>
                        <button id="tabStockOut" role="tab" aria-selected="false" aria-controls="stockOutTabPanel" class="flex-1 py-2 px-4 text-sm font-medium rounded-md focus:outline-none transition-colors duration-150 tab-button-inactive">Riwayat Stok Keluar</button>
                    </div>
                    <div class="relative w-full sm:w-auto md:w-80">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-search text-gray-400" aria-hidden="true"></i></span>
                        <input id="searchInput" type="search" placeholder="Cari riwayat..." aria-label="Cari catatan stok" class="w-full bg-white text-gray-700 placeholder-gray-500 text-sm rounded-full py-2.5 pl-10 pr-4 shadow-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"/>
                    </div>
                </div>
                {{-- Moved buttons below the tab switch --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto mt-4">
                    <button id="openNewItemModalBtn" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg px-5 py-2.5 shadow-md transition flex items-center justify-center sm:justify-start w-full sm:w-auto">
                        <i class="fas fa-plus mr-2" aria-hidden="true"></i>Tambah Barang Baru
                    </button>
                    <button id="openStockOutModalBtn" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg px-5 py-2.5 shadow-md transition flex items-center justify-center sm:justify-start w-full sm:w-auto">
                        <i class="fas fa-minus-circle mr-2"></i>Catat Barang Keluar
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div role="status" aria-live="polite" class="mb-4 p-4 bg-green-50 text-green-700 border border-green-300 rounded-lg shadow-sm flex items-center no-print"><i class="fas fa-check-circle mr-3 text-green-500" aria-hidden="true"></i>{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div role="status" aria-live="polite" class="mb-4 p-4 bg-blue-50 text-blue-700 border border-blue-300 rounded-lg shadow-sm flex items-center no-print"><i class="fas fa-info-circle mr-3 text-blue-500" aria-hidden="true"></i>{{ session('info') }}</div>
            @endif
            @if ($errors->any())
                <div role="alert" class="mb-4 p-4 bg-red-50 text-red-700 border border-red-300 rounded-lg shadow-sm no-print">
                    <p class="font-semibold mb-1">Harap perbaiki kesalahan berikut:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @php
                $items = $items ?? collect();
                $stockIns = $stockIns ?? collect();
                $stockOuts = $stockOuts ?? collect();
                $stockInsInitiallyEmpty = $stockIns->isEmpty();
                $stockOutsInitiallyEmpty = $stockOuts->isEmpty();
            @endphp

            <div id="stockInTabPanel" role="tabpanel" aria-labelledby="tabStockIn" class="bg-white rounded-xl shadow-xl border border-gray-200 flex flex-col table-container">
                <div class="overflow-auto table-sticky-header flex-grow h-[calc(100vh-450px)] sm:h-[calc(100vh-430px)]">
                    <table class="w-full table-fixed text-left text-sm text-gray-700" id="stockInTable">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-16 text-center">No.</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-3/12">Nama Barang</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-2/12">Tipe</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-3/12">Spesifikasi</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-32 text-right">Serial Number</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-24 text-right">Volume</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-3/12">Keterangan Stok</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-40 whitespace-nowrap">Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockIns as $index => $record)
                            <tr class="border-b border-gray-100 hover:bg-orange-50 transition-colors duration-150 stockin-row" data-nama="{{ $record->item->nama_perangkat ?? '' }}" data-tipe="{{ $record->item->type ?? '' }}" data-spesifikasi="{{ $record->item->spesifikasi ?? '' }}" data-serialnumber_item="{{ $record->item->serialnumber ?? '' }}" data-keterangan_stok="{{ $record->keterangan ?? '' }}" data-tanggal_sort="{{ $record->created_at->timestamp }}">
                                <td class="py-3 px-4 font-medium text-gray-500 text-center tabular-nums">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 text-gray-800 font-medium whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->item->nama_perangkat ?? 'N/A' }}">{{ $record->item->nama_perangkat ?? 'N/A' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->item->type ?? '-' }}">{{ $record->item->type ?? '-' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->item->spesifikasi ?? '' }}">{{ $record->item->spesifikasi ?? '-' }}</td>
                                <td class="py-3 px-4 text-right tabular-nums">{{ $record->item->serialnumber ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-semibold text-green-600 tabular-nums">+{{ $record->volume }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->keterangan }}">{{ $record->keterangan ?: '-' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap tabular-nums">{{ $record->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr class="no-records-row-in empty-state-row"><td colspan="8">Tidak ada catatan stok masuk ditemukan.</td></tr>
                            @endforelse
                            <tr class="no-matching-records-row-in empty-state-row" style="display: none;"><td colspan="8">Tidak ada catatan stok masuk yang cocok dengan pencarian Anda.</td></tr>
                        </tbody>
                    </table>
                </div>
                <div id="stockInPaginationContainer" class="p-3 border-t bg-gray-50 no-print"></div>
            </div>

            <div id="stockOutTabPanel" role="tabpanel" aria-labelledby="tabStockOut" class="bg-white rounded-xl shadow-xl border border-gray-200 hidden flex-col table-container">
                 <div class="overflow-auto table-sticky-header flex-grow h-[calc(100vh-450px)] sm:h-[calc(100vh-430px)]">
                    <table class="w-full table-fixed text-left text-sm text-gray-700" id="stockOutTable">
                        <thead class="bg-gray-100 border-b border-gray-300">
                           <tr>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-16 text-center">No.</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-4/12">Nama Barang</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-2/12">Tipe</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-3/12">Spesifikasi</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-24 text-right">Volume</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-3/12">Nama Pengambil</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-3/12">Keterangan Stok</th>
                                <th scope="col" class="py-3 px-4 font-semibold text-gray-600 w-40 whitespace-nowrap">Tanggal Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockOuts as $index => $record)
                            <tr class="border-b border-gray-100 hover:bg-orange-50 transition-colors duration-150 stockout-row" data-nama="{{ $record->item->nama_perangkat ?? '' }}" data-tipe="{{ $record->item->type ?? '' }}" data-spesifikasi="{{ $record->item->spesifikasi ?? '' }}" data-recipient="{{ $record->recipient ?? '' }}" data-keterangan_stok="{{ $record->keterangan ?? '' }}" data-tanggal_sort="{{ $record->created_at->timestamp }}">
                                <td class="py-3 px-4 font-medium text-gray-500 text-center tabular-nums">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 text-gray-800 font-medium whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->item->nama_perangkat ?? 'N/A' }}">{{ $record->item->nama_perangkat ?? 'N/A' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->item->type ?? '-' }}">{{ $record->item->type ?? '-' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->item->spesifikasi ?? '' }}">{{ $record->item->spesifikasi ?? '-' }}</td>
                                <td class="py-3 px-4 text-right font-semibold text-red-600 tabular-nums">-{{ $record->volume }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->recipient ?: '-' }}">{{ $record->recipient ?: '-' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $record->keterangan }}">{{ $record->keterangan ?: '-' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap tabular-nums">{{ $record->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr class="no-records-row-out empty-state-row"><td colspan="8">Tidak ada catatan stok keluar ditemukan.</td></tr>
                            @endforelse
                            <tr class="no-matching-records-row-out empty-state-row" style="display: none;"><td colspan="8">Tidak ada catatan stok keluar yang cocok dengan pencarian Anda.</td></tr>
                        </tbody>
                    </table>
                </div>
                <div id="stockOutPaginationContainer" class="p-3 border-t bg-gray-50 no-print"></div>
            </div>
        </div>
    </main>
</div>

<div id="modalTambahBarang" role="dialog" aria-modal="true" aria-labelledby="modalTambahBarangTitle" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300 ease-in-out opacity-0 no-print">
    <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-xl transform transition-all duration-300 ease-in-out scale-95">
        <div class="flex items-center justify-between mb-5">
            <h1 id="modalTambahBarangTitle" class="text-gray-800 text-xl font-semibold">Tambah Barang Baru</h1>
            <button id="closeNewItemModalBtn" aria-label="Tutup dialog tambah barang" class="text-gray-500 hover:text-gray-800 transition-colors"><i class="fas fa-times fa-lg" aria-hidden="true"></i></button>
        </div>
        <form id="createItemForm" method="POST" action="{{ route('item.add') }}" class="space-y-4">
            @csrf
            <div><label for="newItemNamaPerangkat" class="block text-sm font-medium text-gray-700 mb-1">Nama Perangkat <span class="text-red-500">*</span></label><input id="newItemNamaPerangkat" name="nama_perangkat" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" required/></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label for="newItemType" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label><input id="newItemType" name="type" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500"/></div>
                <div><label for="newItemSerialNumber" class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label><input id="newItemSerialNumber" name="serialnumber" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500"/></div>
            </div>
            <div><label for="newItemSpesifikasi" class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label><textarea id="newItemSpesifikasi" name="spesifikasi" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500"></textarea></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label for="newItemVolume" class="block text-sm font-medium text-gray-700 mb-1">Volume Awal <span class="text-red-500">*</span></label><input id="newItemVolume" name="volume" type="number" min="0" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" required/></div>
                <div><label for="newItemSatuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label><select id="newItemSatuan" name="satuan" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500"><option value="">Pilih Satuan</option><option value="Unit">Unit</option><option value="Pcs">Pcs</option><option value="Set">Set</option><option value="Pack">Pack</option><option value="Lembar">Lembar</option><option value="Meter">Meter</option><option value="Box">Box</option><option value="Roll">Roll</option><option value="Lainnya">Lainnya</option></select></div>
            </div>
            <div><label for="newItemKeterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label><input id="newItemKeterangan" name="keterangan" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Mis: Kondisi barang, lokasi spesifik"/></div>
            <div><label for="newItemReferensi" class="block text-sm font-medium text-gray-700 mb-1">Referensi</label><input id="newItemReferensi" name="referensi" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Mis: Nomor PO, nama proyek"/></div>
            <div class="flex justify-end pt-2 space-x-3">
                <button type="button" id="cancelNewItemModalBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white text-sm font-semibold rounded-md hover:bg-orange-700 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalCatatBarangKeluar" role="dialog" aria-modal="true" aria-labelledby="modalCatatBarangKeluarTitle" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4 transition-opacity duration-300 ease-in-out opacity-0 no-print">
    <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-xl transform transition-all duration-300 ease-in-out scale-95">
        <div class="flex items-center justify-between mb-5">
            <h1 id="modalCatatBarangKeluarTitle" class="text-gray-800 text-xl font-semibold">Catat Barang Keluar</h1>
            <button id="closeStockOutModalBtn" aria-label="Tutup dialog catat barang keluar" class="text-gray-500 hover:text-gray-800 transition-colors"><i class="fas fa-times fa-lg" aria-hidden="true"></i></button>
        </div>
        <form id="stockOutForm" method="POST" action="{{ route('stock.addOut') }}" class="space-y-4">
            @csrf
            <div>
                <label for="searchableOutItemIdInput" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                 <div class="relative">
                    <input type="text" id="searchableOutItemIdInput" placeholder="Ketik untuk mencari barang..." class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500 pr-10" autocomplete="off" aria-haspopup="listbox" aria-expanded="false"/>
                    <input type="hidden" name="itemid" id="outItemId" required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                    <div id="outItemIdDropdown" class="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden" role="listbox">
                    </div>
                </div>
            </div>
            <div>
                <label for="outVolume" class="block text-sm font-medium text-gray-700 mb-1">Volume Keluar <span class="text-red-500">*</span></label>
                <input id="outVolume" name="volume" type="number" min="1" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" required/>
                <p id="volumeWarning" class="text-xs text-red-500 mt-1 hidden">Volume keluar melebihi stok yang tersedia.</p>
            </div>
            <div><label for="outRecipient" class="block text-sm font-medium text-gray-700 mb-1">Nama Pengambil <span class="text-red-500">*</span></label><input id="outRecipient" name="recipient" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" required/></div>
            <div><label for="outKeterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label><input id="outKeterangan" name="keterangan" type="text" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Mis: Untuk proyek X, perbaikan Y"/></div>
            <div class="flex justify-end pt-2 space-x-3">
                <button type="button" id="cancelStockOutModalBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">Batal</button>
                <button type="submit" id="submitStockOutBtn" class="px-6 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 transition-colors">Simpan Stok Keluar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalExport" role="dialog" aria-modal="true" aria-labelledby="modalExportTitle" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] hidden p-4 transition-opacity duration-300 ease-in-out opacity-0 no-print">
    <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-2xl transform transition-all duration-300 ease-in-out scale-95">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 id="modalExportTitle" class="text-gray-900 text-xl font-bold">Export Data Laporan</h1>
                <p class="text-sm text-gray-500 mt-1">Pilih jenis laporan dan format file yang diinginkan.</p>
            </div>
            <button id="closeExportModalBtn" aria-label="Tutup dialog export" class="text-gray-400 hover:text-gray-800 transition-colors">
                <i class="fas fa-times fa-lg" aria-hidden="true"></i>
            </button>
        </div>
        <form id="exportForm" class="space-y-5">
            <div>
                <label for="exportReportType" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Laporan</label>
                <select id="exportReportType" name="type" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500 transition">
                    <option value="allinventory">Laporan Stok Keseluruhan</option>
                    <option value="stockinhistory">Riwayat Stok Masuk</option>
                    <option value="stockouthistory">Riwayat Stok Keluar</option>
                </select>
            </div>
            <div>
                <label for="exportFormat" class="block text-sm font-medium text-gray-700 mb-1.5">Format File</label>
                 <select id="exportFormat" name="format" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500 transition">
                    <option value="pdf">PDF (.pdf)</option>
                    <option value="csv">Excel (.csv)</option>
                </select>
            </div>
            <div class="flex justify-end pt-4 space-x-3">
                <button type="button" id="cancelExportModalBtn" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-download"></i>Export Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stockInsInitiallyEmpty = {{ $stockInsInitiallyEmpty ? 'true' : 'false' }};
        const stockOutsInitiallyEmpty = {{ $stockOutsInitiallyEmpty ? 'true' : 'false' }};

        const itemsData = @json($mappedItemsForJson);

        const searchInput = document.getElementById('searchInput');
        const stockInTable = document.getElementById('stockInTable');
        const stockOutTable = document.getElementById('stockOutTable');
        const stockInTableBody = stockInTable.querySelector('tbody');
        const stockOutTableBody = stockOutTable.querySelector('tbody');

        const tabStockInBtn = document.getElementById('tabStockIn');
        const tabStockOutBtn = document.getElementById('tabStockOut');
        const stockInTabPanel = document.getElementById('stockInTabPanel');
        const stockOutTabPanel = document.getElementById('stockOutTabPanel');
        
        const stockInPaginationContainer = document.getElementById('stockInPaginationContainer');
        const stockOutPaginationContainer = document.getElementById('stockOutPaginationContainer');

        let currentActiveTab = 'stockIn';
        let allStockInRows = [];
        let allStockOutRows = [];

        let stockInCurrentPage = 1;
        let stockOutCurrentPage = 1;
        const itemsPerPageDefault = 10;
        let stockInItemsPerPage = itemsPerPageDefault;
        let stockOutItemsPerPage = itemsPerPageDefault;
        let stockInShowAll = false;
        let stockOutShowAll = false;

        const newItemModal = document.getElementById('modalTambahBarang');
        const openNewItemModalBtn = document.getElementById('openNewItemModalBtn');
        const stockOutModal = document.getElementById('modalCatatBarangKeluar');
        const openStockOutModalBtn = document.getElementById('openStockOutModalBtn');
        const exportModal = document.getElementById('modalExport');
        const openExportModalBtn = document.getElementById('openExportModalBtn');
        const exportForm = document.getElementById('exportForm');

        let lastFocusedElementBeforeModal;

        const openModal = (modal) => {
            lastFocusedElementBeforeModal = document.activeElement;
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            });
            const firstFocusable = modal.querySelector('input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), button:not([disabled])');
            if (firstFocusable) firstFocusable.focus();
            modal.addEventListener('keydown', trapFocus);
        };

        const closeModal = (modal) => {
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            if (lastFocusedElementBeforeModal) lastFocusedElementBeforeModal.focus();
            modal.removeEventListener('keydown', trapFocus);
        };

        const trapFocus = (event) => {
            if (event.key !== 'Tab') return;
            const modal = event.currentTarget;
            const focusableElements = Array.from(modal.querySelectorAll('button:not([disabled]), [href]:not([disabled]), input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"]):not([disabled])')).filter(el => el.offsetParent !== null);
            if (focusableElements.length === 0) return;
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            if (event.shiftKey) {
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    event.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    event.preventDefault();
                }
            }
        };
        
        document.querySelectorAll('[id^="close"], [id^="cancel"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('[role="dialog"]');
                if(modal) closeModal(modal);
            });
        });
        document.querySelectorAll('[role="dialog"]').forEach(modal => {
             modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(modal); });
             modal.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(modal); });
        });

        openNewItemModalBtn?.addEventListener('click', () => { document.getElementById('createItemForm').reset(); openModal(newItemModal); });
        openStockOutModalBtn?.addEventListener('click', () => {
            const form = document.getElementById('stockOutForm');
            form.reset();
            stockOutItemDropdown.reset();
            document.getElementById('volumeWarning').classList.add('hidden');
            document.getElementById('submitStockOutBtn').disabled = false;
            openModal(stockOutModal);
        });
        openExportModalBtn?.addEventListener('click', () => openModal(exportModal));

        exportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const reportType = document.getElementById('exportReportType').value;
            const fileFormat = document.getElementById('exportFormat').value;
            const baseUrl = "{{ route('stock.download') }}";
            const exportUrl = `${baseUrl}?type=${reportType}&format=${fileFormat}`;
            window.location.href = exportUrl;
            closeModal(exportModal);
        });

        const storeInitialRows = () => {
            allStockInRows = [];
            if (stockInTableBody) stockInTableBody.querySelectorAll('tr.stockin-row').forEach(row => allStockInRows.push(row.cloneNode(true)));
            allStockInRows.sort((a,b) => (b.dataset.tanggal_sort || 0) - (a.dataset.tanggal_sort || 0));

            allStockOutRows = [];
            if (stockOutTableBody) stockOutTableBody.querySelectorAll('tr.stockout-row').forEach(row => allStockOutRows.push(row.cloneNode(true)));
            allStockOutRows.sort((a,b) => (b.dataset.tanggal_sort || 0) - (a.dataset.tanggal_sort || 0));
        };
        
        const updateNoItemsMessage = (tableBody, hasVisibleRows, isFiltering, initiallyEmpty, tabName) => {
            const tabSuffix = tabName.substring(5).toLowerCase();
            const noRecordsRow = tableBody.querySelector(`.no-records-row-${tabSuffix}`);
            const noMatchingRow = tableBody.querySelector(`.no-matching-records-row-${tabSuffix}`);
            
            if (noRecordsRow) noRecordsRow.style.display = 'none';
            if (noMatchingRow) noMatchingRow.style.display = 'none';

            if (!hasVisibleRows) {
                if (isFiltering) {
                    if (noMatchingRow) noMatchingRow.style.display = '';
                } else {
                     if (initiallyEmpty && noRecordsRow) {
                        noRecordsRow.style.display = '';
                     } else if (!initiallyEmpty && noRecordsRow) {
                        noRecordsRow.style.display = '';
                        noRecordsRow.querySelector('td').textContent = `Tidak ada catatan stok ${tabSuffix === 'in' ? 'masuk' : 'keluar'} ditemukan.`;
                    }
                }
            }
        };

        const renderTableData = () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let sourceRows, tableBody, currentPage, itemsPerPage, showAll, tabName, initiallyEmptyGlobal, paginationContainer;

            if (currentActiveTab === 'stockIn') {
                sourceRows = allStockInRows;
                tableBody = stockInTableBody;
                currentPage = stockInCurrentPage;
                itemsPerPage = stockInItemsPerPage;
                showAll = stockInShowAll;
                tabName = 'stockIn';
                initiallyEmptyGlobal = stockInsInitiallyEmpty;
                paginationContainer = stockInPaginationContainer;
            } else {
                sourceRows = allStockOutRows;
                tableBody = stockOutTableBody;
                currentPage = stockOutCurrentPage;
                itemsPerPage = stockOutItemsPerPage;
                showAll = stockOutShowAll;
                tabName = 'stockOut';
                initiallyEmptyGlobal = stockOutsInitiallyEmpty;
                paginationContainer = stockOutPaginationContainer;
            }

            if (!tableBody) return;
            tableBody.innerHTML = ''; 

            const filteredRows = sourceRows.filter(row => {
                const rowTextContent = `${row.dataset.nama || ''} ${row.dataset.tipe || ''} ${row.dataset.spesifikasi || ''} ${tabName === 'stockIn' ? (row.dataset.serialnumber_item || '') : (row.dataset.recipient || '')} ${row.dataset.keterangan_stok || ''}`.toLowerCase();
                return rowTextContent.includes(searchTerm);
            });

            const totalFilteredItems = filteredRows.length;
            const totalPages = showAll ? 1 : Math.ceil(totalFilteredItems / itemsPerPage);
            if (currentPage > totalPages && totalPages > 0) {
                 if (currentActiveTab === 'stockIn') stockInCurrentPage = totalPages;
                 else stockOutCurrentPage = totalPages;
                 currentPage = totalPages;
            } else if (totalPages === 0 && currentPage !== 1) {
                 if (currentActiveTab === 'stockIn') stockInCurrentPage = 1;
                 else stockOutCurrentPage = 1;
                 currentPage = 1;
            }

            const startIndex = showAll ? 0 : (currentPage - 1) * itemsPerPage;
            const endIndex = showAll ? totalFilteredItems : startIndex + itemsPerPage;
            const itemsToDisplay = filteredRows.slice(startIndex, endIndex);

            let hasVisibleDataRows = false;
            itemsToDisplay.forEach((row, index) => {
                const clonedRow = row.cloneNode(true);
                clonedRow.querySelector('td:first-child').textContent = showAll ? (index + 1) : (startIndex + index + 1);
                tableBody.appendChild(clonedRow);
                hasVisibleDataRows = true;
            });
            
            updateNoItemsMessage(tableBody, hasVisibleDataRows, searchTerm.length > 0, initiallyEmptyGlobal, tabName);
            renderPaginationControls(paginationContainer, currentPage, totalPages, tabName, showAll);
        };

        const renderPaginationControls = (container, currentPage, totalPages, tabName, showAll) => {
            container.innerHTML = '';
            if (totalPages <= 1 && !showAll) { 
                 if ( (tabName === 'stockIn' && allStockInRows.length <= itemsPerPageDefault) || (tabName === 'stockOut' && allStockOutRows.length <= itemsPerPageDefault) ) {
                    return; 
                 }
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'flex flex-col sm:flex-row items-center justify-between gap-2 text-sm';

            const showAllContainer = document.createElement('div');
            showAllContainer.className = 'flex items-center';
            const showAllCheckbox = document.createElement('input');
            showAllCheckbox.type = 'checkbox';
            showAllCheckbox.id = `${tabName}ShowAllCheckbox`;
            showAllCheckbox.className = 'h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500 mr-2';
            showAllCheckbox.checked = showAll;
            showAllCheckbox.addEventListener('change', (e) => {
                if (tabName === 'stockIn') {
                    stockInShowAll = e.target.checked;
                    stockInItemsPerPage = stockInShowAll ? Infinity : itemsPerPageDefault;
                    stockInCurrentPage = 1;
                } else {
                    stockOutShowAll = e.target.checked;
                    stockOutItemsPerPage = stockOutShowAll ? Infinity : itemsPerPageDefault;
                    stockOutCurrentPage = 1;
                }
                renderTableData();
            });
            const showAllLabel = document.createElement('label');
            showAllLabel.htmlFor = `${tabName}ShowAllCheckbox`;
            showAllLabel.className = 'text-gray-700';
            showAllLabel.textContent = 'Tampilkan Semua';
            showAllContainer.appendChild(showAllCheckbox);
            showAllContainer.appendChild(showAllLabel);
            wrapper.appendChild(showAllContainer);

            if (!showAll && totalPages > 1) {
                const nav = document.createElement('nav');
                nav.setAttribute('aria-label', 'Pagination');
                nav.className = 'flex items-center space-x-1';

                const prevButton = document.createElement('button');
                prevButton.innerHTML = `<i class="fas fa-chevron-left"></i>`;
                prevButton.className = 'pagination-button flex items-center justify-center rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50';
                prevButton.disabled = currentPage === 1;
                prevButton.addEventListener('click', () => {
                    if (tabName === 'stockIn') stockInCurrentPage--;
                    else if (tabName === 'stockOut') stockOutCurrentPage--;
                    renderTableData();
                });
                nav.appendChild(prevButton);

                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, currentPage + 2);
                if (currentPage <= 3) endPage = Math.min(totalPages, 5);
                if (currentPage > totalPages - 3) startPage = Math.max(1, totalPages - 4);

                if (startPage > 1) {
                    const firstPageButton = document.createElement('button');
                    firstPageButton.textContent = '1';
                    firstPageButton.className = 'pagination-button rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50';
                    firstPageButton.addEventListener('click', () => {
                        if (tabName === 'stockIn') stockInCurrentPage = 1; else stockOutCurrentPage = 1;
                        renderTableData();
                    });
                    nav.appendChild(firstPageButton);
                    if (startPage > 2) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        ellipsis.className = 'px-2 py-1 text-gray-500';
                        nav.appendChild(ellipsis);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.className = `pagination-button rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 ${i === currentPage ? 'active' : ''}`;
                    pageButton.addEventListener('click', () => {
                        if (tabName === 'stockIn') stockInCurrentPage = i; else stockOutCurrentPage = i;
                        renderTableData();
                    });
                    nav.appendChild(pageButton);
                }

                 if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '...';
                        ellipsis.className = 'px-2 py-1 text-gray-500';
                        nav.appendChild(ellipsis);
                    }
                    const lastPageButton = document.createElement('button');
                    lastPageButton.textContent = totalPages;
                    lastPageButton.className = 'pagination-button rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50';
                    lastPageButton.addEventListener('click', () => {
                        if (tabName === 'stockIn') stockInCurrentPage = totalPages; else stockOutCurrentPage = totalPages;
                        renderTableData();
                    });
                    nav.appendChild(lastPageButton);
                }

                const nextButton = document.createElement('button');
                nextButton.innerHTML = `<i class="fas fa-chevron-right"></i>`;
                nextButton.className = 'pagination-button flex items-center justify-center rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50';
                nextButton.disabled = currentPage === totalPages;
                nextButton.addEventListener('click', () => {
                    if (tabName === 'stockIn') stockInCurrentPage++;
                    else if (tabName === 'stockOut') stockOutCurrentPage++;
                    renderTableData();
                });
                nav.appendChild(nextButton);
                wrapper.appendChild(nav);
            }
             container.appendChild(wrapper);
        };

        const setActiveTab = (activeBtn, inactiveBtn, activePanel, inactivePanel) => {
            activeBtn.classList.add('tab-button-active');
            activeBtn.classList.remove('tab-button-inactive');
            activeBtn.setAttribute('aria-selected', 'true');
            inactiveBtn.classList.add('tab-button-inactive');
            inactiveBtn.classList.remove('tab-button-active');
            inactiveBtn.setAttribute('aria-selected', 'false');
            activePanel.classList.remove('hidden');
            activePanel.classList.add('flex'); 
            inactivePanel.classList.add('hidden');
            inactivePanel.classList.remove('flex');
            
            currentActiveTab = activeBtn === tabStockInBtn ? 'stockIn' : 'stockOut';
            searchInput.value = '';
            renderTableData(); 
            sessionStorage.setItem('activeStockControlTab', currentActiveTab);
        };
        
        tabStockInBtn.addEventListener('click', () => setActiveTab(tabStockInBtn, tabStockOutBtn, stockInTabPanel, stockOutTabPanel));
        tabStockOutBtn.addEventListener('click', () => setActiveTab(tabStockOutBtn, tabStockInBtn, stockOutTabPanel, stockInTabPanel));

        const debounce = (func, delay) => {
            let timeout;
            return function(...args) { clearTimeout(timeout); timeout = setTimeout(() => func.apply(this, args), delay); };
        };

        searchInput.addEventListener('input', debounce(function() {
            if (currentActiveTab === 'stockIn') stockInCurrentPage = 1; else stockOutCurrentPage = 1;
            renderTableData();
        }, 300));

        const setupSearchableDropdown = (inputId, hiddenInputId, dropdownId, items, isStockOut) => {
            const searchInputEl = document.getElementById(inputId);
            const hiddenInputEl = document.getElementById(hiddenInputId);
            const dropdownEl = document.getElementById(dropdownId);
            let currentSelectedItem = null;

            const renderDropdownItems = (filteredItems) => {
                dropdownEl.innerHTML = '';
                if (filteredItems.length === 0) {
                    const noResultItem = document.createElement('div');
                    noResultItem.className = 'px-4 py-2 text-sm text-gray-500';
                    noResultItem.textContent = 'Tidak ada barang ditemukan.';
                    dropdownEl.appendChild(noResultItem);
                    return;
                }
                filteredItems.forEach(item => {
                    const itemEl = document.createElement('div');
                    itemEl.className = 'px-4 py-2 text-sm text-gray-700 cursor-pointer searchable-dropdown-item';
                    itemEl.textContent = isStockOut ? item.displayTextStockOut : item.displayTextStockIn;
                    itemEl.setAttribute('role', 'option');
                    itemEl.tabIndex = -1; 
                    itemEl.dataset.value = item.id;
                    itemEl.addEventListener('click', () => {
                        searchInputEl.value = isStockOut ? item.displayTextStockOut : item.displayTextStockIn;
                        hiddenInputEl.value = item.id;
                        currentSelectedItem = item;
                        dropdownEl.classList.add('hidden');
                        searchInputEl.setAttribute('aria-expanded', 'false');
                        if (isStockOut) validateStockOutVolume();
                    });
                    dropdownEl.appendChild(itemEl);
                });
            };

            searchInputEl.addEventListener('input', () => {
                const searchTerm = searchInputEl.value.toLowerCase();
                const filtered = items.filter(item => 
                    (isStockOut ? item.displayTextStockOut : item.displayTextStockIn).toLowerCase().includes(searchTerm)
                );
                renderDropdownItems(filtered);
                dropdownEl.classList.remove('hidden');
                searchInputEl.setAttribute('aria-expanded', 'true');
                hiddenInputEl.value = '';
                currentSelectedItem = null;
                if (isStockOut) validateStockOutVolume();
            });

            searchInputEl.addEventListener('focus', () => {
                const searchTerm = searchInputEl.value.toLowerCase();
                 const currentValInHidden = hiddenInputEl.value;
                 let filtered = items;
                 if(searchInputEl.value && !items.find(i => (isStockOut ? i.displayTextStockOut : i.displayTextStockIn) === searchInputEl.value && i.id == currentValInHidden)) {
                     filtered = items.filter(item => (isStockOut ? item.displayTextStockOut : item.displayTextStockIn).toLowerCase().includes(searchTerm));
                 }
                renderDropdownItems(filtered.length > 0 ? filtered : items);
                dropdownEl.classList.remove('hidden');
                searchInputEl.setAttribute('aria-expanded', 'true');
            });
            
            document.addEventListener('click', (e) => {
                if (!searchInputEl.contains(e.target) && !dropdownEl.contains(e.target)) {
                    dropdownEl.classList.add('hidden');
                    searchInputEl.setAttribute('aria-expanded', 'false');
                    if(currentSelectedItem) {
                        searchInputEl.value = isStockOut ? currentSelectedItem.displayTextStockOut : currentSelectedItem.displayTextStockIn;
                    }
                }
            });

            searchInputEl.addEventListener('keydown', (e) => {
                const itemsInDropdown = Array.from(dropdownEl.querySelectorAll('[role="option"]'));
                if (dropdownEl.classList.contains('hidden') && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                    const searchTerm = searchInputEl.value.toLowerCase();
                    const filtered = items.filter(item => 
                        (isStockOut ? item.displayTextStockOut : item.displayTextStockIn).toLowerCase().includes(searchTerm)
                    );
                    renderDropdownItems(filtered.length > 0 ? filtered : items);
                    dropdownEl.classList.remove('hidden');
                    searchInputEl.setAttribute('aria-expanded', 'true');
                    if(itemsInDropdown.length > 0 && e.key === 'ArrowDown') itemsInDropdown[0].focus();
                    return;
                }
                if (itemsInDropdown.length === 0) return;

                let focusedIndex = itemsInDropdown.findIndex(item => item === document.activeElement);

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    focusedIndex = (focusedIndex + 1) % itemsInDropdown.length;
                    itemsInDropdown[focusedIndex].focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    focusedIndex = (focusedIndex - 1 + itemsInDropdown.length) % itemsInDropdown.length;
                    itemsInDropdown[focusedIndex].focus();
                } else if (e.key === 'Enter') {
                     e.preventDefault();
                    if (focusedIndex > -1 && itemsInDropdown[focusedIndex] && !dropdownEl.classList.contains('hidden')) {
                         itemsInDropdown[focusedIndex].click();
                    } else if (!dropdownEl.classList.contains('hidden') && itemsInDropdown.length > 0 && searchInputEl.value) { 
                        const firstMatch = items.find(item => (isStockOut ? item.displayTextStockOut : item.displayTextStockIn).toLowerCase().startsWith(searchInputEl.value.toLowerCase()));
                        if (firstMatch) {
                             const firstMatchInDropdown = itemsInDropdown.find(el => el.dataset.value == firstMatch.id);
                             firstMatchInDropdown?.click();
                        } else if (itemsInDropdown.length ===1) {
                            itemsInDropdown[0].click();
                        }
                    }
                } else if (e.key === 'Escape') {
                    dropdownEl.classList.add('hidden');
                    searchInputEl.setAttribute('aria-expanded', 'false');
                     if(currentSelectedItem) {
                        searchInputEl.value = isStockOut ? currentSelectedItem.displayTextStockOut : currentSelectedItem.displayTextStockIn;
                    }
                }
            });

            return {
                getSelectedItem: () => currentSelectedItem,
                reset: () => {
                    searchInputEl.value = '';
                    hiddenInputEl.value = '';
                    currentSelectedItem = null;
                    dropdownEl.innerHTML = ''; 
                    dropdownEl.classList.add('hidden');
                    searchInputEl.setAttribute('aria-expanded', 'false');
                }
            };
        };

        const stockOutItemDropdown = setupSearchableDropdown('searchableOutItemIdInput', 'outItemId', 'outItemIdDropdown', itemsData, true);

        const outVolumeInput = document.getElementById('outVolume');
        const validateStockOutVolume = () => {
            const selectedItem = stockOutItemDropdown.getSelectedItem();
            const warningEl = document.getElementById('volumeWarning');
            const submitBtn = document.getElementById('submitStockOutBtn');

            if (!selectedItem && document.getElementById('searchableOutItemIdInput').value) {
                warningEl.textContent = 'Pilih barang yang valid dari daftar.';
                warningEl.classList.remove('hidden');
                submitBtn.disabled = true;
                return;
            }
             if (!selectedItem) {
                warningEl.classList.add('hidden');
                submitBtn.disabled = !document.getElementById('outItemId').value;
                return;
            }

            const maxVolume = parseInt(selectedItem.volume, 10);
            const currentVolume = parseInt(outVolumeInput.value, 10);

            if (currentVolume > 0 && currentVolume > maxVolume) {
                warningEl.textContent = `Volume keluar (${currentVolume}) melebihi stok tersedia (${maxVolume}).`;
                warningEl.classList.remove('hidden');
                submitBtn.disabled = true;
            } else {
                warningEl.classList.add('hidden');
                submitBtn.disabled = false;
            }
        };
        outVolumeInput?.addEventListener('input', validateStockOutVolume);

        storeInitialRows();
        
        const params = new URLSearchParams(window.location.search);
        const incomingActiveTab = params.get('active_tab');
        const sessionActiveTab = sessionStorage.getItem('activeStockControlTab');
        
        let targetTab = 'stockIn'; 
        if (incomingActiveTab === 'stockIn' || incomingActiveTab === 'stockOut') {
            targetTab = incomingActiveTab;
        } else if (sessionActiveTab === 'stockIn' || sessionActiveTab === 'stockOut') {
            targetTab = sessionActiveTab;
        }

        if (targetTab === 'stockOut') {
             setActiveTab(tabStockOutBtn, tabStockInBtn, stockOutTabPanel, stockInTabPanel);
        } else {
             setActiveTab(tabStockInBtn, tabStockOutBtn, stockInTabPanel, stockOutTabPanel);
        }
    });
</script>
</body>
</html>