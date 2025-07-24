<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id', 'id');
    }
    public function loanPayment()
    {
        return $this->hasMany(LoanPayment::class,'loan_id', 'id');
    }
}
