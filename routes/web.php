<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\ReservationController;
use App\Models\Book;
use App\Models\User;
use App\Models\Issue;
use Carbon\Carbon;

Route::get('/', function() {
    if (session('is_admin')) {
        return redirect()->route('dashboard');
    }
    if (session()->has('student_batch')) {
        return redirect()->route('student.profile');
    }
    return view('auth.student');
})->name('student.login.form');
Route::get('/login', function() { return redirect('/'); });

// Admin login page
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login.form');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

// Keep /student/login as redirect to root student page
Route::get('/student/login', function() { return redirect('/'); });
Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login');
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');


Route::middleware('admin')->group(function () {
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
});

Route::middleware('student')->group(function () {
    Route::get('/student/profile', [StudentProfileController::class, 'profile'])->name('student.profile');
    Route::post('/student/reservations', [ReservationController::class, 'store'])->name('student.reservations.store');
    Route::delete('/student/reservations/{reservation}', [ReservationController::class, 'cancel'])->name('student.reservations.cancel');
});

// Fallback: block direct URL access and route users to the right home
Route::fallback(function () {
    if (session('is_admin')) {
        return redirect()->route('dashboard');
    }
    if (session()->has('student_batch')) {
        return redirect()->route('student.profile');
    }
    return redirect()->route('student.login.form');
});
