<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostReaction extends Model
{
    protected $table = 'post_reactions';

    protected $primaryKey = 'ReactionID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = null;

    protected $fillable = ['ReactionID', 'PostID', 'UserID', 'Type'];

    protected $casts = ['CreatedAt' => 'datetime'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}
