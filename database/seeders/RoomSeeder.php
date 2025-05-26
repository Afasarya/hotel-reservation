<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomType::all();

        foreach ($roomTypes as $roomType) {
            $prefix = $this->getRoomPrefix($roomType->name);
            
            for ($i = 1; $i <= $roomType->total_rooms; $i++) {
                $floor = ceil($i / 10); // 10 rooms per floor
                $roomNumber = $prefix . str_pad($floor, 1, '0') . str_pad($i % 10 ?: 10, 2, '0', STR_PAD_LEFT);
                
                Room::create([
                    'room_type_id' => $roomType->id,
                    'room_number' => $roomNumber,
                    'status' => 'available',
                    'notes' => null,
                ]);
            }
        }
    }

    private function getRoomPrefix($roomTypeName)
    {
        switch ($roomTypeName) {
            case 'Standard Room':
                return 'STD';
            case 'Deluxe Room':
                return 'DLX';
            case 'Executive Suite':
                return 'EXE';
            case 'Presidential Suite':
                return 'PRE';
            case 'Family Room':
                return 'FAM';
            default:
                return 'ROM';
        }
    }
}
