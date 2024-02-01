<?php

namespace App\Http\Controllers\Site;

use App\Exceptions\ImageSaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\PostRequest;
use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function create()
    {
        $categories = Category::query()->orderBy('order')->get();
        return view('posts.create', compact('categories'));
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $payload = array_merge($request->only([
            'title',
            'category_id',
            'content'
        ]), [
            'user_id' => auth()->id(),
            'status' => Post::STATUS_PENDING
        ]);

        $images = $request->file('images');

        DB::beginTransaction();

        try {

            $post = Post::query()->create($payload);
            $isMain = true;
            $imageModels = [];
            if ($images) {
                foreach ($images as $image) {
                    $imagePath = storePostImageWithRandomName($post, $image);
                    if (!$imagePath) {
                        throw new ImageSaveException("Image can't save");
                    }
                    $imageModels[] = new Image([
                        'path' => $imagePath,
                        'is_main' => $isMain
                    ]);

                    $isMain = false;
                }
                $post->images()->saveMany(
                    $imageModels
                );
            }


        } catch (ImageSaveException $exception) {
            DB::rollBack();
            return redirect()
                ->back(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->withInput()
                ->withErrors(['error' => $exception->getMessage()]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => app()->environment() == 'local' ? $exception->getMessage() : 'server error',]);
        }

        DB::commit();

        return redirect()->route('site.home');
    }


    public function show(string $id)
    {
        $categories = Category::query()->orderBy('order')->get();

        $post = Post::query()
            ->with(['mainImage', 'comments.owner'])
            ->active()
            ->findOrFail($id);

        $relatedPosts = Post::query()
            ->with('mainImage')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(3)
            ->active()
            ->get();

        return view('posts.details', compact('post', 'categories', 'relatedPosts'));
    }


}
