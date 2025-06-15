<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDeal extends Model
{
    protected $table = "company_deals";
    protected $guarded = [];


    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
