<?php

namespace App\Enum;

enum SalaryStatus: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case DISAPPROVED = 2;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approve',
            self::DISAPPROVED => 'Rejected',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PENDING => 'fe fe-alert-circle',
            self::APPROVED => 'fe fe-check-circle',
            self::DISAPPROVED => 'fe fe-x-circle',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-warning text-dark',
            self::APPROVED => 'bg-success text-light',
            self::DISAPPROVED => 'bg-danger text-light',
        };
    }
}