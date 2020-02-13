<?php

namespace Tests\Unit\Services\GalleryImage;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Services\GalleryImage\GalleryImageCreator;
use App\Services\GalleryImage\GalleryImageException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GalleryImageCreatorTest extends TestCase
{
    /**
     * @var \App\Services\GalleryImage\GalleryImageCreator
     */
    private $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creator = app(GalleryImageCreator::class);
    }

    public function testAppend(): void
    {
        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 5,
        ]);

        $maxOrdinal = GalleryImage::max('ordinal');

        $data = [
            'name' => 'foo',
        ];

        $image = $this->creator->append($gallery, array_merge($data, [
            'image' => UploadedFile::fake()->image('1.jpg'),
        ]));

        $this->assertInstanceOf(GalleryImage::class, $image);
        $this->assertEquals($data, $image->only(array_keys($data)));
        $this->assertTrue(Uuid::isValid($image->uuid));
        $this->assertEquals('foo', $image->slug);
        $this->assertEquals($maxOrdinal + 1, $image->ordinal);
    }

    public function testAppendAboveMaximumThrowsException(): void
    {
        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 2,
        ]);

        factory(GalleryImage::class, 2)->create([
            'gallery_uuid' => $gallery->uuid,
        ]);

        $this->expectException(GalleryImageException::class);
        $this->expectExceptionMessage(__('gallery_image.images_max_error'));

        $this->creator->append($gallery, [
            'name' => 'foo',
            'image' => UploadedFile::fake()->image('1.jpg'),
        ]);
    }

    public function testAppendMultiple(): void
    {
        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 5,
        ]);

        $data = [
            [
                'name' => 'foo',
                'image' => UploadedFile::fake()->image('1.jpg'),
            ],

            [
                'name' => 'bar',
                'image' => UploadedFile::fake()->image('2.jpg'),
            ],
        ];

        $images = $this->creator->appendMultiple($gallery, $data);

        $this->assertInstanceOf(Collection::class, $images);
        $this->assertCount(2, $images);
    }

    public function testAppendMultipleAboveMaximumThrowsExceptionAfterOneAppend(): void
    {
        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 1,
        ]);

        $this->assertEquals(0, $gallery->images()->count());

        $data = [
            [
                'name' => 'foo',
                'image' => UploadedFile::fake()->image('1.jpg'),
            ],

            [
                'name' => 'bar',
                'image' => UploadedFile::fake()->image('2.jpg'),
            ],
        ];

        $this->expectException(GalleryImageException::class);
        $this->expectExceptionMessage(__('gallery_image.images_max_error'));

        try {
            $this->creator->appendMultiple($gallery, $data);
        } finally {
            $this->assertEquals(1, $gallery->images()->count());
        }

    }
}
