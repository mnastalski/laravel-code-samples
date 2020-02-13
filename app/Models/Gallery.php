<?php

namespace App\Models;

use App\Models\Traits\GeneratesUuid;
use App\Models\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable as SortableInterface;

/**
 * @property-read string $uuid
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property int $ordinal
 * @property int $images_max_value
 * @property int $is_active
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 *
 * -----
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GalleryImage $images
 */
class Gallery extends Model implements SortableInterface
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
        'images_max_value',
        'is_active',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_active' => 'bool',
    ];

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
