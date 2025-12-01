@extends('layouts.app')

@section('title', 'Keranjang - DailyMoo')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
            <p class="text-gray-600 mt-1">Review produk yang akan Anda beli</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cartItems))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-600 mb-6">Belum ada produk di keranjang Anda.</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Select All Header --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="select-all" 
                                class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2"
                                onchange="toggleSelectAll(this.checked)"
                            >
                            <span class="text-sm font-semibold text-gray-900">Pilih Semua</span>
                            <span class="text-sm text-gray-500 ml-auto" id="selected-count">0 item dipilih</span>
                        </label>
                    </div>

                    @foreach($cartItems as $productId => $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 cart-item" data-product-id="{{ $productId }}" data-price="{{ $item['product']->price }}" data-quantity="{{ $item['quantity'] }}">
                            <div class="flex items-start gap-4">
                                {{-- Checkbox --}}
                                <div class="flex-shrink-0 pt-1">
                                    <input 
                                        type="checkbox" 
                                        class="item-checkbox w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2"
                                        data-product-id="{{ $productId }}"
                                        data-subtotal="{{ $item['subtotal'] }}"
                                        onchange="updateTotal()"
                                        checked
                                    >
                                </div>

                                {{-- Product Image --}}
                                <div class="flex-shrink-0">
                                    <img 
                                        src="{{ $item['product']->image_url }}" 
                                        alt="{{ $item['product']->name }}"
                                        class="w-24 h-24 object-cover rounded-lg"
                                        onerror="this.src='https://placehold.co/96x96/F4F4F4/888888?text=DailyMoo'"
                                    >
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $item['product']->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        Rp {{ number_format($item['product']->price, 0, ',', '.') }} / item
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Stok: {{ $item['product']->stock }}
                                    </p>
                                </div>

                                {{-- Quantity & Actions --}}
                                <div class="flex flex-col items-end gap-3">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('cart.update', $productId) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input 
                                                type="number" 
                                                name="quantity" 
                                                id="quantity-{{ $productId }}"
                                                value="{{ $item['quantity'] }}" 
                                                min="1" 
                                                max="{{ $item['product']->stock }}"
                                                class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary"
                                                onchange="updateQuantityAjax({{ $productId }}, this.value)"
                                            >
                                        </form>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-primary" id="subtotal-{{ $productId }}">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal (<span id="selected-items-count">0</span> item)</span>
                                <span class="text-gray-900 font-medium" id="subtotal-display">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-600">Ongkir</span>
                                    <span class="text-xs text-gray-500 mt-0.5">Ongkir dibuat flat untuk mengefisienkan sistem pembelian</span>
                                </div>
                                <span class="text-gray-900 font-medium" id="shipping-display">Rp 30.000</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-primary" id="total-display">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        @auth
                            <form id="checkout-form" action="{{ route('checkout') }}" method="GET">
                                <input type="hidden" name="selected_items" id="selected-items-input" value="">
                                <button 
                                    type="submit" 
                                    id="checkout-button"
                                    class="w-full px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled
                                >
                                    Checkout (<span id="checkout-items-count">0</span> item)
                                </button>
                            </form>
                        @else
                            <div class="space-y-3">
                                <p class="text-sm text-gray-600 text-center mb-3">
                                    Silakan login untuk melanjutkan checkout
                                </p>
                                <a href="{{ route('login') }}" class="block w-full px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-semibold text-center transition-all duration-300">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="block w-full px-6 py-3 border-2 border-primary text-primary rounded-lg font-semibold text-center hover:bg-primary hover:text-white transition-all duration-300">
                                    Daftar
                                </a>
                            </div>
                        @endauth

                        <a href="{{ route('shop') }}" class="block w-full text-center mt-4 text-sm text-gray-600 hover:text-primary transition-colors">
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Format number to currency
function formatCurrency(amount) {
    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Get all selected items
function getSelectedItems() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const selectedItems = [];
    checkboxes.forEach(checkbox => {
        selectedItems.push(checkbox.getAttribute('data-product-id'));
    });
    return selectedItems;
}

// Update total based on selected items
function updateTotal() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    let subtotal = 0;
    let itemCount = 0;
    const shippingFee = 30000; // Flat shipping fee

    checkboxes.forEach(checkbox => {
        const itemSubtotal = parseFloat(checkbox.getAttribute('data-subtotal'));
        subtotal += itemSubtotal;
        itemCount++;
    });

    // Calculate total with shipping
    const total = subtotal + (itemCount > 0 ? shippingFee : 0);

    // Update display
    document.getElementById('subtotal-display').textContent = formatCurrency(Math.round(subtotal));
    document.getElementById('total-display').textContent = formatCurrency(Math.round(total));
    document.getElementById('selected-items-count').textContent = itemCount;
    document.getElementById('selected-count').textContent = itemCount + ' item dipilih';
    document.getElementById('checkout-items-count').textContent = itemCount;
    
    // Update shipping display (only show if items selected)
    const shippingDisplay = document.getElementById('shipping-display');
    if (shippingDisplay) {
        if (itemCount > 0) {
            shippingDisplay.textContent = formatCurrency(shippingFee);
        } else {
            shippingDisplay.textContent = 'Rp 0';
        }
    }

    // Update selected items input
    const selectedItems = getSelectedItems();
    document.getElementById('selected-items-input').value = selectedItems.join(',');

    // Enable/disable checkout button
    const checkoutButton = document.getElementById('checkout-button');
    if (itemCount > 0) {
        checkoutButton.disabled = false;
    } else {
        checkoutButton.disabled = true;
    }

    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    if (allCheckboxes.length > 0) {
        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
    }
}

// Toggle select all
function toggleSelectAll(checked) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });
    updateTotal();
}

// Handle form submission
document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
    const selectedItems = getSelectedItems();
    if (selectedItems.length === 0) {
        e.preventDefault();
        alert('Silakan pilih minimal 1 produk untuk checkout');
        return false;
    }
});

// Update quantity via AJAX
function updateQuantityAjax(productId, quantity) {
    const form = document.querySelector(`form[action*="/cart/${productId}"]`);
    if (!form) {
        // Fallback: reload page
        location.reload();
        return;
    }

    const formData = new FormData(form);
    formData.set('quantity', quantity);
    formData.append('_method', 'PUT');

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update subtotal display
            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            if (cartItem) {
                const price = parseFloat(cartItem.getAttribute('data-price'));
                const newSubtotal = price * parseInt(quantity);
                const subtotalElement = document.getElementById(`subtotal-${productId}`);
                if (subtotalElement) {
                    subtotalElement.textContent = formatCurrency(Math.round(newSubtotal));
                }
                
                // Update cart item data attribute
                cartItem.setAttribute('data-quantity', quantity);
                
                // Update checkbox data attribute
                const checkbox = document.querySelector(`.item-checkbox[data-product-id="${productId}"]`);
                if (checkbox) {
                    checkbox.setAttribute('data-subtotal', newSubtotal);
                }
                
                // Update total
                updateTotal();
            }
        } else {
            alert(data.message || 'Gagal mengupdate quantity');
            // Reload page to sync
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback to form submit
        form.submit();
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTotal();
});
</script>
@endsection

