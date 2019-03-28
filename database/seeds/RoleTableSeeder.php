<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
	private $roles = [
		'admin',
		'employee',
		'customer'
	];
 
     public function run()
     {
     	foreach($this->roles as $role){
	         Role::create([
	         	'role_name' => $role
	         ]);
     	}
     }
}
