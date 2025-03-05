<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true) . ' ' . $this->faker->randomElement(['Residence', 'Villa', 'Apartment', 'Condo', 'Penthouse']),
            'location' => $this->faker->address,
            'price' => $this->faker->numberBetween(100000, 1000000),
            'size' => $this->faker->numberBetween(50, 500),
            'type' => $this->faker->randomElement(['Apartment', 'House', 'Villa', 'Condo', 'Penthouse']),
            'description' => $this->faker->paragraph(3),
            'rooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'owner_name' => $this->faker->name,
            'image' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750',
                'https://images.unsplash.com/photo-1600585152220-90363fe7e115',
                'https://images.unsplash.com/photo-1600047509807-f8261a3f6dab',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c',
                'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d',
                'https://images.unsplash.com/photo-1600566753086-00f18fb6b3d7',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
                'https://images.unsplash.com/photo-1600566752355-35792bedcfea',
                'https://images.unsplash.com/photo-1600210492493-0946911123ea',
            ]),
        ];
    }
}
