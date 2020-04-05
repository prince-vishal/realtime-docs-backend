<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Modules\Docs\Models\Doc;
use Faker\Generator as Faker;

$factory->define(Doc::class, function (Faker $faker) {
    return [
        'title' => $faker->name . " Doc",
        'data' => json_encode($faker->paragraphs)
    ];
});
