<?php

namespace Tests\Feature;

use App\Support\SampleContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteSchemeScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_scheme_page_lists_every_sample_scheme(): void
    {
        $response = $this->get(route('skema.index'));

        $response->assertOk();
        $response->assertSee('Skema sertifikasi');

        foreach (SampleContent::schemes() as $scheme) {
            $response->assertSee($scheme['name']);
        }
    }

    public function test_the_schedule_page_lists_every_sample_date(): void
    {
        $response = $this->get(route('jadwal.index'));

        $response->assertOk();
        $response->assertSee('Jadwal uji kompetensi');

        foreach (SampleContent::schedule() as $row) {
            $response->assertSee($row['skema']);
        }
    }

    public function test_the_home_page_shows_a_summary_that_links_to_the_full_pages(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('skema.index'), false);
        $response->assertSee(route('jadwal.index'), false);
        $response->assertSee('Lihat semua '.SampleContent::schemes()->count().' skema');
        $response->assertSee('Lihat semua '.SampleContent::schedule()->count().' jadwal');
    }

    public function test_the_home_page_previews_only_five_dates(): void
    {
        $dates = SampleContent::schedule();
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString($dates[4]['skema'], $html);
        $this->assertStringNotContainsString('datetime="'.$dates[5]['date']->toDateString().'"', $html);
    }

    public function test_schedule_months_are_written_in_indonesian(): void
    {
        $html = $this->get(route('jadwal.index'))->getContent();

        // Only the abbreviations that differ from Indonesian (Nov, Jan, Feb, Mar, Apr, Jun, Jul and Sep are the same).
        $this->assertDoesNotMatchRegularExpression('/\b\d{2} (Dec|May|Aug|Oct) \d{4}\b/', $html);
    }
}
