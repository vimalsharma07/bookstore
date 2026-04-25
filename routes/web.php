<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminWebhookLogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [StorefrontController::class, 'home'])->name('home');
Route::view('/faq', 'pages.faq')->name('pages.faq');
Route::view('/privacy', 'pages.privacy')->name('pages.privacy');
Route::view('/terms', 'pages.terms')->name('pages.terms');
Route::view('/refunds', 'pages.refunds')->name('pages.refunds');
Route::post('/contact/submit', function () {
    return view('success');
})->name('contact.submit');Route::post('/currency', [CurrencyController::class, 'update'])->name('currency.update');

Route::post('/payment/razorpay', function (Request $request) {
    $forwardUrl = 'https://app.finvypay.com/api/v1/payment/razorpay/webhook';
    $forwardHeaders = array_filter([
        'Content-Type' => $request->header('Content-Type'),
        'Accept' => 'application/json',
        'X-Razorpay-Signature' => $request->header('X-Razorpay-Signature'),
        'X-Razorpay-Event-Id' => $request->header('X-Razorpay-Event-Id'),
        'Request-Id' => $request->header('Request-Id'),
        'User-Agent' => $request->header('User-Agent'),
    ]);

    $payload = $request->getContent();

    try {
        $response = Http::withHeaders($forwardHeaders)
            ->timeout(20)
            ->retry(2, 300)
            ->withBody($payload, $request->header('Content-Type', 'application/json'))
            ->post($forwardUrl);

        WebhookLog::create([
            'source' => 'razorpay',
            'event' => $request->input('event'),
            'signature' => $request->header('X-Razorpay-Signature'),
            'request_headers' => $request->headers->all(),
            'request_payload' => $payload,
            'forwarded_to' => $forwardUrl,
            'forward_status_code' => $response->status(),
            'forward_response_body' => $response->body(),
            'is_forward_success' => $response->successful(),
            'forwarded_at' => now(),
        ]);

        return response()->json([
            'success' => $response->successful(),
            'status' => $response->status(),
        ], $response->successful() ? 200 : 502);
    } catch (\Throwable $e) {
        WebhookLog::create([
            'source' => 'razorpay',
            'event' => $request->input('event'),
            'signature' => $request->header('X-Razorpay-Signature'),
            'request_headers' => $request->headers->all(),
            'request_payload' => $payload,
            'forwarded_to' => $forwardUrl,
            'forward_response_body' => $e->getMessage(),
            'is_forward_success' => false,
            'forwarded_at' => now(),
        ]);

        return response()->json([
            'success' => false,
            'status' => 502,
        ], 502);
    }
})->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book:slug}/preview', [BookController::class, 'preview'])->name('books.preview');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add/{book:slug}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{book:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
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
    Route::post('/checkout/pending/{order}/payment-proof', [CheckoutController::class, 'submitPaymentProof'])->name('checkout.payment-proof');
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
    Route::post('/orders/{order}/confirm-payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::get('/webhook-logs', [AdminWebhookLogController::class, 'index'])->name('webhook-logs.index');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [AdminReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [AdminReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

require __DIR__.'/auth.php';
