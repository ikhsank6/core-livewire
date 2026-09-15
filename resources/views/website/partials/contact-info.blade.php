{{-- NOTE: currently unused — no view includes this partial. Kept in the new
     token system so it does not rot against the retired palette. --}}
@php
    $items = collect([
        ['label' => 'Alamat',  'value' => $aboutUs?->address, 'mono' => false],
        ['label' => 'Telepon', 'value' => $aboutUs?->phone,   'mono' => true],
        ['label' => 'Surel',   'value' => $aboutUs?->email,   'mono' => false],
    ])->filter(fn ($i) => filled($i['value']));
@endphp

@if($items->isNotEmpty())
    <dl class="divide-y divide-hairline dark:divide-pine-soft">
        @foreach($items as $item)
            <div class="py-4 first:pt-0 last:pb-0">
                <dt class="t-micro text-moss dark:text-bone-dim">{{ $item['label'] }}</dt>
                <dd class="mt-1.5 text-[0.9375rem] leading-relaxed text-ink dark:text-bone wrap-break-word
                           {{ $item['mono'] ? 't-data' : '' }}">
                    {{ $item['value'] }}
                </dd>
            </div>
        @endforeach
    </dl>
@endif
