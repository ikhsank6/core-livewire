<?php

namespace App\Repositories;

use App\Models\AboutUs;
use App\Repositories\Contracts\AboutUsRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AboutUsRepository extends BaseRepository implements AboutUsRepositoryInterface
{
    public function __construct(AboutUs $model)
    {
        parent::__construct($model);
    }

    public function getActive(): ?AboutUs
    {
        return $this->model->active()->first();
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('company_name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
