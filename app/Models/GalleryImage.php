<?php

namespace App\Models;

use App\Models\Traits\GeneratesUuid;
use App\Models\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable as SortableInterface;

/**
 * @property-read string $uuid
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property int $ordinal
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 *
 * -----
 *
 * @property-read \App\Models\Gallery $gallery
 */
class GalleryImage extends Model implements SortableInterface
{
    use GeneratesUuid, Sortable;

    /**
     * @var array
     */
    public const IMAGES = [
        'image',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'gallery_uuid');
    }
}
