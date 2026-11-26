<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Opcodes\LogViewer\Facades\LogViewer;

#[Layout('components.layouts.app')]
#[Title('Laravel Logs')]
class LogIndex extends Component
{
    use WithPagination;

    /**
     * Reading a log indexes it in memory; bigger files (an unrotated server log, for instance)
     * exhaust PHP's memory limit and leave a blank page. They can only be downloaded here.
     */
    public const MAX_READABLE_BYTES = 20 * 1024 * 1024;

    #[Url]
    public $selectedFileIdentifier = '';

    #[Url]
    public $search = '';

    public $perPage = 25;

    public function mount()
    {
        if (empty($this->selectedFileIdentifier)) {
            $this->selectDefaultFile();
        }
    }

    /** Start on the most recently written file that can actually be shown. */
    private function selectDefaultFile(): void
    {
        $files = LogViewer::getFiles()->sortByDesc(fn ($file) => $file->mtime())->values();
        $first = $files->first(fn ($file) => $this->isReadable($file)) ?? $files->first();

        $this->selectedFileIdentifier = $first?->identifier ?? '';
    }

    /** Only logs inside the application's own log directory are removed from disk. */
    private function belongsToApplication($file): bool
    {
        $directory = realpath(storage_path('logs'));

        return $directory !== false && str_starts_with((string) realpath($file->path), $directory.DIRECTORY_SEPARATOR);
    }

    private function isReadable($file): bool
    {
        return $file->size() <= self::MAX_READABLE_BYTES;
    }

    public function reload(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedFileIdentifier()
    {
        $this->resetPage();
    }

    /**
     * Delete a log. Application logs are removed; any other log (web server, PHP-FPM, database)
     * is only emptied, because its server process may still be writing to that very file.
     */
    public function delete(string $identifier): void
    {
        abort_unless(Auth::user()?->role?->slug === 'super-admin', 403);

        // Only files the log viewer is configured to list can be resolved, never an arbitrary path.
        $file = LogViewer::getFile($identifier);

        if (! $file) {
            $this->dispatch('notify', text: 'File log tidak ditemukan.', variant: 'danger');

            return;
        }

        $name = $file->name;
        $remove = $this->belongsToApplication($file);

        try {
            if ($remove) {
                $file->delete();
            } else {
                $file->clearCache();
                file_put_contents($file->path, '');
            }
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('notify', text: "{$name} tidak bisa dihapus. Periksa izin file.", variant: 'danger');

            return;
        }

        LogViewer::clearFileCache();
        $this->reset('selectedFileIdentifier', 'search');
        $this->resetPage();

        // After deleting, stay without a selection until render picks the next file.
        $this->dispatch('notify', text: $remove ? "{$name} dihapus." : "Isi {$name} dikosongkan.", variant: 'success');
    }

    public function downloadLog()
    {
        $file = LogViewer::getFile($this->selectedFileIdentifier);
        if ($file) {
            return $file->download();
        }
    }

    public function render()
    {
        $files = LogViewer::getFiles()->sortByDesc(fn ($file) => $file->mtime())->values();
        $selectedFile = LogViewer::getFile($this->selectedFileIdentifier)
            ?? $files->first(fn ($file) => $this->isReadable($file))
            ?? $files->first();

        $deleteIsRemoval = $selectedFile && $this->belongsToApplication($selectedFile);

        $tooLarge = $selectedFile && ! $this->isReadable($selectedFile);

        $logs = null;
        if ($selectedFile && ! $tooLarge) {
            // We need to scan the file if it hasn't been scanned or is dirty
            if ($selectedFile->requiresScan()) {
                $selectedFile->scan();
            }

            $logs = $selectedFile->logs()
                ->reverse() // newest entries first
                ->search($this->search)
                ->paginate($this->perPage);
        }

        return view('livewire.settings.log-index', [
            'files' => $files,
            'logs' => $logs,
            'selectedFile' => $selectedFile,
            'tooLarge' => $tooLarge,
            'deleteIsRemoval' => $deleteIsRemoval,
            'canDelete' => Auth::user()?->role?->slug === 'super-admin',
        ]);
    }
}
