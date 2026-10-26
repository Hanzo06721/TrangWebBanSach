<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consignment extends Model
{
    protected $table="consignments";
    protected $primaryKey = "ConsignmentID";
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = null;
    protected $fillable = ["ConsignmentID", "UserID", "PostID", "ContactInfo", "Description", "ConsignmentStatus"];
    protected $casts = ["CreatedAt"=>"datetime"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'UserID','UserID');
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }
}
