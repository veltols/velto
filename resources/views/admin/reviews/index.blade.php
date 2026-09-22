@extends('layouts.admin')

@section('header', 'Customer Reviews')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Action -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Customer Reviews</h1>
            <p class="mt-1 text-sm text-gray-500">Manage customer feedback, star ratings, and publication status.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.reviews.create') }}" class="inline-flex items-center gap-2 rounded-md bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Review
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Total Reviews</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-xs">
            <p class="text-xs font-medium uppercase tracking-wider text-emerald-700">Approved</p>
            <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $stats['approved'] }}</p>
        </div>
        <div class="rounded-xl border border-amber-100 bg-amber-50/50 p-5 shadow-xs">
            <p class="text-xs font-medium uppercase tracking-wider text-amber-700">Pending Approval</p>
            <p class="mt-2 text-2xl font-bold text-amber-700">{{ $stats['pending'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-xs">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Average Rating</p>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-2xl font-bold text-gray-900">{{ $stats['average_rating'] }}</span>
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($stats['average_rating']))
                            <svg class="w-4 h-4 text-black fill-current" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search customer, title, text..." class="w-full rounded-md border-gray-300 pl-9 text-sm focus:border-black focus:ring-black">
                    <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="sr-only">Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 text-sm focus:border-black focus:ring-black">
                    <option value="">All Statuses</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Rating Filter -->
            <div>
                <label for="rating" class="sr-only">Rating</label>
                <select name="rating" id="rating" class="w-full rounded-md border-gray-300 text-sm focus:border-black focus:ring-black">
                    <option value="">All Star Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full rounded-md bg-gray-900 py-2 text-sm font-semibold text-white hover:bg-black transition">Filter</button>
                @if(request()->hasAny(['search', 'status', 'rating', 'product_id']))
                    <a href="{{ route('admin.reviews.index') }}" class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-600">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 sm:pl-6">Customer</th>
                        <th scope="col" class="px-3 py-3.5">Rating & Review</th>
                        <th scope="col" class="px-3 py-3.5">Product</th>
                        <th scope="col" class="px-3 py-3.5">Status</th>
                        <th scope="col" class="px-3 py-3.5">Date</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50/70 transition">
                        <!-- Customer -->
                        <td class="py-4 pl-4 pr-3 sm:pl-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-900 text-white font-bold text-xs">
                                    {{ $review->initials }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 flex items-center gap-1.5">
                                        {{ $review->customer_name }}
                                        @if($review->verified_purchase)
                                            <span title="Verified Purchase" class="inline-flex text-emerald-600">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            </span>
                                        @endif
                                    </div>
                                    @if($review->customer_email)
                                        <div class="text-xs text-gray-500">{{ $review->customer_email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Rating & Review Text -->
                        <td class="px-3 py-4 max-w-md">
                            <div class="flex items-center gap-0.5 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <svg class="w-4 h-4 text-black fill-current" viewBox="0 0 24 24">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-1.5 text-xs font-bold text-gray-800">{{ $review->rating }}.0</span>
                                @if($review->is_featured)
                                    <span class="ml-2 inline-flex items-center rounded-full bg-purple-50 px-2 py-0.5 text-[10px] font-semibold text-purple-700 ring-1 ring-inset ring-purple-600/20">Featured</span>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-900 text-sm leading-snug">{{ $review->title }}</h4>
                            <p class="mt-1 text-xs text-gray-600 line-clamp-2">{{ $review->description }}</p>
                        </td>

                        <!-- Product -->
                        <td class="whitespace-nowrap px-3 py-4 text-xs">
                            @if($review->product)
                                <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="font-medium text-gray-900 hover:text-black underline underline-offset-2">
                                    {{ Str::limit($review->product->name, 28) }}
                                </a>
                            @else
                                <span class="text-gray-400 italic">General / Storewide</span>
                            @endif
                        </td>

                        <!-- Status & Toggle -->
                        <td class="whitespace-nowrap px-3 py-4 text-xs">
                            <form action="{{ route('admin.reviews.toggle-status', $review) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="rounded-full border-0 py-1 pl-2.5 pr-7 text-xs font-semibold cursor-pointer {{ $review->getStatusBadgeClass() }} focus:ring-2 focus:ring-black">
                                    <option value="approved" {{ $review->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ $review->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </td>

                        <!-- Date -->
                        <td class="whitespace-nowrap px-3 py-4 text-xs text-gray-500">
                            {{ $review->created_at ? $review->created_at->format('M d, Y') : '—' }}
                        </td>

                        <!-- Actions -->
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-xs font-medium sm:pr-6">
                            <div class="flex items-center justify-end gap-2">
                                @if($review->status === 'pending')
                                    <form action="{{ route('admin.reviews.toggle-status', $review) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="rounded bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-emerald-700 transition flex items-center gap-1 shadow-xs cursor-pointer" title="Approve and Publish to frontend">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>Publish</span>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.reviews.edit', $review) }}" class="rounded border border-gray-300 bg-white px-2.5 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 hover:bg-red-100 cursor-pointer">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-sm text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                            <p class="mt-2 font-medium text-gray-900">No customer reviews found</p>
                            <p class="text-xs text-gray-500">Get started by creating your first review manually.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.reviews.create') }}" class="inline-flex items-center rounded-md bg-black px-3.5 py-2 text-xs font-semibold text-white hover:bg-gray-800">Add First Review</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="border-t border-gray-200 px-4 py-3 sm:px-6">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
