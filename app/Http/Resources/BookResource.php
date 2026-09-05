<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'=>$this->title,
            'author_name'=>$this->author->name,
            'description'=>$this->description,
            'isbn'=>$this->isbn,
            'price'=>$this->price,
            'status'=>$this->status,
        ];
    }
}
