<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Likeable;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory,
        SoftDeletes,
        Likeable,
        Searchable;

    protected $casts = [
        'images' => 'array',
    ];

    protected $dates = [
        'published_at'
    ];

    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query){
        return $query->where('publish_at', '<=', now());
    }
    
    public function scopeSearched($query)
    {
        $search = request()->search;
        if(!$search){
            return $query->published();
        }
        else{
            return $query->published()->where('title', 'LIKE', "%{$search}%");
        }
    }

    public function scopeSort($query)
    {
        $sort = request()->sort;
        if(!$sort){
            return $query->published();
        }
        else{
            return $query->published()->orderBy('created_at', $sort);
        }
    }

    public function scopeTag($query)
    {
        $tag = request()->tag;
        if(!$tag){
            return $query->published();
        }
        else{
            return $query->published()->whereHas('tags', fn ($query) => $query->where('tag_id', $tag));
        }
    }

    public function scopeCategory($query)
    {
        $category = request()->category;
        if(!$category){
            return $query->published();
        }
        else{
            return $query->where('category_id', $category);
        }
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }

    
    /**
     * Get all of the post's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    
}
