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

    public function dealPayments()
    {
        return $this->hasMany(DealPayment::class, 'company_deal_id');
    }

    // Optional helper method
    public function getRemainingBalanceAttribute()
    {
        $totalPaid = $this->dealPayments->sum('amount');
        return $this->deals_amount - $totalPaid;
    }

}
