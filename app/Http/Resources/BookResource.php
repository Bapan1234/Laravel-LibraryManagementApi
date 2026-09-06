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
            //'author_name'=>$this->author->name,
            'description'=>$this->description,
            'isbn'=>$this->isbn,
            'genre'=>$this->genre,
            'availble_copies'=>$this->availble_copies,
            'price'=>$this->price,
            'cover_image'=>$this->cover_image,
            'is_available'=>$this->isAvailable(),
            'status'=>$this->status,
            'author'=>new AuthorResource($this->whenLoaded('author'))
        ];
    }
}
