<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NewsCategoryRepositoryInterface extends RepositoryInterface
{
    public function getActive();

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator;

    public function getForDropdown();
}
