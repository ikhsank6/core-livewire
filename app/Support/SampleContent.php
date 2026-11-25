<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Sample (placeholder) content for the public portal.
 *
 * There is no Skema or Jadwal model yet, so the home summaries and the full /skema and
 * /jadwal pages all read from here. That keeps them in step, and gives one place to swap
 * for real data later. Everything returned is SAMPLE content and the views label it as such.
 */
class SampleContent
{
    /**
     * @return Collection<int, array{name: string, field: string}>
     */
    public static function schemes(): Collection
    {
        return collect(config('sample-schemes.schemes'))->values();
    }

    /**
     * @return array<int, string>
     */
    public static function fields(): array
    {
        return self::schemes()->pluck('field')->unique()->values()->all();
    }

    /**
     * Upcoming sample exam dates, nearest first. Dates are relative to today so the sample never
     * looks stale. TUK = tempat uji kompetensi (test venue).
     *
     * @return Collection<int, array{date: Carbon, skema: string, venue: string, open: bool}>
     */
    public static function schedule(): Collection
    {
        $now = Carbon::now();

        return collect([
            ['date' => $now->copy()->addDays(9),  'skema' => 'Teknisi Jaringan Komputer',           'venue' => 'TUK Gedung A',    'open' => true],
            ['date' => $now->copy()->addDays(16), 'skema' => 'Administrasi Perkantoran',            'venue' => 'TUK Gedung B',    'open' => true],
            ['date' => $now->copy()->addDays(23), 'skema' => 'Pramusaji',                           'venue' => 'TUK Mitra Hotel', 'open' => true],
            ['date' => $now->copy()->addDays(30), 'skema' => 'Pemrogram Junior',                    'venue' => 'TUK Gedung A',    'open' => true],
            ['date' => $now->copy()->addDays(37), 'skema' => 'Juru Gambar Konstruksi',              'venue' => 'TUK Gedung A',    'open' => false],
            ['date' => $now->copy()->addDays(44), 'skema' => 'Asisten Administrasi',                'venue' => 'TUK Gedung B',    'open' => false],
            ['date' => $now->copy()->addDays(51), 'skema' => 'Resepsionis Hotel',                   'venue' => 'TUK Mitra Hotel', 'open' => false],
            ['date' => $now->copy()->addDays(58), 'skema' => 'Pelaksana Lapangan Pekerjaan Gedung', 'venue' => 'TUK Gedung C',    'open' => false],
        ])->sortBy('date')->values();
    }
}
