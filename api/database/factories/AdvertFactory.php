<?php

namespace Database\Factories;

use App\Models\Advert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected string $model = Advert::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imgUrls = [
            'https://images.unsplash.com/photo-1619302947114-e1a8bfc365ae?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80',
            'https://images.unsplash.com/photo-1619350447432-c7f01f6afc01?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80',
            'https://images.unsplash.com/photo-1619361368122-8b35057d44b9?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1652&q=80'
        ];

        return [
            'title'         => $this->faker->sentence,
            'description'   => $this->faker->paragraphs(2, true),
            'images_links'  => $imgUrls,
            'price'         => $this->faker->numberBetween(1000, 20000),
            'added_by'      => 1
        ];
    }
}
