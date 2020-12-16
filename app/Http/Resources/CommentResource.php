<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comments' => $this->comments,
            'user' => $this->user,
            'replies' =>    $this->collection($this->replies),
            'commentable' => $this->commentable,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
        ];
    }
}
