<?php

use App\Models\{Permission,User,Role};
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $user = User::create([
            'name' => 'Admin EDMS',
            'username' => 'adminedms',
            'phone' => '97215188',
            'password' => 'admin'
        ]);
        $permissions = Permission::all();
        $roles = Role::all();
        $user->permissions()->sync($permissions);
        $user->roles()->sync($roles);
       
    }
}
