<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // relasi ke tabel roles buat tau jabatan si user
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // cek kecocokan role user, dibuat case-insensitive biar ga sensitif huruf kapital
    public function hasRole(string|array $roles): bool
    {
        // kalau ga punya role ya langsung gagal aja
        if (!$this->role) {
            return false;
        }

        $roleName = strtolower(trim($this->role->name));

        // kalau parameternya array, cek apakah salah satunya cocok
        if (is_array($roles)) {
            $roles = array_map(fn($r) => strtolower(trim($r)), $roles);
            return in_array($roleName, $roles);
        }

        return $roleName === strtolower(trim($roles));
    }

    // shortcut biar di blade/controller manggilnya gampang & bersih
    public function isAdmin(): bool
    {
        return $this->hasRole(['admin', 'administrator']);
    }

    public function isKasir(): bool
    {
        return $this->hasRole(['kasir', 'cashier']);
    }

    public function isPimpinan(): bool
    {
        return $this->hasRole(['pimpinan', 'leader']);
    }

    // cek apakah user memiliki hak akses spesifik
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permission);
    }

    // Avatar khusus tiap role: Admin (cowo), Kasir (cewek), Pimpinan (cowo berjas)
    public function getAvatarUrlAttribute(): string
    {
        if ($this->isKasir()) {
            return asset('assets/assets/images/avatar_kasir.png');
        }
        if ($this->isPimpinan()) {
            return asset('assets/assets/images/avatar_pimpinan.png');
        }
        return asset('assets/assets/images/avatar.png');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // password langsung otomatis di-hash waktu disimpan
            'password' => 'hashed',
        ];
    }
}
