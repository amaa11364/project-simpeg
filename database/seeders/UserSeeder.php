<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        $admins = [
            ['name' => 'sysadmin',        'email' => 'sysadmin@ikipsiliwangi.ac.id'],
            ['name' => 'adm_kepegawaian', 'email' => 'adm_kepegawaian@ikipsiliwangi.ac.id'],
        ];

        foreach ($admins as $admin) {
            User::create([
                'name'               => $admin['name'],
                'email'              => $admin['email'],
                'role'               => 'superadmin',
                'password'           => Hash::make('Testing1siliwangi!'),
                'email_verified_at'  => now(),
            ]);
        }
    }
}
