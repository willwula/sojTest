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
//        return parent::toArray($request);
        return [
            'book_id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'owned' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
