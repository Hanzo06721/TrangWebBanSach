<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Model
{
    protected $table = 'comment_reactions';

    protected $primaryKey = 'ReactionID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = null;

    protected $fillable = ['ReactionID', 'CommentID', 'UserID', 'Type'];

    protected $casts = ['CreatedAt' => 'datetime'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'CommentID', 'CommentID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
}
