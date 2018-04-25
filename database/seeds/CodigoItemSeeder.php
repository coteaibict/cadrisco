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
            'name' => 'ENV',
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
            'description' => 'Suspenso',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'REV',
            'description' => 'Revogado',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'AUT',
            'description' => 'Autorizado',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'REC',
            'description' => 'Recusado',
        ]);

        DB::table('item_code')->insert([
            'code_id' => '1',
            'name' => 'ENV',
            'description' => 'Enviado',
        ]);

    }
}
