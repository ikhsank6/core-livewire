<?php

namespace App\Repositories;

use App\Models\Carousel;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CarouselRepository extends BaseRepository implements CarouselRepositoryInterface
{
    public function __construct(Carousel $model)
    {
        parent::__construct($model);
    }

    public function getActiveOrdered(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function searchByTerm(?string $term, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        return $query->ordered()->paginate($perPage);
    }

    public function updateOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->model->where('id', $id)->update(['order' => $index + 1]);
        }
    }
}
