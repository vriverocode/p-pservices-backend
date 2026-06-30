<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title' => 'Polarizado Cerámico Premium',
                'description' => 'Protege tu vehículo con el mejor polarizado cerámico. Rechaza hasta 90% del calor y radiación UV.',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 1,
                'display_order' => 1,
                'bg_color' => '#0a2540',
                'text_color' => '#ffffff',
            ],
            [
                'title' => 'Rotulado Comercial Fleet',
                'description' => 'Diseñamos e instalamos rotulado para flotas empresariales. Haz que tu marca se vea en todas partes.',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 1,
                'display_order' => 2,
                'bg_color' => '#162438',
                'text_color' => '#ffffff',
            ],
            [
                'title' => 'Envoltura de Color Completo',
                'description' => 'Cambia el color de tu auto con vinil premium 3M o Avery. Gloss, Matte o Satin.',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 1,
                'display_order' => 3,
                'bg_color' => '#005bc4',
                'text_color' => '#ffffff',
            ],
            [
                'title' => 'Oferta de Verano 2x1',
                'description' => 'Lleva polarizado en las ventanas delanteras y el trasero gratis. ¡Solo por tiempo limitado!',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 2,
                'display_order' => 4,
                'bg_color' => '#eb0606',
                'text_color' => '#ffffff',
            ],
            [
                'title' => 'Protección UV Total',
                'description' => 'Bloquea el 99% de los rayos UV con nuestro polarizado de grado médico. Cuida tu piel y el interior de tu auto.',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 1,
                'display_order' => 5,
                'bg_color' => '#1d1d1d',
                'text_color' => '#ffffff',
            ],
            [
                'title' => 'Refacción de Vinil',
                'description' => '¿Tu rotulado o envoltura se despegó? Lo reparamos rápido y con la misma calidad original.',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 2,
                'display_order' => 6,
                'bg_color' => '#53637a',
                'text_color' => '#ffffff',
            ],
            [
                'title' => 'Instalación Express',
                'description' => '¿Sin tiempo? Instalamos tu polarizado en menos de 2 horas. Agenda tu cita ahora.',
                'image_path' => null,
                'target_url' => null,
                'is_active' => true,
                'type' => 1,
                'display_order' => 7,
                'bg_color' => '#9d0a0e',
                'text_color' => '#ffffff',
            ],
        ];

        foreach ($ads as $ad) {
            Ad::create($ad);
        }
    }
}
