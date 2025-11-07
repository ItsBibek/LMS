<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\ReservationController;
use App\Models\Book;
use App\Models\User;
use App\Models\Issue;
use Carbon\Carbon;

// Admin password reset (forgot/reset) routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/forgot-password', [\App\Http\Controllers\Admin\Auth\PasswordResetLinkController::class, 'create'])->name('admin.password.request');
    Route::post('/admin/forgot-password', [\App\Http\Controllers\Admin\Auth\PasswordResetLinkController::class, 'store'])->name('admin.password.email');
    Route::get('/admin/reset-password/{token}', [\App\Http\Controllers\Admin\Auth\NewPasswordController::class, 'create'])->name('admin.password.reset');
    Route::post('/admin/reset-password', [\App\Http\Controllers\Admin\Auth\NewPasswordController::class, 'store'])->name('admin.password.store');
});

Route::get('/', function() {
    // Redirect authenticated users to their respective dashboards
    if (Auth::guard('admin')->check()) {
        return redirect()->route('dashboard');
    }
    if (Auth::guard('web')->check()) {
        return redirect()->route('student.profile');
    }
    return view('auth.student');
})->name('student.login.form');

// Admin login routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login.form')->middleware('guest:admin');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login')->middleware('guest:admin');

// Keep /student/login as redirect to root student page; Breeze handles POST /login via auth.php
Route::get('/student/login', function() { return redirect('/'); });

Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', function () {
        $booksCount = Book::count();
        $membersCount = User::count();
        $activeLoansCount = Issue::whereNull('return_date')->count();
        $overdueCount = Issue::whereNull('return_date')->where('due_date', '<', Carbon::now()->toDateString())->count();

        return view('welcome', compact('booksCount','membersCount','activeLoansCount','overdueCount'));
    })->name('dashboard');

    Route::get('/welcome', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/books', [BooksController::class, 'index'])->name('books.index');
    Route::post('/books/issue', [BooksController::class, 'issue'])->name('books.issue');
    Route::post('/books/issues/{issue}/return', [BooksController::class, 'return'])->name('books.return');
    Route::get('/books/create', [BooksController::class, 'create'])->name('books.create');
    Route::post('/books', [BooksController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BooksController::class, 'edit'])->name('books.edit');
    Route::match(['put','patch'],'/books/{book}', [BooksController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BooksController::class, 'destroy'])->name('books.destroy');

    // Reservations (admin)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{reservation}/issue', [ReservationController::class, 'issue'])->name('reservations.issue');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
    Route::get('/students/manage', [StudentsController::class, 'manage'])->name('students.manage');
    Route::get('/students/create', [StudentsController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentsController::class, 'edit'])->name('students.edit');
    Route::match(['put','patch'],'/students/{student}', [StudentsController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');

    Route::get('/students/{student}', [StudentsController::class, 'show'])->name('students.show');
    Route::post('/students/{student}/issue', [StudentsController::class, 'issue'])->name('students.issue');
    Route::post('/students/{student}/issues/{issue}/return', [StudentsController::class, 'returnBook'])->name('students.return');
    Route::match(['put','patch'],'/students/{student}/issues/{issue}', [StudentsController::class, 'updateIssue'])->name('students.issues.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/student/profile', [StudentProfileController::class, 'profile'])->name('student.profile');
    Route::post('/student/reservations', [ReservationController::class, 'store'])->name('student.reservations.store');
    Route::delete('/student/reservations/{reservation}', [ReservationController::class, 'cancel'])->name('student.reservations.cancel');
});

require __DIR__.'/auth.php';

// Fallback: route users to the login page by default
Route::fallback(function () {
    return redirect()->route('login');
});
