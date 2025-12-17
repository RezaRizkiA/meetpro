<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProfileController extends Controller
{
    protected $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    // --- HALAMAN SETTINGS ---
    // Route: dashboard/settings (name: profile.edit)
    public function edit()
    {
        return Inertia::render('Administrator/Settings/Index', [
            'user' => Auth::user(),
            // Flash message dari session (compatible dengan kode lama)
            'flash' => [
                'success' => session('success'),
                'error'   => session('error'),
            ]
        ]);
    }

    // --- ACTION: RENEW PROFILE ---
    // Route: renew-profile
    public function renew_profile(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi (Sama persis dengan kode lama)
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'gender'    => 'required|in:L,P',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            // Validasi slug unik kecuali punya user sendiri
            'slug_name' => 'nullable|alpha_dash|unique:users,slug,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Panggil Service
        $this->service->updateProfile($user, $request->all());

        // 3. Redirect (Tetap gunakan logic redirect lama jika ingin konsisten)
        // Di kode lama redirect ke 'profile', tapi karena kita refactor halaman settings ada di 'profile.edit'
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // --- ACTION: RENEW PASSWORD ---
    // Route: renew-password
    public function renew_password(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Kondisional (Sama persis dengan kode lama)
        $rules = [
            'new_password' => 'required|min:4|confirmed',
        ];

        if ($user->password !== null) {
            $rules['current_password'] = 'required|current_password';
        } else {
            $rules['current_password'] = 'nullable|current_password';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Panggil Service (Logic check hash ada di dalam service)
        // Service akan throw validation exception jika password lama salah
        $this->service->updatePassword($user, $request->current_password, $request->new_password);

        return redirect()->back()->with('success', 'Password has been updated successfully!');
    }

    // --- ACTION: RENEW PICTURE ---
    // Route: renew-picture
    public function renew_picture(Request $request)
    {
        // 1. Validasi Gambar
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Panggil Service (Logic S3 ada di dalam service)
        if ($request->hasFile('picture')) {
            $this->service->updateAvatar(Auth::user(), $request->file('picture'));
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}
