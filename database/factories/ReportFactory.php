<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        $reportTypes = ['lead', 'property', 'activity', 'sales', 'performance'];
        $timeRanges = ['last_week', 'last_month', 'last_quarter', 'custom', 'year_to_date', 'all_time'];
        $statuses = ['draft', 'published', 'scheduled'];
        $formats = ['pdf', 'csv', 'excel'];
        
        // Get a random user to be the creator
        $userId = User::inRandomOrder()->first()?->id;
        if (!$userId) {
            $userId = User::factory()->create()->id;
        }
        
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->boolean(70) ? $this->faker->paragraph() : null,
            'type' => $this->faker->randomElement($reportTypes),
            'time_range' => $this->faker->randomElement($timeRanges),
            'start_date' => function (array $attributes) {
                return $attributes['time_range'] === 'custom' ? $this->faker->dateTimeBetween('-1 year', '-1 month') : null;
            },
            'end_date' => function (array $attributes) {
                return $attributes['time_range'] === 'custom' && $attributes['start_date'] ? 
                    $this->faker->dateTimeBetween($attributes['start_date'], 'now') : null;
            },
            'filters' => json_encode([
                'status' => $this->faker->boolean(70) ? $this->faker->randomElement(['new', 'contacted', 'qualified', 'proposal', 'won', 'lost']) : null,
                'source' => $this->faker->boolean(60) ? $this->faker->randomElement(['website', 'referral', 'social media', 'direct']) : null,
                'assigned_to' => $this->faker->boolean(50) ? $userId : null,
            ]),
            'columns' => json_encode($this->getColumnsBasedOnType($this->faker->randomElement($reportTypes))),
            'sort_by' => $this->faker->randomElement(['created_at', 'name', 'status', 'price', 'budget']),
            'sort_direction' => $this->faker->randomElement(['asc', 'desc']),
            'status' => $this->faker->randomElement($statuses),
            'is_favorite' => $this->faker->boolean(30),
            'format' => $this->faker->randomElement($formats),
            'schedule' => $this->faker->boolean(40) ? $this->faker->randomElement(['daily', 'weekly', 'monthly', 'quarterly']) : null,
            'last_run_at' => $this->faker->boolean(60) ? $this->faker->dateTimeThisMonth() : null,
            'created_by' => $userId,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    private function getColumnsBasedOnType($type)
    {
        switch ($type) {
            case 'lead':
                return $this->faker->randomElements([
                    'id', 'first_name', 'last_name', 'email', 'phone', 'status', 
                    'source', 'budget', 'property_interest', 'assigned_to', 'created_at'
                ], $this->faker->numberBetween(5, 8));
            
            case 'property':
                return $this->faker->randomElements([
                    'id', 'name', 'type', 'status', 'price', 'area', 'rooms', 
                    'bathrooms', 'owner_name', 'created_at'
                ], $this->faker->numberBetween(5, 8));
            
            case 'activity':
                return $this->faker->randomElements([
                    'user', 'action', 'lead', 'property', 'description', 'created_at'
                ], $this->faker->numberBetween(3, 6));
            
            case 'sales':
                return $this->faker->randomElements([
                    'property', 'price', 'agent', 'client', 'commission', 'date'
                ], $this->faker->numberBetween(4, 6));
            
            case 'performance':
                return $this->faker->randomElements([
                    'agent', 'leads_assigned', 'leads_converted', 'sales_amount', 
                    'commission_earned', 'conversion_rate'
                ], $this->faker->numberBetween(4, 6));
            
            default:
                return ['id', 'name', 'created_at'];
        }
    }
}
