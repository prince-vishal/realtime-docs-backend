<?php

use App\Models\User;
use App\Modules\Docs\Models\Doc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(User::class, 10)->create()->each(function ($user) {
            $user->docs()->save(factory(Doc::class)->make());

        });
    }
}
