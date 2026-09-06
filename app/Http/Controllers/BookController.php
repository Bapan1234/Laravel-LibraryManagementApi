<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeBookRequest;
use App\Http\Requests\updateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('author');

        //search functionality
        if($request->has('search'))
        {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('isbn','like', "%{$search}%")
                ->orWhereHas('author',function($authorQuery) use ($search){
                    $authorQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if($request->has('genre'))
        {
            $query->where('genre', $request->genre);
        }

        $books = $query->paginate(10);

        //return response()->json(['Book'=>$books]);
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeBookRequest $request)
    {
        $book= Book::create($request->validated());
        $book->load('author');

        // return response()->json(['message'=>'Data was create sucessfull']);
        return new BookResource($book);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $book=Book::findOrFail($id);
            $book->load('author');
            return new BookResource($book);
        }
        catch(\Exception $th){
            return response()->json([
                'status'=>false,
                'message'=>'The Book is not found'
            ],400);
        };
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $book = Book::findOrFail($id)->delete();
            return response()->json(['message'=>'Data was deleted']);
        }
        catch(\Exception $e){
            return response()->json(['message'=>'Book wan Not found']);
        }

    }
}
