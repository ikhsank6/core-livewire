<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ErrorCodeLogTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir().'/error-code-test-'.bin2hex(random_bytes(4));
        File::makeDirectory($this->directory);

        // The real channel definitions, only writing somewhere else.
        config([
            'logging.channels.single.path' => $this->directory.'/single.log',
            'logging.channels.daily.path' => $this->directory.'/daily.log',
            'logging.channels.stack.channels' => ['single'],
        ]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->directory);

        parent::tearDown();
    }

    private function written(): string
    {
        return collect(File::files($this->directory))->map(fn ($file) => File::get($file->getPathname()))->implode('');
    }

    private function codes(): array
    {
        preg_match_all('/local\.\w+: \[(ERR-[0-9A-F]{8})\]|testing\.\w+: \[(ERR-[0-9A-F]{8})\]/', $this->written(), $matches);

        return array_values(array_filter(array_merge($matches[1], $matches[2])));
    }

    public function test_every_error_gets_its_own_code_in_every_channel(): void
    {
        foreach (['single', 'daily', 'stack'] as $channel) {
            File::cleanDirectory($this->directory);

            Log::channel($channel)->error('Gagal pertama');
            Log::channel($channel)->critical('Gagal kedua');

            $codes = $this->codes();

            $this->assertCount(2, $codes, "{$channel}: one code per entry");
            $this->assertNotSame($codes[0], $codes[1], $channel);
            $this->assertSame(2, substr_count($this->written(), '[ERR-'), "{$channel}: never prefixed twice");
            $this->assertStringContainsString('"kode_error":"'.$codes[0].'"', $this->written(), $channel);
        }
    }

    public function test_reported_exceptions_carry_a_code(): void
    {
        config(['logging.default' => 'single']);

        report(new \RuntimeException('Meledak'));

        $this->assertCount(1, $this->codes());
        $this->assertStringContainsString('Meledak', $this->written());
    }

    public function test_ordinary_entries_are_left_alone(): void
    {
        Log::channel('single')->info('Catatan biasa');
        Log::channel('single')->warning('Peringatan');

        $this->assertStringNotContainsString('ERR-', $this->written());
    }
}
