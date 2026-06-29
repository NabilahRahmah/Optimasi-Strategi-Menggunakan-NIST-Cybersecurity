<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'nik',
        'role',     
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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

    public function jawabans(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'user_id', 'user_id');
    }

    public function assignedFrameworks(): BelongsToMany
    {
        return $this->belongsToMany(
            Framework::class, 
            'framework_assignments', 
            'user_id',               
            'framework_id'           
        );
    }

    /**
     * Helper untuk mengecek apakah user punya akses ke framework tertentu
     */
    public function isAssignedTo($frameworkId): bool
    {
        return $this->assignedFrameworks()
            ->where('frameworks.framework_id', $frameworkId)
            ->exists();
    }
}