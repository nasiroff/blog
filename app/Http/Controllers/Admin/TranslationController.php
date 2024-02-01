<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TranslationUpdateRequest;
use App\Models\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $translations = Translate::all();
        return view('admin.translations.index', compact('translations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $translation = Translate::query()->findOrFail($id);
        return view('admin.translations.edit', compact('translation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TranslationUpdateRequest $request, string $id)
    {
        $translate = Translate::query()->findOrFail($id);
        $translate->update(['text' => $request->input('translations')]);
        Artisan::call('optimize');
        return redirect()->route('admin.translations.index');
    }
}
