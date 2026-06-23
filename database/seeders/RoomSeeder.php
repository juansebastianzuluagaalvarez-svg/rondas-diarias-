<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Piso 7 - Llamados
        for ($i = 1; $i <= 21; $i++) {
            $numero = str_pad($i, 2, '0', STR_PAD_LEFT);
            Room::create([
                'room_id' => "7{$numero}A",
                'name' => "Hab 7{$numero}A",
                'section' => 'Piso 7 - Llamados',
                'type' => 'llamados',
            ]);
            Room::create([
                'room_id' => "7{$numero}B",
                'name' => "Hab 7{$numero}B",
                'section' => 'Piso 7 - Llamados',
                'type' => 'llamados',
            ]);
        }

        // Piso 8 - Llamados
        $piso8Rooms = [
            ['801A', 'Hab 801A'],
            ['801B', 'Hab 801B'],
            ['802', 'Hab 802'],
            ['803', 'Hab 803'],
            ['804A', 'Hab 804A'],
            ['804B', 'Hab 804B'],
            ['805', 'Hab 805'],
            ['806A', 'Hab 806A'],
            ['806B', 'Hab 806B'],
            ['807A', 'Hab 807A'],
            ['807B', 'Hab 807B'],
            ['808', 'Hab 808'],
            ['809', 'Hab 809'],
            ['810A', 'Hab 810A'],
            ['810B', 'Hab 810B'],
            ['811', 'Hab 811'],
            ['812', 'Hab 812'],
            ['813', 'Hab 813'],
            ['814', 'Hab 814'],
            ['815', 'Hab 815'],
            ['816', 'Hab 816'],
            ['817', 'Hab 817'],
            ['818', 'Hab 818'],
            ['819', 'Hab 819'],
            ['820', 'Hab 820'],
            ['821', 'Hab 821'],
            ['822', 'Hab 822'],
            ['823', 'Hab 823'],
            ['824', 'Hab 824'],
            ['825', 'Hab 825'],
            ['826', 'Hab 826'],
            ['827', 'Hab 827'],
        ];

        foreach ($piso8Rooms as [$id, $name]) {
            Room::create([
                'room_id' => $id,
                'name' => $name,
                'section' => 'Piso 8 - Llamados',
                'type' => 'llamados',
            ]);
        }

        // UCI - Llamados
        for ($i = 1; $i <= 18; $i++) {
            Room::create([
                'room_id' => "UCI-{$i}",
                'name' => "Cubiculo {$i}",
                'section' => 'UCI - Llamados',
                'type' => 'llamados',
            ]);
        }

        // UCIN - Llamados
        for ($i = 1; $i <= 14; $i++) {
            Room::create([
                'room_id' => "UCIN-{$i}",
                'name' => "Cubiculo {$i}",
                'section' => 'UCIN - Llamados',
                'type' => 'llamados',
            ]);
        }

        // Piso 7 - Baños
        for ($i = 1; $i <= 21; $i++) {
            $numero = str_pad($i, 2, '0', STR_PAD_LEFT);
            Room::create([
                'room_id' => "B7-7{$numero}",
                'name' => "Baño 7{$numero}",
                'section' => 'Piso 7 - Baños',
                'type' => 'baños',
            ]);
        }

        // Piso 8 - Baños
        for ($i = 1; $i <= 27; $i++) {
            $numero = str_pad($i, 2, '0', STR_PAD_LEFT);
            Room::create([
                'room_id' => "B8-8{$numero}",
                'name' => "Baño 8{$numero}",
                'section' => 'Piso 8 - Baños',
                'type' => 'baños',
            ]);
        }
    }
}
