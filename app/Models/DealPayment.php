<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealPayment extends Model
{
    protected $guarded = [];
    protected $table = "deal_payments";

    public function companyDeal()
    {
        return $this->belongsTo(CompanyDeal::class, 'company_deal_id');
    }
    
}
