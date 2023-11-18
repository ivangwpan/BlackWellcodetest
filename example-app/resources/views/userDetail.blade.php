<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('userUpdate') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="d-flex">
            <div class="mt-4">
                <x-input-label for="profile_image" :value="__('Profile Image')" />
                <img src="{{ asset($profileImage->file_path) }}" alt="Profile Image" class="block mt-1 w-40 h-40 rounded-md object-cover" />
            </div>
            
            <div class="mt-4">
                <x-input-label for="address_image" :value="__('Address Image')" />
                <img src="{{ asset($addressImage->file_path) }}" alt="Address Image" class="block mt-1 w-40 h-40 rounded-md object-cover" />
            </div>
        </div>

        <div>
            <x-input-label for="first_name" :value="__('FirstName')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)"
                required autofocus autocomplete="first_name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('LastName')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)"
                required autofocus autocomplete="last_name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <p for="email" :value="__('Email')" />
            <p id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <p class="mt-2" :messages="$errors->get('email')" />

        </div>

        <!-- Address Type -->
        <div class="mt-4">
            <x-input-label for="address_type" :value="__('Address Type')" />
            <select id="address_type" class="block mt-1 w-full" name="address_type" required
                autocomplete="address_type">
                @foreach ($addressTypes as $addressType)
                    <option value="{{ $addressType->id }}" @if ($addressType->id === $addresses->address_type_id) selected @endif>
                        {{ $addressType->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('address_type')" class="mt-2" />
        </div>


        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $addresses->address)"
                required autocomplete="address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Zipcode -->
        <div class="mt-4">
            <x-input-label for="zipcode" :value="__('Zipcode')" />
            <x-text-input id="zipcode" class="block mt-1 w-full" type="text" name="zipcode" :value="old('zipcode', $addresses->zipcode)"
                required autocomplete="zipcode" />
            <x-input-error :messages="$errors->get('zipcode')" class="mt-2" />
        </div>

        <!-- City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $addresses->city)"
                required autocomplete="city" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- State -->
        <div class="mt-4">
            <x-input-label for="state" :value="__('State')" />
            <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state', $addresses->state)"
                required autocomplete="state" />
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country', $addresses->country)"
                required autocomplete="country" />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
