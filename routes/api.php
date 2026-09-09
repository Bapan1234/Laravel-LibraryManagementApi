<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthorController::class,'register']);
Route::post('/login',[AuthorController::class,'login']);

Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('members', MemberController::class);
Route::apiResource('borrowings',BorrowingController::class)->only(['index','store','show']);
Route::post('/borrowings/{borrowing}/return', [BorrowingController::class,'returnBook']);
Route::get('/borrowings/overdue/list',[BorrowingController::class, 'overDue']);

Route::get('statices',function(){
return response()->json([
'total_books'=>App\Models\Book::count(),
'total_authors'=>App\Models\Author::count(),
'total_member'=>App\Models\Member::count(),
'total_borrowed'=>App\Models\Borrowing::where('status','borrowed')->count(),
'total_borrowing'=>App\Models\Borrowing::where('status','overdue')->count(),
]);
});
