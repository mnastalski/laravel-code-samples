<?php

namespace App\Services\Gallery;

use App\Models\Gallery;
use App\Repositories\Contracts\GalleryRepositoryContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GalleryCreator
{
    /**
     * @var \App\Repositories\Contracts\GalleryRepositoryContract
     */
    private $galleryRepository;

    /**
     * @var \App\Services\Gallery\GalleryImageManager
     */
    private $galleryImageManager;

    /**
     * @param \App\Repositories\Contracts\GalleryRepositoryContract $galleryRepository
     * @param \App\Services\Gallery\GalleryImageManager $galleryImageManager
     */
    public function __construct(
        GalleryRepositoryContract $galleryRepository,
        GalleryImageManager $galleryImageManager
    ) {
        $this->galleryRepository = $galleryRepository;
        $this->galleryImageManager = $galleryImageManager;
    }

    /**
     * @param array $data
     * @return \App\Models\Gallery
     * @throws \Exception
     */
    public function create(array $data): Gallery
    {
        $data['slug'] = Str::slug($data['name']);

        $filePaths = $this->galleryImageManager->storeMultiple(
            Arr::only($data, Gallery::IMAGES)
        );

        return $this->galleryRepository->create(
            array_merge($data, $filePaths)
        );
    }
}
