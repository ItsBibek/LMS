<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\StudentsController;
use App\Models\Book;
use App\Models\User;
use App\Models\Issue;
use Carbon\Carbon;

Route::get('/', function () {
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

// Students
Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
// Manage Students CRUD
Route::get('/students/manage', [StudentsController::class, 'manage'])->name('students.manage');
Route::get('/students/create', [StudentsController::class, 'createStudent'])->name('students.create');
Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
Route::get('/students/{student}/edit', [StudentsController::class, 'edit'])->name('students.edit');
Route::match(['put','patch'],'/students/{student}', [StudentsController::class, 'update'])->name('students.update');
Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');

// Existing student detail and issue/return
Route::get('/students/{student}', [StudentsController::class, 'show'])->name('students.show');
Route::post('/students/{student}/issue', [StudentsController::class, 'issue'])->name('students.issue');
Route::post('/students/{student}/issues/{issue}/return', [StudentsController::class, 'returnBook'])->name('students.return');
