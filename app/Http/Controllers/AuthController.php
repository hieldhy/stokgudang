<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
             return back()->withErrors(['login_error' => 'Kredensial tidak valid'])->withInput();
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function list()
    {
        $users = User::orderBy('username', 'asc')->get();
        return view('auth.index', compact('users'));
    }

    public function create()
    {
        return view('auth.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->route('users.list')->with('success', 'Pengguna berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->route('users.create')->with('error', 'Gagal membuat pengguna. Silakan coba lagi.')->withInput();
        }
    }
    
    public function edit(User $user)
    {
        return view('auth.edit', compact('user'));
    }

    public function renew(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->userid, 'userid'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $user->userid)
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $userData = ['username' => $request->username];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
            return redirect()->route('users.list')->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->route('users.edit', $user->userid)->with('error', 'Gagal memperbarui pengguna. Silakan coba lagi.')->withInput();
        }
    }

    public function remove(User $user)
    {
        try {
            if (User::count() === 1) {
                return redirect()->route('users.list')->with('error', 'Tidak dapat menghapus pengguna terakhir.');
            }
            $user->delete();
            return redirect()->route('users.list')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('users.list')->with('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
        }
    }
}