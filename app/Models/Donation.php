<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $table="donations";
    protected $primaryKey = "DonationID";
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = null;
    protected $fillable = ["DonationID", "UserID", "PostID", "ContactInfo", "DonationStatus"];
    protected $casts = ["CreatedAt"=>"datetime"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }
}
