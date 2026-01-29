<x-account-layout>
    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        @if(!Auth::user()->is_admin)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        @endif
    </div>
</x-account-layout>
