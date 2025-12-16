<?php

namespace App\Http\Controllers;

use App\Http\Requests\MultipleMediaRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MultipleMediaResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MediaController extends Controller
{

    protected $imageExtensions  = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
    public function upload(StoreMediaRequest $request)
    {
        //dd('log');
        // 1. Get file from request
        $file = $request->file('file');
        //dd('log');
        // 2. Create unique file name
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // 3. Create folder path
        $directory = 'uploads/media';

        // 4. Save file in storage/public/uploads/media
        $path = $file->storeAs($directory, $fileName, 'public');

        // 5. Save record in database
        $media = Media::create([
            'display_name' => $file->getClientOriginalName(),
            'name'         => $fileName,
            'path'         => 'storage/' . $path,   // full usable URL
            'type'         => $file->getClientOriginalExtension()
        ]);

        // 6. Return response
        return response()->json([
            'message' => 'File uploaded successfully',
            'data' => $media
        ], 200);
    }


    public function uploadMultiple(MultipleMediaRequest $request)
    {
        //dd('log');
        if (!$request->hasFile('files')) {
            throw new HttpException(422, 'Files not found');
        }
        // Log::info($request->file('files'));
        $files = $request->file('files');
        $results = [];


        foreach ($files as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            try {
                $record = $this->verifyAndStore($file, $extension);
                $results[] = $record;
            } catch (\Exception $e) {
                throw new HttpException(422, $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Multiple File uploaded successfully',
            'data' => MultipleMediaResource::collection($record)


            // 'data'=> new MultipleMediaResource($results)
        ],);
    }

    // private function verifyAndStore($file, $extension)
    // {
    //  if (in_array($extension, $this->imageExtensions)) {
    //  return $this->storeImage($file, $extension);
    //  } elseif (in_array($extension, $this->documentExtensions)) {
    //   return $this->storeDocument($file, $extension);
    // } elseif (in_array($extension, $this->audioExtensions)) {
    //    return $this->storeAudio($file, $extension);
    // } else {
    //   return ['error' => 'Unknown file extension'];
    // }
    //  }

    private function verifyAndStore($file, $extension)
    {
        // 1. Create unique file name
        $fileName = time() . '_' . uniqid() . '.' . $extension;

        // 2. Folder
        $directory = 'uploads/media';

        // 3. Store file
        $path = $file->storeAs($directory, $fileName, 'public');

        // 4. Save to database
        return Media::create([
            'display_name' => $file->getClientOriginalName(),
            'name'         => $fileName,
            'path'         => 'storage/' . $path,
            'type'         => $extension,
        ]);
    }
}
