<?php

namespace App\Livewire\Website;

use App\Models\AboutUs;
use App\Models\Carousel;
use App\Models\News;
use App\Models\NewsCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.landing')]
#[Title('Welcome')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.website.landing-page', [
            'carousels' => Carousel::active()->ordered()->get(),
            'featuredNews' => News::with('category')->active()->published()->featured()->latest('published_at')->limit(3)->get(),
            'latestNews' => News::with('category')->active()->published()->latest('published_at')->limit(6)->get(),
            'categories' => NewsCategory::active()->withCount('news')->get(),
            'aboutUs' => AboutUs::getActive(),
        ]);
    }
}
