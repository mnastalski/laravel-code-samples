<?php

namespace App\Services\Gallery;

use App\Repositories\Contracts\GalleryRepositoryContract;
use Illuminate\Support\Collection;

class GalleryFetcher
{
    /**
     * @var \App\Repositories\Contracts\GalleryRepositoryContract
     */
    private $galleryRepository;

    /**
     * @param \App\Repositories\Contracts\galleryRepositoryContract $galleryRepository
     */
    public function __construct(
        GalleryRepositoryContract $galleryRepository
    ) {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @return \Illuminate\Support\Collection|\App\Models\Gallery[]
     */
    public function all(): Collection
    {
        return $this->galleryRepository->get();
    }
}
