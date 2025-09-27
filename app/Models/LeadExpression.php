<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadExpression extends Model
{
    protected $fillable = [
        'name',
        'color_class',
        'text_color',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Scope for active expressions
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Get formatted badge HTML
    public function getBadgeHtmlAttribute()
    {
        return "<span class=\"badge {$this->color_class} {$this->text_color}\">{$this->name}</span>";
    }

    // Get all expressions as array for dropdowns
    public static function getExpressionsArray()
    {
        return self::active()
            ->ordered()
            ->pluck('name', 'id')
            ->toArray();
    }

    // Get expressions with full data for display
    public static function getExpressionsForDisplay()
    {
        return self::active()
            ->ordered()
            ->get()
            ->mapWithKeys(function ($expression) {
                return [
                    $expression->id => [
                        'text' => $expression->name,
                        'class' => $expression->color_class,
                        'text_color' => $expression->text_color
                    ]
                ];
            })
            ->toArray();
    }
}
