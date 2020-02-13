<?php

namespace App\Services\GalleryImage;

use App\Models\Gallery;
use App\Services\FileUpload\PublicDiskManager;

class GalleryImageImageManager extends PublicDiskManager
{
    /**
     * @var string
     */
    protected $path = 'galleries';

    /**
     * @param \App\Models\Gallery $gallery
     * @param array $images
     * @return string[]
     * @throws \Exception
     */
    public function storeImages(Gallery $gallery, array $images): array
    {
        $path = "{$this->path}/{$gallery->uuid}/images";

        $paths = [];

        foreach ($images as $key => $file) {
            $paths[$key] = $this->storeFile($file, $path, $this->name());
        }

        return $paths;
    }
}
