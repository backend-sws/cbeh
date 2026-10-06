<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{

    public function Dashboard()
    {
        return view('dashboard.index');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function Userlist(){
        return view('user-list');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // public function user_list(Request $request,$name)
    // {
    //     $perPage = request()->input('perPage', 10);
    //     $search = request()->input('search', '');
    //     $role = Role::where('name', $name)->first();
    //     $users = $role
    //     ? $role->users()->where(function ($query) use ($search) {
    //         $query->where('name', 'like', '%' . $search . '%')
    //         ->orWhere('email', 'like', '%' . $search . '%');
    //     })->paginate($perPage)
    //     : collect();
    //     return view('user-list',compact('users','name'));
    // }
}
