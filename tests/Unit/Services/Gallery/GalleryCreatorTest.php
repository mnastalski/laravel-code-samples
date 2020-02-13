<?php

namespace Tests\Unit\Services\Gallery;

use App\Models\Gallery;
use App\Services\Gallery\GalleryCreator;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GalleryCreatorTest extends TestCase
{
    /**
     * @var \App\Services\Gallery\GalleryCreator
     */
    private $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creator = app(GalleryCreator::class);
    }

    public function testAppend(): void
    {
        $maxOrdinal = Gallery::max('ordinal');

        $data = [
            'name' => 'foo',
            'is_active' => true,
        ];

        $image = UploadedFile::fake()->image('1.jpg');

        $gallery = $this->creator->create(
            array_merge($data, ['image' => $image])
        );

        $this->assertInstanceOf(Gallery::class, $gallery);
        $this->assertEquals($data, $gallery->only(array_keys($data)));
        $this->assertTrue(Uuid::isValid($gallery->uuid));
        $this->assertEquals('foo', $gallery->slug);
        $this->assertEquals($maxOrdinal + 1, $gallery->ordinal);
    }
}
