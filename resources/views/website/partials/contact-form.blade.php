{{-- NOTE: currently unused — no view includes this partial, and it posts
     nowhere. Kept in the new token system, with visible labels rather than
     the placeholder-only fields it used to have. --}}
<form class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="cf-nama" class="t-micro text-moss dark:text-bone-dim block mb-2">Nama</label>
            <input id="cf-nama" type="text" name="nama" autocomplete="name"
                   class="w-full h-12 px-4 rounded-xl bg-paper dark:bg-ink-card border border-hairline
                          dark:border-pine-soft text-ink dark:text-bone transition-colors
                          focus:border-marigold">
        </div>
        <div>
            <label for="cf-surel" class="t-micro text-moss dark:text-bone-dim block mb-2">Surel</label>
            <input id="cf-surel" type="email" name="surel" autocomplete="email"
                   class="w-full h-12 px-4 rounded-xl bg-paper dark:bg-ink-card border border-hairline
                          dark:border-pine-soft text-ink dark:text-bone transition-colors
                          focus:border-marigold">
        </div>
    </div>

    <div>
        <label for="cf-perihal" class="t-micro text-moss dark:text-bone-dim block mb-2">Perihal</label>
        <input id="cf-perihal" type="text" name="perihal"
               class="w-full h-12 px-4 rounded-xl bg-paper dark:bg-ink-card border border-hairline
                      dark:border-pine-soft text-ink dark:text-bone transition-colors
                      focus:border-marigold">
    </div>

    <div>
        <label for="cf-pesan" class="t-micro text-moss dark:text-bone-dim block mb-2">Pesan</label>
        <textarea id="cf-pesan" name="pesan" rows="5"
                  class="w-full p-4 rounded-xl bg-paper dark:bg-ink-card border border-hairline
                         dark:border-pine-soft text-ink dark:text-bone resize-none transition-colors
                         focus:border-marigold"></textarea>
    </div>

    <button type="submit"
            class="inline-flex items-center justify-center gap-2.5 h-13 px-7 rounded-full
                   bg-marigold text-ink hover:bg-marigold-dim transition-colors t-micro">
        Kirim pesan
    </button>
</form>
