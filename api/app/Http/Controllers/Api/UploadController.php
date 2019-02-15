<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Upload as UploadResource;
use App\Upload;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->user()->uploads();

        if ($request->filled('keyword')) {
            $query->searchBy($request->get('keyword'));
        }

        if ($request->filled('sortBy')) {
            $direction = $request->get('descending') === 'true' ? 'desc' : 'asc';
            $query->orderBy($request->get('sortBy'), $direction);
        }
        $query->orderBy('id', 'desc');

        if ($request->filled('page')) {
            return UploadResource::collection($query->paginate(10));
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => '',
            'file' => 'required|file',
        ]);

        $upload = new Upload($request->all());
        $upload->user_id = $request->user()->id;
        $upload->filetype = $request->file('file')->getMimeType();
        $filepath = $request->file('file')->store('public/uploads');
        $upload->filename = str_replace('public/uploads/', '', $filepath);

        // thumbnail
        if ($upload->isImage()) {
            Image::make($upload->path)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path("app/public/uploads/thumb/{$upload->filename}"));
        }

        $upload->save();

        return new UploadResource($upload);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $upload)
    {
        return new UploadResource($upload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        $request->validate([
            'name' => '',
            'file' => 'file',
        ]);

        $upload->fill($request->all());

        if ($request->hasFile('file')) {
            unlink($upload->path);
            if ($upload->isImage()) {
                unlink($upload->thumb_path);
            }

            $upload->filetype = $request->file('file')->getMimeType();
            $filepath = $request->file('file')->store('public/uploads');
            $upload->filename = str_replace('public/uploads/', '', $filepath);

            // thumbnail
            if ($upload->isImage()) {
                Image::make($upload->path)
                    ->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(storage_path("app/public/uploads/thumb/{$upload->filename}"));
            }
        }

        $upload->save();

        return new UploadResource($upload);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        unlink($upload->path);
        if ($upload->isImage()) {
            unlink($upload->thumb_path);
        }
        $upload->delete();

        return new UploadResource($upload);
    }
}
