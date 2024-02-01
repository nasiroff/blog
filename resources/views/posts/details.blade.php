@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div>
        <div class="row tm-row">
            <div class="col-12">
                <hr class="tm-hr-primary tm-mb-55">
                <!-- Video player 1422x800 -->
                <img width="954" height="535" src="{!! assetPublicFile($post->mainImage->path) !!}" class="tm-mb-40">
            </div>
        </div>
        <div class="row tm-row">
            <div class="col-lg-8 tm-post-col">
                <div class="tm-post-full">
                    <div class="mb-4">
                        <h2 class="pt-2 tm-color-primary tm-post-title">{{ $post->title }}</h2>
                        <p class="tm-mb-40">{{ $post->created_at->format('M d Y') }} posted
                            by {!! $post->owner->name !!}</p>
                        <p>
                            {{ $post->content }}
                        </p>
                        <span class="d-block text-right tm-color-primary">{!! $post->category->name !!}</span>
                    </div>

                    <!-- Comments -->
                    <div>
                        <h2 class="tm-color-primary tm-post-title">Comments</h2>
                        <hr class="tm-hr-primary tm-mb-45">

                        @foreach($post->comments as $comment)
                            <div class="tm-comment tm-mb-45">
                                <div class="container">
                                    <p>
                                        {{ $comment->content }}
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <span class="tm-color-primary">{{ $comment->owner->name }}</span>
                                        <span
                                            class="tm-color-primary">{!! $comment->created_at->format('M d Y') !!}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @auth
                            <form action="{!! route('site.posts.comments.store', $post) !!}" method="post"
                                  class="mb-5 tm-comment-form">
                                @csrf
                                <h2 class="tm-color-primary tm-post-title mb-4">Your comment</h2>
                                <div class="mb-4">
                                    <textarea class="form-control" name="content" rows="6"></textarea>
                                </div>
                                <div class="text-right">
                                    <button class="tm-btn tm-btn-primary tm-btn-small">Submit</button>
                                </div>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
            <aside class="col-lg-4 tm-aside-col">
                <div class="tm-post-sidebar">
                    <hr class="mb-3 tm-hr-primary">
                    <h2 class="mb-4 tm-post-title tm-color-primary">Categories</h2>
                    <ul class="tm-mb-75 pl-5 tm-category-list">
                        @foreach($categories as $category)
                            <li><a href="{!! route('site.home', ['category' => $category->id]) !!}"
                                   class="tm-color-primary">{!! $category->name !!}</a></li>
                        @endforeach
                    </ul>
                    <hr class="mb-3 tm-hr-primary">
                    <h2 class="tm-mb-40 tm-post-title tm-color-primary">Related Posts</h2>
                    @foreach($relatedPosts as $relatedPost)
                        <a href="{!! route('site.posts.show', $relatedPost) !!}" class="d-block tm-mb-40">
                            <figure>
                                <img src="{!! assetPublicFile($relatedPost->mainImage->path) !!}" alt="Image" class="mb-3 img-fluid">
                                <figcaption class="tm-color-primary">
                                    {{ $relatedPost->title }}
                                </figcaption>
                            </figure>
                        </a>
                    @endforeach

                </div>
            </aside>
        </div>
    </div>
@endsection
