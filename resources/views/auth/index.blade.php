<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Manajemen Pengguna - PLN UID Kalselteng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #f97316; }
        .sidebar-link-active { background-color: #c2410c; font-weight: 600; }
        .card { background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
        .card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; }
        .card-title { font-size: 1.125rem; font-weight: 600; color: #374151; }
        .card-body { padding: 1.5rem; }
        .btn-primary { background-color: #f97316; color: white; padding: 0.625rem 1.25rem; border-radius: 0.375rem; font-weight: 500; text-align: center; transition: background-color 0.2s ease-in-out; display: inline-block; }
        .btn-primary:hover { background-color: #ea580c; }
        .btn-secondary { background-color: #f3f4f6; color: #374151; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease-in-out; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background-color: #e5e7eb; }
        .btn-danger { background-color: #ef4444; color: white; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease-in-out; }
        .btn-danger:hover { background-color: #dc2626; }
        .btn-danger:disabled { background-color: #fca5a5; cursor: not-allowed; }
        .table-hover tbody tr:hover { background-color: #fffbeb; }
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

    <main class="flex-1 p-6 sm:p-8 md:p-10 overflow-x-hidden ml-64">
        <div class="w-full">
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h2 class="font-bold text-2xl sm:text-3xl text-gray-800 select-none">Manajemen Pengguna</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola akun pengguna dan izin.</p>
                </div>
                <div class="w-full sm:w-auto mt-4 sm:mt-0">
                    <a href="{{ route('users.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>Buat Pengguna Baru
                    </a>
                </div>
            </header>

            @if(session('success'))
                <div role="alert" class="mb-6 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div role="alert" class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg shadow-sm flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-lg text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="card">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700 table-hover">
                        <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Pengguna</th>
                                <th scope="col" class="px-6 py-3">Dibuat Pada</th>
                                <th scope="col" class="px-6 py-3 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $user->username }}</td>
                                <td class="px-6 py-4">{{ $user->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('users.edit', $user->userid) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('users.remove', $user->userid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" @if(Auth::user()->userid == $user->userid) disabled @endif>Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-gray-500">Tidak ada pengguna ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>