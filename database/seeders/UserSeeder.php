<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Nguyễn Thị Cẩm Ly',
            'email' => 'lycuyt@gmail.com',
            'student_code' => 'B21DCCN506',
            'birthday' => '2003-05-30',
            'address' => 'Hà Nam',
            'image' => 'anhCV.jpg',
            'phone_number' => '0385937348',
            'password' => bcrypt('123456')
        ]);
    }
}
