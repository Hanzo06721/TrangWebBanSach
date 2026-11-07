<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $primaryKey = 'TransactionID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = null;

    protected $fillable = ['TransactionID', 'TransactionStatus', 'PostID', 'BuyerID', 'SellerID'];

    protected $casts = ['CreatedAt' => 'datetime', 'CompletedAt' => 'datetime'];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'SellerID', 'UserID');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'BuyerID', 'UserID');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'PostID', 'PostID');
    }

    public function review(): HasMany
    {
        return $this->hasMany(Review::class, 'TransactionID', 'TransactionID');
    }

    public static function generateId(): string
    {
        $max = static::query()
            ->selectRaw('MAX(CAST(SUBSTRING(TransactionID, 2) AS UNSIGNED)) AS m')
            ->value('m');

        return 'T'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT);
    }
}
