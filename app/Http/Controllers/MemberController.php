<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeMemberRequest;
use App\Http\Requests\updateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::with('activeBorrowings');

        if($request->has('search'))
        {
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email','like',"%{$search}%");
            });
        }

        if($request->has('status'))
        {
            $query->where('status', $request->status);
        }

        $members = $query->paginate(10);

        return MemberResource::collection($members);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeMemberRequest $request)
    {
        $member = Member::create($request->validated());

        return new MemberResource($member);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $member = Member::findOrFail($id);
            $member->load(['activeBorrowings','borrowings']);
            return new MemberResource($member);
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
    public function update(updateMemberRequest $request, Member $member)
    {
        $member->update($request->validated());
        return new MemberResource($member);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try{
            $member = Member::findOrFail($id);
            if($member->activeBorrowings()->count() >0)
            {
                return response()->json([
                    'message'=>'can not deleted'
                ],422);
            }

            $member->delete();
            return response()->json([
                'mesaage'=>'member was deleted successfully'
            ]);
        }
        catch(\Exception $th){
            return response()->json([
                'mesaage'=>'member was Not found'
            ]);
        }
    }
}
