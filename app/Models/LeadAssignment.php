<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadAssignment extends Model
{
    protected $fillable = [
        'lead_id',
        'assigned_to',
        'assigned_by',
        'assignment_type',
        'notes',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(FacebookLead::class, 'lead_id', 'id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function scopeBulkAssignments($query)
    {
        return $query->where('assignment_type', 'bulk');
    }

    public function scopeIndividualAssignments($query)
    {
        return $query->where('assignment_type', 'individual');
    }
}
