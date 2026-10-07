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
            
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
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
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Phone Number <span class="text-xs text-gray-500 font-normal">(WhatsApp Active)</span>
                            </label>
                            <div class="mt-1 relative">
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', auth()->user() ? auth()->user()->phone : '') }}" 
                                       required 
                                       autocomplete="tel"
                                       minlength="10"
                                       maxlength="11"
                                       pattern="^(03[0-9]{9}|3[0-9]{9})$"
                                       placeholder="0300 1234567"
                                       title="Please enter a complete 11-digit Pakistani mobile number starting with 03 (e.g. 0300 1234567)"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm">
                            </div>
                            
                            <!-- Validation Feedback Messages -->
                            <div id="phone-feedback" class="mt-1.5 min-h-[18px]">
                                <p id="phone-error" class="hidden text-red-600 text-xs font-semibold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span id="phone-error-text">Please enter a valid phone number</span>
                                </p>
                                <p id="phone-valid" class="hidden text-emerald-600 text-xs font-semibold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Valid phone number</span>
                                </p>
                                @error('phone') 
                                    <p class="text-red-600 text-xs font-semibold flex items-center gap-1.5">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </p> 
                                @enderror
                            </div>
                            <p class="text-gray-400 text-[11px] mt-0.5">We will send your order confirmation and dispatch updates to this number.</p>
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
                <div class="mt-10 lg:mt-0">
                    <div class="bg-white p-6 rounded-lg shadow-sm sticky top-24">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Order Summary</h2>

                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <li class="flex py-6">
                                     <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
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

                                    <div class="ml-6 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $item->product->name }}</h3>
                                                <div class="flex flex-col items-end">
                                                    @php
                                                        $onSale = $item->variant ? $item->variant->isOnSale() : $item->product->isOnSale();
                                                        $currentPrice = $item->variant ? $item->variant->final_price : $item->product->price;
                                                        $originalPrice = $item->variant ? ($item->variant->price ?: $item->product->base_price) : $item->product->base_price;
                                                    @endphp
                                                    <p class="ml-4 font-bold text-black">Rs. {{ number_format($currentPrice * $item->quantity) }}</p>
                                                    @if($onSale)
                                                        <p class="ml-4 text-xs text-gray-400 line-through text-right">Rs. {{ number_format($originalPrice * $item->quantity) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">{{ $item->variant ? $item->variant->size . ' | ' . $item->variant->color : '' }}</p>
                                        </div>
                                        <div class="flex items-end justify-between text-sm pt-2">
                                            <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">Rs. {{ number_format($subtotal) }}</dd>
                            </div>
                            <div class="flex items-center justify-between pt-4">
                                <dt class="text-sm text-gray-600">Shipping</dt>
                                <dd class="text-sm font-medium text-gray-900">Rs. {{ number_format($shipping) }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4 mt-4">
                                <dt class="text-base font-medium text-gray-900">Order Total</dt>
                                <dd class="text-base font-medium text-gray-900">Rs. {{ number_format($total) }}</dd>
                            </div>
                        </div>

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
                                <span id="submit-text">Place Order (Rs. {{ number_format($total) }})</span>
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

            // Adjust input max length based on selected country and current value
            function getMaxDigits() {
                const countryData = iti ? iti.getSelectedCountryData() : { iso2: 'pk' };
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
                const countryData = iti ? iti.getSelectedCountryData() : { iso2: 'pk' };

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
                validatePhone();
            });

            // 3. Handle paste cleanly
            phoneInput.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
                let digits = pasted.replace(/\D/g, '');
                const countryData = iti ? iti.getSelectedCountryData() : { iso2: 'pk' };

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
                validatePhone();
            });

            // 4. Validation logic
            function validatePhone() {
                const val = phoneInput.value.trim();
                const digits = val.replace(/\D/g, '');
                phoneError.classList.add('hidden');
                phoneValid.classList.add('hidden');
                phoneInput.classList.remove('iti__input-error', 'iti__input-valid');

                if (!digits) {
                    phoneErrorText.textContent = "Phone number is required";
                    phoneError.classList.remove('hidden');
                    phoneInput.classList.add('iti__input-error');
                    return false;
                }

                const countryData = iti ? iti.getSelectedCountryData() : { iso2: 'pk' };

                // Specific validation for Pakistan (pk / +92)
                if (!countryData || countryData.iso2 === 'pk') {
                    if (digits.startsWith('0')) {
                        if (digits.length < 11) {
                            phoneErrorText.textContent = `Please enter complete 11 digits (${digits.length}/11 entered)`;
                            phoneError.classList.remove('hidden');
                            phoneInput.classList.add('iti__input-error');
                            return false;
                        }
                        if (!/^03[0-9]{9}$/.test(digits)) {
                            phoneErrorText.textContent = "Pakistani mobile numbers must start with 03 (e.g. 0300 1234567)";
                            phoneError.classList.remove('hidden');
                            phoneInput.classList.add('iti__input-error');
                            return false;
                        }
                    } else if (digits.startsWith('3')) {
                        if (digits.length < 10) {
                            phoneErrorText.textContent = `Please enter complete 10 digits (${digits.length}/10 entered)`;
                            phoneError.classList.remove('hidden');
                            phoneInput.classList.add('iti__input-error');
                            return false;
                        }
                        if (!/^3[0-9]{9}$/.test(digits)) {
                            phoneErrorText.textContent = "Must start with 3 (e.g. 300 1234567)";
                            phoneError.classList.remove('hidden');
                            phoneInput.classList.add('iti__input-error');
                            return false;
                        }
                    } else {
                        phoneErrorText.textContent = "Mobile numbers in Pakistan start with 03 (e.g. 0300 1234567)";
                        phoneError.classList.remove('hidden');
                        phoneInput.classList.add('iti__input-error');
                        return false;
                    }

                    // Valid Pakistani number!
                    phoneValid.classList.remove('hidden');
                    phoneInput.classList.add('iti__input-valid');
                    return true;
                } else {
                    // International validation
                    if (iti && typeof iti.isValidNumber === 'function' ? iti.isValidNumber() : digits.length >= 8) {
                        phoneValid.classList.remove('hidden');
                        phoneInput.classList.add('iti__input-valid');
                        return true;
                    } else {
                        phoneErrorText.textContent = "Please enter a valid phone number";
                        phoneError.classList.remove('hidden');
                        phoneInput.classList.add('iti__input-error');
                        return false;
                    }
                }
            }

            phoneInput.addEventListener('blur', validatePhone);

            phoneInput.addEventListener('countrychange', function () {
                phoneInput.value = '';
                phoneError.classList.add('hidden');
                phoneValid.classList.add('hidden');
                phoneInput.classList.remove('iti__input-error', 'iti__input-valid');
                updateMaxLength();
                phoneInput.focus();
            });

            // 5. Form submission guard
            if (checkoutForm) {
                const submitBtn = document.querySelector('#submit-order-btn');
                const submitSpinner = document.querySelector('#submit-spinner');
                const submitText = document.querySelector('#submit-text');

                checkoutForm.addEventListener('submit', function (e) {
                    if (!validatePhone()) {
                        e.preventDefault();
                        e.stopPropagation();
                        phoneInput.focus();
                        phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }

                    // Normalize value right before submit so it's always in standard format
                    const countryData = iti ? iti.getSelectedCountryData() : { iso2: 'pk' };
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
            if (phoneInput.value.trim()) {
                validatePhone();
            }
        });
    </script>
</x-app-layout>
