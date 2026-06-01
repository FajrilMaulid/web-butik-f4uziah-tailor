<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

// Route untuk GUEST
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Route untuk USER
Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Route Keranjang
    Route::post('/cart/add', function (\Illuminate\Http\Request $request) {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $size = $request->input('size', 'Custom');
        $quantity = (int) $request->input('quantity', 1);
        $notes = $request->input('notes', '');

        $product = \App\Models\Product::find($productId);

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan!');
        }

        // Gunakan md5 dari catatan agar pesanan dengan catatan berbeda tidak saling menimpa di keranjang
        $cartId = $productId . '-' . $size . '-' . md5($notes);

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $quantity;
        } else {
            $cart[$cartId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'size' => $size,
                'quantity' => $quantity,
                'notes' => $notes,
                'image' => $product->image
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    })->name('cart.add');

    Route::get('/cart', function () {
        $cart = session()->get('cart', []);
        return view('pages.user.cart', compact('cart'));
    })->name('cart.index');

    Route::post('/cart/remove', function (\Illuminate\Http\Request $request) {
        $cart = session()->get('cart', []);
        $cartId = $request->input('cart_id');
        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    })->name('cart.remove');

    Route::post('/cart/checkout', function () {
        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        foreach ($cart as $item) {
            $orderNotes = 'Ukuran: ' . $item['size'] . ', Jumlah: ' . $item['quantity'];
            if (!empty($item['notes'])) {
                $orderNotes .= '. Catatan: ' . $item['notes'];
            }

            \App\Models\Order::create([
                'user_id' => auth()->id(),
                'product_id' => $item['product_id'],
                'total_price' => $item['price'] * $item['quantity'],
                'status' => 'menunggu',
                'notes' => $orderNotes,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('profile')->with('success', 'Checkout berhasil! Pesanan Anda sedang kami tinjau.');
    })->name('cart.checkout');
});

// Route untuk ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
});

// Route Beranda (Bisa diakses siapapun)
Route::get('/', function (\Illuminate\Http\Request $request) {
    $search = $request->input('search');
    $categoryId = $request->input('category');

    $categories = \App\Models\Category::all();

    $query = \App\Models\Product::query()->with('category');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('category', function ($qc) use ($search) {
                    $qc->where('name', 'like', "%{$search}%");
                });
        });
    }

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    $products = $query->get();

    return view('pages.user.beranda', compact('categories', 'products', 'search', 'categoryId'));
})->name('home');
