<section class="relative h-[250px] md:h-[300px] bg-dark flex items-center justify-center"
    style="background-image: linear-gradient(rgba(26,26,46,0.9), rgba(26,26,46,0.9)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1920'); background-size: cover; background-position: center;">
    <div class="text-center text-white">
        <div class="flex items-center justify-center gap-2 text-sm mb-4">
            <a href="/" class="hover:text-primary">Home</a>
            <span>:</span>
            <span class="text-primary">{{ $breadcrumb }}</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-bold">{{ $title }}</h1>
    </div>
</section>