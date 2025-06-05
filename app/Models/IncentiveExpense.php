<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncentiveExpense extends Model
{
    protected $table = "incentive_expenses";
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
