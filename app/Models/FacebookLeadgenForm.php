<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookLeadgenForm extends Model
{
    protected $table = 'facebook_leadgen_forms';
    protected $guarded = [];

    protected $casts = [
        'form_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function facebookPage(): BelongsTo
    {
        return $this->belongsTo(FacebookPage::class, 'page_id', 'page_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPage($query, $pageId)
    {
        return $query->where('page_id', $pageId);
    }

    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeByLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'ACTIVE' => 'success',
            'ARCHIVED' => 'warning',
            'DELETED' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'ACTIVE' => 'Active',
            'ARCHIVED' => 'Archived',
            'DELETED' => 'Deleted',
            default => 'Unknown'
        };
    }
}
