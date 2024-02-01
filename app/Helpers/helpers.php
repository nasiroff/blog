<?php


use App\Models\Post;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\UploadedFile;

if (!function_exists('activeRoute')) {
    function activeRoute($route, $str = 'active'): null|string
    {
        return !request()->routeIs($route) ?: $str;
    }
}

if (!function_exists('whenActiveRoute')) {
    function whenActiveRoute($cond, $str = 'active'): null|string
    {
        return !$cond ?: $str;
    }
}

if (!function_exists('storeImageWithRandomName')) {
    function storePostImageWithRandomName(Post $post, UploadedFile $image): false|string
    {
        $userId = auth()->id();
        return $image->storePublicly($userId . DIRECTORY_SEPARATOR . $post->id, [
            'disk' => 'public'
        ]);
    }
}

if (!function_exists('assetPublicFile')) {
    function assetPublicFile(string $fileName): string
    {
        return asset('storage/' . $fileName);
    }
}

if (!function_exists('adminGuard')) {
    function adminGuard(): StatefulGuard|Guard
    {
        return \Illuminate\Support\Facades\Auth::guard('admin');
    }
}


