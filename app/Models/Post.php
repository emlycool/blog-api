<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory; use SoftDeletes;

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
}
