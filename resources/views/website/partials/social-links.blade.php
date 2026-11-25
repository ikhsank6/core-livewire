{{-- Props: $dark (bool, optional): true when sitting on the navy ground --}}
@php
    $dark = $dark ?? false;

    $socials = collect([
        ['label' => 'Facebook',  'url' => $aboutUs?->facebook,  'path' => 'M14 8.5V6.8c0-.8.2-1.3 1.4-1.3H17V2.6c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.2H8.6v3h2.5V19H14v-7.5h2.5l.4-3H14Z'],
        ['label' => 'Instagram', 'url' => $aboutUs?->instagram, 'path' => 'M10 6.2a3.8 3.8 0 1 0 0 7.6 3.8 3.8 0 0 0 0-7.6Zm0 6.3a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5ZM14 5a.9.9 0 1 0 0 1.8A.9.9 0 0 0 14 5Zm4 5c0-1.7 0-2.6-.3-3.3a4.2 4.2 0 0 0-2.4-2.4C14.6 4 13.7 4 12 4H8c-1.7 0-2.6 0-3.3.3a4.2 4.2 0 0 0-2.4 2.4C2 7.4 2 8.3 2 10v4c0 1.7 0 2.6.3 3.3a4.2 4.2 0 0 0 2.4 2.4c.7.3 1.6.3 3.3.3h4c1.7 0 2.6 0 3.3-.3a4.2 4.2 0 0 0 2.4-2.4c.3-.7.3-1.6.3-3.3v-4Zm-1.5 4.2c0 1.4 0 2.1-.2 2.5a2.7 2.7 0 0 1-1.6 1.6c-.4.2-1.1.2-2.5.2H8c-1.4 0-2.1 0-2.5-.2a2.7 2.7 0 0 1-1.6-1.6c-.2-.4-.2-1.1-.2-2.5V9.8c0-1.4 0-2.1.2-2.5a2.7 2.7 0 0 1 1.6-1.6c.4-.2 1.1-.2 2.5-.2h4.2c1.4 0 2.1 0 2.5.2a2.7 2.7 0 0 1 1.6 1.6c.2.4.2 1.1.2 2.5v4.4Z'],
        ['label' => 'X',         'url' => $aboutUs?->twitter,   'path' => 'M15.2 2.9h2.6l-5.7 6.5 6.7 8.8h-5.2L9.5 13l-4.7 5.2H2.2l6.1-7-6.4-8.3h5.4l3.7 4.9 4.2-4.9Zm-.9 13.8h1.4L6.1 4.3H4.6l9.7 12.4Z'],
        ['label' => 'YouTube',   'url' => $aboutUs?->youtube,   'path' => 'M18.5 6.2a2.2 2.2 0 0 0-1.6-1.6C15.5 4.2 10 4.2 10 4.2s-5.5 0-6.9.4a2.2 2.2 0 0 0-1.6 1.6C1.1 7.6 1.1 10 1.1 10s0 2.4.4 3.8a2.2 2.2 0 0 0 1.6 1.6c1.4.4 6.9.4 6.9.4s5.5 0 6.9-.4a2.2 2.2 0 0 0 1.6-1.6c.4-1.4.4-3.8.4-3.8s0-2.4-.4-3.8ZM8.3 12.6V7.4l4.6 2.6-4.6 2.6Z'],
        ['label' => 'LinkedIn',  'url' => $aboutUs?->linkedin,  'path' => 'M6.1 17.2H3.2V7.6h2.9v9.6ZM4.6 6.3a1.7 1.7 0 1 1 0-3.4 1.7 1.7 0 0 1 0 3.4Zm12.6 10.9h-2.9v-4.7c0-1.1 0-2.5-1.5-2.5s-1.8 1.2-1.8 2.4v4.8H8.1V7.6H11v1.3h.1c.4-.8 1.4-1.6 2.8-1.6 3 0 3.5 2 3.5 4.5v5.4Z'],
    ])->filter(fn ($s) => filled($s['url']));
@endphp

@if($socials->isNotEmpty())
    <ul class="flex flex-wrap items-center gap-1 -ml-3">
        @foreach($socials as $social)
            <li>
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                   aria-label="{{ $social['label'] }}"
                   class="w-11 h-11 rounded-md grid place-items-center transition-colors
                          {{ $dark ? 'text-white/70 hover:text-gold-soft' : 'text-muted hover:text-ink' }}">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="{{ $social['path'] }}"/>
                    </svg>
                </a>
            </li>
        @endforeach
    </ul>
@endif
