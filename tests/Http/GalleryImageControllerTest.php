<?php

namespace Tests\Http;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GalleryImageControllerTest extends TestCase
{
    public function testStore(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();


        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 10,
        ]);

        $data = [
            'images' => [
                [
                    'name' => 'foo',
                    'image' => UploadedFile::fake()->image('1.jpg'),
                ],

                [
                    'name' => 'bar',
                    'image' => UploadedFile::fake()->image('2.jpg'),
                ],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('gallery-image.store', $gallery->uuid), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('gallery_images', [
            'name' => 'foo',
            'slug' => 'foo',
        ]);

        $this->assertDatabaseHas('gallery_images', [
            'name' => 'bar',
            'slug' => 'bar',
        ]);
    }

    public function testStoreAboveMaximumImagesReturnsValidationErrorResponse(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 1,
        ]);

        $data = [
            'images' => [
                [
                    'name' => 'foo',
                    'image' => UploadedFile::fake()->image('1.jpg'),
                ],

                [
                    'name' => 'bar',
                    'image' => UploadedFile::fake()->image('2.jpg'),
                ],
            ],
        ];

        $response = $this->actingAs($user)->postJson(route('gallery-image.store', $gallery->uuid), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors([
            'images' => __('gallery_image.images_max_error'),
        ]);

        $this->assertDatabaseHas('gallery_images', [
            'name' => 'foo',
            'slug' => 'foo',
        ]);

        $this->assertDatabaseMissing('gallery_images', [
            'name' => 'bar',
            'slug' => 'bar',
        ]);
    }

    public function testUpdate(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 10,
        ]);

        /** @var \App\Models\GalleryImage $image */
        $image = factory(GalleryImage::class)->create([
            'gallery_uuid' => $gallery->uuid,
            'name' => 'foo',
        ]);

        $this->assertDatabaseHas('gallery_images', [
            'name' => 'foo',
        ]);

        $data = [
            'name' => 'bar',
        ];

        $response = $this->actingAs($user)->patchJson(route('gallery-image.update', $image->uuid), $data);

        $response->assertNoContent();

        $this->assertDatabaseMissing('gallery_images', [
            'name' => 'foo',
        ]);

        $this->assertDatabaseHas('gallery_images', [
            'name' => 'bar',
            'slug' => 'bar',
        ]);
    }

    public function testDestroy(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'images_max_value' => 10,
        ]);

        /** @var \App\Models\GalleryImage $image */
        $image = factory(GalleryImage::class)->create([
            'gallery_uuid' => $gallery->uuid,
            'name' => 'foo',
        ]);

        $this->assertDatabaseHas('gallery_images', [
            'name' => 'foo',
        ]);

        $response = $this->actingAs($user)->deleteJson(route('gallery-image.destroy', $image->uuid));

        $response->assertNoContent();

        $this->assertDatabaseMissing('gallery_images', [
            'name' => 'foo',
        ]);
    }
}
