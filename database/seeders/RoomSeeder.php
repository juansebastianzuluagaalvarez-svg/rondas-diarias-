<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════
        // PISO 9 - ANGIOGRAFÍA
        // ═══════════════════════════════════════

        $piso9Angio = [
            ['9AG-R', 'Recepción Recuperación'],
            ['9AG-S', 'Sala Angiografía'],
        ];
        foreach ($piso9Angio as [$id, $name]) {
            Room::create(['room_id' => $id, 'name' => $name, 'section' => 'Piso 9 - Angiografía - Llamados', 'type' => 'llamados']);
        }
        for ($i = 1; $i <= 6; $i++) {
            Room::create(['room_id' => "9AG-{$i}", 'name' => "Cubículo {$i}", 'section' => 'Piso 9 - Angiografía - Llamados', 'type' => 'llamados']);
        }
        $piso9AngioBanos = [
            ['9AG-B1', 'Baño Recuperación Hombre'],
            ['9AG-B2', 'Baño Recuperación Mujer'],
            ['9AG-B3', 'Baño Admisiones Hombre'],
            ['9AG-B4', 'Baño Admisiones Mujer'],
        ];
        foreach ($piso9AngioBanos as [$id, $name]) {
            Room::create(['room_id' => $id, 'name' => $name, 'section' => 'Piso 9 - Angiografía - Baños', 'type' => 'baños']);
        }

        // ═══════════════════════════════════════
        // PISO 9 - CARDIOLOGÍA
        // ═══════════════════════════════════════

        $piso9Cardio = [
            ['9C-1', 'Cama 1'],
            ['9C-2', 'Cama 2'],
            ['9C-E', 'Estación Enfermería'],
        ];
        foreach ($piso9Cardio as [$id, $name]) {
            Room::create(['room_id' => $id, 'name' => $name, 'section' => 'Piso 9 - Cardiología - Llamados', 'type' => 'llamados']);
        }
        Room::create(['room_id' => '9C-B1', 'name' => 'Baño Cardiología', 'section' => 'Piso 9 - Cardiología - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 8 - HOSPITALIZACIÓN
        // ═══════════════════════════════════════

        $piso8Hab = [
            '801A','801B','802','803','804A','804B','805A','805B',
            '806A','806B','807A','807B','808','809','810',
            '811','812','813','814','815','816','817','818','819',
            '820','821A','821B','822','823','824','825','826','827',
        ];
        foreach ($piso8Hab as $id) {
            Room::create(['room_id' => $id, 'name' => "Hab {$id}", 'section' => 'Piso 8 - Hospitalización - Llamados', 'type' => 'llamados']);
        }
        $piso8Banos = [
            '801','802','803','804','805','806','807',
            '808','809','810','811','812','813','814','815',
            '816','817','818','819','820','821A','821B',
            '822','823','824','825','826','827',
        ];
        foreach ($piso8Banos as $id) {
            Room::create(['room_id' => "B8-{$id}", 'name' => "Baño 8-{$id}", 'section' => 'Piso 8 - Hospitalización - Baños', 'type' => 'baños']);
        }

        // Piso 8 - Admisiones
        Room::create(['room_id' => '8-ADM', 'name' => 'Admisiones', 'section' => 'Piso 8 - Admisiones - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '8-ENF', 'name' => 'Estación Enfermería', 'section' => 'Piso 8 - Admisiones - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B8-SEH', 'name' => 'Baño Sala Espera Hombre', 'section' => 'Piso 8 - Admisiones - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B8-SEM', 'name' => 'Baño Sala Espera Mujer', 'section' => 'Piso 8 - Admisiones - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 7 - HOSPITALIZACIÓN
        // ═══════════════════════════════════════

        for ($i = 1; $i <= 21; $i++) {
            $n = str_pad($i, 2, '0', STR_PAD_LEFT);
            Room::create(['room_id' => "7{$n}A", 'name' => "Hab 7{$n}A", 'section' => 'Piso 7 - Hospitalización - Llamados', 'type' => 'llamados']);
            Room::create(['room_id' => "7{$n}B", 'name' => "Hab 7{$n}B", 'section' => 'Piso 7 - Hospitalización - Llamados', 'type' => 'llamados']);
        }
        for ($i = 1; $i <= 21; $i++) {
            $n = str_pad($i, 2, '0', STR_PAD_LEFT);
            Room::create(['room_id' => "B7-7{$n}", 'name' => "Baño 7{$n}", 'section' => 'Piso 7 - Hospitalización - Baños', 'type' => 'baños']);
        }

        // Piso 7 - Admisiones
        Room::create(['room_id' => '7-ADM', 'name' => 'Admisiones', 'section' => 'Piso 7 - Admisiones - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '7-SP', 'name' => 'Sala de Procedimientos', 'section' => 'Piso 7 - Admisiones - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '7-ENF', 'name' => 'Estación Enfermería', 'section' => 'Piso 7 - Admisiones - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B7-SEH', 'name' => 'Baño Admisiones Hombre', 'section' => 'Piso 7 - Admisiones - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B7-SEM', 'name' => 'Baño Admisiones Mujer', 'section' => 'Piso 7 - Admisiones - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 6 - CIRUGÍA
        // ═══════════════════════════════════════

        Room::create(['room_id' => '6-ADM', 'name' => 'Admisiones', 'section' => 'Piso 6 - Cirugía - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '6-PQ', 'name' => 'Preparación Quirúrgica', 'section' => 'Piso 6 - Cirugía - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '6-ENF', 'name' => 'Estación Enfermería', 'section' => 'Piso 6 - Cirugía - Llamados', 'type' => 'llamados']);
        for ($i = 1; $i <= 10; $i++) {
            Room::create(['room_id' => "6-RC{$i}", 'name' => "Camilla {$i}", 'section' => 'Piso 6 - Cirugía - Llamados', 'type' => 'llamados']);
        }
        for ($i = 1; $i <= 3; $i++) {
            Room::create(['room_id' => "6-RT{$i}", 'name' => "Tardía {$i}", 'section' => 'Piso 6 - Cirugía - Llamados', 'type' => 'llamados']);
        }
        Room::create(['room_id' => '6-PQN', 'name' => 'Pasillo Quirófanos', 'section' => 'Piso 6 - Cirugía - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B6-VH', 'name' => 'Baño Vestier Hombre', 'section' => 'Piso 6 - Cirugía - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B6-VM1', 'name' => 'Baño Vestier Mujer 1', 'section' => 'Piso 6 - Cirugía - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B6-VM2', 'name' => 'Baño Vestier Mujer 2', 'section' => 'Piso 6 - Cirugía - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B6-MR1', 'name' => 'Baño Mixto 1 Recuperación', 'section' => 'Piso 6 - Cirugía - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B6-MR2', 'name' => 'Baño Mixto 2 Recuperación', 'section' => 'Piso 6 - Cirugía - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 5 - UCI
        // ═══════════════════════════════════════

        for ($i = 1; $i <= 18; $i++) {
            Room::create(['room_id' => "UCI-{$i}", 'name' => "Cubiculo {$i}", 'section' => 'Piso 5 - UCI - Llamados', 'type' => 'llamados']);
        }
        Room::create(['room_id' => 'UCI-ENF', 'name' => 'Estación Enfermería', 'section' => 'Piso 5 - UCI - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B-UCI', 'name' => 'Baño Mixto', 'section' => 'Piso 5 - UCI - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 5 - UCIN
        // ═══════════════════════════════════════

        for ($i = 1; $i <= 14; $i++) {
            Room::create(['room_id' => "UCIN-{$i}", 'name' => "Cubiculo {$i}", 'section' => 'Piso 5 - UCIN - Llamados', 'type' => 'llamados']);
        }
        Room::create(['room_id' => 'UCIN-ENF1', 'name' => 'Estación Enfermería 1', 'section' => 'Piso 5 - UCIN - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'UCIN-ENF2', 'name' => 'Estación Enfermería 2', 'section' => 'Piso 5 - UCIN - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B-UCIN', 'name' => 'Baño Mixto', 'section' => 'Piso 5 - UCIN - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 5 - ADMISIONES
        // ═══════════════════════════════════════

        Room::create(['room_id' => '5-ADM', 'name' => 'Admisiones', 'section' => 'Piso 5 - Admisiones - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B5-ADMH', 'name' => 'Baño Admisiones Hombre', 'section' => 'Piso 5 - Admisiones - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B5-ADMM', 'name' => 'Baño Admisiones Mujer', 'section' => 'Piso 5 - Admisiones - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 2 - HOSPITAL DÍA
        // ═══════════════════════════════════════

        Room::create(['room_id' => '2-ENFA', 'name' => 'Estación Enfermería A', 'section' => 'Piso 2 - Hospital Día - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '2-ENFB', 'name' => 'Estación Enfermería B', 'section' => 'Piso 2 - Hospital Día - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '2-SPA', 'name' => 'Sala de Procedimientos', 'section' => 'Piso 2 - Hospital Día - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => '2-SPB', 'name' => 'Sala de Procedimientos 2', 'section' => 'Piso 2 - Hospital Día - Llamados', 'type' => 'llamados']);
        for ($i = 1; $i <= 35; $i++) {
            Room::create(['room_id' => "2-{$i}", 'name' => "Cubiculo {$i}", 'section' => 'Piso 2 - Hospital Día - Llamados', 'type' => 'llamados']);
        }
        Room::create(['room_id' => 'B2-SEH', 'name' => 'Baño Sala Espera Hombre', 'section' => 'Piso 2 - Hospital Día - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B2-SEM', 'name' => 'Baño Sala Espera Mujer', 'section' => 'Piso 2 - Hospital Día - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B2-IH', 'name' => 'Baño Interno Hombre', 'section' => 'Piso 2 - Hospital Día - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B2-IM', 'name' => 'Baño Interno Mujer', 'section' => 'Piso 2 - Hospital Día - Baños', 'type' => 'baños']);

        // ═══════════════════════════════════════
        // PISO 1 - IMÁGENES
        // ═══════════════════════════════════════

        Room::create(['room_id' => '1-RE', 'name' => 'Recuperación', 'section' => 'Piso 1 - Imágenes - Llamados', 'type' => 'llamados']);
        Room::create(['room_id' => 'B1-ECO1', 'name' => 'Baño Sala Ecografia 1', 'section' => 'Piso 1 - Imágenes - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B1-ECO2', 'name' => 'Baño Sala Ecografia 2', 'section' => 'Piso 1 - Imágenes - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B1-ADMH1', 'name' => 'Baño Admisiones Hombre 1', 'section' => 'Piso 1 - Imágenes - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B1-ADMH2', 'name' => 'Baño Admisiones Hombre 2', 'section' => 'Piso 1 - Imágenes - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B1-ADMM1', 'name' => 'Baño Admisiones Mujer 1', 'section' => 'Piso 1 - Imágenes - Baños', 'type' => 'baños']);
        Room::create(['room_id' => 'B1-ADMM2', 'name' => 'Baño Admisiones Mujer 2', 'section' => 'Piso 1 - Imágenes - Baños', 'type' => 'baños']);
    }
}
