<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ExpertiseCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function google_redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function google_callback(Request $request){
        if (session()->has('url.intended')) {
            $redirectTo = session()->get('url.intended');
        }else{
            $redirectTo = false;
        } //if intended url is available

        $google_user = Socialite::driver('google')->user();
        $avatarUrl = $google_user->getAvatar();
        $imageContents = file_get_contents($avatarUrl);
        $filename = 'avatars/' . uniqid() . '.png';

        if(User::where('email', $google_user->email)->exists()){
            $user = User::where('email', $google_user->email)->first();
            if($user->google_id == NULL){
                $user->update([
                    'google_id' => $google_user->id
                ]);
                Auth::login($user, true);
            }elseif($user->google_id == $google_user->id){
                Auth::login($user, true);
            }
        }else{
            $user = User::create([
                'name' => $google_user->name,
                'roles' => ['user'],
                'email' => $google_user->email,
                'google_id' => $google_user->id,
                'email_verified_at' => now(),
            ]);
            Auth::login($user, true);
        }

        $user->slug ??= slugName($user->name, $user->id);
        if (!$user->picture) {
            Storage::disk('s3')->put($filename, $imageContents, 'public');
            $user->picture = $filename;
        }
        $user->save();

        $credentials = [
            'user' => Auth::user()->id,
            'token_jwt' => Auth::user()->id,
        ];
        $request->session()->regenerate();
        $request->session()->put('loggedUser', $credentials);

        if ($redirectTo != FALSE) {
            session()->forget('url.intended');
            return redirect($redirectTo);
        }
        return redirect()->route('profile');
    }

    public function register(){
        $expertise_categories = ExpertiseCategory::with('expertises')->get();
        return view('expert/expert_action', compact('expertise_categories'));
    }

    // Menangani request perubahan password
    public function renew_password(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required_if:password,true|current_password',
            'new_password' => 'required|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika password yang dimasukkan adalah password lama yang benar
        if (Auth::check() && Hash::check($request->current_password, Auth::user()->password)) {
            // Perbarui password user
            Auth::user()->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->route('profile')->with('success', 'Password has been updated successfully!');
        } else {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }
    }

    public function renew_picture(Request $request){
        // Validasi gambar yang di-upload
        $validator = Validator::make($request->all(), [
            'picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:800', // Validasi format dan ukuran
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil user yang sedang login
        $user = Auth::user();

        // Hapus file gambar lama jika ada
        if ($user->picture) {
            // Menghapus file gambar lama di S3
            Storage::disk('s3')->delete($user->picture);
        }

        // Menyimpan gambar baru ke S3
        if($request->hasFIle('picture')){
          $image = $request->file('picture');
          $filename = 'avatars/' . uniqid() . '.' . $image->getClientOriginalExtension();
          Storage::disk('s3')->put($filename, file_get_contents($image), 'public');
        }

        // Simpan path gambar baru ke database
        $user->picture = $filename;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function renew_profile(Request $request){
        $user = Auth::user();
        // Validasi input, termasuk validasi slug yang unik
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P', // L = Male, P = Female
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'slug_name' => 'nullable|unique:users,slug,' . $user->id . '|alpha_dash', // Slug unik kecuali slug milik user yang sedang diupdate
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Periksa apakah ada perubahan di input yang dimasukkan, jika tidak, biarkan kosong
        $user->name = $request->name ?: $user->name;
        $user->gender = $request->gender ?: $user->gender;
        $user->phone = $request->phone ?: $user->phone;
        $user->address = $request->address ?: $user->address;
        
        // Perbarui slug hanya jika ada perubahan
        if ($request->slug_name && $request->slug_name != $user->slug) {
            $user->slug = $request->slug_name;
        }

        // Simpan perubahan di database
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function register_client_post(Request $request){
      dd($request->all());
      $client = Client::create($request->all());

    }
}
