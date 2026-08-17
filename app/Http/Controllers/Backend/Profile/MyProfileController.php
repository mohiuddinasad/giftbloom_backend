<?php

namespace App\Http\Controllers\Backend\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;

class MyProfileController extends Controller
{
    public function index()
    {
        return view('backend.profile.profile');
    }

    // ---- Profile Info + Image Update ----
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
            'user_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800', // 800K as UI বলছে
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('user_image')) {

            if ($user->user_image && file_exists(public_path('upload/' . $user->user_image))) {
                unlink(public_path('upload/' . $user->user_image));
            }

            if (!file_exists(public_path('upload'))) {
                mkdir(public_path('upload'), 0755, true);
            }

            $image     = $request->file('user_image');
            $imageName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload'), $imageName);

            $user->user_image = $imageName;
        }

        $user->save();

        return back()->with('success', 'profile updated successfully.');
    }

    // ---- Password Update ----
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'currentPassword' => 'required|string',
            'newPassword'     => 'required|string|min:6|confirmed',
        ], [
            'newPassword.confirmed' => 'New password and confirm password must match.',
        ]);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return back()
                ->withErrors(['currentPassword' => 'Current password is incorrect.'])
                ->withInput()
                ->withFragment('password-section');
        }

        $user->password = $request->newPassword; // 'hashed' cast থাকায় auto hash হবে
        $user->save();

        return back()
            ->with('success', 'Password updated successfully.')
            ->withFragment('password-section');
    }
}
