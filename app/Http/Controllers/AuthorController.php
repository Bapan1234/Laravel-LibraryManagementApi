<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author:: paginate(10);
        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeAuthorRequest $request)
    {
        $authors = Author::create($request->validated());

        return new AuthorResource($authors);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
