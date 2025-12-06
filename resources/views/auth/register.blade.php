<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')"
                required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>




        <!-- First Horizontal Line -->
        <hr class="mt-7 mb-5 border-gray-100">

        <!-- Employee Id -->
        <div class="mt-4">
            <x-input-label for="employee_code" :value="__('Employee Id')" />
            <x-text-input id="employee_code" class="block mt-1 w-full" type="number" name="employee_code"
                :value="old('employee_code')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('employee_code')" class="mt-2" />
        </div>

        <!-- Team -->
        <div class="mt-4">
            <x-input-label for="team_id" :value="__('Team Name')" />

            <select id="team_id" name="team_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                required>
                <option value="">Select Team</option>
                @foreach (\App\Models\Team::all() as $team)
                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('team_id')" class="mt-2" />
        </div>

        <div class="grid grid-cols-3 gap-4 my-4 mb-10">
            <!-- Floor -->
            <div>
                <x-input-label for="floor" :value="__('Floor')" />
                <x-text-input id="floor" class="block mt-1 w-full" type="text" name="floor" :value="old('floor')"
                    required />
                <x-input-error :messages="$errors->get('floor')" class="mt-2" />
            </div>

            <!-- Row -->
            <div>
                <x-input-label for="row" :value="__('Row')" />
                <x-text-input id="row" class="block mt-1 w-full" type="text" name="row" :value="old('row')"
                    required />
                <x-input-error :messages="$errors->get('row')" class="mt-2" />
            </div>

            <!-- Seat Number -->
            <div>
                <x-input-label for="seat_number" :value="__('Seat Number')" />
                <x-text-input id="seat_number" class="block mt-1 w-full" type="text" name="seat_number"
                    :value="old('seat_number')" required />
                <x-input-error :messages="$errors->get('seat_number')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
