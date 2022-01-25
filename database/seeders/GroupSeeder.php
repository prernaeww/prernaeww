<?php

namespace Database\Seeders;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = Group::create(['name' => 'admin' , 'description' => 'Admin']);
        $group = Group::create(['name' => 'canteen' , 'description' => 'Canteen']);
        $group = Group::create(['name' => 'parent' , 'description' => 'Parent']);
        $group = Group::create(['name' => 'student' , 'description' => 'Student']);
        $group = Group::create(['name' => 'employee' , 'description' => 'Employee']);
        $group = Group::create(['name' => 'sub_student' , 'description' => 'Sub Student']);
    }
}
