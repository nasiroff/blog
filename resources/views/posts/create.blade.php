@extends('layouts.app')

@section('title', 'Create post page')

@section('content')
    <div class="container">
        <form method="POST" action="{!! route('site.posts.store') !!}" enctype="multipart/form-data">
            @csrf
            <div class="row mb-2">
                <div class="col-3">
                    <label for="category">Category</label>
                </div>
                <div class="col-9">
                    <select class="form-control" name="category_id" id="category">
                        @foreach($categories as $category)
                            <option
                                value="{!! $category->id !!}" @selected(old('category_id') === $category->id)>{!! $category->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="title">Title</label>
                </div>
                <div class="col-9">
                    <input class="form-control"
                           name="title"
                           id="title"
                           type="text"
                           placeholder="Title"
                           value="{!! old('title') !!}"
                           required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="content">Name</label>
                </div>
                <div class="col-9">
                            <textarea class="form-control" name="content" id="content" type="text"
                                      rows="5"
                                      style="resize: none"
                                      placeholder="Content" required>{!! old('content') !!}</textarea>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-3">
                    <label for="images">Images</label>
                </div>
                <div class="col-9">
                    <input type="file" class="form-control" name="images[]" accept="image/jpeg, image/png" id="images"
                           multiple>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-success">
                    Login
                    <i class="fa fa-sign-in-alt"></i>
                </button>
            </div>

        </form>
    </div>
@endsection
