<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    // daftar user yang punya role ini
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // cek apakah role ini punya izin tertentu
    public function hasPermission(string $permission): bool
    {
        $roleName = strtolower(trim($this->name ?? ''));
        // Admin otomatis memiliki semua hak akses
        if (in_array($roleName, ['admin', 'administrator'])) {
            return true;
        }

        $perms = $this->permissions ?? [];
        return is_array($perms) && in_array($permission, $perms);
    }
}
