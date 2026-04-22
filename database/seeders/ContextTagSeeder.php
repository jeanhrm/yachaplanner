<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContextTag;

class ContextTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name'=>'Río Ichu',           'type'=>'geography',   'district'=>'Huancavelica', 'is_featured'=>true],
            ['name'=>'Lago Choclococha',    'type'=>'geography',   'district'=>'Castrovirreyna','is_featured'=>true],
            ['name'=>'Pampa Galeras',       'type'=>'geography',   'district'=>'Huancavelica', 'is_featured'=>false],
            ['name'=>'Papa nativa',         'type'=>'agriculture', 'district'=>null,            'is_featured'=>true],
            ['name'=>'Quinua andina',       'type'=>'agriculture', 'district'=>null,            'is_featured'=>true],
            ['name'=>'Maíz morado',         'type'=>'agriculture', 'district'=>null,            'is_featured'=>false],
            ['name'=>'Ganadería de alpaca', 'type'=>'agriculture', 'district'=>null,            'is_featured'=>true],
            ['name'=>'Trucha del río',      'type'=>'agriculture', 'district'=>'Huancavelica',  'is_featured'=>false],
            ['name'=>'Huaylarsh',           'type'=>'culture',     'district'=>null,            'is_featured'=>true],
            ['name'=>'Lengua quechua',      'type'=>'culture',     'district'=>null,            'is_featured'=>true],
            ['name'=>'Carnaval de Lircay',  'type'=>'culture',     'district'=>'Lircay',        'is_featured'=>false],
            ['name'=>'Feria de Acobamba',   'type'=>'culture',     'district'=>'Acobamba',      'is_featured'=>false],
            ['name'=>'Contaminación minera','type'=>'science',     'district'=>null,            'is_featured'=>true],
            ['name'=>'Cambio climático andino','type'=>'science',  'district'=>null,            'is_featured'=>true],
            ['name'=>'Heladas y friaje',    'type'=>'science',     'district'=>null,            'is_featured'=>false],
            ['name'=>'Recursos hídricos',   'type'=>'science',     'district'=>null,            'is_featured'=>true],
            ['name'=>'Zona altoandina',     'type'=>'community',   'district'=>null,            'is_featured'=>true],
            ['name'=>'Comunidad campesina', 'type'=>'community',   'district'=>null,            'is_featured'=>true],
        ];

        foreach ($tags as $tag) {
            ContextTag::updateOrCreate(
                ['name' => $tag['name']],
                array_merge($tag, ['province' => 'Huancavelica'])
            );
        }
    }
}