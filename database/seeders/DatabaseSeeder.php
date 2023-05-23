<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $role1 = Roles::create([
            'role' => 'masyarakat',
            'deskripsi' => 'role untuk masyarakat',
        ])->getAttributes();

        $role2 = Roles::create([
            'role' => 'pengepul',
            'deskripsi' => 'role untuk pengepul',
        ])->getAttributes();

        $role3 = Roles::create([
            'role' => 'penampung',
            'deskripsi' => 'role untuk penampung',
        ])->getAttributes();

        User::create([
            'name' => 'kenny',
            'email' => 'kenny@mail.com',
            'hp' => '081247569523',
            'password' => Hash::make('admin1234'),
            'role_id' => $role1['role_id'],
        ])->getAttributes();

        User::create([
            'name' => 'robert',
            'email' => 'robert@mail.com',
            'hp' => '081247569521',
            'password' => Hash::make('admin1234'),
            'role_id' => $role2['role_id'],
        ])->getAttributes();

        User::create([
            'name' => 'PT. Pengelola Sampah',
            'email' => 'pengelola.sampah@mail.com',
            'hp' => '081247569522',
            'password' => Hash::make('admin1234'),
            'role_id' => $role3['role_id'],
        ])->getAttributes();

    }
}
