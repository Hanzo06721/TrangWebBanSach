<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostModeration extends Model
{
    protected $table = 'postmoderation';
    protected $primaryKey = 'ModerationID';
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = null;
    protected $fillable = ["ModerationID", "Status", "Reason", "PostID", "AdminID"];
    protected $casts = ["CreatedAt"=>"datetime"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'AdminID','UserID');
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    } 
}
