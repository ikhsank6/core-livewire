<?php

namespace App\Repositories;

use App\Models\News;
use App\Repositories\Contracts\NewsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function getPublished(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->active()
            ->published()
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getFeatured(int $limit = 5): Collection
    {
        return $this->model
            ->with('category')
            ->active()
            ->published()
            ->featured()
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getByCategory(int $categoryId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->where('news_category_id', $categoryId)
            ->active()
            ->published()
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('category');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function findBySlug(string $slug): ?News
    {
        return $this->model
            ->with('category')
            ->where('slug', $slug)
            ->active()
            ->published()
            ->first();
    }

    public function getLatest(int $limit = 6): Collection
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getActiveWithFilter(?string $categorySlug, int $perPage = 8): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->when($categorySlug, function ($query, $categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            })
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getRecentExcept(string $exceptId, int $limit = 5): Collection
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $exceptId)
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getRelated(string $categoryId, string $exceptId, int $limit = 4): Collection
    {
        return $this->model
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $exceptId)
            ->where('news_category_id', $categoryId)
            ->take($limit)
            ->get();
    }

    public function findActiveBySlugOrFail(string $slug): News
    {
        return $this->model
            ->with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
