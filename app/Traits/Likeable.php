<?php

namespace App\Traits;


use App\Models\Like;
use Illuminate\Database\Eloquent\Builder;

/**
 *  all functionalies of likeable on model goes here
 */
trait Likeable
{
    public function likes(){
        return $this->hasMany(Like::class, 'post_id');
    }

    public function like($user = null, $like = true){
        $this->likes()->updateOrCreate(
            [
                'user_id' => $user? $user->id : auth()->user()->id
            ],
            [
                'liked'  => !$this->isLikedByUser()
            ]
        );
    }

    public function isLikedByUser($user =null){
        return (bool) $this->likes()->where([
                                                ['user_id', $user? $user->id: auth()->user()->id],
                                                ['liked', true]
                                            
                                            ])->count();
    }

    public function scopeWithLikesCount(Builder $query){
        // SELECT * FROM `posts` LEFT JOIN ( SELECT post_id, SUM(liked) likes FROM `likes` group by post_id) likes on posts.id = likes.post_id
        $query->leftJoinSub('SELECT post_id, SUM(liked) total_likes FROM `likes` group by post_id',
         'likes',
         'posts.id',
         '=',
         'likes.post_id'
        )->get();
    }
}

