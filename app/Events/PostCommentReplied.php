<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\CommentResource;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostCommentReplied implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $comment;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = ( new CommentResource($comment) )->resolve();
        // $this->comment->user;
        // $this->comment->replies;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // $commentable = $this->comment->commentable;
        $commentable = $this->comment['commentable'];

        // return new Channel('comments-'.$commentable->slug);
        return new Channel('comments-'.$commentable['slug']);
    }
}
