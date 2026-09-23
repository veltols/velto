@props(['product'])

@php
    // Collect all unique images for the product in order (primary first, then display_order)
    $cardImages = collect();

    if ($product->images && $product->images->isNotEmpty()) {
        $sortedImages = $product->images->sortBy([
            ['is_primary', 'desc'],
            ['display_order', 'asc'],
            ['id', 'asc'],
        ]);
        
        foreach ($sortedImages as $img) {
            if (!empty($img->image_path)) {
                $cardImages->push([
                    'url' => asset('storage/' . $img->image_path),
                    'alt' => $product->name,
                ]);
            }
        }
    } elseif ($product->primaryImage && !empty($product->primaryImage->image_path)) {
        $cardImages->push([
            'url' => asset('storage/' . $product->primaryImage->image_path),
            'alt' => $product->name,
        ]);
    }

    // Deduplicate by URL
    $cardImages = $cardImages->unique('url')->values();

    if ($cardImages->isEmpty()) {
        $cardImages->push([
            'url' => asset('images/hero-shoes.png'),
            'alt' => $product->name,
        ]);
    }

    $imageCount = $cardImages->count();

    // Truncate product title to 4 words
    $displayTitle = \Illuminate\Support\Str::words($product->name, 4, '...');

    // Check stock status
    $isInStock = true;
    if ($product->relationLoaded('variants')) {
        $isInStock = $product->variants->sum('stock_quantity') > 0;
    } elseif ($product->variants) {
        $isInStock = $product->variants->sum('stock_quantity') > 0;
    }
@endphp

<div 
    @if($imageCount > 1)
        x-data="{
            activeIndex: 0,
            total: {{ $imageCount }},
            touchStartX: 0,
            touchStartY: 0,
            isSwiping: false,
            next() {
                this.activeIndex = (this.activeIndex + 1) % this.total;
            },
            prev() {
                this.activeIndex = (this.activeIndex - 1 + this.total) % this.total;
            },
            handleTouchStart(e) {
                this.touchStartX = e.touches[0].clientX;
                this.touchStartY = e.touches[0].clientY;
                this.isSwiping = false;
            },
            handleTouchMove(e) {
                const diffX = Math.abs(e.touches[0].clientX - this.touchStartX);
                const diffY = Math.abs(e.touches[0].clientY - this.touchStartY);
                if (diffX > diffY && diffX > 10) {
                    this.isSwiping = true;
                }
            },
            handleTouchEnd(e) {
                if (!this.touchStartX) return;
                const diffX = this.touchStartX - e.changedTouches[0].clientX;
                const diffY = Math.abs(this.touchStartY - e.changedTouches[0].clientY);
                if (Math.abs(diffX) > 40 && Math.abs(diffX) > diffY) {
                    if (diffX > 0) this.next();
                    else this.prev();
                    setTimeout(() => { this.isSwiping = false; }, 80);
                } else {
                    this.isSwiping = false;
                }
            }
        }"
    @endif
    class="relative flex flex-col h-full cursor-pointer rounded-sm bg-white shadow-[0_2px_12px_rgba(0,0,0,0.07)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.13)] transition-all duration-300 hover:-translate-y-1 p-2.5 pb-4"
