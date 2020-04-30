<?php

use App\Enums\AccessType;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Creoshift',
            'email' => 'admin@creoshift.com',
            'email_verified_at' => time(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'access_type' => AccessType::ADMIN,
        ]);
        DB::table('users')->insert([
            'first_name' => 'User',
            'last_name' => 'Creoshift',
            'email' => 'user@creoshift.com',
            'email_verified_at' => time(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'access_type' => AccessType::USER,
        ]);

        $user = User::find(1);
        $user->assignRole('super-admin');
    }
}
