<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts= Post::orderBy('id','desc')->with('category')->get();
            if ($posts) {
                return response()->json([
                    'success'=>true,
                    'posts'=>$posts
                ],200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'error'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validation= Validator::make( $request->all(),[
                'title'=>'required|string|max:100|min:10|unique:posts',
                'decription'=>'required|string',
                'image'=>'required|image|max:2048',
                'views'=>'nullable|int',
                'category_id'=>'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success'=>false,
                    'message'=> "Validator error",
                    'error'=> $validation->errors(),
                ],422);
            }
            else{
                $filename="";
                if ($request->hasFile('image')) {
                    $filename= $request->file('image')->store('posts', 'public');
                }else{
                    $filename=null;
                };
                $post = Post::create([
                    'title'=> $request->title,
                    'decription'=> $request->decription,
                    'image'=>$filename,
                    'views'=> $request->views,
                    'category_id'=> $request->category_id,
                ]);
                if ($post) {
                    return response()->json([
                        'success'=> true,
                        'message'=> "post add succefully",
                        'post'=> $post,
                    ],201);
                }else{
                    return response()->json([
                        'success'=> false,
                        'message'=> "some problem",
                    ],201);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'error'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return  response()->json([
                'status'=> false,
                'message'=> "Post not found"
            ],404); ;
        }
        return  response()->json([
            'status'=> true,
            'post'=> $post
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $post=Post::findOrFail($id);
            $validation= Validator::make( $request->all(),[
                'title'=>'required|string|max:100|min:10|unique:posts',
                'decription'=>'required|string',
                'image'=>'required|image|max:2048',
                'views'=>'nullable|int',
                'category_id'=>'required|exists:categories,id',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success'=>false,
                    'message'=> "Validator error",
                    'error'=> $validation->errors(),
                ],422);
            }
            else{
                if ($request->hasFile('image')) {
                    if ($post->image) {
                        Storage::disk('public')->delete($post->image);
                    }
                    $filename = $request->file('image')->store('posts', 'public');
                }else{
                    $filename=$post->image;
                };
                $post->update([
                    'title' => $request->title,
                    'decription' => $request->decription,
                    'image' => $filename,
                    'views' => $request->views,
                    'category_id' => $request->category_id,
                ]);

                if ($post) {
                    return response()->json([
                        'success'=> true,
                        'message'=> "post add succefully",
                        'post'=> $post,
                    ],201);
                }else{
                    return response()->json([
                        'success'=> false,
                        'message'=> "some problem",
                    ],201);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'error'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOFail($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ],422);
        }
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Podt delete succefully'
        ], 200);
    }
    public function searchByTitle(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'title' => 'required|string|max:100',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Validation error",
                    'error' => $validation->errors(),
                ], 422);
            }else{
                $title = $request->input('title');
                $posts = Post::where('title', 'LIKE', '%' . $title . '%')
                              ->orderBy('created_at', 'desc')
                              ->get();
                if ($posts) {
                    return response()->json([
                        'success'=>true,
                        'message' => 'Search results',
                        'categories'=>$posts
                    ],200);
                }
            }

        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'error'=>$e->getMessage()
            ]);
        }

    }
}

