<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Login - Sistem Inventaris PLN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col justify-center items-center p-4 font-sans antialiased">
    <div class="w-full max-w-md">
        <header class="flex flex-col items-center mb-8">
            <img alt="PLN Logo" class="w-20 h-20 mb-4" src="{{ asset('img/logo_pln.png') }}" />
            <h1 class="text-3xl font-bold text-slate-800">PLN UID KALSELTENG</h1>
            <p class="text-slate-600">Stok Gudang</p>
        </header>

        <main>
            <form method="POST" action="{{ route('login.post') }}" class="bg-white rounded-xl shadow-xl p-8 sm:p-10">
                @csrf
                <h2 class="text-2xl font-bold text-orange-600 text-center mb-8">Masuk Akun</h2>

                @if ($errors->any())
                <div role="alert" class="mb-6 p-3 bg-red-50 border border-red-300 text-red-700 rounded-md text-sm">
                    <p class="font-medium mb-1">Login gagal. Silakan periksa kredensial Anda.</p>
                    <ul class="list-disc list-inside ml-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mb-5">
                    <label for="username" class="block mb-1.5 text-sm font-medium text-slate-700 sr-only">Nama Pengguna</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="fas fa-user" aria-hidden="true"></i>
                        </span>
                        <input
                            id="username" name="username" type="text"
                            placeholder="Nama Pengguna"
                            value="{{ old('username') }}"
                            required autofocus
                            aria-label="Nama Pengguna"
                            class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500 placeholder-slate-400 placeholder:italic text-sm transition" />
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block mb-1.5 text-sm font-medium text-slate-700 sr-only">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                        </span>
                        <input
                            id="password" name="password" type="password"
                            placeholder="Kata Sandi"
                            required
                            aria-label="Kata Sandi"
                            class="block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500 placeholder-slate-400 placeholder:italic text-sm transition" />
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-orange-600 focus:outline-none focus:ring-1 focus:ring-orange-500 rounded-r-lg" aria-label="Tampilkan atau sembunyikan kata sandi">
                            <i class="fas fa-eye" id="eyeIcon" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-4 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors duration-150 flex items-center justify-center text-base">
                    <i class="fas fa-sign-in-alt mr-2" aria-hidden="true"></i>
                    Login
                </button>
            </form>
        </main>
        <footer class="text-center mt-8">
            <p class="text-slate-500 text-xs">
                &copy; {{ date('Y') }} PLN UID Kalselteng. Semua hak dilindungi.
            </p>
        </footer>
    </div>

<script>
    const togglePasswordButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePasswordButton && passwordInput && eyeIcon) {
        togglePasswordButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');

            if (type === 'password') {
                togglePasswordButton.setAttribute('aria-label', 'Tampilkan kata sandi');
            } else {
                togglePasswordButton.setAttribute('aria-label', 'Sembunyikan kata sandi');
            }
        });
    }
</script>
</body>
</html>