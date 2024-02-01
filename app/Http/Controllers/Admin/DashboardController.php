<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function posts()
    {
        $status = \request()->input('status', Post::STATUS_PENDING);

        $posts = Post::query()
            ->with('owner')
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(30);
        return view('admin.posts.index', compact('posts'));
    }

    public function changeStatus(Post $post)
    {
        $status = \request()->input('status', Post::STATUS_REJECTED);
        $post->update(['status' => $status]);
        return redirect()->back()->with(['success' => 'elave edildi']);
    }

    public function getMostPopularPosts()
    {
        $posts = Post::select('posts.*', DB::raw('COUNT(comments.id) as comment_count'))
            ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
            ->groupBy('posts.id')
            ->orderByDesc('comment_count')
            ->take(config('post.popular_post_count'))
            ->get();

        return view('admin.posts.popular', compact('posts'));
    }

    public function users()
    {
        $users = User::query()->paginate(30);
        return view('admin.users.index', compact('users'));
    }

    public function blockUser(User $user)
    {
        $user->delete();
        Post::query()->where('user_id', $user->id)->delete();
        return redirect()->back()->with(['success' => 'Istifadeci silindi']);
    }
}
