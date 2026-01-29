<x-app-layout>
    <div class="bg-white min-h-[85vh] flex flex-col items-center justify-center px-6 py-20">
        <div class="max-w-4xl w-full text-center">
            <!-- Content Area with precise vertical gaps -->
            <div class="flex flex-col items-center">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <h1 class="text-5xl sm:text-7xl font-serif font-medium text-gray-900 leading-tight">
                            Page Not <span class="italic font-bold">Found</span>
                        </h1>
                    </div>

                    <div class="max-w-lg mx-auto py-8 border-y border-gray-50">
                        <p class="text-base sm:text-lg text-gray-500 font-medium leading-relaxed">
                            It seems you've wandered into an uncharted corner of our archive. <br class="hidden sm:block">
                            Let's guide you back to the collection.
                        </p>
                    </div>

                    <div class="pt-10 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6">
                        <a href="{{ route('home') }}" 
                           class="w-full sm:w-auto min-w-[220px] bg-black text-white px-8 py-5 text-xs font-bold uppercase tracking-[0.25em] hover:bg-gray-800 transition-all shadow-xl active:scale-[0.98]">
                            Return to Home
                        </a>
                        <a href="{{ route('shop.index') }}" 
                           class="w-full sm:w-auto min-w-[220px] bg-white text-black border border-gray-200 px-8 py-5 text-xs font-bold uppercase tracking-[0.25em] hover:bg-gray-50 transition-all active:scale-[0.98]">
                            Browse Shop
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
