<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Local-only: migrate, storage link, seed. Remove or protect before production.
 *
 * Session middleware is disabled so the DB `sessions` table is not queried
 * before migrations create it (SESSION_DRIVER=database).
 */
Route::get('/setup/install', function () {
    abort_unless(app()->environment('local'), 404);

    $steps = [];

    Artisan::call('migrate', ['--force' => true]);
    $steps['migrate'] = trim(Artisan::output()) ?: 'OK';

    Artisan::call('storage:link', ['--force' => true]);
    $steps['storage:link'] = trim(Artisan::output()) ?: 'OK';

    Artisan::call('db:seed', ['--force' => true]);
    $steps['db:seed'] = trim(Artisan::output()) ?: 'OK';

    return response()->json([
        'ok' => true,
        'steps' => $steps,
    ], 200, [], JSON_PRETTY_PRINT);
})->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class])->name('setup.install');

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [StorefrontController::class, 'home'])->name('home');
Route::view('/faq', 'pages.faq')->name('pages.faq');
Route::view('/privacy', 'pages.privacy')->name('pages.privacy');
Route::view('/terms', 'pages.terms')->name('pages.terms');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book:slug}/preview', [BookController::class, 'preview'])->name('books.preview');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add/{book:slug}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{book:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()?->is_admin) {
            return redirect()->route('admin.home');
        }
        return redirect()->route('library.index');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/books/{book:slug}/wishlist', [BookController::class, 'toggleWishlist'])->name('books.wishlist');
    Route::post('/books/{book:slug}/reviews', [BookController::class, 'storeReview'])->name('books.reviews.store');

    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/library/{book:slug}/download', [LibraryController::class, 'download'])->name('library.download');

    Route::match(['get', 'post'], '/checkout/cart', [CheckoutController::class, 'startFromCart'])->name('checkout.cart');
    Route::post('/checkout/{book:slug}', [CheckoutController::class, 'start'])->name('checkout.start'); // single-book
    Route::get('/checkout/pending/{order}', [CheckoutController::class, 'pending'])->name('checkout.pending');
    Route::post('/checkout/pending/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('home');

    Route::get('/books', [AdminBookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [AdminBookController::class, 'create'])->name('books.create');
    Route::post('/books', [AdminBookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [AdminBookController::class, 'update'])->name('books.update');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/auth.php';
