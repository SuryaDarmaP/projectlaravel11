<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:dns',
            'password' => 'required|min:8|max:15',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Pastikan semua email dan password terisi dengan benar!');
            return redirect()->back();
        }

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            toast('Selamat datang admin', 'success');
            return redirect()->to(route('admin.dashboard'));
        } elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            toast('Selamat datang', 'success');
            return redirect()->to(route('user.dashboard'));
        } else {
            Alert::error('Login Gagal', 'Email atau password tidak valid!');
            return redirect()->back();
        }
    }

    public function admin_logout()
    {
        Auth::guard('admin')->logout();
        toast('Berhasil logout!', 'success');
        return redirect('/');
    }

    public function user_logout()
    {
        Auth::logout();
        toast('Berhasil logout!', 'success');
        return redirect('/');
    }

    public function register()
    {
        return view('register');
    }

    public function post_register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email:dns',
            'password' => 'required|min:8|max:8'
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
            return redirect()->back();
        }

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'point' => 0 // Menambahkan nilai default untuk 'point'
        ]);

        // Cek apakah user berhasil dibuat
        if ($user) {
            Alert::success('Berhasil!', 'Akun baru berhasil dibuat, silahkan melakukan login!');
            return redirect('/');
        } else {
            Alert::error('Gagal!', 'Akun gagal dibuat, silahkan coba lagi!');
            return redirect()->back();
        }
    }

        
}
