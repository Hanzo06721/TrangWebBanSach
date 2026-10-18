<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'Roles';
    protected $primaryKey = 'RoleID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['RoleID', 'RoleName'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'RoleID', 'RoleID');
    }

    /** Tên hiển thị tiếng Việt */
    public function getLabelAttribute(): string
    {
        return match ($this->RoleName) {
            'Admin' => 'Quản trị viên',
            'User'  => 'Người dùng',
            default => $this->RoleName,
        };
    }
}
