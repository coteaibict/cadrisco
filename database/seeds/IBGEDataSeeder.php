<?php

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\State;
use App\Models\Mesoregion;
use App\Models\Microregion;
use App\Models\County;

class IBGEDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region_url = 'https://servicodados.ibge.gov.br/api/v1/localidades/regioes';
        $region_json = json_decode(file_get_contents($region_url), true);

        foreach ($region_json as $j){
            $region = Region::create([
                'id' => $j['id'],
                'name' => $j['nome'],
                'initials' => $j['sigla']
            ]);

            $state_url = "https://servicodados.ibge.gov.br/api/v1/localidades/regioes/{$j['id']}/estados";
            $state_json = json_decode(file_get_contents($state_url), true);

            foreach ($state_json as $s){
                $state = State::create([
                    'id' => $s['id'],
                    'name' => $s['nome'],
                    'initials' => $s['sigla'],
                    'region' => $j['id']
                ]);

                $meso_url = "http://servicodados.ibge.gov.br/api/v1/localidades/estados/{$s['id']}/mesorregioes";
                $meso_json = json_decode(file_get_contents($meso_url), true);

                foreach ($meso_json as $mr){
                    $meso = Mesoregion::create([
                        'id' => $mr['id'],
                        'name' => $mr['nome'],
                        'state' => $s['id'],
                    ]);

                    $micro_url = "https://servicodados.ibge.gov.br/api/v1/localidades/mesorregioes/{$mr['id']}/microrregioes";
                    $micro_json = json_decode(file_get_contents($micro_url), true);

                    foreach ($micro_json as $mi){
                        $micro = Microregion::create([
                            'id' => $mi['id'],
                            'name' => $mi['nome'],
                            'mesoregion' => $mr['id'],
                        ]);

                        $county_url = "https://servicodados.ibge.gov.br/api/v1/localidades/microrregioes/{$mi['id']}/municipios";
                        $county_json = json_decode(file_get_contents($county_url), true);

                        foreach ($county_json as $c){
                            $county = County::create([
                                'id' => $c['id'],
                                'name' => $c['nome'],
                                'microregion' => $mi['id'],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
