<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\AddressType;
use App\Models\Document;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\FileService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    protected $FileService;

    public function __construct(FileService $item)
    {
        $this->FileService=$item;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $addressTypes = AddressType::all();

        return view('auth.register', compact('addressTypes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => 'required|string|max:255',
            'zipcode' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'address_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($request->hasFile('profile_image')) {
            $path = $this->FileService->imgUpload($request->file('profile_image'),'profile-image');
            Document::create([
                'documentable_type' => 'profile_image',
                'documentable_id' => $user->id,
                'file_path' => $path,
            ]);
        }
        if ($request->hasFile('address_image')) {
            $path = $this->FileService->imgUpload($request->file('address_image'),'address-image');
            Document::create([
                'documentable_type' => 'address_image',
                'documentable_id' => $user->id,
                'file_path' => $path,
            ]);
        }

        Address::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'address_type_id' => $request->address_type,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
