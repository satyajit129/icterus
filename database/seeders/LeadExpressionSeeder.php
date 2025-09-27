<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeadExpression;

class LeadExpressionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expressions = [
            [
                'name' => 'Interested',
                'color_class' => 'bg-success',
                'text_color' => 'text-white',
                'description' => 'Lead has shown interest in the product/service',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Not Interested',
                'color_class' => 'bg-danger',
                'text_color' => 'text-white',
                'description' => 'Lead is not interested in the product/service',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Converted',
                'color_class' => 'bg-primary',
                'text_color' => 'text-white',
                'description' => 'Lead has been successfully converted to customer',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Follow Up',
                'color_class' => 'bg-warning',
                'text_color' => 'text-dark',
                'description' => 'Lead requires follow up',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Qualified',
                'color_class' => 'bg-info',
                'text_color' => 'text-white',
                'description' => 'Lead has been qualified as potential customer',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($expressions as $expression) {
            LeadExpression::firstOrCreate(
                ['name' => $expression['name']],
                $expression
            );
        }
    }
}
