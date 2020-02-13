<?php

namespace Tests\Unit\Services\GalleryImage;

use App\Models\GalleryImage;
use App\Services\GalleryImage\GalleryImageUpdater;
use Tests\TestCase;

class GalleryImageUpdaterTest extends TestCase
{
    /**
     * @var \App\Services\GalleryImage\GalleryImageUpdater
     */
    private $updater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updater = app(GalleryImageUpdater::class);
    }

    public function testUpdateWithoutFiles(): void
    {
        /**
         * @var \App\Models\GalleryImage $image
         */
        $image = factory(GalleryImage::class)->create();

        $originalImagePath = $image->image;

        $data = [
            'name' => 'New name',
        ];

        $this->updater->update($image, $data);

        $this->assertEquals($data, $image->only(array_keys($data)));
        $this->assertEquals('new-name', $image->slug);
        $this->assertEquals($originalImagePath, $image->image);
    }
}
