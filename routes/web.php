<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    BookController,
    CategoryController,
    RatingController,
    ReviewController,
    FeedbackController,
    ProfileController,
    DashboardController,
};
use App\Http\Controllers\Auth\{
    RegisterController,
    GoogleController,
    ForgotPasswordController,
    ResetPasswordController,
    loginController,
    EmailVerificationController,
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    BookController as AdminBookController,
    CategoryController as AdminCategoryController,
    UserController as AdminUserController,
    ReviewController as AdminReviewController,
    FeedbackController as AdminFeedbackController,
};

// ─── HOMEPAGE ───
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── AUTH ───
Route::middleware('guest')->group(function () {
    Route::get('/auth/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/auth/register', [RegisterController::class, 'store']);
    Route::get('/auth/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/auth/login', [LoginController::class, 'login']); // Gunakan default Laravel atau custom
    Route::get('/auth/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/auth/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('/auth/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/auth/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
});

Route::post('/auth/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ─── PUBLIC BOOKS & CATEGORIES ───
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/search', [BookController::class, 'index'])->name('search');

// ─── USER (terautentikasi & terverifikasi) ───
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/books/{slug}/read', [BookController::class, 'read'])->name('books.read');
    Route::post('/books/{book}/rate', [RatingController::class, 'store'])->name('books.rate');
    Route::get('/books/{book}/my-rating', [RatingController::class, 'getUserRating'])->name('books.my-rating');
    // Route::post('/books/{book}/review', [ReviewController::class, 'store'])->name('reviews.store');
    // Route::post('/books/{book}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/bookmarks', [ProfileController::class, 'bookmarks'])->name('profile.bookmarks');
    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');
    Route::get('/feedback', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// ─── ADMIN ───
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', AdminBookController::class);
    Route::resource('categories', AdminCategoryController::class)->except('show');
    // Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    // Route::patch('users/{user}/toggle-suspend', [AdminUserController::class, 'toggleSuspend'])->name('users.toggle-suspend');
    // Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    // Route::patch('reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.update-status');
    // Route::get('feedbacks', [AdminFeedbackController::class, 'index'])->name('feedbacks.index');
    // Route::post('feedbacks/{feedback}/reply', [AdminFeedbackController::class, 'reply'])->name('feedbacks.reply');
    Route::get('reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('settings', fn() => view('admin.settings'))->name('settings');
});
