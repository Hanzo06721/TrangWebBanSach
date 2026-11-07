<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';

    protected $primaryKey = 'MessageID';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';

    const UPDATED_AT = null;

    protected $fillable = [
        'MessageID',
        'PostID',
        'TransactionID',
        'SenderID',
        'Message',
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'SenderID', 'UserID');
    }

    public static function generateId(): string
    {
        $max = static::query()
            ->selectRaw('MAX(CAST(SUBSTRING(MessageID, 3) AS UNSIGNED)) AS m')
            ->value('m');

        return 'MS'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT);
    }
}
