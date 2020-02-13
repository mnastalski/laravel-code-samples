<?php

namespace App\Services\Gallery;

use App\Models\Gallery;
use App\Repositories\Contracts\GalleryRepositoryContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GalleryUpdater
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
     * @param Gallery $gallery
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function update(Gallery $gallery, array $data): void
    {
        if ($name = Arr::get($data, 'name')) {
            $data['slug'] = Str::slug($name);
        }

        $this->galleryImageManager->delete(
            $gallery->only(Gallery::IMAGES)
        );

        $filePaths = $this->galleryImageManager->storeMultiple(
            Arr::only($data, Gallery::IMAGES)
        );

        $this->galleryRepository->update(
            $gallery, array_merge($data, $filePaths)
        );
    }
}
