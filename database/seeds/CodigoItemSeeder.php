<?php

use Illuminate\Database\Seeder;

class CodigoItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Solicitações
        DB::table('code')->insert([
            'description' => 'Documents Status',
            'visible' => false,
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'PEN',
            'description' => 'Pendente de Envio',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'ANA',
            'description' => 'Em Análise',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'SUS',
            'description' => 'Suspensa',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'REV',
            'description' => 'Revogada',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'AUT',
            'description' => 'Autorizada',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'REC',
            'description' => 'Recusada',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'ENV',
            'description' => 'Enviada',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'DEV',
            'description' => 'Devolvida',
        ]);

        DB::table('code')->insert([
            'description' => 'Pretend Role',
            'visible' => false,
        ]);

        DB::table('item_code')->insert([
            'code_id' => '2',
            'name' => 'state',
            'description' => 'Estadual',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '2',
            'name' => 'county',
            'description' => 'Municipal',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '2',
            'name' => 'national',
            'description' => 'Nacional',
        ]);


    }
}
