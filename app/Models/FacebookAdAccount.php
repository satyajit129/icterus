<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookAdAccount extends Model
{
    protected $table = 'facebook_ad_accounts';
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%$term%")
                ->orWhere('ad_account_id', 'like', "%$term%")
                ->orWhere('ad_account_gid', 'like', "%$term%")
                ->orWhere('business_name', 'like', "%$term%");
        });
    }
}
