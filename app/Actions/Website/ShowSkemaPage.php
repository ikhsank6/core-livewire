<?php

namespace App\Actions\Website;

use App\Support\SampleContent;
use Illuminate\View\View;

class ShowSkemaPage
{
    /**
     * The full list of certification schemes (sample content for now).
     */
    public function __invoke(): View
    {
        return view('website.skema.index', [
            'skema' => SampleContent::schemes(),
        ]);
    }
}
