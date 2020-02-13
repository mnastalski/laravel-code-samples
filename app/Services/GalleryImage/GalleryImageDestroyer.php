<?php

namespace App\Services\GalleryImage;

use App\Models\GalleryImage;
use App\Repositories\Contracts\GalleryImageRepositoryContract;

class GalleryImageDestroyer
{
    /**
     * @var \App\Repositories\Contracts\GalleryImageRepositoryContract
     */
    private $imageRepository;

    /**
     * @var \App\Services\GalleryImage\GalleryImageImageManager
     */
    private $imageManager;

    /**
     * @param \App\Repositories\Contracts\GalleryImageRepositoryContract $imageRepository
     * @param \App\Services\GalleryImage\GalleryImageImageManager $imageManager
     */
    public function __construct(
        GalleryImageRepositoryContract $imageRepository,
        GalleryImageImageManager $imageManager
    ) {
        $this->imageRepository = $imageRepository;
        $this->imageManager = $imageManager;
    }

    /**
     * @param \App\Models\GalleryImage $image
     * @return void
     * @throws \Exception
     */
    public function destroy(GalleryImage $image): void
    {
        $this->imageManager->delete($image->image);

        $this->imageRepository->destroy($image);
    }
}
