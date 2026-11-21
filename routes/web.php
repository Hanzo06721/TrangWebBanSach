<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostModerationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostInteractionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/forum', [PostController::class, 'forum'])->name('posts.forum');

/* ---------- Dành cho khách (chưa đăng nhập) ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

/* ---------- Dành cho người đã đăng nhập ---------- */
Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/my-posts', [PostController::class, 'mine'])->name('posts.mine');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comments', [PostInteractionController::class, 'comment'])->name('posts.comments.store');
    Route::put('/comments/{comment}', [PostInteractionController::class, 'updateComment'])->name('posts.comments.update');
    Route::delete('/comments/{comment}', [PostInteractionController::class, 'destroyComment'])->name('posts.comments.destroy');
    Route::post('/posts/{post}/reports', [PostInteractionController::class, 'report'])->name('posts.reports.store');
    Route::post('/posts/{post}/purchase', [PostController::class, 'purchase'])->name('posts.purchase.store');
    Route::get('/posts/{post}/messages', [ChatController::class, 'messages'])->name('posts.messages.index');
    Route::post('/posts/{post}/messages', [ChatController::class, 'store'])->name('posts.messages.store');
    Route::post('/posts/{post}/reactions', [PostInteractionController::class, 'reactPost'])->name('posts.reactions.store');
    Route::post('/comments/{comment}/reactions', [PostInteractionController::class, 'reactComment'])->name('posts.comments.reactions.store');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');

        Route::get('/password', [ProfileController::class, 'passwordForm'])->name('password');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

        Route::get('/avatar', [ProfileController::class, 'avatarForm'])->name('avatar');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::delete('/avatar', [ProfileController::class, 'destroyAvatar'])->name('avatar.destroy');
    });

    /* ---------- Chỉ Quản trị viên ---------- */
    Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::patch('/roles/users/{user}', [RoleController::class, 'assign'])->name('roles.assign');

        Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
        Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
        Route::resource('books', BookController::class);
        Route::resource('categories', CategoryController::class);

        Route::get('/posts', [PostModerationController::class, 'index'])->name('posts.index');
        Route::patch('/posts/{post}/approve', [PostModerationController::class, 'approve'])->name('posts.approve');
        Route::patch('/posts/{post}/reject', [PostModerationController::class, 'reject'])->name('posts.reject');
        Route::patch('/posts/{post}/hide', [PostModerationController::class, 'hide'])->name('posts.hide');
        Route::delete('/posts/{post}', [PostModerationController::class, 'destroy'])->name('posts.destroy');
        Route::get('/post-reports', [PostModerationController::class, 'reports'])->name('post-reports.index');
        Route::patch('/post-reports/{report}/resolve', [PostModerationController::class, 'resolveReport'])->name('post-reports.resolve');
        Route::patch('/post-reports/{report}/reject', [PostModerationController::class, 'rejectReport'])->name('post-reports.reject');
        Route::patch('/comments/{comment}/hide', [PostModerationController::class, 'hideComment'])->name('comments.hide');
        Route::delete('/comments/{comment}', [PostModerationController::class, 'destroyComment'])->name('comments.destroy');
        Route::get('/comments', [PostModerationController::class, 'comments'])->name('comments.index');
    });
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
