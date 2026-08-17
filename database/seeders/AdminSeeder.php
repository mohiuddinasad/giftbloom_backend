<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Age theke Super Admin role thakle use korবে, na thakle notun banabe
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // Notun Super Admin user toiri koro (age theke thakle update kore dibে)
        $admin = User::updateOrCreate(
            ['email' => 'mohiuddinasad46@gmail.com'], // ei email diye khuje dekhবে already ache kina
            [
                'name' => 'Mohiuddin Asad',
                'password' => Hash::make('Asad6251'), // production e shohoje guess kora jay emon password diyo na
                'email_verified_at' => now(),
            ]
        );

        // User ke Super Admin role assign koro (age deya thakle abar add hobe na)
        if (! $admin->hasRole('Super Admin')) {
            $admin->assignRole($superAdminRole);
        }

        $this->command->info('Super Admin created: mohiuddinasad46@gmail.com / Asad6251');
    }
}
