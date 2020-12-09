<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Post;
use App\PostComment;
use App\PostLike;

class MainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function getTimeline(Request $request)
    {
        $input = $this->validate($request, [
            'user_id' => 'required',
        ]);

        try {
            $posts = Post::with(['user', 'comment', 'like'])->get()->toArray();

            foreach ($posts as &$post) {
                $isLiked = PostLike::where(['user_id' => $input['user_id'], 'post_id' => $post['id']])->count();
                if ($isLiked > 0) {
                    $post['isLiked'] = true;
                }else{
                    $post['isLiked'] = false;
                }
            }
           
            return $this->successResponse($posts);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function detailStory(Request $request)
    {
        try {
            
            $input = $this->validate($request, [
                'user_id' => 'required',
                'post_id' => 'required'
            ]);

            $posts = Post::where(['id' => $input['post_id']])->with(['user', 'comment', 'like'])->first()->toArray();

           
            $isLiked = PostLike::where(['user_id' => $input['user_id'], 'post_id' => $input['post_id']])->count();
            if ($isLiked > 0) {
                $posts['isLiked'] = true;
            }else{
                $posts['isLiked'] = false;
            }
            

            return $this->successResponse($posts);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function myStory(Request $request)
    {
        $input = $this->validate($request, [
            'user_id' => 'required',
        ]);

        try {
            $posts = Post::where(['user_id' => $input['user_id']])->with(['user', 'comment', 'like'])->get()->toArray();

            foreach ($posts as &$post) {
                $isLiked = PostLike::where(['user_id' => $input['user_id'], 'post_id' => $post['id']])->count();
                if ($isLiked > 0) {
                    $post['isLiked'] = true;
                }else{
                    $post['isLiked'] = false;
                }
            }
           
            return $this->successResponse($posts);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function createStory(Request $request)
    {
        try {
            $input = $this->validate($request, [
                'user_id' => 'required',
                'caption' => 'required',
                'photo' => 'mimes:jpg,bmp,png'
            ]);

            $post = new Post();
            $post->user_id = $input['user_id'];
            $post->caption = $input['caption'];

            if ($request->hasFile('photo')) {
                $filename =  Str::random(32).'.'.$request->photo->extension();
                $path = $request->file('photo')->move('storage/photo', $filename);
                $post->photo = 'storage/photo'.'/'.$filename;
            }
            
            $post->save();

            return $this->successResponse($post);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function like(Request $request)
    {
        try {
            $input = $this->validate($request, [
                'post_id' => 'required',
                'user_id' => 'required',
                'status' => 'required'
            ]);

            if ($input['status']) {
                $like = new PostLike();
                $like->post_id = $input['post_id'];
                $like->user_id = $input['user_id'];
                $like->save();
            }else{
                PostLike::where(['post_id' => $input['post_id'], 'user_id' => $input['user_id']])->delete();
            }
           

            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function comment(Request $request)
    {
        try {
            $input = $this->validate($request, [
                'post_id' => 'required',
                'user_id' => 'required',
                'caption' => 'required',
            ]);

            $comment = new PostComment();
            $comment->post_id = $input['post_id'];
            $comment->user_id = $input['user_id'];
            $comment->caption = $input['caption'];
            $comment->save();

            return $this->successResponse($comment);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //
}
