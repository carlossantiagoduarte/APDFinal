<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class UserInterestSeeder extends Seeder
{
    public function run()
    {
        $user      = User::where('email','estudiante@example.com')->first();
        $category  = Category::first();

        if ($user && $category) {
            $user->interests()->attach($category->id);
        }
    }
}
