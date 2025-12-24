<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Enums\MediaTypeEnum;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\MultipleMediaRequest;
use App\Http\Resources\MultipleMediaResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MediaController extends Controller
{

    protected $imageExtensions  = ['png', 'jpg', 'jpeg', 'svg', 'webp'];

    public function upload(StoreMediaRequest $request)
    {
        if (!$request->hasFile('file')) {
            throw new HttpException(422, 'File not found');
        }

        $file = $request['file'];

        // Try to extract extension from content-type if filename is "blob"
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());


        $record = $this->verifyAndStore($file, $extension);

        if (isset($record['error'])) {
            throw new HttpException(422, $record['error']);
        }

        return $record;
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
                //  $results[] = $record;
            } catch (\Exception $e) {
                throw new HttpException(422, $e->getMessage());
            }
        }
        return response()->json([
            'message' => 'Multiple File uploaded successfully',


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

        if (in_array($extension, $this->imageExtensions)) {
            return $this->storeImage($file, $extension);
        } else {
            return ['error' => 'Unknown file extension'];
        }


        //     // 1. Create unique file name
        //     $fileName = time() . '_' . uniqid() . '.' . $extension;

        //     // 2. Folder
        //     $directory = 'uploads/media';

        //     // 3. Store file
        //     $path = $file->storeAs($directory, $fileName, 'public');

        //     // 4. Save to database
        //     return Media::create([
        //         'display_name' => $file->getClientOriginalName(),
        //         'name'         => $fileName,
        //         'path'         => 'storage/' . $path,
        //         'type'         => $extension,
        //     ]);
    }

    private function storeImage($file, $extension)
    {
        $originalNameWithExtension = $file->getClientOriginalName();
        $uniqueFileName = date('Y_m_d_H_i_s') . '_' . uniqid() . '.' . $extension;
        $directory = 'uploads/' . MediaTypeEnum::IMAGE . '/original';

        // Ensure the directory is created
        Storage::disk('public')->makeDirectory($directory);

        // Store the file in the specified directory
        Storage::disk('public')->putFileAs($directory, $file, $uniqueFileName);

        $this->storeImageSizes($file, $uniqueFileName);

        return $this->storeInDatabase($originalNameWithExtension, $uniqueFileName, MediaTypeEnum::IMAGE);
    }

    private function storeImageSizes($file, $fileName)
    {
        // $imageManager = new ImageManager(new Driver());
        // $uploadedImage = $imageManager->read('public/uploads/images/original/' . $fileName);
        $imageSizes = config('common.imagesSizes');
        foreach ($imageSizes as $imageSize) {
            if (!$imageSize['convertable']) continue;

            $folderName = $imageSize['name'];
            $directory = 'uploads/' . MediaTypeEnum::IMAGE . '/' . $folderName;

            Storage::disk('public')->makeDirectory($directory);
            Storage::disk('public')->putFileAs($directory, $file, $fileName);
            // $uploadedImage->resize(250,250);
            // $uploadedImage->save('public/uploads/images/' . $folderName);
            // $encodedImage = Image::make($file);
            // $encodedImage->save(public_path('public/uploads/images/' . $folderName . '/' . $fileName), $imageSize['quality']);
        }
    }

    private function storeInDatabase($displayName, $fileName, $type)

    {
        $fileData = [
            'display_name' => $displayName,
            'name' => $fileName,
            'type' => $type
        ];
        return ([

            'data'=> $fileData
        ]);
    }
}
