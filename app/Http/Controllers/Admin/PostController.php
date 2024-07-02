<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
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
                'description'=>'required|string',
                'image'=>'required|image|max:2048',
                'category_id'=>'required',
                'views'=>'nullable|int',
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
                }
                $post = Post::create([
                    'title'=> $request->title,
                    'description'=> $request->description,
                    'image'=>$filename,
                    'category_id'=> $request->category_id,
                    'views'=> $request->views,
                ]);
                if ($post) {
                    return response()->json([
                        'success'=> true,
                        'message'=> "Category add succefully",
                        'category'=> $post,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
