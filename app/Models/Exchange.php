<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exchange extends Model
{
    protected $table="exchanges";
    protected $primaryKey = "ExchangeID";
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = null;
    protected $fillable = ["ExchangeID", "SenderID", "PostID", "OfferedBookID", "Message", "ExchangeStatus"];
    protected $casts = ["CreatedAt"=>"datetime"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'SenderID', 'UserID');
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }
    
}
