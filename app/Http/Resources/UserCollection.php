<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' =>   $this->collection->map( fn($user) => [
                            'name' => $user->name,
                            'email' => $user->email,
                            'profile_pic' => $user->profile_pic,
                            'is_admin' => $user->is_admin,
                            'role' => $user->role->name
                        ])
        ];
    }
}
