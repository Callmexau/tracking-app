<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Controle Interne']);
        Role::firstOrCreate(['name' => 'OPS']);
        Role::firstOrCreate(['name' => 'CCB']);
    }
}