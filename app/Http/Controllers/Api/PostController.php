<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Posts",
    description: "Post management endpoints"
)]
class PostController extends Controller
{
    private $repo;

    public function __construct(PostRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    //  Helper methods
    private function success($message, $data = [], $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    private function error($message, $code = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], $code);
    }

    #[OA\Get(
        path: "/api/posts",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "List posts")
        ]
    )]
    public function index()
    {
        try {
            return $this->success('Posts fetched successfully', $this->repo->all());

        } catch (\Exception $e) {
            return $this->error('Failed to fetch posts');
        }
    }

    #[OA\Post(
        path: "/api/posts",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Post created")
        ]
    )]
    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->repo->create($request->validated());

            return $this->success('Post created successfully', $post);

        } catch (\Exception $e) {
            return $this->error('Failed to create post');
        }
    }

    #[OA\Get(
        path: "/api/posts/{id}",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Single post")
        ]
    )]
    public function show($id)
    {
        try {
            $post = $this->repo->find($id);

            return $this->success('Post fetched successfully', $post);

        } catch (\Exception $e) {
            return $this->error('Failed to fetch post');
        }
    }

    #[OA\Put(
        path: "/api/posts/{id}",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Updated")
        ]
    )]
    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = $this->repo->update($id, $request->validated());

            return $this->success('Post updated successfully', $post);

        } catch (\Exception $e) {
            return $this->error('Failed to update post');
        }
    }

    #[OA\Delete(
        path: "/api/posts/{id}",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Deleted")
        ]
    )]
    public function destroy($id)
    {
        try {
            $this->repo->delete($id);

            return $this->success('Post deleted successfully', [
                'deleted' => true
            ]);

        } catch (\Exception $e) {
            return $this->error('Failed to delete post');
        }
    }
}
