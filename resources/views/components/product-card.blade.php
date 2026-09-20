@props(['product'])

@php
    // Primary image
    if ($product->primaryImage) {
        $primaryImage = asset('storage/' . $product->primaryImage->image_path);
        $primaryPath  = $product->primaryImage->image_path;
    } elseif ($product->images && $product->images->isNotEmpty()) {
        $primaryImage = asset('storage/' . $product->images->first()->image_path);
        $primaryPath  = $product->images->first()->image_path;
    } else {
        $primaryImage = asset('images/hero-shoes.png');
        $primaryPath  = null;
    }

    // Secondary image — pick the first image whose path differs from the primary
    $secondaryImage = null;
    if ($product->images && $product->images->count() > 1) {
        $altImage = $product->images->filter(fn($img) => $img->image_path !== $primaryPath)->first();
        if ($altImage) {
            $secondaryImage = asset('storage/' . $altImage->image_path);
        }
    }

    // Truncate product title to 4 words
    $displayTitle = \Illuminate\Support\Str::words($product->name, 4, '...');
@endphp

<div class="relative flex flex-col h-full cursor-pointer">
    
    {{-- Product Image Container — 'group' scoped HERE so hover only fires on image area --}}
    <div class="group relative w-full aspect-[3/4] bg-white overflow-hidden mb-3.5 rounded-none">
        <a href="{{ route('product.show', $product->slug) }}" class="absolute inset-0 block">

            {{-- Primary Image --}}
            <img src="{{ $primaryImage }}"
                 alt="{{ $product->name }}"
                 class="absolute inset-0 w-full h-full object-contain object-center transition-opacity duration-500 ease-in-out z-10 {{ $secondaryImage ? 'group-hover:opacity-0' : '' }}"
                 onerror="this.onerror=null;this.src='{{ asset('images/hero-shoes.png') }}';">

            {{-- Secondary Image (shown on hover) --}}
            @if($secondaryImage)
                <img src="{{ $secondaryImage }}"
                     alt="{{ $product->name }} Alternate View"
                     class="absolute inset-0 w-full h-full object-contain object-center transition-opacity duration-500 ease-in-out z-20 opacity-0 group-hover:opacity-100"
                     onerror="this.onerror=null;this.src='{{ $primaryImage }}';">
            @endif
        </a>

        {{-- Top Right "Sale" Tag --}}
        @if($product->isOnSale())
            <div class="absolute top-0 right-0 bg-black text-white text-[10px] sm:text-[11px] font-medium px-2.5 py-1 tracking-wider uppercase z-10 pointer-events-none shadow-sm">
                Sale
            </div>
        @endif

        {{-- Top Left Badge: Sold Out --}}
        @if($product->variants->sum('stock_quantity') <= 0)
            <div class="absolute top-0 left-0 bg-gray-900/90 text-white text-[10px] sm:text-xs font-bold px-2.5 py-1 tracking-widest uppercase z-10 pointer-events-none">
                SOLD OUT
            </div>
        @endif

        {{-- Quick View Button (Floats at bottom of image on hover) --}}
        <div class="absolute inset-x-3 bottom-3 z-30 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
            <button 
                type="button" 
                @click.stop="$dispatch('open-quick-view', { id: {{ $product->id }} })"
                class="w-full bg-white/95 hover:bg-black text-gray-900 hover:text-white text-[11px] font-bold uppercase tracking-[0.14em] py-2.5 px-3 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-1.5 backdrop-blur-xs border border-gray-100"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Quick View</span>
            </button>
        </div>
    </div>

    {{-- Product Meta Details (Centered text layout matching screenshot) --}}
    <div class="flex flex-col flex-grow items-center text-center">
        
        {{-- Product Title (Uppercase, 5-word limit + '...') --}}
        <h3 class="text-xs sm:text-sm font-sans font-normal uppercase tracking-[0.18em] text-[#333333] group-hover:text-black transition-colors mb-1.5 leading-tight text-center">
            <a href="{{ route('product.show', $product->slug) }}" title="{{ $product->name }}">
                {{ $displayTitle }}
            </a>
        </h3>

        {{-- Price & Discount Row (Centered below title matching screenshot) --}}
        <div class="flex items-center justify-center flex-wrap gap-1.5 text-center mt-auto pt-0.5">
            @if($product->isOnSale())
                <span class="text-[11px] sm:text-xs text-gray-400 line-through font-normal">
                    PKR {{ number_format($product->base_price) }}
                </span>
                <span class="text-[11px] sm:text-xs font-medium text-gray-900">
                    PKR {{ number_format($product->sale_price) }}
                </span>
                <span class="text-[11px] sm:text-xs font-medium text-[#d93838]">
                    Save {{ $product->discountPercentage() }}%
                </span>
            @else
                <span class="text-[11px] sm:text-xs font-medium text-gray-900">
                    PKR {{ number_format($product->base_price) }}
                </span>
            @endif
        </div>

    </div>

</div>
