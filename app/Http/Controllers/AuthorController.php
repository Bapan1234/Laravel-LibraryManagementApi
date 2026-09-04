<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeAuthorRequest;
use App\Models\Author;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author:: all();
        return response()->json(['authors'=>$authors, 'message'=>'Author Fetched with Success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeAuthorRequest $request)
    {
        $authors = Author::create($request->validated());

        return response()->json(['author'=>$authors]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
