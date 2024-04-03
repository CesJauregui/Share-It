<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse as HttpFoundationJsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function getPosts(): JsonResponse
    {
        return response()->json([
            Post::all()
        ], Response::HTTP_OK);
    }

    public function createPost(PostRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!$validated) {
            return response()->json([
                'status' => false,
                'errors' => $validated
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = Post::create($validated);

        return response()->json([
            'status' => true,
            'post' => $post,
            'message' => 'Se creó el post correctamente.'
        ], Response::HTTP_OK);
    }

    public function getPostById($id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró un post con el id: ' . $id
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'post' => $post
        ], Response::HTTP_OK);
    }

    public function countPost(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'count' => Post::where('user_id', Auth::id())->count()
        ], Response::HTTP_OK);
    }

    public function updatePost(PostRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        if (!$validated) {
            return response()->json([
                'status' => false,
                'errors' => $validated
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = Post::where('id', $id);
        $post->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Se actualizó el post correctamente.',
            'post' => Post::find($id)
        ], Response::HTTP_OK);
    }

    public function deletePost($id): JsonResponse
    {
        $post = Post::destroy($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'No se encontró un post con el id: ' . $id
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => true,
            'message' => 'Se eliminó el post con el id: ' . $id
        ]);
    }

    public function getPostType(): JsonResponse
    {
        return response()->json([
            DB::select('select * from post_types'),
        ], Response::HTTP_OK);
    }

    public function createPostType(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'description' => 'required|string|unique:post_types'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }
        DB::insert('insert into post_types (description) values (?)', [$request->description]);

        return response()->json([
            'status' => true,
            'message' => 'Se creó un tipo de post correctamente.',
        ], Response::HTTP_CREATED);
    }


    //TODO: MAS METODOS, UPDATE, DELETE, FIND_BY_ID
}
