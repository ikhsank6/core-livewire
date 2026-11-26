{{-- Action icons follow components/ui/button: flux:icon, variant mini, w-4 h-4. --}}
@php
    $secondary = 'inline-flex h-10 items-center justify-center gap-1.5 rounded-full border border-zinc-200 px-5 text-sm font-bold text-zinc-700 transition-all hover:bg-zinc-100 active:scale-95 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800 cursor-pointer';
    $card = 'rounded-xl border border-zinc-200 dark:border-zinc-700';
    // The admin stylesheet forces 1rem on every table cell; these important utilities keep the preview compact.
    $cell = 'px-3! py-2.5! align-top';
    $head = 'px-3! py-2.5! text-left whitespace-nowrap';
@endphp

<div>
    <x-ui.modal wire:model="showModal" title="Impor Pengguna" maxWidth="4xl" :crudEvents="false">

        @if($rows === [])
            {{-- Before a file is chosen: the two steps side by side --}}
            <div class="grid gap-4 sm:grid-cols-2">

                <section class="{{ $card }} flex flex-col p-5">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-blue-600/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400" aria-hidden="true">
                            <flux:icon name="document-arrow-down" variant="mini" class="w-5 h-5" />
                        </span>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white">1. Isi template</h4>
                    </div>

                    <dl class="mt-4 space-y-2 text-sm">
                        @foreach([
                            'nama' => 'Wajib',
                            'email' => 'Wajib, belum terdaftar',
                            'role' => 'Wajib, pisahkan dengan koma',
                        ] as $column => $hint)
                            <div class="flex items-baseline gap-3">
                                <dt class="w-14 shrink-0 font-mono text-xs font-semibold text-zinc-900 dark:text-white">{{ $column }}</dt>
                                <dd class="text-zinc-500 dark:text-zinc-400">{{ $hint }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <p class="mt-4 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">
                        Template sudah berisi contoh pengisian dan petunjuk. Baris contoh tidak ikut diimpor.
                        Akun langsung aktif; kata sandi sementara dikirim lewat email verifikasi.
                    </p>

                    <div class="mt-auto pt-5">
                        <button type="button" wire:click="downloadTemplate"
                                class="inline-flex h-10 w-full cursor-pointer items-center justify-center gap-1.5 rounded-full bg-orange-500 px-5 text-sm font-bold text-zinc-950 shadow-xs transition-all hover:bg-orange-400 active:scale-95 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500">
                            <flux:icon name="arrow-down-tray" variant="mini" class="w-4 h-4" />
                            Unduh template
                        </button>
                    </div>
                </section>

                <section class="flex flex-col">
                    {{-- The file input covers the whole box, so clicking and dropping both work without scripts --}}
                    <label class="relative flex min-h-64 flex-1 cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 p-5 text-center transition-colors hover:border-blue-500 hover:bg-blue-50/50 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/40 dark:border-zinc-600 dark:hover:bg-blue-950/20">
                        <span class="grid h-12 w-12 place-items-center rounded-full bg-blue-600/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400" aria-hidden="true">
                            <flux:icon name="arrow-up-tray" variant="mini" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">2. Unggah file</span>
                        <span class="text-sm text-zinc-600 dark:text-zinc-300">Klik atau seret file ke sini</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">.xlsx atau .csv, maks. 2 MB dan {{ $maxRows }} baris</span>
                        <span wire:loading wire:target="file" class="text-sm font-semibold text-blue-600 dark:text-blue-400" role="status">Membaca file...</span>
                        <input type="file" wire:model="file" wire:key="user-import-file-{{ $inputKey }}" accept=".xlsx,.csv"
                               class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                    </label>
                </section>
            </div>

            @if($errors->has('file') || $fileError !== '')
                <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/30 dark:text-red-300" role="alert">
                    <flux:icon name="exclamation-circle" variant="mini" class="w-4 h-4 mt-0.5 shrink-0" />
                    <p>{{ $errors->first('file') ?: $fileError }}</p>
                </div>
            @else
                <p class="mt-4 text-xs text-zinc-500 dark:text-zinc-400">Setelah diunggah Anda melihat pratinjau dulu. Belum ada yang disimpan sebelum Anda menekan Impor.</p>
            @endif

        @else
            {{-- Preview --}}
            <div class="space-y-4" x-data="{ onlyProblems: false }" wire:key="user-import-preview-{{ $inputKey }}">

                <div class="{{ $card }} flex items-center gap-3 px-4 py-3">
                    <flux:icon name="document-text" variant="mini" class="w-5 h-5 shrink-0 text-zinc-500 dark:text-zinc-400" />
                    <p class="min-w-0 flex-1 truncate text-sm">
                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $fileName }}</span>
                        <span class="ml-2 text-zinc-500 dark:text-zinc-400">{{ count($rows) }} baris</span>
                    </p>
                    <button type="button" wire:click="resetImport"
                            class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 text-sm font-bold text-blue-600 hover:underline dark:text-blue-400">
                        <flux:icon name="arrow-path" variant="mini" class="w-4 h-4" />
                        Ganti file
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">{{ $validCount }} siap diimpor</span>
                        @if($invalidCount > 0)
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800 dark:bg-red-900/40 dark:text-red-300">{{ $invalidCount }} bermasalah</span>
                        @endif
                    </div>

                    @if($invalidCount > 0 && $validCount > 0)
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                            <input type="checkbox" x-model="onlyProblems" class="h-4 w-4 cursor-pointer rounded border-zinc-300 text-blue-600 dark:border-zinc-600 dark:bg-zinc-800">
                            Tampilkan yang bermasalah saja
                        </label>
                    @endif
                </div>

                <div class="{{ $card }} max-h-[min(18rem,36vh)] overflow-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="{{ $head }} w-14">Baris</th>
                                <th scope="col" class="{{ $head }}">Pengguna</th>
                                <th scope="col" class="{{ $head }}">Role</th>
                                <th scope="col" class="{{ $head }}">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($rows as $row)
                                <tr wire:key="import-row-{{ $row['line'] }}"
                                    x-show="!onlyProblems || {{ $row['errors'] !== [] ? 'true' : 'false' }}"
                                    @class(['bg-red-50/70 dark:bg-red-950/20' => $row['errors'] !== []])>
                                    <td class="{{ $cell }} tabular-nums">{{ $row['line'] }}</td>
                                    <td class="{{ $cell }}">
                                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $row['name'] ?: '(tanpa nama)' }}</p>
                                        <p class="break-all text-xs text-zinc-500 dark:text-zinc-400">{{ $row['email'] ?: '(tanpa email)' }}</p>
                                    </td>
                                    <td class="{{ $cell }}">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse(array_filter(array_map('trim', explode(',', $row['role']))) as $role)
                                                <span class="rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-semibold text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">{{ $role }}</span>
                                            @empty
                                                <span>-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="{{ $cell }}">
                                        @if($row['errors'] === [])
                                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 dark:text-emerald-400">
                                                <flux:icon name="check-circle" variant="mini" class="w-4 h-4" /> Siap
                                            </span>
                                        @else
                                            <ul class="space-y-0.5 text-red-700 dark:text-red-400">
                                                @foreach($row['errors'] as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($validCount > 0)
                    <div class="flex items-start gap-2.5 rounded-xl bg-blue-50 p-3 text-xs leading-relaxed text-blue-800 dark:bg-blue-950/30 dark:text-blue-200">
                        <flux:icon name="information-circle" variant="mini" class="w-4 h-4 shrink-0" />
                        <p>
                            Akun langsung aktif. {{ $validCount }} email verifikasi akan dikirim, masing-masing berisi kata sandi sementara.
                            Pengguna bisa masuk setelah menekan tautan di email. Tautan berlaku {{ \App\Notifications\VerifyEmailNotification::lifetimeLabel() }}.
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <x-slot name="footer">
            @if($invalidCount > 0)
                <p class="mr-auto text-xs text-zinc-500 dark:text-zinc-400">{{ $invalidCount }} baris bermasalah akan dilewati.</p>
            @endif
            <button type="button" wire:click="$set('showModal', false)" class="{{ $secondary }}">
                <flux:icon name="x-mark" variant="mini" class="w-4 h-4" />
                Batal
            </button>
            {{-- Keyed by the count: morphing alone leaves a stale disabled attribute next to wire:loading.attr --}}
            <button type="button" wire:click="import" wire:key="user-import-submit-{{ $validCount }}"
                    wire:loading.attr="disabled" wire:target="import"
                    @disabled($validCount === 0)
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-full bg-blue-600 px-6 text-sm font-bold text-white transition-all hover:bg-blue-700 active:scale-95 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50">
                <flux:icon name="arrow-up-tray" variant="mini" class="w-4 h-4" />
                <span wire:loading.remove wire:target="import">{{ $validCount > 0 ? "Impor {$validCount} pengguna" : 'Impor' }}</span>
                <span wire:loading wire:target="import">Mengimpor...</span>
            </button>
        </x-slot>
    </x-ui.modal>
</div>
