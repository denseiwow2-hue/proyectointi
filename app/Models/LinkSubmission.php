<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_by',
        'platform',
        'url',
        'block',
        'action',
        'due_date',
        'status',
        'image_path',
        'admin_comment',
        'user_comment',
        'submitted_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'submitted_at' => 'datetime',
        'image_path' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
