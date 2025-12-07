<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Category;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::first();
        $category = Category::first();

        if ($event && $category) {
            $event->categories()->attach($category->id);
        }
    }
}
