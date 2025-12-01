<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display shop page
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔍 Search produk berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 🏷️ Filter kategori
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // 💰 Sorting berdasarkan harga
        if ($request->filled('sort')) {
            if ($request->sort === 'lowest') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'highest') {
                $query->orderBy('price', 'desc');
            }
        } else {
            // Default urutan terbaru
            $query->latest();
        }

        // Ambil produk (12 per halaman)
        $products = $query->paginate(12);

        // Ambil daftar kategori unik
        $categoryOptions = Product::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        // Data kategori untuk filter
        $categories = $categoryOptions;
        array_unshift($categories, 'all');

        // Admin Dashboard Data
        $adminData = null;
        if (Auth::check() && in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            $adminData = $this->getAdminDashboardData();
        }

        // Kirim data ke view
        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
            'categoryOptions' => $categoryOptions,
            'adminData' => $adminData,
        ]);
    }

    /**
     * Display single product
     */
    public function show(Product $product)
    {
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display checkout page
     */
    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        
        // Handle direct buy from product page
        if ($request->has('product_id') && $request->has('quantity')) {
            // Save to session for processCheckout to use
            $cart = [
                $request->product_id => [
                    'quantity' => $request->quantity,
                ]
            ];
            Session::put('cart', $cart);
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Get selected items from request (if coming from cart with checkboxes)
        $selectedItems = [];
        if ($request->has('selected_items') && !empty($request->selected_items)) {
            $selectedItems = explode(',', $request->selected_items);
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            // If selected_items is provided, only include selected items
            if (!empty($selectedItems) && !in_array($productId, $selectedItems)) {
                continue;
            }

            $product = Product::find($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $product->price * $item['quantity'];
                $subtotal += $item['subtotal'];
                $cartItems[$productId] = $item;
            }
        }

        // If no items selected, redirect back
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal 1 produk untuk checkout.');
        }

        // Add shipping fee (flat rate)
        $shippingFee = 30000;
        $total = $subtotal + $shippingFee;

        // Store selected items in session for processCheckout
        if (!empty($selectedItems)) {
            Session::put('checkout_selected_items', $selectedItems);
        }

        // Bank account info (bisa dipindah ke config atau database)
        $bankAccount = [
            'bank' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT DailyMoo Indonesia',
        ];

        return view('shop.checkout', compact('cartItems', 'subtotal', 'total', 'bankAccount', 'selectedItems', 'shippingFee'));
    }

    /**
     * Process checkout
     */
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_phone' => 'required|string|max:20',
            'selected_items' => 'nullable|string', // Comma-separated product IDs
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Get selected items from request or session
        $selectedItems = [];
        if ($request->has('selected_items') && !empty($request->selected_items)) {
            $selectedItems = explode(',', $request->selected_items);
        } elseif (Session::has('checkout_selected_items')) {
            $selectedItems = Session::get('checkout_selected_items');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            // If selected_items is provided, only include selected items
            if (!empty($selectedItems) && !in_array($productId, $selectedItems)) {
                continue;
            }

            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            // Check stock
            if ($product->stock < $item['quantity']) {
                return redirect()->back()->with('error', "Stok {$product->name} tidak mencukupi.");
            }

            $item['product'] = $product;
            $item['subtotal'] = $product->price * $item['quantity'];
            $subtotal += $item['subtotal'];
            $cartItems[$productId] = $item;
        }

        // If no items selected, redirect back
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal 1 produk untuk checkout.');
        }

        // Add shipping fee (flat rate)
        $shippingFee = 30000;
        $total = $subtotal + $shippingFee;

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'customer_address' => $validated['customer_address'],
            'customer_phone' => $validated['customer_phone'],
            'total_amount' => $total,
            'status' => 'pending_payment',
            'bank_account' => 'Bank BCA - 1234567890 - PT DailyMoo Indonesia',
        ]);

        // Create transaction items and update stock
        foreach ($cartItems as $productId => $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['product']->price,
            ]);

            // Update product stock
            $item['product']->decrement('stock', $item['quantity']);
        }

        // Remove only selected items from cart (not all items)
        if (!empty($selectedItems)) {
            $remainingCart = $cart;
            foreach ($selectedItems as $productId) {
                if (isset($remainingCart[$productId])) {
                    unset($remainingCart[$productId]);
                }
            }
            if (empty($remainingCart)) {
                Session::forget('cart');
            } else {
                Session::put('cart', $remainingCart);
            }
            // Clear checkout selected items from session
            Session::forget('checkout_selected_items');
        } else {
            // If no selected items specified (direct buy), clear all cart
            Session::forget('cart');
        }

        return redirect()->route('transactions.show', $transaction)->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');
    }

    /**
     * Upload payment proof
     */
    public function uploadPayment(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id() && !in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        // Delete old payment proof if exists
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        // Store payment proof
        $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        
        $transaction->update([
            'payment_proof' => $paymentProofPath,
            'status' => 'payment_verification',
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    /**
     * Display product image
     */
    public function showProductImage(Product $product)
    {
        if (!$product->image || Str::startsWith($product->image, ['http://', 'https://'])) {
            abort(404);
        }

        $path = storage_path('app/public/' . $product->image);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Display payment proof image
     */
    public function showPaymentProof(Transaction $transaction)
    {
        // Check if user has access (owner, superadmin, or pegawai)
        if ($transaction->user_id !== Auth::id() && !in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            abort(403);
        }

        if (!$transaction->payment_proof) {
            abort(404);
        }

        $path = storage_path('app/public/' . $transaction->payment_proof);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Display payment verification page (Admin only)
     */
    public function paymentVerification()
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            abort(403);
        }

        $pendingVerifications = Transaction::where('status', 'payment_verification')
            ->with('items.product', 'user')
            ->latest()
            ->paginate(10);

        return view('shop.payment-verification', compact('pendingVerifications'));
    }

    /**
     * Get count of pending payment verifications (for navbar badge)
     */
    public static function getPendingVerificationCount(): int
    {
        return Transaction::where('status', 'payment_verification')->count();
    }

    /**
     * Get admin dashboard data
     */
    private function getAdminDashboardData()
    {
        // Total Products
        $totalProducts = Product::count();
        
        // Products with low stock (less than 10)
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        $outOfStockProducts = Product::where('stock', '=', 0)->count();
        
        // Today's sales (only completed, processing, shipped transactions)
        $todaySales = Transaction::whereDate('created_at', today())
            ->whereIn('status', ['completed', 'processing', 'shipped'])
            ->sum('total_amount');
        
        // This month's sales (only completed, processing, shipped transactions)
        $monthSales = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['completed', 'processing', 'shipped'])
            ->sum('total_amount');
        
        // Today's transactions count (all non-cancelled)
        $todayTransactions = Transaction::whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // Pending verifications
        $pendingVerifications = Transaction::where('status', 'payment_verification')->count();
        
        // Total completed transactions this month
        $monthCompletedTransactions = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['completed', 'processing', 'shipped'])
            ->count();
        
        // Sales data for last 7 days (for chart) - only completed/processing/shipped
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $sales = Transaction::whereDate('created_at', $date)
                ->whereIn('status', ['completed', 'processing', 'shipped'])
                ->sum('total_amount');
            $salesData[] = [
                'date' => $date->format('d M'),
                'sales' => (float) $sales
            ];
        }
        
        // Sales data for last 12 months (for monthly chart) - only completed/processing/shipped
        $monthlySalesData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $sales = Transaction::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->whereIn('status', ['completed', 'processing', 'shipped'])
                ->sum('total_amount');
            $monthlySalesData[] = [
                'month' => $date->format('M Y'),
                'sales' => (float) $sales
            ];
        }
        
        // Best selling products (top 10) - only from completed/processing/shipped transactions
        $bestSellingProducts = TransactionItem::select('product_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereIn('transactions.status', ['completed', 'processing', 'shipped'])
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $product = Product::find($item->product_id);
                return (object)[
                    'product_id' => $item->product_id,
                    'total_sold' => (int) $item->total_sold,
                    'product' => $product
                ];
            })
            ->filter(function($item) {
                return $item->product !== null; // Filter out deleted products
            })
            ->values();
        
        // Low stock products list
        $lowStockProductsList = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->get();
        
        return [
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'todaySales' => (float) $todaySales,
            'monthSales' => (float) $monthSales,
            'todayTransactions' => $todayTransactions,
            'monthCompletedTransactions' => $monthCompletedTransactions,
            'pendingVerifications' => $pendingVerifications,
            'salesData' => $salesData,
            'monthlySalesData' => $monthlySalesData,
            'bestSellingProducts' => $bestSellingProducts,
            'lowStockProductsList' => $lowStockProductsList,
        ];
    }

    /**
     * Quick update stock (AJAX)
     */
    public function quickUpdateStock(Request $request, Product $product)
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $validated['stock']]);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui',
            'stock' => $product->stock
        ]);
    }

    /**
     * Display user transactions
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        
        $query = Transaction::query();
        
        // Admin can see all transactions
        if (in_array($user->role, ['superadmin', 'pegawai'])) {
            $query->with('items.product', 'user');
        } else {
            $query->where('user_id', $user->id)
                ->with('items.product');
        }
        
        // Filter: Hari Ini
        if ($request->has('filter') && $request->filter === 'today') {
            $query->whereDate('created_at', today());
        }
        
        // Filter: Bulan Ini
        if ($request->has('filter') && $request->filter === 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }
        
        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $transactions = $query->latest()->paginate(10);

        return view('shop.transactions', compact('transactions'));
    }

    /**
     * Display single transaction
     */
    public function showTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id() && !in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            abort(403);
        }

        $transaction->load('items.product', 'user');

        // Bank account info
        $bankAccount = [
            'bank' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT DailyMoo Indonesia',
        ];

        return view('shop.transaction-detail', compact('transaction', 'bankAccount'));
    }

    /**
     * Approve payment (Admin only)
     */
    public function approvePayment(Transaction $transaction)
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            abort(403);
        }

        if ($transaction->status !== 'payment_verification') {
            return redirect()->route('payment.verification')->with('error', 'Status transaksi tidak valid untuk approval.');
        }

        $transaction->update([
            'status' => 'processing',
        ]);

        return redirect()->route('payment.verification')->with('success', 'Pembayaran berhasil diverifikasi. Pesanan sedang diproses.');
    }

    /**
     * Reject payment (Admin only)
     */
    public function rejectPayment(Transaction $transaction)
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'pegawai'])) {
            abort(403);
        }

        if ($transaction->status !== 'payment_verification') {
            return redirect()->route('payment.verification')->with('error', 'Status transaksi tidak valid untuk rejection.');
        }

        // Return stock
        foreach ($transaction->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $transaction->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('payment.verification')->with('success', 'Pembayaran ditolak. Stok produk telah dikembalikan.');
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        try {
            $data = $this->validatedProductData($request, 'productStore');
            $data['image'] = $this->resolveProductImage($request);

            Product::create($data);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['productStore' => ['image' => $e->getMessage()]]);
        }
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        try {
            $data = $this->validatedProductData($request, 'productUpdate');
            $data['image'] = $this->resolveProductImage($request, $product->image);

            $product->update($data);

            return redirect()->back()->with('success', 'Produk berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['productUpdate' => ['image' => $e->getMessage()]]);
        }
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $this->deleteProductImage($product->image);
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    private function validatedProductData(Request $request, string $errorBag): array
    {
        $validated = $request->validateWithBag($errorBag, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url',
        ]);

        unset($validated['image'], $validated['image_url']);

        return $validated;
    }

    private function resolveProductImage(Request $request, ?string $currentImage = null): ?string
    {
        // Priority 1: Upload file (if provided) - highest priority
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Validate file
            if ($file->isValid()) {
                try {
                    $this->deleteProductImage($currentImage);
                    
                    // Ensure storage directory exists
                    if (!Storage::disk('public')->exists('products')) {
                        Storage::disk('public')->makeDirectory('products');
                    }
                    
                    // Store file
                    $path = $file->store('products', 'public');
                    
                    if ($path) {
                        return $path;
                    }
                } catch (\Exception $e) {
                    // If file upload fails, log error
                    \Log::error('Failed to upload product image: ' . $e->getMessage());
                    throw new \Exception('Gagal mengupload gambar: ' . $e->getMessage());
                }
            } else {
                throw new \Exception('File gambar tidak valid atau rusak.');
            }
        }

        // Priority 2: URL (if provided and no file was uploaded)
        if ($request->filled('image_url')) {
            $this->deleteProductImage($currentImage);
            $imageUrl = $request->input('image_url');
            
            // Validate and clean URL if it's a Google Images redirect URL
            $imageUrl = $this->cleanImageUrl($imageUrl);
            
            return $imageUrl;
        }

        // Priority 3: Keep current image
        return $currentImage;
    }

    /**
     * Clean and extract actual image URL from Google Images redirect URLs
     */
    private function cleanImageUrl(string $url): string
    {
        // Check if it's a Google Images redirect URL
        if (str_contains($url, 'google.com/url') && str_contains($url, 'url=')) {
            // Extract the actual image URL from the redirect
            $parsedUrl = parse_url($url);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $params);
                if (isset($params['url'])) {
                    $cleanUrl = urldecode($params['url']);
                    // Validate that the extracted URL is actually an image URL
                    if (filter_var($cleanUrl, FILTER_VALIDATE_URL)) {
                        return $cleanUrl;
                    }
                }
            }
        }
        
        // If URL is too long, try to extract direct image URL
        // For Google Images, the actual image URL is usually in the 'url' parameter
        if (strlen($url) > 500 && str_contains($url, 'url=')) {
            $parts = parse_url($url);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $params);
                if (isset($params['url'])) {
                    $extractedUrl = urldecode($params['url']);
                    if (filter_var($extractedUrl, FILTER_VALIDATE_URL) && 
                        (str_contains($extractedUrl, '.jpg') || 
                         str_contains($extractedUrl, '.jpeg') || 
                         str_contains($extractedUrl, '.png') || 
                         str_contains($extractedUrl, '.gif') ||
                         str_contains($extractedUrl, '.webp'))) {
                        return $extractedUrl;
                    }
                }
            }
        }
        
        return $url;
    }

    private function deleteProductImage(?string $path): void
    {
        if (!$path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
