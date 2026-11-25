<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WebsiteNewsPaginationTest extends TestCase
{
    use RefreshDatabase;

    /** The page text with runs of whitespace collapsed, so wrapped lines in the view do not matter. */
    private function text(\Illuminate\Testing\TestResponse $response): string
    {
        return preg_replace('/\s+/', ' ', $response->getContent());
    }

    private function category(string $name): NewsCategory
    {
        return NewsCategory::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
        ]);
    }

    private function articles(NewsCategory $category, int $count, string $prefix = 'Berita'): void
    {
        foreach (range(1, $count) as $i) {
            News::create([
                'news_category_id' => $category->id,
                'title' => "$prefix nomor $i",
                'slug' => Str::slug("$prefix nomor $i"),
                'excerpt' => 'Ringkasan.',
                'content' => '<p>Isi.</p>',
                'published_at' => now()->subMinutes($i),
                'is_active' => true,
            ]);
        }
    }

    public function test_twenty_articles_are_split_into_three_pages_of_eight(): void
    {
        $this->articles($this->category('Skema'), 20);

        $first = $this->get(route('news.index'));
        $first->assertOk();
        $first->assertSee('Navigasi halaman', false);
        $this->assertStringContainsString('Menampilkan 1 sampai 8 dari 20 berita', $this->text($first));
        $this->assertSame(8, substr_count($first->getContent(), '<article'));

        $last = $this->get(route('news.index', ['page' => 3]));
        $this->assertStringContainsString('Menampilkan 17 sampai 20 dari 20 berita', $this->text($last));
        $this->assertSame(4, substr_count($last->getContent(), '<article'));
    }

    public function test_the_category_filter_survives_a_page_change(): void
    {
        $this->articles($this->category('Skema'), 12, 'Skema');
        $this->articles($this->category('Asesmen'), 3, 'Asesmen');

        $response = $this->get(route('news.index', ['category' => 'skema']));

        $this->assertStringContainsString('Menampilkan 1 sampai 8 dari 12 berita', $this->text($response));
        $response->assertSee('category=skema&amp;page=2', false);
    }

    public function test_one_page_shows_a_count_but_no_page_controls(): void
    {
        $this->articles($this->category('Skema'), 3);

        $response = $this->get(route('news.index'));

        $response->assertOk();
        $response->assertDontSee('Navigasi halaman', false);
        $response->assertSee('Menampilkan 3 berita');
    }
}
