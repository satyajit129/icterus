<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class FacebookLead extends Model
{
    protected $table = 'facebook_leads';
    protected $guarded = [];

    protected $casts = [
        'field_data' => 'array',
        'extracted_fields' => 'array',
        'created_time' => 'datetime',
        'is_processed' => 'boolean',
    ];

    public function facebookLeadgenForm(): BelongsTo
    {
        return $this->belongsTo(FacebookLeadgenForm::class, 'form_id', 'form_id');
    }

    public function facebookPage(): BelongsTo
    {
        return $this->belongsTo(FacebookPage::class, 'page_id', 'page_id');
    }

    public function leadAssignment(): HasOne
    {
        return $this->hasOne(LeadAssignment::class, 'lead_id', 'id');
    }

    public function scopeByForm($query, $formId)
    {
        return $query->where('form_id', $formId);
    }

    public function scopeByPage($query, $pageId)
    {
        return $query->where('page_id', $pageId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_time', [$startDate, $endDate]);
    }

    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    public function scopeByFieldValue($query, $fieldName, $value)
    {
        return $query->whereJsonContains('extracted_fields->' . $fieldName, $value);
    }

    public function scopeByFieldName($query, $fieldName)
    {
        return $query->whereJsonContains('field_data', [['name' => $fieldName]]);
    }

    public function getFieldValue($fieldName)
    {
        if (isset($this->extracted_fields[$fieldName])) {
            return $this->extracted_fields[$fieldName];
        }

        // Fallback to searching in field_data
        foreach ($this->field_data as $field) {
            if (($field['name'] ?? '') === $fieldName) {
                // Safely extract field value
                if (isset($field['values']) && is_array($field['values']) && count($field['values']) > 0) {
                    return $field['values'][0];
                } elseif (isset($field['values']) && !is_array($field['values'])) {
                    return $field['values'];
                } elseif (isset($field['value'])) {
                    return $field['value'];
                }
                return null;
            }
        }

        return null;
    }

    public function getFormattedCreatedTimeAttribute()
    {
        return $this->created_time->format('M d, Y H:i:s');
    }

    public function getLeadAgeAttribute()
    {
        return $this->created_time->diffForHumans();
    }

    public function getCommonFieldsAttribute()
    {
        $commonFields = [];

        // Extract common fields from field_data
        foreach ($this->field_data as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldValue = '';

            // Safely extract field value
            if (isset($field['values']) && is_array($field['values']) && count($field['values']) > 0) {
                $fieldValue = $field['values'][0];
            } elseif (isset($field['values']) && !is_array($field['values'])) {
                $fieldValue = $field['values'];
            } elseif (isset($field['value'])) {
                $fieldValue = $field['value'];
            }

            // Map common field names
            if (in_array($fieldName, ['full_name', 'name', 'first_name', 'last_name'])) {
                $commonFields['name'] = $fieldValue;
            } elseif (in_array($fieldName, ['phone_number', 'phone', 'mobile', 'contact_number'])) {
                $commonFields['phone'] = $fieldValue;
            } elseif (in_array($fieldName, ['email', 'email_address'])) {
                $commonFields['email'] = $fieldValue;
            } elseif (in_array($fieldName, ['city', 'location', 'address'])) {
                $commonFields['location'] = $fieldValue;
            } else {
                // Store other fields with their original names
                $commonFields[$fieldName] = $fieldValue;
            }
        }

        return $commonFields;
    }

    public static function extractCommonFields($fieldData)
    {
        $extracted = [];

        foreach ($fieldData as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldValue = '';

            // Safely extract field value
            if (isset($field['values']) && is_array($field['values']) && count($field['values']) > 0) {
                $fieldValue = $field['values'][0];
            } elseif (isset($field['values']) && !is_array($field['values'])) {
                $fieldValue = $field['values'];
            } elseif (isset($field['value'])) {
                $fieldValue = $field['value'];
            }

            // Map common field names
            if (in_array($fieldName, ['full_name', 'name', 'first_name', 'last_name'])) {
                $extracted['name'] = $fieldValue;
            } elseif (in_array($fieldName, ['phone_number', 'phone', 'mobile', 'contact_number'])) {
                $extracted['phone'] = $fieldValue;
            } elseif (in_array($fieldName, ['email', 'email_address'])) {
                $extracted['email'] = $fieldValue;
            } elseif (in_array($fieldName, ['city', 'location', 'address'])) {
                $extracted['location'] = $fieldValue;
            } else {
                $extracted[$fieldName] = $fieldValue;
            }
        }

        return $extracted;
    }
}
