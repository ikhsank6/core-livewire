<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;

/**
 * Provides standard searchable + paginated table behaviour used across
 * all Index components.
 *
 * Companion to HasTableView — use both together for full table support.
 *
 * Usage:
 *   use App\Livewire\Concerns\WithSearchablePagination;
 *   use Livewire\WithPagination;
 *
 *   class MyIndex extends Component
 *   {
 *       use WithSearchablePagination, WithPagination;
 *   }
 *
 * Provides:
 *   - $search  (URL-synced)
 *   - $perPage (URL-synced, default 10)
 *   - updatedSearch()   → resetPage()
 *   - updatedPerPage()  → resetPage()
 *   - reload()          → clears search & resets page
 */
trait WithSearchablePagination
{
    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Clear the search term and reset to page 1.
     */
    public function reload(): void
    {
        $this->search = '';
        $this->resetPage();
    }
}
