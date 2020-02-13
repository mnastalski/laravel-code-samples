<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gallery\GalleryCreateRequest;
use App\Http\Requests\Gallery\GalleryUpdateRequest;
use App\Http\Resources\Gallery\Gallery as GalleryResource;
use App\Http\Resources\GalleryImage\GalleryImageCollection as GalleryImageCollectionResource;
use App\Models\Gallery;
use App\Services\Gallery\GalleryCreator;
use App\Services\Gallery\GalleryDestroyer;
use App\Services\Gallery\GalleryUpdater;
use App\Services\GalleryImage\GalleryImageFetcher;
use Illuminate\Http\Response;

class GalleryController
{
    /**
     * @var \App\Services\Gallery\GalleryCreator
     */
    private $galleryCreator;

    /**
     * @var \App\Services\Gallery\GalleryUpdater
     */
    private $galleryUpdater;

    /**
     * @var \App\Services\Gallery\GalleryDestroyer
     */
    private $galleryDestroyer;

    /**
     * @var \App\Services\GalleryImage\GalleryImageFetcher
     */
    private $imageFetcher;

    /**
     * @param \App\Services\Gallery\GalleryCreator $galleryCreator
     * @param \App\Services\Gallery\GalleryUpdater $galleryUpdater
     * @param \App\Services\Gallery\GalleryDestroyer $galleryDestroyer
     * @param \App\Services\GalleryImage\GalleryImageFetcher $imageFetcher
     */
    public function __construct(
        GalleryCreator $galleryCreator,
        GalleryUpdater $galleryUpdater,
        GalleryDestroyer $galleryDestroyer,
        GalleryImageFetcher $imageFetcher
    ) {
        $this->galleryCreator = $galleryCreator;
        $this->galleryUpdater = $galleryUpdater;
        $this->galleryDestroyer = $galleryDestroyer;
        $this->imageFetcher = $imageFetcher;
    }

    /**
     * @param \App\Http\Requests\Gallery\GalleryCreateRequest $request
     * @return GalleryResource
     * @throws \Exception
     */
    public function store(GalleryCreateRequest $request): GalleryResource
    {
        $gallery = $this->galleryCreator->create(
            $request->validated()
        );

        return new GalleryResource($gallery);
    }

    /**
     * @param \App\Http\Requests\Gallery\GalleryUpdateRequest $request
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(GalleryUpdateRequest $request, Gallery $gallery): Response
    {
        $this->galleryUpdater->update(
            $gallery,
            $request->validated()
        );

        return response()->noContent();
    }

    /**
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Gallery $gallery): Response
    {
        $this->galleryDestroyer->destroy($gallery);

        return response()->noContent();
    }

    /**
     * @param \App\Models\Gallery $gallery
     * @return \App\Http\Resources\GalleryImage\GalleryImageCollection
     */
    public function images(Gallery $gallery): GalleryImageCollectionResource
    {
        return new GalleryImageCollectionResource(
            $this->imageFetcher->galleryImages($gallery)
        );
    }
}
