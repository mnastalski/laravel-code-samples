<?php

namespace App\Services\GalleryImage;

use App\Models\GalleryImage;
use App\Repositories\Contracts\GalleryImageRepositoryContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GalleryImageUpdater
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
     * @param array $data
     * @return void
     */
    public function update(GalleryImage $image, array $data): void
    {
        if ($name = Arr::get($data, 'name')) {
            $data['slug'] = Str::slug($name);
        }

        $this->imageRepository->update(
            $image, $data
        );
    }
}
