<?php

namespace App\Http\Resources\GalleryImage;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class GalleryImage extends JsonResource
{
    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image' => url(Storage::url($this->image)),
        ];
    }
}
