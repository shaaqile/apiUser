<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i <= 5; $i++) {
            $data = [
                'username' => $faker->firstName,
                'password'    => md5('12345'),
                'fullname' => $faker->name
            ];
            $this->db->table('users')->insert($data);
        }
    }
}
