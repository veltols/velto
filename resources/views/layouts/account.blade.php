<x-app-layout>
    <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12 py-12" x-data="{ sidebarOpen: false }">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Sidebar -->
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="flex justify-between items-center mb-6 lg:mb-6">
                    <h2 class="text-xl font-serif font-bold">My Account</h2>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="space-y-1 lg:block" :class="{'hidden': !sidebarOpen}">
                    <a href="{{ route('account.index') }}" class="block px-4 py-3 {{ request()->routeIs('account.index') ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-50' }} text-sm font-bold uppercase tracking-widest rounded-sm transition">Dashboard</a>
                    <a href="{{ route('account.orders') }}" class="block px-4 py-3 {{ request()->routeIs('account.orders*') ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-50' }} text-sm font-bold uppercase tracking-widest rounded-sm transition">Orders</a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 {{ request()->routeIs('profile.edit') ? 'bg-black text-white' : 'text-gray-600 hover:bg-gray-50' }} text-sm font-bold uppercase tracking-widest rounded-sm transition">Account Details</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-gray-100 pt-4">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-gray-600 hover:bg-gray-100 text-sm font-bold uppercase tracking-widest rounded-sm transition">Log Out</button>
                    </form>
                </nav>
            </div>

            <!-- Content -->
            <div class="flex-1">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
