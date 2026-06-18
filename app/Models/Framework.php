<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Framework extends Model
{
    protected $primaryKey = 'framework_id';

    protected $fillable = [
        'name_framework',
        'description',
        'is_active',
        'pic_user_id',
    ];

    // Relasi ke Domain
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class, 'framework_id', 'framework_id');
    }

    // PIC dari Super Admin
    public function picUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id', 'user_id');
    }

    // Relasi ke Assessment
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'framework_id', 'framework_id');
    }

    // User & Approver yang di-assign oleh Admin
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'framework_assignments',
            'framework_id',
            'user_id',
            'framework_id',
            'user_id'
        )->withTimestamps();
    }

    // Helper: cek apakah user tertentu di-assign ke framework ini
    public function isAssigned(int $userId): bool
    {
        return $this->assignedUsers()->where('users.user_id', $userId)->exists();
    }
}