<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use App\Models\Roles;
use App\Models\Transaction;
use App\Models\UserDetails;
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

        $user1 = User::create([
            'email' => 'kenny@mail.com',
            'password' => Hash::make('admin1234'),
            'role_id' => $role1['role_id'],
        ])->getAttributes();

        UserDetails::create([
            'user_id' => $user1['user_id'],
            'name' => 'kenny',
            'jk' => 'Laki-laki',
            'tgl_lahir' => '1997-01-01',
            'no_hp' => '081247569523',
            'pekerjaan' => 'PNS',
            'alamat' => 'Lasiana',
        ]);

        $user2 = User::create([
            'email' => 'robert@mail.com',
            'password' => Hash::make('admin1234'),
            'role_id' => $role2['role_id'],
        ])->getAttributes();

        UserDetails::create([
            'user_id' => $user2['user_id'],
            'name' => 'robert',
            'jk' => 'Laki-laki',
            'tgl_lahir' => '1998-01-01',
            'no_hp' => '081247569521',
            'pekerjaan' => 'PNS',
            'alamat' => 'Penfui',
        ]);

        $user3 = User::create([
            'email' => 'pengelola.sampah@mail.com',
            'password' => Hash::make('admin1234'),
            'role_id' => $role3['role_id'],
        ])->getAttributes();

        UserDetails::create([
            'user_id' => $user3['user_id'],
            'name' => 'PT. Pengelola Sampah',
            'jk' => '',
            'tgl_lahir' => '2000-01-01',
            'no_hp' => '081247569522',
            'pekerjaan' => '',
            'alamat' => 'Oesapa',
        ]);

        for ($i=1; $i <= 4 ; $i++) { 
            News::create([
                'judul' => "$i Lorem, ipsum dolor sit amet consectetur adipisicing elit.",
                'isi' => "$i Ad atque voluptatibus enim harum placeat exercitationem suscipit ipsum tempora doloribus porro earum, laborum quibusdam debitis, accusantium quas numquam nobis incidunt quo.",
                'foto' => "/unggah/news/user-$i.jpg",
                'tanggal' => "202$i-01-01",
                'keterangan' => "ket $i",
            ]);
        }

        for ($i=1; $i <= 4 ; $i++) { 
            Transaction::create([
                'user_id' => $user1['user_id'],
                'pengepul_id' => $user2['user_id'],
                'penampung_id' => $user3['user_id'],
                'judul' => "Transaksi $i",
                'deskripsi' => "Deskripsi transaksi $i",
                'status' => $i % 2 == 0 ? "Selesai" : "Proses",
                'keterangan' => "ket $i",
                "foto" => "/unggah/news/user-$i.jpg"
            ]);
        }
    }
}
