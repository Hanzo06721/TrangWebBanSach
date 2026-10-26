<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table="categories";
    protected $primaryKey = "CategoryID";
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = null;
    protected $fillable = ["CategoryID", "CategoryName", "Description", "Status"];
    protected $casts = ["Status"=>"boolean", "CreatedAt"=>"datetime"];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'CategoryID', 'CategoryID');
    }
}
