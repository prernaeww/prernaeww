<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\UsersGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$user = User::create([
			'first_name' => 'Admin',
            'last_name' => 'Canteeny',
			'email' => 'admin@admin.com',
			'email_verified_at' => now(),
			'password' => Hash::make('12345678'),
		]);

		UsersGroup::create([
            'user_id' => $user->id,
            'group_id' => 1,
        ]);

	}
}
