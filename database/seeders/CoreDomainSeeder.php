<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServicePricing;
use App\Models\VehicleCategory;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoreDomainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Workspaces (Bahías de trabajo)
        $workspaces = ['Bay 1', 'Bay 2', 'Mobile Unit'];
        foreach ($workspaces as $workspace) {
            Workspace::create(['name' => $workspace]);
        }

        // 2. Crear Categorías de Vehículos
        $categories = [
            ['name' => 'Coupe / Compact', 'slug' => 'coupe', 'icon' => 'directions_car'],
            ['name' => 'Sedan', 'slug' => 'sedan', 'icon' => 'directions_car'],
            ['name' => 'Small SUV / Crossover', 'slug' => 'small-suv', 'icon' => 'airport_shuttle'],
            ['name' => 'Large SUV / Minivan', 'slug' => 'large-suv', 'icon' => 'airport_shuttle'],
            ['name' => 'Truck', 'slug' => 'truck', 'icon' => 'local_shipping'],
            ['name' => 'Boat / Marine', 'slug' => 'boat', 'icon' => 'sailing'], // Agregado para tu requerimiento
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = VehicleCategory::create($cat);
        }

        // 3. Opciones de Configuración JSON para el Frontend
        $tintOptions = [
            'opacity' => [
                'label' => 'Nivel de Opacidad',
                'type' => 'select',
                'choices' => ['5% (Limo)', '15% (Oscuro)', '20% (Fábrica)', '35% (Medio)', '50% (Claro)', '70% (Casi Transparente)']
            ]
        ];

        $wrapOptions = [
            'finish' => [
                'label' => 'Tipo de Acabado',
                'type' => 'radio',
                'choices' => ['Gloss (Brillante)', 'Matte (Mate)', 'Satin (Satinado)', 'Color Shift']
            ]
        ];

        // 4. Crear Servicios
        $services = [
            [
                'name' => 'Ceramic Window Tint - Full Car',
                'slug' => 'ceramic-tint-full',
                'description' => 'Polarizado cerámico de alta calidad. Rechaza hasta 90% de calor.',
                'requires_quote' => false,
                'configurable_options' => $tintOptions,
                'sort_order' => 1,
            ],
            [
                'name' => 'Full Color Change Wrap',
                'slug' => 'full-color-wrap',
                'description' => 'Cambio de color completo con vinil premium (3M/Avery).',
                'requires_quote' => false,
                'configurable_options' => $wrapOptions,
                'sort_order' => 2,
            ],
            [
                'name' => 'Custom Commercial Fleet Wrap',
                'slug' => 'commercial-wrap',
                'description' => 'Diseño e instalación de rotulado comercial. Requiere medidas exactas.',
                'requires_quote' => true, // Requiere cotización, sin precio fijo
                'configurable_options' => null,
                'sort_order' => 3,
            ],
            [
                'name' => 'Marine Boat Wrap',
                'slug' => 'marine-wrap',
                'description' => 'Rotulado para botes. Sujeto a inspección y medidas de eslora.',
                'requires_quote' => true, // Botes siempre bajo cotización
                'configurable_options' => null,
                'sort_order' => 4,
            ]
        ];

        $serviceModels = [];
        foreach ($services as $srv) {
            $serviceModels[$srv['slug']] = Service::create($srv);
        }

        // 5. Crear Tabla de Precios Dinámicos (Pricing Matrix)
        $pricingData = [
            // --- Precios para Polarizado Cerámico (Precios Fijos) ---
            [
                'service' => 'ceramic-tint-full', 'category' => 'coupe',
                'price' => 250.00, 'duration_minutes' => 120
            ],
            [
                'service' => 'ceramic-tint-full', 'category' => 'sedan',
                'price' => 280.00, 'duration_minutes' => 150
            ],
            [
                'service' => 'ceramic-tint-full', 'category' => 'large-suv',
                'price' => 350.00, 'duration_minutes' => 180
            ],
            // --- Precios para Full Wrap (Precios Fijos pero muy costosos/largos) ---
            [
                'service' => 'full-color-wrap', 'category' => 'sedan',
                'price' => 2800.00, 'duration_minutes' => 2880 // 48 horas de trabajo
            ],
            [
                'service' => 'full-color-wrap', 'category' => 'truck',
                'price' => 3500.00, 'duration_minutes' => 4320 // 72 horas
            ],

            // --- Servicios BAJO COTIZACIÓN (Precio nulo, solo se estima tiempo si se desea) ---
            [
                'service' => 'commercial-wrap', 'category' => 'large-suv',
                'price' => null, 'duration_minutes' => 1440
            ],
            [
                'service' => 'marine-wrap', 'category' => 'boat',
                'price' => null, 'duration_minutes' => 2880
            ],
        ];

        foreach ($pricingData as $pd) {
            ServicePricing::create([
                'service_id' => $serviceModels[$pd['service']]->id,
                'vehicle_category_id' => $categoryModels[$pd['category']]->id,
                'price' => $pd['price'],
                'duration_minutes' => $pd['duration_minutes'],
            ]);
        }
    }
}
