<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'subject',
        'body',
        'status',
        'priority',
        'estimated_hours',
        'due_at',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'estimated_hours' => 'float',
        'due_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function scopeOpen(Builder $query): void
    {
        $query->where('status', '!=', TicketStatus::Closed);
    }

    public function scopeForUser(Builder $query, User $user): void
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
}
