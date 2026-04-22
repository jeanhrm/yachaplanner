<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ugel;

class UgelSeeder extends Seeder
{
    public function run(): void
    {
        $ugels = [
            ['name' => 'UGEL Huancavelica',  'code' => 'HV-01', 'province' => 'Huancavelica',
             'districts' => ['Huancavelica','Ascensión','Vilca','Manta','Mariscal Cáceres','Cuenca','Izcuchaca']],
            ['name' => 'UGEL Angaraes',       'code' => 'HV-02', 'province' => 'Angaraes',
             'districts' => ['Lircay','Anchonga','Callanmarca','Ccochaccasa','Chincho']],
            ['name' => 'UGEL Acobamba',       'code' => 'HV-03', 'province' => 'Acobamba',
             'districts' => ['Acobamba','Andabamba','Anta','Caja','Marcas','Paucara']],
            ['name' => 'UGEL Churcampa',      'code' => 'HV-04', 'province' => 'Churcampa',
             'districts' => ['Churcampa','Chinchihuasi','El Carmen','La Merced','Locroja','Pachamarca']],
            ['name' => 'UGEL Tayacaja',       'code' => 'HV-05', 'province' => 'Tayacaja',
             'districts' => ['Pampas','Acostambo','Acraquia','Ahuaycha','Colcabamba','Daniel Hernández']],
            ['name' => 'UGEL Huaytará',       'code' => 'HV-06', 'province' => 'Huaytará',
             'districts' => ['Huaytará','Ayavi','Córdova','Huayacundo Arma','Laramarca']],
            ['name' => 'UGEL Castrovirreyna', 'code' => 'HV-07', 'province' => 'Castrovirreyna',
             'districts' => ['Castrovirreyna','Arma','Aurahua','Capillas','Chupamarca','Cocas']],
        ];

        foreach ($ugels as $ugel) {
            Ugel::updateOrCreate(
                ['code' => $ugel['code']],
                [
                    'name'      => $ugel['name'],
                    'region'    => 'Huancavelica',
                    'province'  => $ugel['province'],
                    'districts' => $ugel['districts'],
                    'is_active' => true,
                ]
            );
        }
    }
}