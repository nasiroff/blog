@extends('layouts.app')

@section('title', 'Home page')

@section('content')

    <div>
        <div class="row tm-row">
            @foreach($posts as $post)
                <article class="col-12 col-md-6 tm-post">
                    <hr class="tm-hr-primary">
                    <a href="{!! route('site.posts.show', $post) !!}" class="effect-lily tm-post-link tm-pt-60">

                        @if($post->mainImage)
                            <div class="tm-post-link-inner">
                                <img src="{!! asset('storage/'. $post->mainImage->path) !!}" alt="Image"
                                     class="img-fluid">
                            </div>
                        @endif

                        @if(!$post->created_at->diffInWeeks())
                            <span class="position-absolute tm-new-badge">New</span>
                        @endif
                        <h2 class="tm-pt-30 tm-color-primary tm-post-title">{{ mb_substr($post->title, 0, 30) }}</h2>
                    </a>
                    <p class="tm-pt-30">
                        {{ mb_substr($post->content, 0, 50) }}...
                    </p>
                    <div class="d-flex justify-content-between tm-pt-45">
                        <span class="tm-color-primary">{!! $post->category->name !!}</span>
                        <span class="tm-color-primary">{!! $post->created_at->format('M d Y') !!}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>{{ $post->comments_count }} comments</span>
                        <span>by {{ $post->owner->name }}</span>
                    </div>
                </article>
            @endforeach
        </div>
        {!! $posts->links('layouts.paginate') !!}
    </div>

@endsection
