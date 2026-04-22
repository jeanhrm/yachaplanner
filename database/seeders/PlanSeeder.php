<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'             => 'Gratuito',
                'slug'             => 'free',
                'target'           => 'user',
                'price_soles'      => 0,
                'billing_cycle'    => 'weekly',
                'weekly_ai_credits'=> 5,
                'teacher_seats'    => 1,
                'export_watermark' => true,
                'library_publish'  => false,
                'sort_order'       => 1,
                'features'         => [
                    '5 generaciones IA por semana',
                    'Todos los módulos disponibles',
                    'Exportar Word (con marca de agua)',
                    'Biblioteca (solo lectura)',
                ],
            ],
            [
                'name'             => 'Pro Docente',
                'slug'             => 'pro',
                'target'           => 'user',
                'price_soles'      => 2500,
                'billing_cycle'    => 'monthly',
                'weekly_ai_credits'=> 999,
                'teacher_seats'    => 1,
                'export_watermark' => false,
                'library_publish'  => true,
                'sort_order'       => 2,
                'features'         => [
                    'Generaciones ilimitadas',
                    'Word sin marca de agua',
                    'Historial completo',
                    'Publicar en biblioteca',
                    'Soporte prioritario',
                ],
            ],
            [
                'name'             => 'Institución',
                'slug'             => 'institution',
                'target'           => 'institution',
                'price_soles'      => 35000,
                'billing_cycle'    => 'yearly',
                'weekly_ai_credits'=> 999,
                'teacher_seats'    => 30,
                'export_watermark' => false,
                'library_publish'  => true,
                'sort_order'       => 3,
                'features'         => [
                    'Hasta 30 docentes Pro',
                    'Panel del director',
                    'Contexto personalizado IE',
                    'Plantillas institucionales',
                    'Factura para UGEL',
                    'Soporte dedicado',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}