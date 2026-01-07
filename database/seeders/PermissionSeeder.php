<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar cache de permissões para evitar erros de duplicidade
        // Nota a diferença: usamos [] (colchetes) e não -> (seta)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Criar Permissões Granulares
        $permissions = [
            // Dashboard
            'access_dashboard',

            // Gestão de Usuários
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Gestão de Perfis (Próprio e de outros)
            'view_profile',
            'edit_own_profile',
            'edit_any_profile',

            // Roles e Permissões
            'manage_roles',
            'manage_permissions',
        ];

        foreach ($permissions as $permission) {
            // firstOrCreate evita duplicatas se rodar o seeder duas vezes
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // 2. Criar Papeis (Roles) e atribuir permissões

        // Super Admin (Tem todas as permissões)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        // Pegamos todas as permissões criadas acima e atribuímos ao Super Admin
        $superAdmin->givePermissionTo(Permission::all());

        // Admin (Exemplo: Não deleta usuários, mas gerencia o resto)
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'access_dashboard',
            'view_users',
            'create_users',
            'edit_users',
            'view_profile',
            'edit_any_profile',
        ]);

        // Usuário Comum (Acesso básico)
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'access_dashboard',
            'view_profile',
            'edit_own_profile',
        ]);

        // 3. Criação de Usuário Administrador Padrão
        $adminEmail = 'admin@corretores.com';
        $adminUser = \App\Models\User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrador do Sistema',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $adminUser->assignRole($superAdmin);

        // Opcional: Atribuir ao primeiro usuário existente também (casos de teste)
        $firstUser = \App\Models\User::first();
        if ($firstUser && $firstUser->id !== $adminUser->id) {
            $firstUser->assignRole($superAdmin);
        }
    }
}
