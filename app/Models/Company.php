<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
    protected $table = "companies";

    public function deals()
    {
        return $this->hasMany(CompanyDeal::class);
    }
}
