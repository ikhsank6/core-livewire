<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface extends RepositoryInterface
{
    public function getPublished(int $perPage = 10): LengthAwarePaginator;

    public function getFeatured(int $limit = 5);

    public function getByCategory(int $categoryId, int $perPage = 10): LengthAwarePaginator;

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator;

    public function findBySlug(string $slug);
}
