<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $primaryKey = 'PostID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'PostID',
        'BookID',
        'UserID',
        'Title',
        'Price',
        'PostType',
        'Condition',
        'Description',
        'PostStatus',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
    ];

    public function books(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'BookID', 'BookID');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function donation(): HasMany
    {
        return $this->hasMany(Donation::class, 'PostID', 'PostID');
    }

    public function consignment(): HasMany
    {
        return $this->hasMany(Consignment::class, 'PostID', 'PostID');
    }

    public function exchange(): HasMany
    {
        return $this->hasMany(Exchange::class, 'PostID', 'PostID');
    }

    public function favorite(): HasMany
    {
        return $this->hasMany(Favorite::class, 'PostID', 'PostID');
    }

    public function postmoderation(): HasMany
    {
        return $this->hasMany(PostModeration::class, 'PostID', 'PostID');
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'PostID', 'PostID');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class, 'PostID', 'PostID');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'PostID', 'PostID')->orderByDesc('CreatedAt');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class, 'PostID', 'PostID');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(PostReport::class, 'PostID', 'PostID');
    }

    public static function generateId(): string
    {
        $max = static::query()
            ->selectRaw('MAX(CAST(SUBSTRING(PostID, 2) AS UNSIGNED)) AS m')
            ->value('m');

        return 'P'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT);
    }
}
