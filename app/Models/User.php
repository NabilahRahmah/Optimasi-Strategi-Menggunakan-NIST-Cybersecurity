<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',      // ← tambah ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Helper untuk cek role ──────────────────────────
    public function isAdminSuper(): bool
    {
        return $this->role === 'admin_super';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isApprover(): bool
    {
        return $this->role === 'approver';
    }

    // ── Relasi ────────────────────────────────────────
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'user_id', 'user_id');
    }

     public function assignedFrameworks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Framework::class,
            'framework_assignments',
            'user_id',
            'framework_id',
            'user_id',
            'framework_id'
        )->withTimestamps();
    }
 
    // Helper: cek apakah user di-assign ke framework tertentu
    public function isAssignedTo(int $frameworkId): bool
    {
        return $this->assignedFrameworks()->where('frameworks.framework_id', $frameworkId)->exists();
    }
}