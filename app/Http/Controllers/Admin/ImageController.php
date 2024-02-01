<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\ImageUpdateRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Image::query()->where('is_hidden', \request('hidden-images') ?? 0)->paginate(32);
        return view('admin.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImageRequest $request)
    {
        $imageData = [];
        DB::beginTransaction();
        try {
            if ($imageDetail = saveOriginalImage($request->file('image'))) {
                $imageData = [
                    'is_hidden' => !!$request->input('is_hidden', false),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    ...$imageDetail
                ];
                $image = Image::create($imageData);
                saveImageMediumAndSmallVersions($image->name);
                DB::commit();
                return redirect()->route('admin.images.index')->setStatusCode(200)->with(['success' => 'Şəkil əlavə edildi']);
            } else {
                throw new \Exception('Şəkil yadda saxlanılmadı');
            }
        } catch (\Exception $e) {
            deleteImageAllVersions($imageData['name']);
            Log::error($e->getMessage()."----".$e->getLine()."----".$e->getFile().'----'.$e->getTraceAsString());
            DB::rollBack();
            return redirect()->back()->withInput()->setStatusCode(500)->with(['error' => $e->getMessage()]);
        }


    }

    public function edit(string $id)
    {
        $image = Image::query()->findOrFail($id);
        return view('admin.images.edit', compact('image'));
    }

    public function update(ImageUpdateRequest $request, string $id)
    {
        $imageData = null;
        $image = Image::query()->findOrFail($id);
        DB::beginTransaction();
        try {
            if ($request->has('image')) {
                if (!$imageDetail = saveOriginalImage($request->file('image'))) {
                    throw new \Exception('Şəkil əlavə edilə bilmədi');
                }
                $imageData = $imageDetail;
                saveImageMediumAndSmallVersions($imageDetail['name']);
                deleteImageAllVersions($image->name);
            }
            $imageData['is_hidden'] = !!$request->input('is_hidden', false);
            $imageData['title'] = $request->input('title');
            $imageData['description'] = $request->input('description');
            $image->update($imageData);
            DB::commit();
            return redirect()->route('admin.images.index')->setStatusCode(200)->with(['success' => 'Dəyişiklikər yadda saxlanıldı']);
        } catch (\Exception $e) {
            if (!is_null($imageData) && File::exists($imageData['full_path'])) {
                File::delete($imageData['full_path']);
            }
            DB::rollBack();
            return redirect()->back()->withInput()->setStatusCode(500)->with(['error' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = Image::query()->findOrFail($id);
        try {
            deleteImageAllVersions($image->name);
            $image->delete();
            return redirect()->back()->with(['success' => 'Şəkil silindi']);
        } catch (\Exception) {

            return redirect()->back()->with(['error' => 'Şəkil silinə bilmədi']);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function hide(string $id)
    {
        $image = Image::query()->findOrFail($id);
        $image->update(['is_hidden' => true]);
        return redirect()->back()->with(['success' => 'Şəkil gizlədildi']);
    }

    public function unHide(string $id)
    {
        $image = Image::query()->findOrFail($id);
        $image->update(['is_hidden' => false]);
        return redirect()->back()->with(['success' => 'Şəkil gizlindən çıxarıldı']);
    }

    public function displayOriginalImage($imageKey)
    {
        $key = env('IMAGE_KEY');
        $id = openssl_decrypt(base64_decode($imageKey), 'AES-128-ECB', $key);

        $image = Image::query()->find($id);

        $path = storage_path('app/' . $image->full_path);


        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file);
        $response->header("Content-Type", $type);

        return $response;
    }


}
