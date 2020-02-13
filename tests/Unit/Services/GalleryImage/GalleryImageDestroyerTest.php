<?php

namespace Tests\Unit\Services\GalleryImage;

use App\Models\GalleryImage;
use App\Services\GalleryImage\GalleryImageDestroyer;
use Tests\TestCase;

class GalleryImageDestroyerTest extends TestCase
{
    /**
     * @var \App\Services\GalleryImage\GalleryImageDestroyer
     */
    private $updater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updater = app(GalleryImageDestroyer::class);
    }

    public function testDestroy(): void
    {
        /**
         * @var \App\Models\GalleryImage $image
         */
        $image = factory(GalleryImage::class)->create();

        $this->updater->destroy($image);

        $this->assertFalse($image->exists);
    }
}
