<?php

namespace Tests\Http;

use App\Models\Gallery;
use App\Models\User;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    public function testStore(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        $data = [
            'name' => 'foo',
            'image' => UploadedFile::fake()->image('1.jpg'),
        ];

        $response = $this->actingAs($user)->postJson(route('gallery.store'), $data);

        $response->assertCreated();

        $this->assertDatabaseHas('galleries', [
            'name' => 'foo',
            'slug' => 'foo',
        ]);
    }

    public function testUpdate(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'name' => 'foo',
        ]);

        $data = [
            'name' => 'bar',
        ];

        $this->assertDatabaseHas('galleries', [
            'name' => 'foo',
        ]);

        $response = $this->actingAs($user)->patchJson(route('gallery.update', $gallery->uuid), $data);

        $response->assertNoContent();

        $this->assertDatabaseMissing('galleries', [
            'name' => 'foo',
        ]);

        $this->assertDatabaseHas('galleries', [
            'name' => 'bar',
            'slug' => 'bar',
        ]);
    }

    public function testUpdateAsNonManagerReturnsForbidden(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create();

        $response = $this->actingAs($user)->patchJson(route('gallery.update', $gallery->uuid));

        $response->assertForbidden();
    }

    public function testDestroy(): void
    {
        /** @var \App\Models\User $user */
        $user = factory(User::class)->create();

        /** @var \App\Models\Gallery $gallery */
        $gallery = factory(Gallery::class)->create([
            'name' => 'foo',
        ]);

        $this->assertDatabaseHas('galleries', [
            'name' => 'foo',
        ]);

        $response = $this->actingAs($user)->deleteJson(route('gallery.destroy', $gallery->uuid));

        $response->assertNoContent();

        $this->assertDatabaseMissing('galleries', [
            'name' => 'foo',
        ]);
    }
}
