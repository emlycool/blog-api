<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Events\PostCommented;
use App\Events\PostCommentReplied;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct(){
    
        $this->middleware(['auth:sanctum'])->except(['getPostComments']);
    }

    public function commentPost(Request $request, Post $post){
        // print $post;
        $validatedData = Validator::make($request->all(), [
            'comment' => 'required'
        ])->validate();
        $comment = $post->comments()->create([
                        'user_id' => $request->user()->id,
                        'comments' => $validatedData['comment']
                    ]);
        // PostCommented::dispatch($comment);
        event(
            (new PostCommented($comment))
            // ->dontBroadcastToCurrentUser()
        );
        return (new CommentResource($comment) )->response()->setStatusCode(200);
    }

    public function replyCommentPost(Post $post, Comment $comment, Request $request){
        
        $validatedData = Validator::make($request->all(), [
            'comment' => 'required'
        ])->validate();
        $comment = $post->comments()->create([
                        'user_id' => $request->user()->id,
                        'comments' => $validatedData['comment'],
                        'parent_id' => $comment->id
                    ]);
        // PostCommented::dispatch($comment);
        event(
            (new PostCommentReplied($comment))
            // ->dontBroadcastToCurrentUser()
        );
        return (new CommentResource($comment) )->response()->setStatusCode(200);
    }

    public function getPostComments(Post $post, Request $request){
        $comments = Comment::
        where([['commentable_id', $post->id], ['commentable_type', Post::class]])
        ->whereNull('parent_id')
        ->latest();
        return (CommentResource::collection($comments->paginate(5)) )->response()->setStatusCode(200);
    }
}
