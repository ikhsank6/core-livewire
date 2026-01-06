<section class="relative h-[250px] md:h-[300px] bg-dark dark:bg-dark flex items-center justify-center grid-pattern"
    style="background-image: linear-gradient(rgba(10,10,15,0.9), rgba(10,10,15,0.9)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1920'); background-size: cover; background-position: center;">
    <div class="text-center text-white px-4">
        <div class="flex items-center justify-center gap-2 text-sm mb-4">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <span class="text-slate-500">›</span>
            <span class="text-primary">{{ $breadcrumb }}</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-bold">{{ $title }}</h1>
    </div>
</section>