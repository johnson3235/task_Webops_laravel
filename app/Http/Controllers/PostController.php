<?php


namespace App\Http\Controllers;


use App\Traits\ApiTrait;
use App\Traits\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\Tag;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\AddPostRequest;
use App\Http\Requests\UpdatePostRequest;
class PostController extends Controller
{

    private $user;
    public function __construct()
    {
         $this->middleware('auth:api');
         $token = JWTAuth::getToken();
         $this->user = JWTAuth::toUser($token);
       
    }

    public function getallposts()
    {
        try {
            $posts = Post::with('tags', 'comments')->get();
            return ApiTrait::data(compact('posts'));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage([],'Unprocessable content',422);
        }
    }
    

    public function AddPost(AddPostRequest $request)
    {
        try {
            $user =$this->user;
            $post = Post::create([
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'user_id' => $user->id
            ]);
        $tags = collect($request->input('tags'))->unique();
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag);
        }
        return ApiTrait::successMessage('Post Created Successfully',201);
           
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(["error"=> $e ],'Bad Request',400);
        }
    }


    public function UpdatePost(UpdatePostRequest $request, $postId)
    {
      try {
        $user = $this->user;

    
        $post = Post::where('id', $postId)
            ->where('user_id', $user->id)
            ->first();

        if (!$post) {
            return ApiTrait::errorMessage([],'Post not found or you do not have permission to update it', 404);
        }

        $post->title = $request->input('title', $post->title);
        $post->body = $request->input('body', $post->body);
        $post->save();

        $tags = collect($request->input('tags'))->unique();
        $post->tags()->detach();

        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag);
        }

        return ApiTrait::successMessage('Post Updated Successfully', 200);

      } catch (\Exception $e) {
        return ApiTrait::errorMessage(["error" => $e->getMessage()], 'Bad Request', 400);
      }
    }

    public function DeletePost($id)
    {
        
        try {
            $user = $this->user;
            $post = post::findOrFail($id);
            if($post->user_id == $user->id)
            {
                Post::where('id', $id)->delete();
                return ApiTrait::successMessage('Post Deleted Successfully',200);
            }
            else
            {
                return ApiTrait::errorMessage([],'Sorry you can delete your posts only',400);
            }
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>'The Given Id Is Invalid'],'Unprocessable content',200);
        }

    }

    


}
