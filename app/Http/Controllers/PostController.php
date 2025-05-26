<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $postService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the posts.
     */
    public function index(Request $request)
    {
        $platform = $request->query('platform');
        $status = $request->query('status');
        $search = $request->query('search');

        $posts = $this->postService->getAllPosts($platform, $status, $search);
        $platforms = $this->postService->getPlatformsForSelection();

        return view('posts.index', [
            'posts' => $posts,
            'platforms' => $platforms
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $platforms = $this->postService->getPlatformsForSelection();

        return view('posts.create', [
            'platforms' => $platforms
        ]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = $this->postService->createPost($request->validated());

        return redirect()->route('posts.index')
            ->with('success', 'Post was successfully ' . ($request->status == 'draft' ? 'saved as draft' : 'scheduled'));
    }

    /**
     * Display the specified post.
     */
    public function show(string $id)
    {
        $post = $this->postService->getPost($id);

        return view('posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(string $id)
    {
        $post = $this->postService->getPost($id);
        $platforms = $this->postService->getPlatformsForSelection();

        return view('posts.edit', [
            'post' => $post,
            'platforms' => $platforms
        ]);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $post = $this->postService->updatePost($id, $request->validated());

        return redirect()->route('posts.index')
            ->with('success', 'Post was successfully updated');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(string $id)
    {
        $this->postService->deletePost($id);

        return redirect()->route('posts.index')
            ->with('success', 'Post was successfully deleted');
    }
}
