<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if (isset($params['orderBy']))
            $posts = Post::orderBy($params['orderBy'], $params['dir'])->get();
        else
            $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $params = $request->validate([
            'title' => 'required|max:255|distinct',
            'content' => 'required'
        ]);
        $params['slug'] = str_replace(' ', '-', $params['title']);

        $post = Post::create($params);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        dd($request->all());
        $params = $request->validate([
            'title' => 'required|max:255|distinct',
            'content' => 'required'
        ]);
        $params['slug'] = $post->slug;

        $post->update($params);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Request $request)
    {
        $request = ['orderBy' => $request['orderBy'], 'dir' => $request['dir']];
        $post->delete();
        return redirect()->route('admin.posts.index', $request);
    }
}
