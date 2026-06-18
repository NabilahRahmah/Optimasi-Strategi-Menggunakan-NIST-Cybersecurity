<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameworkAssignment extends Model
{
    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'framework_id',
        'user_id',
    ];

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class, 'framework_id', 'framework_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}