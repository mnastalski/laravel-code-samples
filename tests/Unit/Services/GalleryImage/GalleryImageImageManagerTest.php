<?php

namespace Tests\Unit\Services\GalleryImage;

use App\Models\Gallery;
use App\Services\GalleryImage\GalleryImageImageManager;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class GalleryImageImageManagerTest extends TestCase
{
    /**
     * @var \App\Services\GalleryImage\GalleryImageImageManager
     */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = app(GalleryImageImageManager::class);
    }

    public function testStoreImages()
    {
        Storage::fake();

        /** @var \App\Models\GalleryImage $gallery */
        $gallery = factory(Gallery::class)->create();

        $paths = $this->manager->storeImages($gallery, [
            'image' => $file = UploadedFile::fake()->image('1.jpg'),
        ]);

        foreach ($paths as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }
}
