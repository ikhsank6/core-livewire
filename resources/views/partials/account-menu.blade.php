{{--
    Account menu in the admin header: who is signed in, account actions, role switch, sign out.
    A plain disclosure (links and buttons, Tab order, Esc closes) rather than role="menu".
    Rows are 44px tall; names line up on one left edge, with or without an icon.
--}}
@php
    $user = auth()->user();
    $roles = $user->roles;
    $avatarUrl = $user->avatar ? Storage::url($user->avatar) : null;
    $initials = mb_strtoupper(mb_substr($user->name, 0, 2));

    $row = 'flex w-full min-h-11 items-center gap-3 rounded-lg px-2.5 text-left text-sm text-zinc-700 transition-colors cursor-pointer hover:bg-zinc-100 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-[#2D68A7] dark:text-zinc-200 dark:hover:bg-white/5 dark:focus-visible:outline-[#4BA5DA]';
    $current = 'bg-zinc-100 font-semibold text-zinc-900 dark:bg-white/10 dark:text-white';
    $icon = 'w-4 h-4 shrink-0 text-zinc-500 dark:text-zinc-400';
    $group = 'border-t border-zinc-200 p-1.5 dark:border-[#2d2d3a]';
@endphp

<div class="relative" x-data="{
        profileOpen: false,
        get isDark() { return $store.theme ? $store.theme.isDark : (localStorage.getItem('theme') === 'dark') },
        toggle() {
            if ($store.theme) {
                $store.theme.toggle();
            } else {
                const newTheme = this.isDark ? 'light' : 'dark';
                localStorage.setItem('theme', newTheme);
                document.documentElement.classList.toggle('dark', newTheme === 'dark');
            }
        }
    }"
    @click.away="profileOpen = false"
    @keydown.escape="if (profileOpen) { profileOpen = false; $refs.trigger.focus() }">

    <button type="button" x-ref="trigger" @click="profileOpen = !profileOpen"
            aria-controls="account-menu" :aria-expanded="profileOpen ? 'true' : 'false'"
            aria-label="Menu akun {{ $user->name }}"
            class="flex min-h-11 items-center gap-2 rounded-lg px-2 py-1 transition-colors cursor-pointer hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-white">
        @if($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="" class="h-8 w-8 rounded-full object-cover ring-2 ring-white/20">
        @else
            <span class="grid h-8 w-8 place-items-center rounded-full bg-[#2D68A7] text-xs font-bold text-white ring-2 ring-white/20" aria-hidden="true">{{ $initials }}</span>
        @endif
        <span class="hidden sm:flex flex-col text-left leading-tight">
            <span class="text-sm font-semibold text-white max-w-[120px] truncate">{{ $user->name }}</span>
            <span class="text-xs text-white/85 max-w-[120px] truncate">{{ $user->role?->name ?? 'Tanpa role' }}</span>
        </span>
        <flux:icon name="chevron-down" variant="mini" class="hidden sm:block w-4 h-4 text-white/70 transition-transform duration-150 motion-reduce:transition-none"
                   x-bind:class="profileOpen && 'rotate-180'" />
    </button>

    <div id="account-menu" x-show="profileOpen" x-cloak
         x-transition:enter="transition ease-out duration-150 motion-reduce:transition-none"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100 motion-reduce:transition-none"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute right-0 top-[calc(100%+6px)] z-50 w-72 max-h-[calc(100dvh-5rem)] overflow-y-auto rounded-xl border border-zinc-200 bg-white shadow-[0_16px_36px_-10px_rgba(15,23,42,0.35)] dark:border-[#2d2d3a] dark:bg-[#1e1e2d]">

        <div class="flex items-center gap-3 p-4">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="" class="h-11 w-11 shrink-0 rounded-full object-cover">
            @else
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[#2D68A7] text-sm font-bold text-white" aria-hidden="true">{{ $initials }}</span>
            @endif
            <div class="min-w-0">
                <p class="truncate text-[0.9375rem] font-semibold text-zinc-900 dark:text-white">{{ $user->name }}</p>
                <p class="truncate text-[0.8125rem] text-zinc-500 dark:text-zinc-400" title="{{ $user->email }}">{{ $user->email }}</p>
                @if($roles->count() <= 1 && $user->role)
                    <p class="truncate text-[0.8125rem] text-zinc-500 dark:text-zinc-400">Role: {{ $user->role->name }}</p>
                @endif
            </div>
        </div>

        <div class="{{ $group }}">
            <a href="{{ route('profile') }}" @if(request()->routeIs('profile')) aria-current="page" @endif
               class="{{ $row }} {{ request()->routeIs('profile') ? $current : '' }}">
                <flux:icon name="user" variant="mini" class="{{ $icon }}" />
                Profil
            </a>
            <a href="{{ route('password.change') }}" @if(request()->routeIs('password.change')) aria-current="page" @endif
               class="{{ $row }} {{ request()->routeIs('password.change') ? $current : '' }}">
                <flux:icon name="key" variant="mini" class="{{ $icon }}" />
                Ganti kata sandi
            </a>
            <button type="button" role="switch" :aria-checked="isDark ? 'true' : 'false'" @click="toggle()" class="{{ $row }}">
                <flux:icon name="moon" variant="mini" class="{{ $icon }}" />
                <span class="flex-1">Mode gelap</span>
                {{-- The switch shows the state, so the label never has to change --}}
                <span class="relative inline-flex h-5 w-9 shrink-0 rounded-full transition-colors duration-150 motion-reduce:transition-none"
                      :class="isDark ? 'bg-[#2D68A7]' : 'bg-zinc-500'" aria-hidden="true">
                    <span class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white transition-transform duration-150 motion-reduce:transition-none"
                          :class="isDark && 'translate-x-4'"></span>
                </span>
            </button>
        </div>

        @if($roles->count() > 1)
            <div class="{{ $group }}">
                <p id="account-menu-roles" class="px-2.5 pb-1 pt-1.5 text-[0.8125rem] font-medium text-zinc-500 dark:text-zinc-400">Ganti role</p>
                <ul aria-labelledby="account-menu-roles">
                    @foreach($roles as $role)
                        <li>
                            @if($user->role_id === $role->id)
                                {{-- In use: shown, not clickable --}}
                                <div aria-current="true" class="flex min-h-11 items-center gap-3 rounded-lg pl-[2.375rem] pr-2.5 text-sm {{ $current }}">
                                    <span class="min-w-0 flex-1 truncate">{{ $role->name }}</span>
                                    <span class="inline-flex items-center gap-1 text-[0.8125rem] font-medium text-[#245A94] dark:text-[#7FC1EA]">
                                        <flux:icon name="check" variant="mini" class="w-4 h-4" />
                                        Dipakai
                                    </span>
                                </div>
                            @else
                                <a href="{{ route('roles.switch', $role) }}" class="{{ $row }} pl-[2.375rem]">
                                    <span class="min-w-0 flex-1 truncate">{{ $role->name }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="{{ $group }}">
            <button type="button" @click="$dispatch('open-modal', { name: 'logout-modal' }); profileOpen = false"
                    class="{{ $row }} text-red-700! hover:bg-red-50! dark:text-red-400! dark:hover:bg-red-900/20!">
                <flux:icon name="arrow-right-start-on-rectangle" variant="mini" class="w-4 h-4 shrink-0" />
                Keluar
            </button>
        </div>
    </div><!-- /account-menu -->
</div>
