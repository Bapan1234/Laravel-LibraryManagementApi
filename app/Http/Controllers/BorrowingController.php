<?php

namespace App\Http\Controllers;
use App\Models\Borrowing;
use App\Models\Book;
use App\Http\Resources\BorrowingResource;
use App\Http\Requests\storeBorrowingRequest;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['book','member']);

        if($request->has('status')){
            $query->where('status',$request->status);
        }

        if($request->has('member_id')){
            $query->where('member_id',$request->member_id);
        }

        $borrowing = $query->paginate(10);

        return BorrowingResource::collection($borrowing);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeBorrowingRequest $request)
    {
        $book=Book::findOrFail($request->book_id);

        if(!$book->isAvailable()){
            return response()->json([
                'message'=>'Book is not available'
            ],422);
        }

        $borrowing = Borrowing::create($request->validated());

        $book->borrow();

        $borrowing->load(['book','member']);

        return new BorrowingResource($borrowing);

    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['book','member']);
        return new BorrowingResource($borrowing);
    }

    public function returnBook(Borrowing $borrowing){

        if($borrowing->status!=='borrowed'){
            return response()->json([
                'message'=>'Book has already returned'
            ],422);
        }

        $borrowing->update([
            'returned_date'=>now(),
            'status'=>'returned'
        ]);

        $borrowing->book->returnBook();

        $borrowing->load(['book','member']);

        return BorrowingResource::collection($borrowing);
    }

    public function overDue(){
        $overDueBorrowing = Borrowing::with(['book','member'])
        ->where('status','borrowed')
        ->where('due_date','<',now())
        ->get();

        Borrowing::where('status','borrowed')
        ->where('due_date','<',now())
        ->update(['status'=>'overdue']);

        return BorrowingResource::collection($overDueBorrowing);

    }
}
