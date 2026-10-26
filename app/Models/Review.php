<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Review extends Model
{
    protected $table="reviews";
    protected $primaryKey = "ReviewID";
    public $incrementing = false;
    protected $keyType = "string";
    const CREATED_AT = "CreatedAt";
    const UPDATED_AT = null;
    protected $fillable = ["ReviewID", "TransactionID", "ReviewerID", "ReviewedUserID", "Rating", "Comment"];
    protected $casts = ["CreatedAt"=>"datetime"];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ReviewerID', 'UserID');
    }
    public function revieweduser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ReviewedUserID', 'UserID');
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'TransactionID', 'TransactionID');
    }
}
