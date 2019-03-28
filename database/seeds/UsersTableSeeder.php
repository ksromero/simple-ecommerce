<?php

use Illuminate\Database\Seeder;
use App\User;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
	private $users = [
		1,
		2,
		3
	];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 1; $i <= count($this->users); $i++){
        	User::create([
        		'name' => $faker->name,
			    'email' => $faker->unique()->safeEmail,
			    'role_id' => $i,
			    'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
			    'remember_token' => str_random(10),
        	]);
    	}
    }
}
