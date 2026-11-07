<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    protected $table = 'post_comments';

    protected $primaryKey = 'CommentID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'CommentID',
        'PostID',
        'UserID',
        'ParentCommentID',
        'Content',
        'ImagePath',
        'Status',
        'UpdatedAt',
    ];

    protected $casts = ['CreatedAt' => 'datetime', 'UpdatedAt' => 'datetime'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'ParentCommentID', 'CommentID');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(PostComment::class, 'ParentCommentID', 'CommentID')->orderBy('CreatedAt');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(CommentReaction::class, 'CommentID', 'CommentID');
    }
}
