<?php

namespace App\Http\Resources\Post;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'images' => $this->mapImages(),
            'user' => $this->user,
            'category' => ['id' => $this->category->id, 'name' => $this->category->name],
            'tags'  => $this->tags,
            'slug'  => $this->slug,
            'publish_at' => $this->publish_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'likes' => $this->total_likes ?? $this->likes()->where('liked', true)->count(),
            'is_liked' => auth()->user()? $this->isLikedByUser(): false,
        ];
    }

    public function mapImages(){
        $images = $this->images instanceof Collection ? $this->images : Collection::make($this->images);
        return collect($images)->map( function ($image){
            return  secure_asset('storage/'.$image);
        })->toArray();
    }
}
