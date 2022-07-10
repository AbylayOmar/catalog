<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names_1 = [
            'Программирование',
            'Фильмы',
            'Музыка',
        ];

        $names_2 = [
            'Игры',
            'Спорт',
            'Книги',
        ];

        $names_3 = [
            'Фан-арт',
            'Кулинария',
            'Искусство',
        ];

        foreach ($names_1 as $name) {
            Category::create([
                'name' => $name,
            ]);
        }

        foreach ($names_2 as $name) {
            Category::create([
                'name' => $name,
                'parent_id' => random_int(1, 3),
            ]);
        }

        foreach ($names_3 as $name) {
            Category::create([
                'name' => $name,
                'parent_id' => random_int(4, 6),
            ]);
        }
    }
}
