<?php

namespace App\Customs;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class UploadBuilder
{
    protected $file;
    protected $filename;
    protected $filePath;
    protected $fileResizePath;
    protected $basePath;
    protected $driver;

    public function __construct()
    {
        // $this->driver = app()->environment('local') ? 'public' : 's3';
        $this->driver = 'public';
    }

    /**
     * Initial upload file and set filename
     * @param UploadedFileObject $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        $this->basePath = public_path();
        $this->setFileName();
        return $this;
    }

    /**
     * Rename file for store
     * @return $this
     */
    public function setFileName()
    {
        //get filename with extension
        $fileNameWithExtension = $this->file->getClientOriginalName();

        //get filename without extension
        $filename = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);

        //get file extension
        $extension = $this->file->getClientOriginalExtension();

        //filename to store
        $this->filename = md5(time()) . '.' . $extension;
    }

    /**
     * set given custom file directory
     * @param boolean|string $dir
     * @return $this
     */
    public function setDir($dir = '')
    {
        $dir = ($dir) ? $dir . '/' : '';

        $folderPath = $dir;
        $this->createPath($this->basePath . $folderPath);
        $this->filePath = $folderPath;

        return $this;
    }

    /**
     * Store file into given directory
     * @return `storage full file path`
     */
    public function save()
    {
        $this->file->move($this->basePath . $this->filePath, $this->filename);

        return $this->fullFilePath();
    }

    /**
     * Store file into given path & Resize image
     * @param  string $w [width]
     * @param  string $h [height]
     * @return `storage full file path`
     */
    public function resize($w = 80, $h = 80)
    {
        //Resize image here
        $img = Image::make($this->file->getPathName())->resize($w, $h, function ($constraint) {
            $constraint->aspectRatio();
        })->save($this->basePath . $this->fullFilePath());

        return $this->fullFilePath();
    }

    /**
     * Store file into given path & Fit image
     * @param  string $w [width]
     * @param  string $h [height]
     * @return `storage full file path`
     */
    public function fit($w = 100, $h = null)
    {
        //fit image here
        $img = Image::make($this->file->getPathName());
        // crop the best fitting 1:1 ratio (100x100) and resize to 100x100 pixel
        if (is_null($h)) {
            $img->fit($w);
        }

        // add callback functionality to retain maximal original image size
        if ($w && $h) {
            $img->fit($w, $h, function ($constraint) {
                $constraint->upsize();
            });
        }
        $img->save($this->basePath . $this->fullFilePath());

        return $this->fullFilePath();
    }

    /**
     * Get full file path with file name
     * @return string
     */
    public function fullFilePath()
    {
        return $this->filePath . $this->filename;
    }

    /**
     * store file
     * @param  UploadedFileObject $file
     * @param  boolean|string $dir
     * @return $this
     */
    public function uploadFile($file, $dir = false)
    {
        return $this->setFile($file)
                    ->setDir($dir);
    }

    /**
     * Generate custom file name
     *
     * @param UploadedFile $uploadedFile
     * @return string
     */
    public function generateFilename(UploadedFile $uploadedFile)
    {
        //get filename without extension
        // $filename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        return rand(1000, 9999) . md5(time()) . '.' . strtolower($uploadedFile->getClientOriginalExtension());
    }

    public function storeFile(UploadedFile $uploadedFile, $directory = 'draft', $driver = null)
    {
        $directory = $directory;

        return Storage::disk($driver ?? $this->driver)->putFileAs(
            $directory,
            $uploadedFile,
            $this->generateFilename($uploadedFile)
        );
    }

    public function storeFileByRealName(UploadedFile $uploadedFile, $directory = 'draft', $driver = null)
    {
        $directory = $directory;
        Storage::disk($driver ?? $this->driver)->putFileAs(
            $directory,
            $uploadedFile,
            $uploadedFile->getClientOriginalName()
        );
        return $uploadedFile->getClientOriginalName();
    }

    /**
     * recursively create a long directory path
     * @param string $files
     * @return mixed
     */
    public function createPath($path)
    {
        if (is_dir($path)) {
            return true;
        }
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);
        $return = $this->createPath($prev_path);

        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }

    /**
     * Delete unnecessary files
     * @param  Array $files
     * @return void
     */
    public function deleteFiles($files, $fromLocal = false)
    {
        if (!empty($files)) {
            $method = $fromLocal ? 'deleteFileFromLocalDisk' : 'deleteFile';
            foreach ($files as $file) {
                $this->{$method}($file);
            }
        }
    }

    /**
     * Delete file from base directory
     * @param  string $path
     * @return boolean
     */
    public function deleteFile($path)
    {
        if ($this->driver == 's3') {
            if ($this->checkS3FileExists($path)) {
                Storage::disk('s3')->delete($path);
                return true;
            }
            return false;
        }
        return $this->deleteFileFromLocalDisk($path);
    }

    /**
     * Delete file from local disk
     *
     * @param string $path
     * @return boolean
     */
    public function deleteFileFromLocalDisk($path)
    {
        if ($this->checkFileExists($path, 'public')) {
            // @TODO why File not working, have to check
            // \File::delete($path);
            // disk = public, because local disk
            Storage::disk('public')->delete($path);
            return true;
        }
        return false;
    }

    /**
     * Delete file from Media disk
     *
     * @param string $path
     * @return boolean
     */
    public function deleteFileFromMediaDisk($path)
    {
        if ($this->checkFileExists($path, 'media')) {
            Storage::disk('media')->delete($path);
            return true;
        }
        return false;
    }

    /**
     * check s3 has file exists
     *
     * @param $path
     * @return boolean
     */
    public function checkS3FileExists($path)
    {
        return Storage::disk('s3')->exists($path);
    }

    /**
     * check file is exists
     *
     * @param string $path
     * @return boolean
     */
    public function checkFileExists($path, $driver = null)
    {
        return Storage::disk($driver ?? $this->driver)->exists($path);
    }

    /**
     * file download based on given path
     *
     * @param $path
     * @return boolean
     */
    public function download($path)
    {
        if ($this->driver == 's3') {
            if ($this->checkS3FileExists($path)) {
                return Storage::disk('s3')->download($path);
            }
            return false;
        }
        if ($this->checkFileExists($path)) {
            return Storage::disk($this->driver)->download($path);
            // return response()->download($path);
        }
        return false;
    }

    /**
     * Move file from local disk to s3 disk
     *
     * @param string $existingPath
     * @param string $newPath
     * @return mix
     */
    public function moveFileLocalDiskToS3($existingPath, $newPath)
    {
        if ($this->checkFileExists($existingPath, 'public')) {
            $moveTo = $newPath . '/' . basename($existingPath);
            $fileContent = Storage::disk('public')->get($existingPath);
            Storage::disk($this->driver)->put($moveTo, $fileContent);
            return $moveTo;
        }

        return false;
    }
}
