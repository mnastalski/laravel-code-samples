<?php

namespace App\Repositories\Contracts;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @method \Illuminate\Database\Eloquent\Collection|\App\Models\GalleryImage[] get()
 * @method \App\Models\GalleryImage|null findByUuid(string $uuid)
 * @method \App\Models\GalleryImage create(array $data)
 * @method \App\Models\GalleryImage update(\App\Models\GalleryImage $image, array $data)
 * @method \App\Models\GalleryImage destroy(\App\Models\GalleryImage $image)
 * @method \App\Models\GalleryImage firstOrCreate(array $attributes, array $values)
 * @method \App\Models\GalleryImage updateOrCreate(array $attributes, array $values)
 */
interface GalleryImageRepositoryContract extends AbstractRepositoryContract
{
    /**
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\GalleryImage[]
     */
    public function galleryImages(Gallery $gallery): EloquentCollection;

    /**
     * @param \App\Models\Gallery $gallery
     * @return int
     */
    public function galleryImagesCount(Gallery $gallery): int;

    /**
     * @inheritdoc
     */
    public function append(Gallery $gallery, array $data): GalleryImage;
}
