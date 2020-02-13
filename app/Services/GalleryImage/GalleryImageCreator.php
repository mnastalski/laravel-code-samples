<?php

namespace App\Services\GalleryImage;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Repositories\Contracts\GalleryImageRepositoryContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GalleryImageCreator
{
    /**
     * @var GalleryImageRepositoryContract
     */
    private $imageRepository;

    /**
     * @var GalleryImageImageManager
     */
    private $imageManager;

    /**
     * @param GalleryImageRepositoryContract $imageRepository
     * @param GalleryImageImageManager $imageManager
     */
    public function __construct(
        GalleryImageRepositoryContract $imageRepository,
        GalleryImageImageManager $imageManager
    ) {
        $this->imageRepository = $imageRepository;
        $this->imageManager = $imageManager;
    }

    /**
     * @param Gallery $gallery
     * @param array $data
     * @return GalleryImage
     * @throws \Exception
     */
    public function append(Gallery $gallery, array $data): GalleryImage
    {
        if ($this->imageRepository->galleryImagesCount($gallery) >= $gallery->images_max_value) {
            throw new GalleryImageException(__('gallery_image.images_max_error'));
        }

        $data['slug'] = Str::slug($data['name']);

        $filePaths = $this->imageManager->storeImages(
            $gallery, Arr::only($data, GalleryImage::IMAGES)
        );

        return $this->imageRepository->append(
            $gallery, array_merge($data, $filePaths)
        );
    }

    /**
     * @param Gallery $gallery
     * @param array $images
     * @return \Illuminate\Support\Collection|\App\Models\GalleryImage[]
     * @throws \Exception
     */
    public function appendMultiple(Gallery $gallery, array $images): Collection
    {
        $collection = new Collection();

        foreach ($images as $image) {
            $collection->push(
                $this->append($gallery, $image)
            );
        }

        return $collection;
    }
}
