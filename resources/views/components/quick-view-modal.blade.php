<div 
    x-data="quickViewModal()"
    x-cloak
    x-show="isOpen"
    @open-quick-view.window="open($event.detail.id)"
    @keydown.escape.window="close()"
    class="fixed inset-0 z-[9999] overflow-y-auto"
    style="display: none;"
    role="dialog" 
    aria-modal="true"
>
    <!-- Dark Backdrop -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-xs"
        @click="close()"
    ></div>

    <!-- Modal Wrapper -->
    <div class="min-h-full flex items-center justify-center p-3 sm:p-6 text-center">
        <div 
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="relative w-full max-w-4xl bg-white shadow-2xl overflow-hidden text-left my-8"
            @click.outside="close()"
        >
            <!-- Close Button (styled exactly like reference: black square in top right) -->
            <button 
                type="button" 
                @click="close()" 
                class="absolute top-0 right-0 z-30 bg-black text-white p-2.5 hover:bg-neutral-800 transition focus:outline-none"
                aria-label="Close modal"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Loading Spinner State -->
            <div x-show="isLoading" class="p-16 flex flex-col items-center justify-center min-h-[400px]">
                <svg class="animate-spin h-9 w-9 text-black mb-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-xs uppercase tracking-widest text-gray-500 font-bold">Loading product details...</p>
            </div>

            <!-- Product Quick View Content (Layout matching uploaded screenshot) -->
            <div x-show="!isLoading && product" class="grid grid-cols-1 md:grid-cols-2 gap-0">
                
                <!-- Left: Product Image & Gallery -->
                <div class="p-6 md:p-8 bg-white flex flex-col justify-between border-b md:border-b-0 md:border-r border-gray-100">
                    <!-- Main Big Image -->
                    <div class="w-full aspect-[4/4] sm:aspect-[4/3.8] bg-white flex items-center justify-center mb-4 overflow-hidden">
                        <img 
                            :src="activeImage" 
                            :alt="product ? product.name : ''" 
                            class="w-full h-full object-contain object-center transition-all duration-300"
                        >
                    </div>

                    <!-- Thumbnails row -->
                    <div class="flex items-center gap-2.5 overflow-x-auto pb-1" x-show="product && product.images && product.images.length > 1">
                        <template x-for="img in product ? product.images : []" :key="img.id">
                            <button 
                                type="button" 
                                @click="activeImage = img.url"
                                class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 border transition-all duration-150 p-1 bg-white"
                                :class="activeImage === img.url ? 'border-black ring-1 ring-black' : 'border-gray-200 hover:border-gray-400 opacity-70 hover:opacity-100'"
                            >
                                <img :src="img.url" class="w-full h-full object-contain">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Right: Product Info & Actions -->
                <div class="p-6 md:p-10 flex flex-col justify-between">
                    <div>
                        <!-- Title -->
                        <h2 class="text-xl sm:text-2xl font-bold font-sans text-gray-950 tracking-tight leading-snug mb-2" x-text="product ? product.name : ''"></h2>

                        <!-- Price -->
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-base sm:text-lg font-bold text-gray-900" x-text="'Rs.' + Number(effectivePrice).toLocaleString() + ' PKR'"></span>
                            <template x-if="product && product.is_on_sale">
                                <span class="text-sm text-gray-400 line-through" x-text="'Rs.' + Number(product.base_price).toLocaleString()"></span>
                            </template>
                        </div>

                        <div class="w-full h-px bg-gray-100 mb-5"></div>

                        <!-- Description Snippet -->
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed line-clamp-3 mb-6" x-text="product ? product.description : ''"></p>

                        <!-- COLOR Selection -->
                        <div class="mb-5" x-show="uniqueColors.length > 0">
                            <span class="text-xs font-black uppercase tracking-wider text-black block pb-1 border-b-2 border-black w-fit mb-3">COLOR</span>
                            <div class="flex items-center gap-2 pt-1">
                                <template x-for="color in uniqueColors" :key="color">
                                    <button 
                                        type="button" 
                                        @click="selectColor(color)"
                                        class="w-7 h-7 rounded-full border-2 transition-all p-0.5"
                                        :class="selectedColor === color ? 'border-black ring-1 ring-black' : 'border-gray-300 hover:border-black'"
                                        :title="color"
                                    >
                                        <span class="block w-full h-full rounded-full" :style="'background-color: ' + getColorHex(color)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- SIZE Selection -->
                        <div class="mb-6" x-show="availableSizes.length > 0">
                            <span class="text-xs font-black uppercase tracking-wider text-black block pb-1 border-b-2 border-black w-fit mb-3">SIZE</span>
                            <div class="flex flex-wrap gap-2 pt-1">
                                <template x-for="variant in availableSizes" :key="variant.id">
                                    <button 
                                        type="button" 
                                        @click="selectedVariant = variant"
                                        class="min-w-[42px] h-10 px-2.5 flex items-center justify-center border text-xs font-bold transition-all"
                                        :class="selectedVariant && selectedVariant.id === variant.id 
                                            ? 'bg-black text-white border-black' 
                                            : (variant.stock_quantity <= 0 ? 'border-gray-200 text-gray-300 line-through bg-gray-50 cursor-not-allowed' : 'border-gray-300 text-gray-800 hover:border-black bg-white')"
                                    >
                                        <span x-text="variant.size"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Add to Cart (Styled matching screenshot: boxed qty on left, solid ADD TO CART on right) -->
                    <div class="pt-2">
                        <div class="flex items-center gap-3">
                            <!-- Boxed Quantity Selector -->
                            <div class="flex items-stretch border border-black w-24 h-11 flex-shrink-0">
                                <div class="flex-1 flex items-center justify-center font-bold text-sm text-black" x-text="quantity"></div>
                                <div class="w-8 flex flex-col border-l border-black">
                                    <button 
                                        type="button" 
                                        class="flex-1 flex items-center justify-center text-xs font-bold hover:bg-gray-100 select-none border-b border-black leading-none"
                                        @click="quantity++"
                                    >+</button>
                                    <button 
                                        type="button" 
                                        class="flex-1 flex items-center justify-center text-xs font-bold hover:bg-gray-100 select-none leading-none"
                                        @click="if(quantity > 1) quantity--"
                                    >−</button>
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <button 
                                type="button" 
                                @click="addToCartFromModal()"
                                :disabled="adding || !canAddToCart"
                                class="flex-1 bg-black text-white h-11 px-6 text-xs sm:text-sm font-black uppercase tracking-[0.15em] hover:bg-neutral-800 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2"
                            >
                                <span x-text="adding ? 'ADDING...' : (isOutOfStock ? 'OUT OF STOCK' : 'ADD TO CART')"></span>
                            </button>
                        </div>

                        <!-- View Full Details Link -->
                        <div class="mt-4 text-center">
                            <a :href="product ? product.show_url : '#'" class="text-xs font-semibold text-gray-500 hover:text-black uppercase tracking-wider underline">
                                View Full Product Details →
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function quickViewModal() {
        return {
            isOpen: false,
            isLoading: false,
            adding: false,
            product: null,
            activeImage: '',
            selectedColor: null,
            selectedVariant: null,
            quantity: 1,

            open(id) {
                this.isOpen = true;
                this.isLoading = true;
                this.product = null;
                this.quantity = 1;
                this.selectedColor = null;
                this.selectedVariant = null;

                fetch(`/products/${id}/quick-view`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.product) {
                        this.product = data.product;
                        // Set primary/first image as active
                        const primary = data.product.images.find(img => img.is_primary) || data.product.images[0];
                        this.activeImage = primary ? primary.url : '';

                        // Auto-select first color
                        if (this.uniqueColors.length > 0) {
                            this.selectColor(this.uniqueColors[0]);
                        } else if (this.product.variants && this.product.variants.length > 0) {
                            const firstInStock = this.product.variants.find(v => v.stock_quantity > 0) || this.product.variants[0];
                            this.selectedVariant = firstInStock;
                        }
                    }
                    this.isLoading = false;
                })
                .catch(err => {
                    console.error('Quick view error:', err);
                    showNotification('Could not load product details', 'error');
                    this.close();
                });
            },

            close() {
                this.isOpen = false;
            },

            get uniqueColors() {
                if (!this.product || !this.product.variants) return [];
                const colors = this.product.variants.map(v => v.color).filter(Boolean);
                return [...new Set(colors)];
            },

            get availableSizes() {
                if (!this.product || !this.product.variants) return [];
                if (!this.selectedColor && this.uniqueColors.length > 0) return [];
                if (this.uniqueColors.length === 0) return this.product.variants;

                return this.product.variants.filter(v => v.color === this.selectedColor);
            },

            get effectivePrice() {
                if (!this.product) return 0;
                if (this.selectedVariant) {
                    return this.selectedVariant.sale_price || this.selectedVariant.price || this.product.sale_price || this.product.base_price;
                }
                return this.product.sale_price || this.product.base_price;
            },

            get canAddToCart() {
                if (!this.product) return false;
                if (this.product.variants && this.product.variants.length > 0) {
                    if (!this.selectedVariant) return false;
                    if (this.selectedVariant.stock_quantity <= 0) return false;
                }
                return true;
            },

            get isOutOfStock() {
                if (!this.product) return false;
                if (this.selectedVariant && this.selectedVariant.stock_quantity <= 0) return true;
                if (this.product.variants && this.product.variants.length > 0) {
                    const totalStock = this.product.variants.reduce((acc, v) => acc + (v.stock_quantity || 0), 0);
                    return totalStock <= 0;
                }
                return false;
            },

            selectColor(color) {
                this.selectedColor = color;
                const sizes = this.product.variants.filter(v => v.color === color);
                const firstAvailable = sizes.find(v => v.stock_quantity > 0) || sizes[0];
                this.selectedVariant = firstAvailable || null;
            },

            getColorHex(name) {
                const map = {
                    'black': '#111111',
                    'brown': '#6b3e26',
                    'dark brown': '#3e2723',
                    'tan': '#d2b48c',
                    'burgundy': '#800020',
                    'blue': '#1e3a8a',
                    'navy': '#0f172a',
                    'white': '#ffffff',
                    'grey': '#6b7280',
                    'gray': '#6b7280'
                };
                return map[name.toLowerCase()] || '#111111';
            },

            addToCartFromModal() {
                if (!this.canAddToCart) {
                    showNotification('Please select an available size', 'error');
                    return;
                }

                this.adding = true;
                addToCart(this.product.id, this.quantity, this.selectedVariant ? this.selectedVariant.id : null)
                    .then((res) => {
                        this.adding = false;
                        if (res && res.success) {
                            this.close();
                        }
                    })
                    .catch(() => {
                        this.adding = false;
                    });
            }
        };
    }
</script>
