<?php

namespace App\Repositories\Eloquent;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Repositories\Contracts\GalleryImageRepositoryContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class GalleryImageRepository extends AbstractRepository implements GalleryImageRepositoryContract
{
    /**
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\GalleryImage[]
     */
    public function galleryImages(Gallery $gallery): EloquentCollection
    {
        return $gallery->images()->get();
    }

    /**
     * @param \App\Models\Gallery $gallery
     * @return int
     */
    public function galleryImagesCount(Gallery $gallery): int
    {
        return $gallery->images()->count();
    }

    /**
     * @inheritdoc
     */
    public function append(Gallery $gallery, array $data): GalleryImage
    {
        return $gallery->images()->create($data);
    }
}
