<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use App\Models\AddressType;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $addresses = Address::where('user_id', $user->id)->first();
        $profileImage = Document::where('documentable_id', $user->id)->first();
        $addressImage = Document::where('documentable_id', $user->id)->first();
        $addressTypes = AddressType::all();

        return view('profile.edit', [
            'user' => $user,
            'addressTypes' => $addressTypes,
            'addresses' => $addresses,
            'profileImage' => $profileImage,
            'addressImage' => $addressImage,
        ]);
    }


    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $addresses = Address::where('user_id', $request->user()->id)->first();
        
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);
        $addresses->update([
            'address_type_id' => $request->address_type,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ]);

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
}
