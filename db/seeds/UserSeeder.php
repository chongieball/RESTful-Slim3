<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'username'  => $faker->userName,
                'email'     => $faker->email,
                'password'  => password_hash($faker->password, PASSWORD_DEFAULT)
                ];
        }

        $this->insert('users', $data);
    }
}
