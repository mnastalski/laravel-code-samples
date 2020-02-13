<?php

namespace App\Services\GalleryImage;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Repositories\Contracts\GalleryImageRepositoryContract;
use Illuminate\Support\Collection;

class GalleryImageFetcher
{
    /**
     * @var \App\Repositories\Contracts\GalleryImageRepositoryContract
     */
    private $imageRepository;

    /**
     * @param \App\Repositories\Contracts\GalleryImageRepositoryContract $imageRepository
     */
    public function __construct(
        GalleryImageRepositoryContract $imageRepository
    ) {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param string $uuid
     * @return \App\Models\GalleryImage|null
     */
    public function byUuid(string $uuid): ?GalleryImage
    {
        return $this->imageRepository->findByUuid($uuid);
    }

    /**
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Support\Collection|\App\Models\GalleryImage[]
     */
    public function galleryImages(Gallery $gallery): Collection
    {
        return $this->imageRepository->galleryImages($gallery);
    }
}
