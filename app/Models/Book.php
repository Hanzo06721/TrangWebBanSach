<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table="books";
    protected $primaryKey = "BookID";
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = "UpdatedAt";
    protected $fillable = ["BookID", "Title", "AuthorName", "ISBN", "Publisher", "CategoryID", "Description", "CoverImage"];
    protected $casts = ["CreatedAt"=>"datetime", "UpdatedAt"=>"datetime"];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'CategoryID',
            'CategoryID'
        );
    }
    public function post(): HasMany
    {
        return $this->hasMany(Post::class, 'BookID', 'BookID');
    }
}
