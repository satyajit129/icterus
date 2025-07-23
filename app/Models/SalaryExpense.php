<?php

namespace App\Models;

use App\Enum\SalaryStatus;
use Illuminate\Database\Eloquent\Model;

class SalaryExpense extends Model
{
    protected $table = "salary_expenses";
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
        protected $casts = [
        'salary_status' => SalaryStatus::class,
    ];

}
