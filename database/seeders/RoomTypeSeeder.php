<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard Room',
                'description' => 'Kamar standar yang nyaman dengan fasilitas lengkap untuk tamu yang mencari kenyamanan dengan harga terjangkau.',
                'price_per_night' => 500000,
                'capacity' => 2,
                'total_rooms' => 20,
                'facilities' => [
                    'AC',
                    'TV LED 32"',
                    'WiFi Gratis',
                    'Kamar Mandi Dalam',
                    'Lemari Es Mini',
                    'Meja Kerja',
                    'Telepon',
                    'Safe Deposit Box'
                ],
                'images' => [
                    '/images/rooms/standard-1.jpg',
                    '/images/rooms/standard-2.jpg',
                    '/images/rooms/standard-3.jpg'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Kamar deluxe dengan ruang yang lebih luas dan pemandangan kota yang menawan, dilengkapi dengan fasilitas premium.',
                'price_per_night' => 750000,
                'capacity' => 2,
                'total_rooms' => 15,
                'facilities' => [
                    'AC',
                    'TV LED 43"',
                    'WiFi Gratis',
                    'Kamar Mandi Dalam dengan Bathtub',
                    'Lemari Es Mini',
                    'Meja Kerja',
                    'Telepon',
                    'Safe Deposit Box',
                    'Balkon',
                    'City View',
                    'Coffee Maker',
                    'Bathrobes'
                ],
                'images' => [
                    '/images/rooms/deluxe-1.jpg',
                    '/images/rooms/deluxe-2.jpg',
                    '/images/rooms/deluxe-3.jpg'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Suite eksekutif dengan ruang tamu terpisah, ideal untuk tamu bisnis yang membutuhkan ruang kerja dan kenyamanan ekstra.',
                'price_per_night' => 1200000,
                'capacity' => 3,
                'total_rooms' => 10,
                'facilities' => [
                    'AC',
                    'TV LED 55"',
                    'WiFi Gratis',
                    'Kamar Mandi Dalam dengan Jacuzzi',
                    'Lemari Es',
                    'Meja Kerja Besar',
                    'Telepon',
                    'Safe Deposit Box',
                    'Balkon',
                    'City View',
                    'Coffee Maker',
                    'Bathrobes',
                    'Ruang Tamu Terpisah',
                    'Sofa Bed',
                    'Minibar',
                    'Executive Lounge Access'
                ],
                'images' => [
                    '/images/rooms/executive-1.jpg',
                    '/images/rooms/executive-2.jpg',
                    '/images/rooms/executive-3.jpg'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'Suite presidensial mewah dengan fasilitas terlengkap dan pemandangan panorama kota, memberikan pengalaman menginap yang tak terlupakan.',
                'price_per_night' => 2500000,
                'capacity' => 4,
                'total_rooms' => 3,
                'facilities' => [
                    'AC',
                    'TV LED 65"',
                    'WiFi Gratis',
                    'Kamar Mandi Master dengan Jacuzzi',
                    'Lemari Es Besar',
                    'Meja Kerja Premium',
                    'Telepon',
                    'Safe Deposit Box',
                    'Balkon Luas',
                    'Panoramic City View',
                    'Coffee Maker',
                    'Bathrobes Premium',
                    'Ruang Tamu Mewah',
                    'Ruang Makan',
                    'Dapur Kecil',
                    'Minibar Premium',
                    'Executive Lounge Access',
                    'Butler Service',
                    'Private Elevator Access'
                ],
                'images' => [
                    '/images/rooms/presidential-1.jpg',
                    '/images/rooms/presidential-2.jpg',
                    '/images/rooms/presidential-3.jpg'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Family Room',
                'description' => 'Kamar keluarga yang luas dengan tempat tidur tambahan, cocok untuk keluarga dengan anak-anak.',
                'price_per_night' => 900000,
                'capacity' => 4,
                'total_rooms' => 8,
                'facilities' => [
                    'AC',
                    'TV LED 43"',
                    'WiFi Gratis',
                    'Kamar Mandi Dalam',
                    'Lemari Es',
                    'Meja Kerja',
                    'Telepon',
                    'Safe Deposit Box',
                    'Balkon',
                    'Extra Bed',
                    'Kids Amenities',
                    'Family Entertainment System'
                ],
                'images' => [
                    '/images/rooms/family-1.jpg',
                    '/images/rooms/family-2.jpg',
                    '/images/rooms/family-3.jpg'
                ],
                'is_active' => true,
            ]
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}
