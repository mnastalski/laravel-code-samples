<?php

namespace Tests\Unit\Services\Gallery;

use App\Models\Gallery;
use App\Services\Gallery\GalleryDestroyer;
use Tests\TestCase;

class GalleryDestroyerTest extends TestCase
{
    /**
     * @var \App\Services\Gallery\GalleryDestroyer
     */
    private $destroyer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->destroyer = app(GalleryDestroyer::class);
    }

    public function testDestroy(): void
    {
        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create();

        $this->destroyer->destroy($gallery);

        $this->assertFalse($gallery->exists);
    }
}
