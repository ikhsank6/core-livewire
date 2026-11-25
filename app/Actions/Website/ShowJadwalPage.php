<?php

namespace App\Actions\Website;

use App\Support\SampleContent;
use Illuminate\View\View;

class ShowJadwalPage
{
    /**
     * The full exam schedule (sample content for now).
     */
    public function __invoke(): View
    {
        return view('website.jadwal.index', [
            'jadwal' => SampleContent::schedule(),
        ]);
    }
}
