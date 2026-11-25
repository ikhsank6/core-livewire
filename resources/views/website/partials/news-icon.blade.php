{{--
    Props:
    $slug  (string) – news category slug
    $class (string, optional) – size/colour classes for the <svg>
    Outline icons (24px grid, 1.75 stroke), decorative: always aria-hidden.
--}}
@php
    $paths = match ($slug ?? '') {
        'asesmen'    => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 14l2 2 4-4',
        'jadwal-uji' => 'M8 7V3m8 4V3M5 11h14M6 5h12a1 1 0 011 1v13a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z',
        'pengumuman' => 'M11 5.88V19.24a1.76 1.76 0 01-3.42.6L5.88 15.4M18 10a3 3 0 010 6m-12.1-.6H4a2 2 0 01-2-2v-2a2 2 0 012-2h1.9L11 5.9c2.2-1.2 5-1.3 7-1.9v14c-2-.6-4.8-.7-7-1.9',
        'sertifikat' => 'M9 12.75L11.25 15 15 9.75M12 2.25l2.4 1.7 2.9-.1.9 2.8 2.4 1.7-.9 2.8.9 2.8-2.4 1.7-.9 2.8-2.9-.1-2.4 1.7-2.4-1.7-2.9.1-.9-2.8-2.4-1.7.9-2.8-.9-2.8 2.4-1.7.9-2.8 2.9.1 2.4-1.7z',
        'skema'      => 'M12 3l9 4.5-9 4.5-9-4.5L12 3zM3 12l9 4.5 9-4.5M3 16.5L12 21l9-4.5',
        default      => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM14 4v5h5M8 13h8M8 17h5',
    };
@endphp
<svg class="{{ $class ?? 'h-4 w-4' }}" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $paths }}"/></svg>
