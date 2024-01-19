<?php


namespace App\Http\Controllers;


use App\Models\Comment;
use App\Traits\ApiTrait;
use App\Models\Post;
use App\Models\Tag;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\AddCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{

    private $user;
    public function __construct()
    {
         $this->middleware('auth:api');
         $token = JWTAuth::getToken();
         $this->user = JWTAuth::toUser($token);
       
    }

    public function getallComments()
    {
        try {
            $comments = Comment::with('user', 'post')->where('user_id', $this->user->id)->get();
            return ApiTrait::data(compact('comments'));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([],'Unprocessable content',422);
        }
    }
    

    public function addComment(AddCommentRequest $request)
    {
        try {
            $user = $this->user;
            $post = Post::find($request->post_id);

            if (!$post) {
                return ApiTrait::errorMessage([], 'Not Found', 404);
            }
            $comment = new Comment([
                'body' => $request->input('body'),
                'user_id' => $user->id,
            ]);

        
            $post->comments()->save($comment);

            return ApiTrait::successMessage('Comment added successfully', 201);

        } catch (\Exception $e) {
            return ApiTrait::errorMessage(["error" => $e->getMessage()], 'Bad Request', 400);
        }
    }


    public function updateComment(UpdateCommentRequest $request, $commentId)
    {
        try {
            $user = $this->user;
    
            $comment = Comment::find($commentId);
    
            if (!$comment) {
                return ApiTrait::errorMessage([], 'Comment not found', 404);
            }
    
           
            if ($user->id !== $comment->user_id) {
                return ApiTrait::errorMessage([], 'Unauthorized to update Comment', 401);
            }
    
            // Update the comment body
            $comment->body = $request->input('body');
            $comment->save();
    
            return ApiTrait::successMessage('Comment updated successfully', 200);
    
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(["error" => $e->getMessage()], 'Bad Request', 400);
        }
    }



    public function DeleteComment($id)
    {
        
        try {
            $user = $this->user;
            $comment = Comment::findOrFail($id);
            if($comment->user_id == $user->id)
            {
                Comment::where('id', $id)->delete();
                return ApiTrait::successMessage('Comment Deleted Successfully',200);
            }
            else
            {
                return ApiTrait::errorMessage([],'Sorry you can delete your Comments only',400);
            }
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>'The Given Id Is Invalid'],'Unprocessable content',422);
        }

    }

    


}
