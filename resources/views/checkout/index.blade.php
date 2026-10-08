<x-app-layout>
    <!-- intl-tel-input CSS with CDN fallback for live server -->
    <link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/css/intlTelInput.min.css';">
    <style>
        .iti {
            width: 100% !important;
            display: block !important;
        }
        .iti input.iti__tel-input {
            width: 100% !important;
            height: 42px !important;
            border-radius: 0.375rem !important;
            border: 1px solid #d1d5db !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
        }
        .iti input.iti__tel-input:focus {
            border-color: #000000 !important;
            box-shadow: 0 0 0 1px #000000 !important;
            outline: none !important;
        }
        .iti input.iti__tel-input.iti__input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
        }
        .iti input.iti__tel-input.iti__input-valid {
            border-color: #10b981 !important;
        }
        .iti__country-container {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }
        .iti__selected-country {
            padding: 0 8px 0 12px !important;
        }
        .iti__selected-dial-code {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #374151 !important;
            margin-left: 6px !important;
        }
        .iti__country-list {
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid #e5e7eb !important;
            z-index: 50 !important;
        }
        .iti__country-name {
            font-size: 0.8125rem !important;
        }

        /* Spinner animation fallback */
        @keyframes custom-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-custom-spin {
            animation: custom-spin 0.8s linear infinite !important;
        }
    </style>

    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8 text-center">Checkout</h1>
            
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" novalidate class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                @csrf
                
                <!-- Contact & Shipping Info -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 mb-6">Contact & Shipping</h2>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user() ? auth()->user()->name : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user() ? auth()->user()->email : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            @error('email') <p class="text-gray-900 font-bold text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Country / Region Field (Defaulted to Pakistan) -->
                        <div class="sm:col-span-2">
                            <label for="country" class="block text-sm font-medium text-gray-700">Country / Region</label>
                            <div class="mt-1 relative">
                                <select name="country" id="country" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm bg-gray-50/70 font-medium text-gray-900 cursor-default">
                                    <option value="PK" selected>Pakistan (پاکستان)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Phone Number Field with intl-tel-input -->
                        <div class="sm:col-span-2">
                            <div class="flex items-center justify-between">
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Phone Number <span class="text-xs text-gray-500 font-normal">(WhatsApp Active)</span>
                                </label>
                                <span id="phone-digit-counter" class="text-xs font-mono text-gray-400">0/11 digits</span>
                            </div>
                            <div class="mt-1 relative">
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', auth()->user() ? auth()->user()->phone : '') }}" 
                                       required 
                                       autocomplete="tel"
                                       inputmode="numeric"
                                       maxlength="11"
                                       placeholder="0300 1234567"
                                       aria-describedby="phone-format-hint phone-error"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            </div>
                            
                            <!-- Helpful format pattern hint visible upfront -->
                            <p id="phone-format-hint" class="text-xs text-gray-500 mt-1 flex items-center justify-between">
                                <span><strong class="font-medium text-gray-700">Format:</strong> 0303 1234567 (11 digits) or 303 1234567 (10 digits)</span>
                            </p>

                            <!-- Validation Feedback Messages & Prominent Error Box -->
                            <div id="phone-feedback" class="mt-2 min-h-[18px]">
                                <div id="phone-error" class="hidden bg-red-50 border border-red-200 rounded-md p-2.5 text-red-700 text-xs shadow-sm">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p id="phone-error-text" class="font-bold text-red-800">Invalid phone number</p>
                                            <p id="phone-error-subtext" class="text-red-700 mt-0.5">Please enter 11 digits starting with 03 (e.g. 0300 1234567)</p>
                                        </div>
                                    </div>
                                </div>
                                <p id="phone-valid" class="hidden text-emerald-600 text-xs font-semibold flex items-center gap-1.5 pt-0.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Valid Pakistani phone number</span>
                                </p>
                                @error('phone') 
                                    <div class="bg-red-50 border border-red-200 rounded-md p-2.5 text-red-700 text-xs mt-1.5">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold">{{ $message }}</span>
                                        </div>
                                    </div>
                                @enderror
                            </div>
                            <p class="text-gray-400 text-[11px] mt-1">We will send your order confirmation and dispatch updates to this number.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">{{ old('shipping_address', auth()->user() ? auth()->user()->address : '') }}</textarea>
                            @error('shipping_address') <p class="text-gray-900 font-bold text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city', auth()->user() ? auth()->user()->city : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', auth()->user() ? auth()->user()->postal_code : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mt-8 lg:mt-0">
                    <div class="bg-white rounded-lg shadow-sm lg:p-6 sticky top-24 overflow-hidden border border-gray-100 lg:border-transparent">
                        
                        <!-- Mobile Accordion Trigger Header (Visible on Mobile only) -->
                        <button type="button" 
                                id="order-summary-toggle" 
                                class="w-full flex lg:hidden items-center justify-between p-4 bg-gray-50/80 hover:bg-gray-100/80 transition-colors text-left border-b border-gray-200"
                                aria-expanded="false" 
                                aria-controls="order-summary-content">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="text-sm font-semibold text-gray-900">
                                    <span id="order-summary-toggle-text">Show order summary</span>
                                    <span class="text-xs text-gray-500 font-normal">({{ $cartItems->sum('quantity') }} {{ Str::plural('item', $cartItems->sum('quantity')) }})</span>
                                </span>
                                <svg id="order-summary-chevron" class="w-4 h-4 text-gray-500 transition-transform duration-200 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-900" id="mobile-summary-total">Rs. {{ number_format($total) }}</span>
                        </button>

                        <!-- Desktop Header (Always visible on lg screens) -->
                        <div class="hidden lg:flex items-center justify-between mb-6">
                            <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                            <span class="text-xs text-gray-500">({{ $cartItems->sum('quantity') }} {{ Str::plural('item', $cartItems->sum('quantity')) }})</span>
                        </div>

                        <!-- Accordion Content Container (Collapsed by default on mobile, always visible on desktop) -->
                        <div id="order-summary-content" class="hidden lg:block p-4 lg:p-0">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                    <li class="flex py-5 lg:py-6">
                                         <div class="h-20 w-20 lg:h-24 lg:w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            @if($item->product->primaryImage)
                                                @php 
                                                    $path = $item->product->primaryImage->image_path;
                                                    $url = Str::startsWith($path, 'http') ? $path : asset('storage/' . $path);
                                                @endphp
                                                 <img src="{{ $url }}" 
                                                      class="h-full w-full object-cover object-center"
                                                      onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                            @else
                                                <div class="h-full w-full bg-gray-100 flex items-center justify-center text-xs">No Img</div>
                                            @endif
                                        </div>

                                        <div class="ml-4 lg:ml-6 flex-1 flex flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between text-sm lg:text-base font-medium text-gray-900">
                                                    <h3 class="pr-2">{{ $item->product->name }}</h3>
                                                    <div class="flex flex-col items-end flex-shrink-0">
                                                        @php
                                                            $onSale = $item->variant ? $item->variant->isOnSale() : $item->product->isOnSale();
                                                            $currentPrice = $item->variant ? $item->variant->final_price : $item->product->price;
                                                            $originalPrice = $item->variant ? ($item->variant->price ?: $item->product->base_price) : $item->product->base_price;
                                                        @endphp
                                                        <p class="font-bold text-black">Rs. {{ number_format($currentPrice * $item->quantity) }}</p>
                                                        @if($onSale)
                                                            <p class="text-xs text-gray-400 line-through text-right">Rs. {{ number_format($originalPrice * $item->quantity) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="mt-1 text-xs lg:text-sm text-gray-500">{{ $item->variant ? $item->variant->size . ' | ' . $item->variant->color : '' }}</p>
                                            </div>
                                            <div class="flex items-end justify-between text-xs lg:text-sm pt-2">
                                                <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="border-t border-gray-200 pt-5 lg:pt-6 mt-4 lg:mt-6 space-y-3">
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rs. {{ number_format($subtotal) }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600 flex flex-col">
                                        <span>Shipping</span>
                                        <span id="shipping-method-name" class="text-[11px] text-gray-400 font-normal">
                                            {{ $shippingData['name'] ?? 'Standard Delivery' }}
                                            @if(!empty($shippingData['notes']))
                                                ({{ $shippingData['notes'] }})
                                            @endif
                                        </span>
                                    </dt>
                                    <dd class="text-sm font-medium text-gray-900" id="shipping-display">
                                        @if($shipping <= 0)
                                            <span class="text-emerald-600 font-bold">FREE</span>
                                        @else
                                            Rs. {{ number_format($shipping) }}
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between border-t border-gray-200 pt-3 mt-3">
                                    <dt class="text-base font-medium text-gray-900">Order Total</dt>
                                    <dd class="text-base font-bold text-gray-900" id="total-display">Rs. {{ number_format($total) }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Checkout Actions (Always accessible) -->
                        <div class="p-4 lg:p-0">

                        <div class="mt-6 border-t border-gray-200 pt-6">
                             <div class="flex items-center mb-4">
                                <input id="payment_cod" name="payment_method" type="radio" checked class="h-4 w-4 border-gray-300 text-black focus:ring-black">
                                <label for="payment_cod" class="ml-3 block text-sm font-medium text-gray-700">Cash on Delivery</label>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">Pay when you receive your order.</p>
                            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 5.323V3a1 1 0 011-1z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-bold text-amber-800">📦 Open Box Policy</h3>
                                        <div class="mt-2 text-sm text-amber-900 space-y-1.5">
                                            <p>✅ You can <strong>open the box and inspect your order</strong> before making the payment to the courier.</p>
                                            <p>❌ <strong>Do not wear the shoes.</strong> If the shoes are worn, the return or refund will not be accepted.</p>
                                            <p class="pt-1 text-xs text-amber-700 font-medium">Once worn, the item is considered accepted and cannot be returned.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="submit-order-btn" class="w-full bg-black border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-black disabled:opacity-75 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition duration-150">
                                <span id="submit-spinner" class="hidden animate-custom-spin" style="display: none;">
                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                </span>
                                <span id="submit-text">Place Order (<span id="submit-total">Rs. {{ number_format($total) }}</span>)</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- intl-tel-input JS with built-in utils/libphonenumber and CDN fallback -->
    <script src="{{ asset('vendor/intl-tel-input/js/intlTelInputWithUtils.min.js') }}"></script>
    <script>
        if (typeof intlTelInput === 'undefined' && typeof window.intlTelInput === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/intlTelInputWithUtils.min.js"><\/script>');
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.querySelector('#phone');
            const phoneError = document.querySelector('#phone-error');
            const phoneErrorText = document.querySelector('#phone-error-text');
            const phoneValid = document.querySelector('#phone-valid');
            const checkoutForm = document.querySelector('#checkout-form');

            if (!phoneInput) return;

            // Resolve factory from window or local scope (optional enhancement)
            const itiFactory = window.intlTelInput || (typeof intlTelInput !== 'undefined' ? intlTelInput : null);
            let iti = null;

            if (itiFactory) {
                try {
                    iti = itiFactory(phoneInput, {
                        initialCountry: "pk",
                        preferredCountries: ["pk", "ae", "sa", "gb", "us"],
                        separateDialCode: true,
                        strictMode: false,
                        countrySearch: true,
                        autoPlaceholder: "aggressive",
                        formatOnDisplay: false
                    });
                } catch (e) {
                    console.warn('iti initialization notice:', e);
                }
            }

            const phoneDigitCounter = document.querySelector('#phone-digit-counter');
            const phoneErrorSubtext = document.querySelector('#phone-error-subtext');

            // Safe helper to get country data across different intl-tel-input versions (v24: getSelectedCountryData(), v29+: getSelectedCountry())
            function getSelectedCountryDataSafe() {
                if (!iti) return { iso2: 'pk' };
                try {
                    if (typeof iti.getSelectedCountryData === 'function') {
                        return iti.getSelectedCountryData() || { iso2: 'pk' };
                    }
                    if (typeof iti.getSelectedCountry === 'function') {
                        return iti.getSelectedCountry() || { iso2: 'pk' };
                    }
                } catch (e) {
                    // Fallback to default
                }
                return { iso2: 'pk' };
            }

            // Adjust input max length based on selected country and current value
            function getMaxDigits() {
                const countryData = getSelectedCountryDataSafe();
                if (countryData && countryData.iso2 === 'pk') {
                    const raw = phoneInput.value.replace(/\D/g, '');
                    // Pakistani numbers: 11 digits if starting with 0 (03001234567), 10 digits if starting with 3 (3001234567)
                    return raw.startsWith('0') ? 11 : 10;
                }
                return 15; // International E.164 max
            }

            function updateMaxLength() {
                const max = getMaxDigits();
                phoneInput.setAttribute('maxlength', String(max));
            }

            function updateDigitCounter() {
                if (!phoneDigitCounter) return;
                const countryData = getSelectedCountryDataSafe();
                const digits = phoneInput.value.replace(/\D/g, '');
                if (!countryData || countryData.iso2 === 'pk') {
                    const target = digits.startsWith('0') ? 11 : (digits.startsWith('3') ? 10 : 11);
                    phoneDigitCounter.textContent = `${digits.length}/${target} digits`;
                    if (digits.length === target) {
                        phoneDigitCounter.className = "text-xs font-mono font-semibold text-emerald-600";
                    } else {
                        phoneDigitCounter.className = "text-xs font-mono text-gray-400";
                    }
                } else {
                    phoneDigitCounter.textContent = `${digits.length} digits`;
                    phoneDigitCounter.className = "text-xs font-mono text-gray-400";
                }
            }

            // 1. Prevent entering more than allowed digits & block non-numeric characters
            phoneInput.addEventListener('keydown', function(e) {
                // Allow control/navigation keys
                if (['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(e.key) ||
                    e.ctrlKey || e.metaKey) {
                    return;
                }

                // Block non-digits
                if (!/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                    return;
                }

                // Check digit limit
                const digits = phoneInput.value.replace(/\D/g, '');
                const hasSelection = phoneInput.selectionStart !== phoneInput.selectionEnd;
                if (!hasSelection) {
                    const max = getMaxDigits();
                    if (digits.length >= max) {
                        e.preventDefault();
                        return;
                    }
                }
            });

            // 2. Filter input in real-time
            phoneInput.addEventListener('input', function() {
                let digits = phoneInput.value.replace(/\D/g, '');
                const countryData = getSelectedCountryDataSafe();

                if (countryData && countryData.iso2 === 'pk') {
                    // Convert 923... to 03... if someone types/pastes 923
                    if (digits.startsWith('923')) {
                        digits = '0' + digits.slice(2);
                    }
                    const max = digits.startsWith('0') ? 11 : 10;
                    if (digits.length > max) {
                        digits = digits.slice(0, max);
                    }
                } else {
                    if (digits.length > 15) {
                        digits = digits.slice(0, 15);
                    }
                }

                if (phoneInput.value !== digits) {
                    phoneInput.value = digits;
                }
                updateMaxLength();
                updateDigitCounter();
                validatePhone(false);
            });

            // 3. Handle paste cleanly
            phoneInput.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
                let digits = pasted.replace(/\D/g, '');
                const countryData = getSelectedCountryDataSafe();

                if (countryData && countryData.iso2 === 'pk') {
                    if (digits.startsWith('923')) {
                        digits = '0' + digits.slice(2);
                    }
                    const max = digits.startsWith('0') ? 11 : 10;
                    digits = digits.slice(0, max);
                } else {
                    digits = digits.slice(0, 15);
                }

                phoneInput.value = digits;
                updateMaxLength();
                updateDigitCounter();
                validatePhone(false);
            });

            // 4. Validation logic
            function showPhoneError(title, subtitle) {
                if (phoneErrorText) phoneErrorText.textContent = title;
                if (phoneErrorSubtext) phoneErrorSubtext.textContent = subtitle;
                if (phoneError) phoneError.classList.remove('hidden');
                if (phoneValid) phoneValid.classList.add('hidden');
                phoneInput.classList.remove('iti__input-valid');
                phoneInput.classList.add('iti__input-error');
            }

            function clearPhoneFeedback() {
                if (phoneError) phoneError.classList.add('hidden');
                if (phoneValid) phoneValid.classList.add('hidden');
                phoneInput.classList.remove('iti__input-error', 'iti__input-valid');
            }

            function validatePhone(showError = true) {
                const val = phoneInput.value.trim();
                const digits = val.replace(/\D/g, '');
                
                clearPhoneFeedback();

                if (!digits) {
                    if (showError) {
                        showPhoneError(
                            "Phone number is required", 
                            "Please enter your 11-digit mobile number starting with 03 (e.g. 0300 1234567)"
                        );
                    }
                    return false;
                }

                const countryData = getSelectedCountryDataSafe();

                // Specific validation for Pakistan (pk / +92)
                if (!countryData || countryData.iso2 === 'pk') {
                    if (digits.startsWith('0')) {
                        if (digits.length < 11) {
                            if (showError) {
                                showPhoneError(
                                    `Incomplete phone number (${digits.length}/11 digits)`, 
                                    `Pakistani mobile numbers must be 11 digits starting with 03 (e.g. 0300 1234567). Please add ${11 - digits.length} more digit(s).`
                                );
                            }
                            return false;
                        }
                        if (!/^03[0-9]{9}$/.test(digits)) {
                            if (showError) {
                                showPhoneError(
                                    "Invalid prefix - must start with 03", 
                                    "Pakistani mobile numbers start with 03 (e.g. 0300, 0301, 0312, 0321, 0333, 0345...)"
                                );
                            }
                            return false;
                        }
                    } else if (digits.startsWith('3')) {
                        if (digits.length < 10) {
                            if (showError) {
                                showPhoneError(
                                    `Incomplete phone number (${digits.length}/10 digits)`, 
                                    `Please enter 10 digits without leading 0 (e.g. 300 1234567) or 11 digits starting with 03.`
                                );
                            }
                            return false;
                        }
                        if (!/^3[0-9]{9}$/.test(digits)) {
                            if (showError) {
                                showPhoneError(
                                    "Invalid mobile number format", 
                                    "Pakistani mobile numbers start with 3 (e.g. 300 1234567) or 03."
                                );
                            }
                            return false;
                        }
                    } else {
                        if (showError) {
                            showPhoneError(
                                "Invalid Pakistani mobile number", 
                                "Mobile numbers in Pakistan start with 03 (e.g. 0300 1234567). Landline numbers are not accepted."
                            );
                        }
                        return false;
                    }

                    // Valid Pakistani number!
                    if (phoneValid) phoneValid.classList.remove('hidden');
                    phoneInput.classList.add('iti__input-valid');
                    return true;
                } else {
                    // International validation
                    if (iti && typeof iti.isValidNumber === 'function' ? iti.isValidNumber() : digits.length >= 8) {
                        if (phoneValid) phoneValid.classList.remove('hidden');
                        phoneInput.classList.add('iti__input-valid');
                        return true;
                    } else {
                        if (showError) {
                            showPhoneError(
                                "Invalid phone number", 
                                "Please enter a valid international mobile phone number for the selected country."
                            );
                        }
                        return false;
                    }
                }
            }

            phoneInput.addEventListener('blur', function() {
                validatePhone(true);
            });

            // Prevent native browser tooltip suppression and show our custom error box instead
            phoneInput.addEventListener('invalid', function(e) {
                e.preventDefault();
                validatePhone(true);
            });

            phoneInput.addEventListener('countrychange', function () {
                phoneInput.value = '';
                clearPhoneFeedback();
                updateMaxLength();
                updateDigitCounter();
                phoneInput.focus();
            });

            // 5. Form submission guard
            if (checkoutForm) {
                const submitBtn = document.querySelector('#submit-order-btn');
                const submitSpinner = document.querySelector('#submit-spinner');
                const submitText = document.querySelector('#submit-text');

                checkoutForm.addEventListener('submit', function (e) {
                    // First check standard HTML5 required fields if any are empty
                    const requiredInputs = checkoutForm.querySelectorAll('input[required], textarea[required], select[required]');
                    for (let el of requiredInputs) {
                        if (!el.value.trim()) {
                            e.preventDefault();
                            el.focus();
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return false;
                        }
                    }

                    // Explicit phone validation
                    const isPhoneValid = validatePhone(true);
                    if (!isPhoneValid) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Scroll smoothly to the phone input and focus it
                        phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(function() {
                            phoneInput.focus();
                        }, 250);
                        return false;
                    }

                    // Normalize value right before submit so it's always in standard format
                    const countryData = getSelectedCountryDataSafe();
                    const digits = phoneInput.value.replace(/\D/g, '');
                    if (!countryData || countryData.iso2 === 'pk') {
                        // Standardize Pakistani number to 03XXXXXXXXX (11 digits)
                        phoneInput.value = digits.startsWith('0') ? digits : ('0' + digits);
                    } else if (iti && typeof iti.getNumber === 'function') {
                        // International format
                        phoneInput.value = iti.getNumber() || phoneInput.value;
                    }

                    // Show spinner and change text immediately
                    if (submitSpinner) {
                        submitSpinner.classList.remove('hidden');
                        submitSpinner.style.display = 'inline-block';
                    }
                    if (submitText) {
                        submitText.textContent = 'Placing Order... Please wait';
                    }
                    if (submitBtn) {
                        // Use pointer-events and opacity to avoid cancelling the submit event in some browsers
                        submitBtn.style.pointerEvents = 'none';
                        submitBtn.style.opacity = '0.75';
                        setTimeout(function () {
                            submitBtn.disabled = true;
                        }, 50);
                    }
                });
            }

            // Initial check and setup
            updateMaxLength();
            updateDigitCounter();
            if (phoneInput.value.trim()) {
                validatePhone(false);
            }

            // Accordion Toggle for Mobile Order Summary
            const summaryToggle = document.getElementById('order-summary-toggle');
            const summaryContent = document.getElementById('order-summary-content');
            const summaryToggleText = document.getElementById('order-summary-toggle-text');
            const summaryChevron = document.getElementById('order-summary-chevron');

            if (summaryToggle && summaryContent) {
                summaryToggle.addEventListener('click', function() {
                    const isExpanded = summaryToggle.getAttribute('aria-expanded') === 'true';
                    if (isExpanded) {
                        summaryContent.classList.add('hidden');
                        summaryToggle.setAttribute('aria-expanded', 'false');
                        if (summaryToggleText) summaryToggleText.textContent = 'Show order summary';
                        if (summaryChevron) summaryChevron.classList.remove('rotate-180');
                    } else {
                        summaryContent.classList.remove('hidden');
                        summaryToggle.setAttribute('aria-expanded', 'true');
                        if (summaryToggleText) summaryToggleText.textContent = 'Hide order summary';
                        if (summaryChevron) summaryChevron.classList.add('rotate-180');
                    }
                });
            }

            // Real-time Shipping Rate update on City change
            const cityInput = document.getElementById('city');
            let cityDebounceTimer = null;
            if (cityInput) {
                cityInput.addEventListener('input', function() {
                    clearTimeout(cityDebounceTimer);
                    cityDebounceTimer = setTimeout(function() {
                        const cityName = cityInput.value.trim();
                        fetch('{{ route("checkout.shipping-rate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                city: cityName,
                                subtotal: {{ (float)$subtotal }}
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.success) {
                                const shippingEl = document.getElementById('shipping-display');
                                const totalEl = document.getElementById('total-display');
                                const submitTotalEl = document.getElementById('submit-total');
                                const mobileSummaryTotalEl = document.getElementById('mobile-summary-total');
                                const shippingMethodEl = document.getElementById('shipping-method-name');

                                if (shippingEl) shippingEl.textContent = data.formatted_cost;
                                if (totalEl) totalEl.textContent = data.formatted_total;
                                if (submitTotalEl) submitTotalEl.textContent = data.formatted_total;
                                if (mobileSummaryTotalEl) mobileSummaryTotalEl.textContent = data.formatted_total;
                                if (shippingMethodEl) {
                                    shippingMethodEl.textContent = data.name + (data.notes ? ' (' + data.notes + ')' : '');
                                }
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching dynamic shipping rate:', err);
                        });
                    }, 400);
                });
            }
        });
    </script>
</x-app-layout>
