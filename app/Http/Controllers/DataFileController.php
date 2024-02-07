<?php

namespace App\Http\Controllers;

use App\Models\DataFile;
use App\Http\Requests\StoreDataFileRequest;
use App\Http\Requests\UpdateDataFileRequest;
use Illuminate\Http\Request;

class DataFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $file = $request->search_file ?? NULL;
        $results = $request->results ?? 10;

        $dataFiles = DataFile::select();

        if (!is_null($file)) {
            $dataFiles = $dataFiles->where('title', "LIKE", "%$file%")
                ->orWhere('description', "LIKE", "%$file%");
        }

        $dataFiles = $dataFiles->paginate($results);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'dataFiles' => $dataFiles,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDataFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDataFileRequest $request)
    {
        $fileProperties = $request->file('file');
        $category = $request->category ?? 'general';
        if ($request->hasFile('file')) {
            $file = str_replace(' ', '-', $request->file('file')->getClientOriginalName());
            $name = uniqid() . $file;
            $request->file->move(public_path("storage/$category"), $name);
        }

        $properties = [
            'original_name' => $fileProperties->getClientOriginalName(),
            'mime_type' => $fileProperties->getClientMimeType(),
            'error_code' => $fileProperties->getError(),
            'temporary_path' => $fileProperties->getPathname(),
            'temporary_name' => $fileProperties->getFileName(),
        ];

        $dataFile = new DataFile();
        $dataFile->file = $name;
        $dataFile->title = $request->title;
        $dataFile->category = $category;
        $dataFile->description = $request->description;
        $dataFile->url_path = "storage/$category/";
        $dataFile->metadata = json_encode($properties);
        $dataFile->save();

        return response()->json(['message' => 'You have successfully uploaded "' . $dataFile, 'success' => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataFile  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function show(DataFile $dataFile)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataFile  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function edit(DataFile $dataFile)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDataFileRequest  $request
     * @param  \App\Models\DataFile  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDataFileRequest $request, DataFile $dataFile)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataFile  $dataFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataFile $dataFile)
    {
        abort(404);
    }
}
