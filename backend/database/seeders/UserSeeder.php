<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    private $username = 'Skvadmin';

    private $email = 'tibor.aranytoth@skvad.com';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', env('ADMIN_USERNAME', $this->username))->first();

        if (empty($user)) {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $role1 = Role::findOrCreate('skvadmin');

            $user = User::factory()->create([
                'name' => env('ADMIN_USERNAME', $this->username),
                'email' => env('ADMIN_EMAIL', $this->email),
                // user létrehozása után első dolgod a password reset legyen
            ]);
            $user->assignRole($role1);
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);
        }

        $roles = [
            'webshop szerkesztő',
            'webshop ügyvitel',
            'webshop statisztika',
            'shop eladó',
            'kiadó',
            'szerző',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}
