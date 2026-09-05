<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();

        return response()->json(['Book'=>$books]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeBookRequest $request)
    {
        $book= Book::create($request->validated());

        // return response()->json(['message'=>'Data was create sucessfull']);
        return new BookResource($book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // $book = Book::find($id);
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(storeBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id)->delete();
        return response()->json(['message'=>'Data was deleted']);
    }
}
