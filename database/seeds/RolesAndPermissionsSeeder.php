<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // acesso aos modulos
        Permission::create(['name' => 'acesso_mod_administracao', 'description' => 'Administração - Acesso ao Módulo Administração']);

        //acesso aos menus
        Permission::create(['name' => 'acesso_cad_usuario', 'description' => 'Administração - Acesso ao Cadastro de Usuário']);

        //permissão usuários
        Permission::create(['name' => 'inserir_usuario', 'description' => 'Administração - Inserir Usuários']);
        Permission::create(['name' => 'alterar_usuario', 'description' => 'Administração - Alterar Usuário']);
        Permission::create(['name' => 'deletar_usuario', 'description' => 'Administração - Deletar Usuário']);

        $national = Role::create(['name' => 'national', 'description' => 'Usuário Nacional']);
        $state = Role::create(['name' => 'state', 'description' => 'Usuário Estadual']);
        $county = Role::create(['name' => 'county', 'description' => 'Usuário Municipal']);

        // create roles and assign existing permissions
        $sadmin = Role::create(['name' => 'sadmin', 'description' => 'Super Administrador']);
        $sadmin->givePermissionTo('acesso_mod_administracao');
        $sadmin->givePermissionTo('acesso_cad_usuario');
        $sadmin->givePermissionTo('inserir_usuario');
        $sadmin->givePermissionTo('alterar_usuario');
        $sadmin->givePermissionTo('deletar_usuario');



    }
}
