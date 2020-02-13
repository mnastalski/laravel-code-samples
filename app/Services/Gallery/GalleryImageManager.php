<?php

namespace App\Services\Gallery;

use App\Services\FileUpload\PublicDiskManager;

class GalleryImageManager extends PublicDiskManager
{
    /**
     * @var string
     */
    protected $path = 'galleries';

    /**
     * @var string
     */
    protected $name = 'image';
}
