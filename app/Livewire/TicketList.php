<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TicketList extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $onlyOpen = true;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedOnlyOpen(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function tickets(): LengthAwarePaginator
    {
        /** @var User $user */
        $user = Auth::user();

        return Ticket::query()
            ->forUser($user)
            ->when($this->onlyOpen, fn (Builder $query) => $query->open())
            ->when($this->search !== '', fn (Builder $query) => $query->where('subject', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);
    }

    public function render(): View
    {
        return view('livewire.ticket-list');
    }
}
