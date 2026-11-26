<?php

namespace App\Livewire\Users;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserImportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Import modal on the users page: download the template, upload it, check the preview, then import.
 */
class UserImport extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

    public $file = null;

    /** Changing it re-creates the file input, which is the only way to clear a chosen file. */
    public int $inputKey = 0;

    /** Locked: what gets imported is what the server read, not what the browser sends back. */
    #[Locked]
    public array $rows = [];

    #[Locked]
    public string $fileError = '';

    /** Shown above the preview. */
    #[Locked]
    public string $fileName = '';

    protected UserRepositoryInterface $userRepository;

    protected RoleRepositoryInterface $roleRepository;

    protected NotificationRepositoryInterface $notificationRepository;

    protected UserImportService $importer;

    public function boot(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository,
        NotificationRepositoryInterface $notificationRepository,
        UserImportService $importer
    ): void {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->notificationRepository = $notificationRepository;
        $this->importer = $importer;
    }

    #[On('open-user-import')]
    public function open(): void
    {
        $this->resetImport();
        $this->showModal = true;
    }

    public function resetImport(): void
    {
        $this->reset('file', 'rows', 'fileError', 'fileName');
        $this->resetValidation();
        $this->inputKey++;
    }

    public function downloadTemplate()
    {
        $path = tempnam(sys_get_temp_dir(), 'tpl');
        $this->importer->writeTemplate($path);

        return response()->download($path, 'template-impor-pengguna.xlsx')->deleteFileAfterSend();
    }

    public function updatedFile(): void
    {
        $this->reset('rows', 'fileError', 'fileName');

        $this->validate(
            ['file' => ['required', 'file', 'extensions:xlsx,csv', 'max:2048']],
            [
                'file.extensions' => 'File harus berformat .xlsx atau .csv.',
                'file.max' => 'Ukuran file maksimal 2 MB.',
            ]
        );

        try {
            $this->rows = $this->importer->validate(
                $this->importer->read($this->file->getRealPath(), $this->file->getClientOriginalExtension())
            );
            $this->fileName = $this->file->getClientOriginalName();
        } catch (InvalidArgumentException $e) {
            $this->fileError = $e->getMessage();
        }
    }

    public function import(): void
    {
        if ($this->rows === []) {
            return;
        }

        // Check again: emails or roles may have changed since the preview was built.
        $checked = $this->importer->validate($this->rows);
        $valid = array_values(array_filter($checked, fn ($row) => $row['errors'] === []));

        if ($valid === []) {
            $this->rows = $checked;

            return;
        }

        set_time_limit(120); // each password is hashed separately

        try {
            DB::transaction(function () use ($valid) {
                foreach ($valid as $row) {
                    $this->userRepository->createInvited(
                        ['name' => $row['name'], 'email' => $row['email']],
                        $row['role_ids'],
                        $row['role_ids'][0]
                    );
                }

                $this->notifySuperAdmins(count($valid));
            });
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('notify', text: 'Impor dibatalkan, tidak ada pengguna yang dibuat.', variant: 'danger');

            return;
        }

        $skipped = count($checked) - count($valid);
        $message = count($valid).' pengguna berhasil diimpor.'.($skipped > 0 ? " {$skipped} baris dilewati." : '');

        $this->showModal = false;
        $this->resetImport();
        $this->dispatch('users-imported');
        $this->dispatch('notify', text: $message, variant: 'success');
    }

    public function render()
    {
        $validCount = count(array_filter($this->rows, fn ($row) => $row['errors'] === []));

        return view('livewire.users.import', [
            'validCount' => $validCount,
            'invalidCount' => count($this->rows) - $validCount,
            'maxRows' => UserImportService::MAX_ROWS,
        ]);
    }

    private function notifySuperAdmins(int $count): void
    {
        $superAdmin = $this->roleRepository->findBySlug('super-admin');

        if ($superAdmin) {
            $this->notificationRepository->create([
                'from_role_id' => Auth::user()->role_id,
                'to_role_id' => $superAdmin->id,
                'message' => $count.' pengguna diimpor oleh '.Auth::user()->name.'.',
                'url' => null,
                'id_reference' => null,
                'read' => false,
            ]);
        }
    }
}
