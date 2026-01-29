<x-app-layout>
    <div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white shadow-sm overflow-hidden border border-gray-100">
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-serif font-bold text-gray-900 mb-2">Welcome Back</h1>
                <p class="text-sm text-gray-500 uppercase tracking-widest">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-900 mb-2">Email Address</label>
                    <input id="email" class="block w-full border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-900">Password</label>
                    </div>
                    <input id="password" class="block w-full border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-colors"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 border-gray-200 text-black focus:ring-0 focus:ring-offset-0" name="remember">
                    <label for="remember_me" class="ml-3 text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">{{ __('Remember me') }}</label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-black text-white py-4 text-xs font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all transform active:scale-[0.98] shadow-lg hover:shadow-xl">
                        {{ __('Sign In') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
