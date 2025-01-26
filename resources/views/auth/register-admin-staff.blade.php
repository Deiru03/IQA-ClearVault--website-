<x-guest-layout>
<form method="POST" action="{{ route('registerAdminStaff') }}" class="w-full bg-white p-1 rounded-lg ">
    @csrf

    @if ($errors->any())
    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-center text-gray-800">Create an Admin Staff Account</h2>

    <div class="grid grid-cols-2 gap-3 mb-4">
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-xs font-medium text-gray-700" />
            <x-text-input id="name" class="mt-1 block w-full text-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-medium text-gray-700" />
            <x-text-input id="email" class="mt-1 block w-full text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Campus and Office -->
    <div class="grid grid-cols-2 gap-3 mb-4">
        <div>
            <x-input-label for="campus_id" :value="__('Campus')" class="text-xs font-medium text-gray-700" />
            <select id="campus_id" name="campus_id" class="mt-1 block w-full text-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                <option value="">Select Campus</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                @endforeach
            </select>
            @error('campus_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <x-input-label for="office_id" :value="__('Office')" class="text-xs font-medium text-gray-700" />
            <select id="office_id" name="office_id" class="mt-1 block w-full text-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                <option value="">Select Office</option>
            </select>
            @error('office_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-4">
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-medium text-gray-700" />
            <x-text-input id="password" class="mt-1 block w-full text-sm" type="password" name="password" required autocomplete="new-password" />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-medium text-gray-700" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full text-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-between mb-4">
        <a class="text-md text-blue-600 hover:text-blue-600" href="{{ route('login') }}">
            {{ __('Sign In') }}
        </a>
        <x-primary-button class="ml-3 px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700">
            {{ __('Register') }}
        </x-primary-button>
    </div>

    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or continue with</span>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('google.login') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-sky-100">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Google
        </a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const campusSelect = document.getElementById('campus_id');
        const officeSelect = document.getElementById('office_id');

        campusSelect.addEventListener('change', function() {
            const campusId = this.value;
            fetch(`/get-offices/${campusId}`)
                .then(response => response.json())
                .then(data => {
                    officeSelect.innerHTML = '<option value="">Select Office</option>';
                    data.forEach(office => {
                        officeSelect.innerHTML += `<option value="${office.id}">${office.name}</option>`;
                    });
                })
                .catch(error => console.error('Error fetching offices:', error));
        });
    });
</script>

</x-guest-layout>