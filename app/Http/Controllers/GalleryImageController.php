<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryImage\GalleryImageCreateRequest;
use App\Http\Requests\GalleryImage\GalleryImageUpdateRequest;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Services\GalleryImage\GalleryImageCreator;
use App\Services\GalleryImage\GalleryImageDestroyer;
use App\Services\GalleryImage\GalleryImageException;
use App\Services\GalleryImage\GalleryImageUpdater;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class GalleryImageController
{
    /**
     * @var \App\Services\GalleryImage\GalleryImageCreator
     */
    private $imageCreator;

    /**
     * @var \App\Services\GalleryImage\GalleryImageUpdater
     */
    private $imageUpdater;

    /**
     * @var \App\Services\GalleryImage\GalleryImageDestroyer
     */
    private $imageDestroyer;

    /**
     * @param \App\Services\GalleryImage\GalleryImageCreator $imageCreator
     * @param \App\Services\GalleryImage\GalleryImageUpdater $imageUpdater
     * @param \App\Services\GalleryImage\GalleryImageDestroyer $imageDestroyer
     */
    public function __construct(
        GalleryImageCreator $imageCreator,
        GalleryImageUpdater $imageUpdater,
        GalleryImageDestroyer $imageDestroyer
    ) {
        $this->imageCreator = $imageCreator;
        $this->imageUpdater = $imageUpdater;
        $this->imageDestroyer = $imageDestroyer;
    }

    /**
     * @param \App\Http\Requests\GalleryImage\GalleryImageCreateRequest $request
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(GalleryImageCreateRequest $request, Gallery $gallery): JsonResponse
    {
        try {
            $this->imageCreator->appendMultiple($gallery, $request->images);
        } catch (GalleryImageException $e) {
            throw ValidationException::withMessages([
                'images' => $e->getMessage(),
            ]);
        }

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param \App\Http\Requests\GalleryImage\GalleryImageUpdateRequest $request
     * @param \App\Models\GalleryImage $image
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryImageUpdateRequest $request, GalleryImage $image): Response
    {
        $this->imageUpdater->update(
            $image,
            $request->validated()
        );

        return response()->noContent();
    }

    /**
     * @param \App\Models\GalleryImage $image
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(GalleryImage $image): Response
    {
        $this->imageDestroyer->destroy($image);

        return response()->noContent();
    }
}
