<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->withCount('comments')
            ->with(['owner', 'mainImage'])
            ->when(\request()->filled('category'), function (Builder $query) {
                $query->where('category_id', \request()->input('category'));
            })
            ->when(request()->filled('query'), function (Builder $query) {
                $query->where('title', 'like', sprintf('%%%s%%', \request()->input('query')));
            })
            ->active()
            ->orderByDesc('id')
            ->paginate(30);
        return view('index', compact('posts'));
    }

    public function chat()
    {
        return \view('chat');
    }
}
