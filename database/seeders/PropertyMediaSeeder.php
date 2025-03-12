<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyMedia;
use Illuminate\Database\Seeder;

class PropertyMediaSeeder extends Seeder
{
    public function run()
    {
        // تنظيف جدول الصور القديمة أولاً
        PropertyMedia::truncate();

        // الصور الجديدة
        $sampleImages = [
            // صور للشقق
            'apartments' => [
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3',
            ],
            // صور للفلل
            'villas' => [
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750',
                'https://images.unsplash.com/photo-1600585154526-990dced4db0d',
                'https://images.unsplash.com/photo-1600573472592-401b489a3cdc',
            ],
            // صور للمكاتب
            'offices' => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2',
                'https://images.unsplash.com/photo-1582407947304-fd86f028f716',
            ],
        ];

        Property::chunk(50, function ($properties) use ($sampleImages) {
            foreach ($properties as $property) {
                // اختيار مجموعة الصور المناسبة حسب نوع العقار
                $propertyType = $property->type;
                $imageGroup = 'apartments'; // افتراضي

                if (in_array($propertyType, ['villa', 'duplex'])) {
                    $imageGroup = 'villas';
                } elseif (in_array($propertyType, ['office', 'retail'])) {
                    $imageGroup = 'offices';
                }

                // إضافة 3-4 صور لكل عقار
                $mediaCount = rand(3, 4);
                for ($i = 0; $i < $mediaCount; $i++) {
                    PropertyMedia::create([
                        'property_id' => $property->id,
                        'file_path' => $sampleImages[$imageGroup][array_rand($sampleImages[$imageGroup])],
                        'file_type' => 'image',
                        'is_featured' => $i === 0,
                        'sort_order' => $i,
                    ]);
                }
            }
        });
    }
}
