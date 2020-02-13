<?php

namespace Tests\Unit\Services\Gallery;

use App\Models\Gallery;
use App\Services\Gallery\GalleryUpdater;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Storage;
use Tests\TestCase;

class GalleryUpdaterTest extends TestCase
{
    /**
     * @var \App\Services\Gallery\GalleryUpdater
     */
    private $updater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updater = app(GalleryUpdater::class);
    }

    public function testUpdateWithoutFiles(): void
    {
        $gallery = factory(Gallery::class)->create();

        $originalImagePath = $gallery->image;

        $data = [
            'name' => 'New name',
            'is_active' => true,
        ];

        $this->updater->update($gallery, $data);

        $this->assertEquals($data, $gallery->only(array_keys($data)));
        $this->assertEquals('new-name', $gallery->slug);
        $this->assertEquals($originalImagePath, $gallery->image);
    }

    public function testUpdateWithFiles(): void
    {
        Storage::fake();

        $gallery = factory(Gallery::class)->create();

        $originalImagePath = $gallery->image;

        $this->updater->update($gallery, [
            'image' => UploadedFile::fake()->image('1.jpg'),
            'is_active' => true,
        ]);

        $this->assertTrue(Uuid::isValid($gallery->uuid));
        $this->assertNotEquals($originalImagePath, $gallery->image);
    }
}
