<?php

namespace App\Services\Gallery;

use App\Models\Gallery;
use App\Repositories\Contracts\GalleryRepositoryContract;

class GalleryDestroyer
{
    /**
     * @var \App\Repositories\Contracts\GalleryRepositoryContract
     */
    private $galleryRepository;

    /**
     * @var \App\Services\Gallery\GalleryImageManager
     */
    private $imageManager;

    /**
     * @param \App\Repositories\Contracts\GalleryRepositoryContract $galleryRepository
     * @param \App\Services\Gallery\GalleryImageManager $imageManager
     */
    public function __construct(
        GalleryRepositoryContract $galleryRepository,
        GalleryImageManager $imageManager
    ) {
        $this->galleryRepository = $galleryRepository;
        $this->imageManager = $imageManager;
    }

    /**
     * @param \App\Models\Gallery $gallery
     * @return void
     * @throws \Exception
     */
    public function destroy(Gallery $gallery): void
    {
        $this->imageManager->deleteFolder($gallery->uuid);

        $this->galleryRepository->destroy($gallery);
    }
}
