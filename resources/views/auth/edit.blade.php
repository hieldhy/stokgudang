<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Edit Pengguna - PLN UID Kalselteng</title>
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
        .card-footer { padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; }
        .btn-primary { background-color: #f97316; color: white; padding: 0.625rem 1.25rem; border-radius: 0.375rem; font-weight: 500; text-align: center; transition: background-color 0.2s ease-in-out; display: inline-block; }
        .btn-primary:hover { background-color: #ea580c; }
        .form-input { width: 100%; border-radius: 0.375rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem; font-size: 0.875rem; transition: border-color 0.2s, box-shadow 0.2s; }
        .form-input:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.3); outline: none; }
        .form-label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #374151; }
        .form-error { color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; }
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
        <header class="mb-8">
            <h2 class="font-bold text-2xl sm:text-3xl text-gray-800 select-none">Edit Pengguna: {{ $user->username }}</h2>
        </header>

        <div class="card max-w-2xl mx-auto">
            <form action="{{ route('users.renew', $user->userid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-4">
                        <label for="username" class="form-label">Nama Pengguna</label>
                        <input type="text" id="username" name="username" class="form-input @error('username') border-red-500 @enderror" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" class="form-input @error('password') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong untuk mempertahankan kata sandi saat ini.</p>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('users.list') }}" class="text-gray-600 mr-4">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>