<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Support\Sla;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Appends([
    'hours_left',
])]
#[Fillable([
    'subject',
    'body',
    'status',
    'priority',
    'estimated_hours',
    'due_at',
])]
class Ticket extends Model
{
    use HasFactory, HasUuids;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<TicketReply, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    /**
     * @return HasMany<Attachment, $this>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    protected function hoursLeft(): Attribute
    {
        return Attribute::get(fn (): ?int => $this->isClosed() || $this->due_at === null
            ? null
            : app(Sla::class)->hoursLeft($this->due_at));
    }

    #[Scope]
    protected function open(Builder $query): void
    {
        $query->where('status', '!=', TicketStatus::Closed);
    }

    #[Scope]
    protected function forUser(Builder $query, User $user): void
    {
        $query->unless(
            $user->can('tickets.view-any'),
            fn (Builder $query) => $query->whereBelongsTo($user),
        );
    }

    public function isClosed(): bool
    {
        return $this->status === TicketStatus::Closed;
    }

    public function close(): void
    {
        $this->forceFill([
            'status' => TicketStatus::Closed,
            'closed_at' => now(),
        ])->save();
    }

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'priority' => TicketPriority::class,
            'estimated_hours' => 'float',
            'due_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }
}
