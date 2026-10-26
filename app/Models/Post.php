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
    protected $keyType = "string";
    public $timestamps = false;
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
}
