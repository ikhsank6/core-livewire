<x-slot name="breadcrumbs">
    <flux:breadcrumbs.item>Dashboard</flux:breadcrumbs.item>
</x-slot>

<div class="space-y-6">

    {{-- ===================== WELCOME CARD ===================== --}}
    <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-[#1a2238] border border-blue-200 dark:border-[#2b3a61] text-blue-800 dark:text-blue-200 rounded-2xl shadow-sm" role="alert">
        <svg class="flex-shrink-0 w-5 h-5 mt-0.5 text-blue-600 dark:text-blue-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div class="text-sm">
            <span class="font-semibold text-blue-900 dark:text-blue-100">Selamat datang kembali, {{ auth()->user()->name }}!</span>
            <div class="mt-1 text-xs text-blue-700 dark:text-blue-300/90 leading-relaxed">
                Anda masuk sebagai <span class="font-semibold text-blue-800 dark:text-blue-200">{{ auth()->user()->role->name ?? '-' }}</span>. Gunakan menu di atas untuk berpindah antar halaman.
            </div>
        </div>
    </div>

    @if(auth()->user()->role?->slug !== 'user')
    {{-- ===================== STAT CARDS ===================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Pengguna</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Aktif</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $activeUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-zinc-400 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Pending</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $pendingUsers }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-500 text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Ditangguhkan</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-0.5">{{ $suspendedUsers }}</p>
            </div>
        </div>
    </div>

    {{-- Two columns from lg; items-start so folding one card does not stretch the other --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 items-start">
        <x-ui.pie-chart
            title="Distribusi Pengguna"
            :subtitle="'Berdasarkan status akun. Total '.$totalUsers.' pengguna.'"
            :items="[
                ['label' => 'Aktif',        'value' => $activeUsers,    'color' => '#1b84ff'],
                ['label' => 'Pending',      'value' => $pendingUsers,   'color' => '#99a1b7'],
                ['label' => 'Ditangguhkan', 'value' => $suspendedUsers, 'color' => '#f8285a'],
            ]"
            :total="$totalUsers"
            unit="pengguna"
            label-heading="Status" />

        <x-ui.bar-chart
            title="Pengguna per Role"
            subtitle="Satu pengguna bisa punya lebih dari satu role."
            :min-height="296"
            :items="$usersPerRole"
            unit="pengguna"
            label-heading="Role"
            empty="Belum ada role." />
    </div>
    @endif

</div>