>
    
    {{-- Product Image Container — 'group' scoped HERE so hover only fires on image area --}}
    <div 
        class="group relative w-full aspect-[3/4] bg-white overflow-hidden mb-3.5 rounded-none select-none"
        @if($imageCount > 1)
            @touchstart.passive="handleTouchStart($event)"
            @touchmove.passive="handleTouchMove($event)"
            @touchend="handleTouchEnd($event)"
        @endif
    >
        <a 
            href="{{ route('product.show', $product->slug) }}" 
            class="absolute inset-0 block w-full h-full overflow-hidden"
            @if($imageCount > 1)
                @click="if (isSwiping) { $event.preventDefault(); isSwiping = false; }"
            @endif
        >
            @if($imageCount > 1)
                {{-- Sliding Track --}}
                <div 
                    class="flex h-full w-full transition-transform duration-300 ease-out will-change-transform"
                    :style="'transform: translateX(-' + (activeIndex * 100) + '%)'"
                >
                    @foreach($cardImages as $idx => $img)
                        <div class="w-full h-full flex-shrink-0 flex items-center justify-center relative bg-white">
                            <img 
                                src="{{ $img['url'] }}"
                                alt="{{ $img['alt'] }} - View {{ $idx + 1 }}"
                                class="w-full h-full object-contain object-center pointer-events-none"
                                @if($idx > 0) loading="lazy" @endif
                                onerror="this.onerror=null;this.src='{{ asset('images/hero-shoes.png') }}';"
                            >
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Single Static Image --}}
                <img 
                    src="{{ $cardImages[0]['url'] }}"
                    alt="{{ $cardImages[0]['alt'] }}"
                    class="w-full h-full object-contain object-center transition-transform duration-500 group-hover:scale-105"
                    onerror="this.onerror=null;this.src='{{ asset('images/hero-shoes.png') }}';"
                >
            @endif
        </a>

        {{-- Top Right "Sale" Tag --}}
        @if($product->isOnSale())
            <div class="absolute top-0 right-0 bg-black text-white text-[10px] sm:text-[11px] font-medium px-2.5 py-1 tracking-wider uppercase z-20 pointer-events-none shadow-sm">
                Sale
            </div>
        @endif

        {{-- Top Left Badge: Sold Out --}}
        @if(!$isInStock)
            <div class="absolute top-0 left-0 bg-gray-900/90 text-white text-[10px] sm:text-xs font-bold px-2.5 py-1 tracking-widest uppercase z-20 pointer-events-none">
                SOLD OUT
            </div>
        @endif

        @if($imageCount > 1)
            {{-- Navigation Arrows (Left & Right) --}}
            <button 
                type="button" 
                @click.stop.prevent="prev()"
                aria-label="Previous image"
                class="absolute left-1.5 top-1/2 -translate-y-1/2 z-20 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/90 hover:bg-black text-gray-800 hover:text-white shadow-md flex items-center justify-center opacity-75 sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none border border-gray-100/60 backdrop-blur-xs"
            >
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button 
                type="button" 
                @click.stop.prevent="next()"
                aria-label="Next image"
                class="absolute right-1.5 top-1/2 -translate-y-1/2 z-20 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-white/90 hover:bg-black text-gray-800 hover:text-white shadow-md flex items-center justify-center opacity-75 sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-200 hover:scale-110 active:scale-95 focus:outline-none border border-gray-100/60 backdrop-blur-xs"
            >
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Slider Pagination Dots (Rests at bottom, smoothly shifts up when Quick View hovers on desktop) --}}
            <div class="absolute inset-x-0 bottom-2.5 z-20 flex justify-center items-center gap-1 sm:gap-1.5 pointer-events-none group-hover:bottom-14 transition-all duration-300">
                @foreach($cardImages as $idx => $img)
                    <button 
                        type="button" 
                        @click.stop.prevent="activeIndex = {{ $idx }}" 
                        class="h-1 sm:h-1.5 rounded-full transition-all duration-300 pointer-events-auto cursor-pointer focus:outline-none"
                        :class="activeIndex === {{ $idx }} ? 'w-3.5 sm:w-4 bg-black' : 'w-1 sm:w-1.5 bg-black/25 hover:bg-black/60'"
                        aria-label="View image {{ $idx + 1 }}"
                    ></button>
                @endforeach
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

    {{-- Product Meta Details --}}
    <div class="flex flex-col flex-grow items-center text-center">
        
        {{-- Product Title (Uppercase, 4-word limit + '...') --}}
        <h3 class="text-xs sm:text-sm font-sans font-normal uppercase tracking-[0.18em] text-[#333333] group-hover:text-black transition-colors mb-1.5 leading-tight text-center">
            <a href="{{ route('product.show', $product->slug) }}" title="{{ $product->name }}">
                {{ $displayTitle }}
            </a>
        </h3>

        {{-- Price & Discount Row --}}
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
