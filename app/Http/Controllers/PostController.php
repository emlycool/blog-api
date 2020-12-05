<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Post\PostResource;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;


class PostController extends Controller
{
    public function __construct(){
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(
            Post::with('category')
            ->sort()
            ->tag()
            ->category()
            ->searched()
            ->paginate(2)
        );
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userPosts(Request $request)
    {
       
        $user = $request->user();
        // $user = User::find(1);
        if($user->is_admin){
            return PostResource::collection(Post::latest()->with('category')->paginate());
        }
        $posts = $user->posts()->latest()->with('category')->paginate();
        // $posts = $request->user()->posts()->with('category')-paginate(2);
        return PostResource::collection($posts);
    }

    public function getTrashedPost(Request $request){
        $user = $request->user();
        // $user = User::find(1);
        if($user->is_admin){
            return PostResource::collection(Post::onlyTrashed()->latest()->with('category')->paginate());
        }
        $posts = $user->posts()->onlyTrashed()->latest()->with('category')->paginate();
        // $posts = $request->user()->posts()->with('category')-paginate(2);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        // validated data for post
        $data = $request->only(['title', 'description', 'content', 'images', 'category_id', 'publish_at']);

        $data['images'] = $this->uploadPostImages($data['images']);

        $data['publish_at'] = Carbon::parse($data['publish_at'])->toDateTimeString();

        $data['slug'] = $this->generateSlug(Post::class, $data['title']);
        $post = $request->user()->posts()->create($data);
        
        // tag
        $post->tags()->attach($request->tags);

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {

        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $data = $request->only(['title', 'description', 'content', 'images', 'category_id', 'publish_at']);

        // remove deleted images
        if($request->deleted_images){
            $this->deletePostImages($request->deleted_images, $post);
        }

        // map imagespath from file uploads
        if($request->images){
            $data['images'] = $this->uploadPostImages($data['images']);
        }
        
        $data['publish_at'] = Carbon::parse($data['publish_at'])->toDateTimeString();
        $data['slug'] = $this->generateSlug(Post::class, $data['title']);

        $post->update($data);

        $post->tags()->sync($request->tags);
        return (new PostResource($post->fresh()))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // delete post model binding cant work for trash posts
        // $post = Post::withTrashed()->where('id', $id)->firstorfail();

        $this->authorize('delete', $post);
        
        if($post->trashed()){
            //storage::delete($post->image);
            $this->deletePostImages($post->images, $post);
            $post->forceDelete();
        }
        else{
            $post->delete();
        }
        return response()->json(null, 204);
    }

    /**
     * Restore the specified resource to storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        // delete post model binding cant work for trash posts
        $post = Post::withTrashed()->where('id', $id)->firstorfail();

        $this->authorize('delete', $post);
        $post->restore();
    }

    private function uploadPostImages(array $images){
        // map imagespath from file uploads
        $imagesPath = collect([]);
        collect($images)->each( function (UploadedFile $image) use(&$imagesPath){
            $path = $image->store('posts', 'public');
            $imagesPath->push($path);
        });
        return $imagesPath->toArray();
        
    }

    private function deletePostImages(array $postImagesUrl, Model $post){
        $deletedImagePaths = [];
        foreach ($postImagesUrl as $imageUrl) {
            $parsedUrl = parse_url($imageUrl, PHP_URL_PATH);
            $imagePath = str_replace('/storage/', '', $parsedUrl);
            
            Storage::disk('public')->delete($imagePath);
            array_push($deletedImagePaths, $imagePath);

        }
        $imagesNotDeleted = collect($post->images)
                                    ->filter( fn ($imagePath) => in_array($imagePath, $deletedImagePaths) )
                                    ->toArray();

        $post->images = $imagesNotDeleted;
        $post->save();
    }
}
