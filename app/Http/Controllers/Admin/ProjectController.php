<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Image;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{


    public function index()
    {
        $projects = Project::query()->with(['coverImage.image'])->where('is_hidden', \request('hidden-projects') ?? 0)->paginate(20);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }


    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();
        $isCoverPhoto = true;
        $imageDetails = [];
        try {
            $isCommitable = [];
            DB::beginTransaction();
            $isCommitable[] = !!$project = Project::query()->create(
                [
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'is_hidden' => !!$request->input('is_hidden', false),
                ]
            );
            $files = $request->file('images');
            foreach ($files as $file) {
                if ($imageDetails[] = saveOriginalImage($file)) {
                    $imageData = [
                        'is_hidden' => !!$request->input('is_hidden', false),
                        'title' => $validated['title'],
                        'description' => $validated['description'],
                        ...$imageDetails[count($imageDetails)-1]
                    ];
                    $isCommitable[] = !!$image = Image::query()->create($imageData);
                    $isCommitable[] = DB::table('project_images')->insert([
                        'project_id' => $project->id,
                        'image_id' => $image->id,
                        'is_cover_photo' => $isCoverPhoto
                    ]);
                    $isCoverPhoto = false;
                    if (in_array(false, $isCommitable)) {
                        throw new \Exception('Proyekt yadda saxlanılmadı');
                    }
                    saveImageMediumAndSmallVersions($imageDetails[count($imageDetails)-1]['name']);

                } else {
                    throw new \Exception('Proyekt yadda saxlanılmadı');
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if (count($imageDetails)) {
                foreach ($imageDetails as $imageDetail) {
                    deleteImageAllVersions($imageDetail['name']);
                }
            }
            return redirect()->back()->withInput()->setStatusCode(500)->with(['error' => $e->getMessage()]);
        }
        return redirect()->route('admin.projects.index', ['hidden-projects' => (int) !!$request->input('is_hidden', false)])->setStatusCode(200)->with(['success' => 'Proyekt əlavə edildi']);

    }
    public function unHide($id)
    {
        $project = Project::query()->with(['images'])->findOrFail($id);
        $project->update(['is_hidden' => false]);
        foreach ($project->images as $image) {
            $image->update(['is_hidden' => false]);
        }
        return redirect()->back()->with(['success' => 'Proyekt və Şəkillər uğurla aktiv edildi']);
    }

    public function hide($id)
    {
        $project = Project::query()->with(['images'])->findOrFail($id);
        $project->update(['is_hidden' => true]);
        foreach ($project->images as $image) {
            $image->update(['is_hidden' => true]);
        }
        return redirect()->back()->with(['success' => 'Proyekt və Şəkillər uğurla gizlədildi']);
    }
}
