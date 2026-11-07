<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostImage extends Model
{
    protected $table = 'post_images';

    protected $primaryKey = 'ImageID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = null;

    protected $fillable = ['ImageID', 'PostID', 'ImagePath', 'IsPrimary'];

    protected $casts = ['IsPrimary' => 'boolean', 'CreatedAt' => 'datetime'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }
}
