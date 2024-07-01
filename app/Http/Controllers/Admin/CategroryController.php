<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategroryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories= Category::orderBy('id', 'desc')->get();
            if ($categories) {
                return response()->json([
                    'success'=>true,
                    'categories'=>$categories
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
                'category_name'=>'required|string|max:20|min:10|unique:categories',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success'=>false,
                    'message'=> "Validator error",
                    'error'=> $validation->errors(),
                ],422);
            }
            else{
                $category = Category::create($request->all());
                if ($category) {
                    return response()->json([
                        'success'=> true,
                        'message'=> "Category add succefully",
                        'category'=> $category,
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
        try {
            $category = Category::findOrFail($id);
            if ($category) {
                return  response()->json([
                    'status'=> true,
                    'article'=> $category
                ],200);
            }
        } catch (Exception $e) {
            return  response()->json([
                'success'=> false,
                'message'=> "Category not found"
            ],404); ;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $validation= Validator::make( $request->all(),[
                'category_name'=>'required|string|max:20|min:10|unique:categories',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success'=>false,
                    'message'=> "Validator error",
                    'error'=> $validation->errors(),
                ],422);
            }
            else{
                $category= $category->update($request->$validation);
                if ($category) {
                    return response()->json([
                        'success'=> true,
                        'message'=> "Category update succefully",
                        'category'=> $category,
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
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category introuvable'
            ],422);
        }
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category supprimé success'
        ], 200);
    }

    public function search($search)
    {
        try {
            $categories= Category::where('category_name','LIKE','%'.$search.'%')-> orderBy('id', 'desc')->get();
            if ($categories) {
                return response()->json([
                    'success'=>true,
                    'categories'=>$categories
                ],200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'error'=>$e->getMessage()
            ]);
        }

    }
}


