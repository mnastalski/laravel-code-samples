<?php

namespace App\Repositories\Contracts;

/**
 * @method \Illuminate\Database\Eloquent\Collection|\App\Models\Gallery[] get()
 * @method \App\Models\Gallery|null findByUuid(string $uuid)
 * @method \App\Models\Gallery create(array $data)
 * @method \App\Models\Gallery update(\App\Models\Gallery $gallery, array $data)
 * @method \App\Models\Gallery destroy(\App\Models\Gallery $gallery)
 * @method \App\Models\Gallery firstOrCreate(array $attributes, array $values)
 * @method \App\Models\Gallery updateOrCreate(array $attributes, array $values)
 */
interface GalleryRepositoryContract extends AbstractRepositoryContract
{
    //
}
