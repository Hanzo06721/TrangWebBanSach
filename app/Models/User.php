<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    // Bảng Users chỉ có CreatedAt, không có UpdatedAt
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    protected $table = 'Users';
    protected $primaryKey = 'UserID';
    public $incrementing = false;       // khóa chính dạng chuỗi: U001, U002...
    protected $keyType = 'string';

    protected $fillable = [
        'UserID', 'Email', 'FullName', 'PasswordHash', 'RoleID',
        'Phone', 'Address', 'Status', 'ProfilePicture',
    ];

    protected $hidden = ['PasswordHash'];

    protected $casts = [
        'Status'    => 'boolean',
        'CreatedAt' => 'datetime',
    ];

    /* ---------- Tùy biến cho hệ thống xác thực (cột PasswordHash, không có remember_token) ---------- */

    public function getAuthPassword(): string
    {
        return $this->PasswordHash;
    }

    public function getAuthPasswordName(): string
    {
        return 'PasswordHash';
    }

    public function getRememberTokenName(): string
    {
        return ''; // bảng không có cột remember_token
    }

    /* ---------- Quan hệ ---------- */

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'RoleID', 'RoleID');
    }

    /* ---------- Tiện ích ---------- */

    public function isAdmin(): bool
    {
        return optional($this->role)->RoleName === 'Admin';
    }

    /** Sinh mã người dùng tiếp theo: U001, U002, ... */
    public static function generateId(): string
    {
        $max = static::query()
            ->selectRaw('MAX(CAST(SUBSTRING(UserID, 2) AS UNSIGNED)) AS m')
            ->value('m');

        return 'U' . str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT);
    }

    /** URL ảnh đại diện (null nếu chưa có hoặc file không tồn tại) */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->ProfilePicture && Storage::disk('public')->exists($this->ProfilePicture)) {
            return asset('storage/' . $this->ProfilePicture);
        }
        return null;
    }

    /** Chữ cái đầu của tên, dùng khi chưa có ảnh */
    public function getInitialAttribute(): string
    {
        $name = trim((string) $this->FullName);
        return $name === '' ? '?' : mb_strtoupper(mb_substr(collect(explode(' ', $name))->last(), 0, 1));
    }
}
